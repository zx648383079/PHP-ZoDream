<?php
declare(strict_types=1);
namespace Module\CMS\Domain;

use Domain\Providers\MemoryCacheProvider;
use Infrastructure\HtmlExpand;
use Module\Auth\Domain\Repositories\UserRepository;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\LinkageRepository;
use Zodream\Database\Query\Builder;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;
use Zodream\Html\Form;
use Zodream\Html\Page;
use Zodream\Html\Tree;
use Zodream\Html\VerifyCsrfToken;
use Zodream\Infrastructure\Support\Html;
use Zodream\Template\Engine\ParserCompiler;
use Zodream\Helpers\Tree as TreeHelper;
use Zodream\Helpers\Html as HtmlHelper;

class FuncHelper {

    public static array $current = [
        'channel' => 0,
        'content' => 0,
        'model' => 0,
    ];

    protected static array $index = [];

    public static function cache(): MemoryCacheProvider {
        return MemoryCacheProvider::getInstance();
    }

    public static function option(string $code, string $type = '') {
        if (empty($code)) {
            return null;
        }
        $options = static::cache()->getOrSet(__FUNCTION__, 'all', function () {
            return CMSRepository::options();
        });
        $val = $options[$code] ?? null;
        if ($type === 'number') {
            return static::toNumber($val);
        }
        return $val;
    }

    protected static function toNumber(mixed $value) {
        if (is_numeric($value)) {
            return $value;
        }
        if (preg_match('/\d+/', (string)$value, $match)) {
            return $match[0];
        }
        return intval($value);
    }

    public static function channels(array $params = null) {
        $data = static::cache()->getOrSet(__FUNCTION__, 'all',
            function () {
                $data = CategoryModel::query()->orderBy('position', 'asc')->get();
                $maps = [];
                foreach ($data as $item) {
                    if (!isset($maps[$item['parent_id']])) {
                        $maps[$item['parent_id']] = 0;
                    }
                    $maps[$item['parent_id']] ++;
                }
                $items = [];
                foreach ($data as $item) {
                    $formatted = $item->toArray();
                    $formatted['children_count'] = $maps[$item['id']] ?? 0;
                    $items[] = $formatted;
                }
                unset($data, $maps);
                static::setChannel(...$items);
                return $items;
            });
        if (empty($params)) {
            return $data;
        }
        if (isset($params['children'])) {
            return TreeHelper::getTreeChild($data, is_numeric($params['children']) ? $params['children']
                : static::getChannelId($params['children']));
        }
        if (isset($params['group'])) {
            $data = array_filter($data, function ($item) use ($params) {
                $groups = is_array($item['groups']) ? $item['groups'] : explode(',', $item['groups']);
                return in_array($params['group'], $groups);
            });
        }
        if (isset($params['parent'])) {
            $params['parent'] = is_numeric($params['parent']) ? $params['parent']
                : static::getChannelId($params['parent']);
            $data = array_filter($data, function ($item) use ($params) {
                return $item['parent_id'] == $params['parent'];
            });
        } elseif (isset($params['tree'])) {
            $data = static::cache()->getOrSet(__FUNCTION__,
                sprintf('tree-%s', $params['group'] ?? ''),
                function () use ($data) {
                    return (new Tree($data))->makeIdTree();
                });
        }
        if (isset($params['id'])) {
            $ids = array_map('intval', explode(',', $params['id']));
            $data = array_filter($data, function ($item) use ($ids) {
                return in_array($item['id'], $ids);
            });
        }
        return $data;
    }

    protected static function getCategoryId(array $params) {
        $category = static::getVal($params, ['category', 'cat_id', 'cat', 'channel']);
        if (!empty($category) && !is_numeric($category)) {
            $category = static::getChannelId($category);
        }
        if (empty($category)) {
            $category = static::$current['channel'];
        }
        return $category;
    }

    /**
     * @param string|int $model
     * @return ModelModel
     */
    public static function model(string|int $model) {
        static::cache()->getOrSet(__FUNCTION__, 'all', function () use ($model) {
            $data = ModelModel::query()->get();
            static::setModel(...$data);
            return $data;
        });
        $item = static::cache()->getOrSet(__FUNCTION__, $model, function () use ($model) {
            if (is_numeric($model)) {
                return ModelModel::find($model);
            }
            return ModelModel::query()->where('`table`', $model)->first();
        });
        if (empty($item)) {
            return $item;
        }
        if (is_numeric($model)) {
            return static::cache()->getOrSet(__FUNCTION__, $item->table, $item);
        }
        return static::cache()->getOrSet(__FUNCTION__, $item->id, $item);
    }

    protected static function setModel(...$data) {
        foreach ($data as $item) {
            static::cache()->trySet('model', $item['id'], $item);
            static::cache()->trySet('model', $item['table'], $item);
        }
    }

    /**
     * @param array|null $params
     * @return Page
     */
    public static function contents(array $params = null) {
        $data = self::getContentsQuery($params);
        return static::cache()->getOrSet(__FUNCTION__,
            md5(Json::encode($data)),
            function () use ($data) {
            if (isset($data[1]['model_id'])) {
                $scene = CMSRepository::scene()->setModel(self::model($data[1]['model_id']));
                return $scene->search(...$data);
            }
            $category = $data[1]['cat_id'];
            $children = static::channels(['children' => $category]);
            $cat = static::channel($category, true);
            $model = empty($cat) ? null : static::model($cat['model_id']);
            if (empty($cat) || !$model) {
                return new Page(0);
            }
            $children[] = $category;
            $data[1]['cat_id'] = $children;
            $scene = CMSRepository::scene()->setModel($model);
            return $scene->search(...$data);
        });
    }

    protected static function getContentsQuery(array $param) {
        $data = [];
        if (isset($param['model'])) {
            $data['model_id'] = self::model($param['model'])->id;
        } else {
            $data['cat_id'] = self::getCategoryId($param);
        }
        $keywords = static::getVal($param, ['keywords', 'keyword', 'query'], '');
        $page = intval(static::getVal($param, ['page'], 1));
        $param['parent_id'] = static::getVal($param, ['parent_id', 'parent'], 0);
        $fields = static::getVal($param, ['field', 'fields'], '');
        $order = static::getVal($param, ['order', 'orderBy'], 'updated_at desc');
        if (strpos($order, '_') > 0) {
            $order = preg_replace('/_(desc|asc)/i', ' $1', $order);
        }
        if ($order === 'hot') {
            $order = 'view_count desc';
        }
        $per_page = static::getVal($param, ['per_page', 'size', 'num', 'limit'], 20);
        $tags = ['model', 'keywords', 'keyword',
            'query', 'page', 'field', 'fields',
            'order', 'orderBy', 'per_page',
            'size', 'num', 'limit',
            'parent',
            'category', 'cat_id',
            'cat', 'channel'];
        // 通过标签提前过滤
        foreach ($param as $key => $value) {
            if (in_array($key, $tags)) {
                continue;
            }
            $data[$key] = $value;
        }
        return [$keywords, $data, $order, $page, $per_page, $fields];
    }

    /**
     * 获取分页内容
     * @param array|null $params
     * @return mixed
     * @throws \Exception
     */
    public static function contentPage(array $params = null) {
        if (empty($params)) {
            $params = [];
        }
        $params['page'] = max(intval(request()->get('page')), 1);
        if (!isset($params['per_page'])) {
            $params['per_page'] = intval(request()->get('per_page', 20));
        }
        return static::contents($params);
    }

    public static function locationPath(): array {
        $path = static::cache()->getOrSet(__FUNCTION__, __FUNCTION__, function () {
            $path = TreeHelper::getTreeParent(static::channels(), static::$current['channel']);
            $path[] = static::$current['channel'];
            return $path;
        });
        return array_map(function ($id) {
            return [
                'id' => $id,
                'title' => static::channel($id, 'title'),
                'url' => static::channel($id, 'url'),
            ];
        }, $path);
    }

    public static function location(): string {
        return implode('', array_map(function ($item) {
            return Html::li(Html::a($item['title'],
                $item['url']));
        }, static::locationPath()));
    }

    public static function previous($name = null) {
        $data = static::cache()->getOrSet(__FUNCTION__, static::$current['content'], function () {
            $cat = static::channel(static::$current['channel'], true);
            $catModel = static::model($cat['model_id']);
            $scene = CMSRepository::scene()->setModel($catModel);
            return $scene->query()->where('id', '<', static::$current['content'])
                ->orderBy('id', 'desc')->first();
        });
        return static::getContentValue($name, $data);
    }

    public static function next($name = null) {
        $data = static::cache()->getOrSet(__FUNCTION__, static::$current['content'], function () {
            $cat = static::channel(static::$current['channel'], true);
            $catModel = static::model($cat['model_id']);
            $scene = CMSRepository::scene()->setModel($catModel);
            return $scene->query()->where('id', '>', static::$current['content'])
                ->orderBy('id', 'asc')->first();
        });
        return static::getContentValue($name, $data);
    }

    protected static function getChannelId($val, $key = 'name') {
        $data = static::channels();
        foreach ($data as $item) {
            if ($item[$key] === $val || $item['id'] === $val) {
                return $item['id'];
            }
        }
        return 0;
    }

    protected static function setChannel(...$data) {
        foreach ($data as $item) {
            if (static::cache()->has('channel', $item['id'])) {
                return;
            }
            $item['model'] = self::model($item['model_id']);
            static::cache()->set('channel', $item['id'], $item);
        }
    }

    /***
     * 获取字段集合
     * @param string|int $model_id
     * @return ModelFieldModel[]
     */
    public static function fieldList(string|int $model_id): array {
        return static::cache()->getOrSet(__FUNCTION__,
            $model_id,
            function () use ($model_id) {
                return ModelFieldModel::query()->where('model_id', $model_id)->get();
            });
    }

    /**
     * 获取摸一个字段
     * @param string|int $model_id
     * @param string $field
     * @return ModelFieldModel|null
     */
    public static function getField(string|int $model_id, string $field): ?ModelFieldModel {
        $field_list = static::fieldList($model_id);
        foreach ($field_list as $item) {
            if ($item['field'] == $field) {
                return $item;
            }
        }
        return null;
    }

    public static function field(array $params) {
        $category = self::getCategoryId($params);
        $field = static::cache()->getOrSet(__FUNCTION__,
            sprintf('%s-%s', $category, $params['field']),
            function () use ($category, $params) {
                $cat = static::channel($category, true);
                if (empty($cat) || $cat['model_id'] < 1) {
                    return null;
                }
                return static::getField($cat['model_id'], $params['field']);
            });
        if (empty($field)) {
            return '';
        }
        if (!array_key_exists('name', $params)) {
            return $field;
        }
        $name = empty($params['name']) ? 'name' : $params['name'];
        return $field[$name];
    }

    public static function searchField(string|int $category) {
        return static::cache()->getOrSet(__FUNCTION__, $category,function () use ($category) {
            $fields = ['id', 'cat_id', 'model_id', 'view_count',
                'title', 'keywords', 'description', 'thumb',
                'updated_at', 'created_at', 'parent_id'];
            $cat = static::channel($category, true);
            $catModel = static::model($cat['model_id']);
            $scene = CMSRepository::scene()->setModel($catModel);
            if ($scene) {
                foreach ($scene->fieldList() as $item) {
                    if ($item->is_disable) {
                        continue;
                    }
                    if (!$item->is_system && $item->is_search && !in_array($item->field, $fields)) {
                        $fields[] = $item->field;
                    }
                }
            }
            return implode(',', $fields);
        });
    }

    public static function markdown(string $content): string {
        return HtmlExpand::toHtml($content, true);
    }

    /**
     * @param mixed $id
     * @param string|bool|null $name
     * @return array|string
     * @throws \Exception
     */
    public static function channel(mixed $id, string|bool|null $name = null): mixed {
        if (is_array($id)) {
            isset($id['name']) && $name = $id['name'];
            $id = $id['id'];
        }
        if (!is_numeric($id)) {
            $id = static::getChannelId($id);
        }
        if ($id < 1) {
            return null;
        }
        if ($name === 'url') {
            return url('./category', ['id' => $id]);
        }
        $data = static::cache()->getOrSet(__FUNCTION__, $id, function () use ($id) {
            return CategoryModel::find($id);
        });
        $data['model'] = self::model($data['model_id']);
        if ($name === true) {
            return $data;
        }
        return $data[$name];
    }

    public static function channelRoot(mixed $id) {
        $id = static::getChannelId($id);
        if (empty($id)) {
            return null;
        }
        $items = static::channels();
        $parent = TreeHelper::getTreeParent($items, $id);
        return static::channel(empty($parent) ? $id : $parent[0], true);
    }

    /**
     * @param array|integer $id
     * @param null|string $name
     * @param string|int $category
     * @return array|string
     */
    public static function content(mixed $id, mixed $name = null, string|int $category = 0) {
        if (is_array($id)) {
            isset($id['name']) && $name = $id['name'];
            $category = static::getVal($id, ['category', 'cat_id', 'cat', 'channel']);
            if (isset($id['model']) && !isset($id['id'])) {
                return self::formContent($id['model'], $name, $category);
            }
            $id = $id['id'];
        }
        $id = intval($id);
        $category = intval($category);
        if (empty($category)) {
            $category = static::$current['channel'];
        }
        if ($id < 1 || $category < 1) {
            return null;
        }
        $data = static::cache()->getOrSet(__FUNCTION__, sprintf('%s:%s', $category, $id),
            function () use ($id, $category) {
                $cat = static::channel($category, true);
                $catModel = static::model($cat['model_id']);
                $scene = CMSRepository::scene()->setModel($catModel);
            return $scene->find($id);
        });
        return self::getContentValue($name, $data);
    }

    public static function formContent($model, $name = null, $category = null, $user = null) {
        if (is_array($model)) {
            isset($model['name']) && $name = $model['name'];
            $category = static::getVal($model, ['category', 'cat_id', 'cat', 'channel']);
            $user = static::getVal($model, ['user', 'user_id']);
            $model = $model['model'];
        }
        $model = self::model($model);
        if (!$model->setting('is_show')) {
            return null;
        }
        if ($model->setting('is_only') && $user < 1) {
            $user = auth()->id();
        }
        $data = static::cache()->getOrSet(__FUNCTION__, sprintf('%s:%s:%s', $category, $model->id, $user),
            function () use ($model, $category, $user) {
                $scene = CMSRepository::scene()->setModel($model);
                return $scene->find(
                    function (Builder $query, $pre, $i) use ($category, $user, $model) {
                        if (!empty($pre) && isset($pre['id'])) {
                            $query->where('id', $pre['id']);
                            return;
                        }
                        if ($i > 0 && empty($pre)) {
                            return false;
                        }
                    if (!empty($category)) {
                        $query->where('cat_id', self::channel($category, 'id'));
                    }
                    if (!empty($user) || $model->setting('is_only')) {
                        $query->where('user_id', intval($user));
                    }
                });
            });
        return self::getContentValue($name, $data);
    }

    /**
     * @param string|bool $name
     * @param array|null $data
     * @return null|string|mixed
     */
    protected static function getContentValue(string|bool|null $name, ?array $data): mixed {
        if (empty($data)) {
            return null;
        }
        if ($name === true) {
            return $data;
        }
        if ($name === 'url') {
            return self::url($data);
        }
        return $data[$name] ?? null;
    }

    public static function isChildren(int $parent_id, int $id): int {
        if ($parent_id === $id) {
            return 0;
        }
        $children = static::channels(['children' => $parent_id]);
        if (!empty($children) && in_array($id, $children)) {
            return 1;
        }
        return -1;
    }

    public static function channelActive(int|string $id): string {
        if (!is_numeric($id)) {
            $id = static::getChannelId($id);
        }
        $current = intval(static::$current['channel']);
        if (self::isChildren($id, $current) >= 0) {
            return ' active';
        }
        return '';
    }

    protected static function getVal(array $data, $columns, $default = null) {
        foreach ((array)$columns as $column) {
            if (!empty($column) && isset($data[$column])) {
                return $data[$column];
            }
        }
        return $default;
    }

    protected static function hasVal(array $data, array|string $columns): bool {
        foreach ((array)$columns as $column) {
            if (!empty($column) && isset($data[$column])) {
                return true;
            }
        }
        return false;
    }

    public static function linkage(int|string $id) {
        if (!is_numeric($id)) {
            $id = static::cache()->getOrSet('linkageId', $id, function () use ($id) {
                return LinkageModel::query()->where('code', $id)->value('id');
            });
        }
        if (empty($id)) {
            return [];
        }
        return LinkageRepository::idTree(intval($id));
    }

    public static function linkageText(int|string $id): string {
        if (empty($id)) {
            return '';
        }
        return static::cache()->getOrSet(__FUNCTION__, $id, function () use ($id) {
            return LinkageDataModel::where('id', $id)->value('full_name');
        });
    }

    public static function range(string|int $start, string|int $end, string|int $step = 1) {
        if (empty($step)) {
            $step = 1;
        }
        $step = abs(intval($step));
        if (!is_numeric($start)) {
            $start = intval(date($start));
        }
        if (!is_numeric($end)) {
            $end = intval(date($end));
        }
        if ($start > $end) {
            $step *= -1;
        }
        $items = [];
        while (true) {
            $items[] = $start;
            $start += $step;
            if (
            ($step > 0 && $start > $end)
            || ($step < 0 && $start < $end)
            ) {
                break;
            }
        }
        return $items;
    }

    /**
     * @param int|string $id
     * @return string
     * @throws \Exception
     */
    public static function formAction(int|string $id): string {
        return url('./form/save', [
            is_numeric($id) ? 'id' : 'model' => $id
        ]);
    }

    /**
     * 追加数据，只针对附属表的数据追加
     * @param $data
     * @param mixed ...$fields
     * @throws \Exception
     */
    public static function extendContent(&$data, ...$fields) {
        if (empty($data)) {
            return;
        }
        $items = [];
        foreach ($data as $item) {
            $items[] = $item['id'];
        }
        $args = CMSRepository::scene()->extendQuery()->whereIn('id', $items)
            ->select('id', ...$fields)->pluck(null, 'id');
        $arr = $data instanceof Page ? $data->getPage() : $data;
        foreach ($arr as &$item) {
            foreach ($fields as $field) {
                $item[$field] = $args[$item['id']][$field];
            }
        }
        unset($item);
        if ($data instanceof Page) {
            $data->setPage($arr);
        } else {
            $data = $arr;
        }
    }

    /**
     * 获取表单
     * @param int|string $id
     * @return string
     */
    public static function extendForm(int|string $id): string {
        $model = self::model($id);
        if (empty($model) || $model->type != 1) {
            return '';
        }
        $fileName = $model->setting('form_template');
        if (empty($fileName)) {
            return '';
        }
        $formAction = self::formAction($model->id);
        $formData = self::formData($id);
        $token = VerifyCsrfToken::get();
        return CMSRepository::registerView()->render(sprintf('Content/%s', $fileName),
            compact('formAction',
            'formData', 'token'));
    }

    public static function formData(int|string $id): array {
        $model = self::model($id);
        if (empty($model) || $model->type != 1) {
            return [];
        }
        return static::cache()->getOrSet(__FUNCTION__,
            $model->id,
            function () use ($model) {
                $items = self::fieldList($model->id);
                $data = [];
                foreach ($items as $item) {
                    if ($item->is_disable) {
                        continue;
                    }
                    $data[] = [
                        'name' => $item->name,
                        'field' => $item->field,
                        'type' => $item->type,
                        'length' => $item->length,
                        'is_required' => $item->is_required,
                        'match' => $item->match,
                        'tip_message' => $item->tip_message,
                        'error_message' => $item->error_message,
                    ];
                }
                return $data;
            });
    }

    public static function formColumns(int|string $id): array {
        return self::formData($id);
    }

    public static function contentUrl(array $data): string {
        $args = [
            'id' => $data['id'],
            'category' => $data['cat_id'],
            'model' => $data['model_id']
        ];
        if (isset($_GET['preview'])) {
            $args['preview'] = $_GET['preview'];
        }
        return url('./content', $args);
    }

    public static function url(mixed $data) {
        if (is_array($data)) {
            return self::contentUrl($data);
        }
        $args = [];
        if (isset($_GET['preview'])) {
            $args['preview'] = $_GET['preview'];
        }
        if (!is_null($data) && Str::endWith($data, ',false')) {
            return url(substr($data, 0, strlen($data) - 6), $args, null, false);
        }
        return url($data, $args);
    }

    protected static function getCommentQuery(array $param) {
        $data = [];
        $contentId = static::getVal($param, ['content_id', 'article'], '');
        if (!empty($contentId)) {
            $param['content_id'] = $contentId;
        }
        if (static::hasVal($param, ['model', 'model_id'])) {
            if (isset($param['model'])) {
                $data['model_id'] = self::model($param['model'])->id;
            }
        } elseif (static::hasVal($param, ['category', 'cat_id', 'cat', 'channel'])) {
            $category = self::getCategoryId($param);
            $modelId = static::channel($category, 'model_id');
            if (!empty($modelId)) {
                $data['model_id'] = $modelId;
            }
        } else {
            $data['model_id'] = static::$current['model'];
            if (empty($contentId)) {
                $param['content_id'] = static::$current['content'];
            }
        }
        $keywords = static::getVal($param, ['keywords', 'keyword', 'query'], '');
        $extra = static::getVal($param, ['extra'], '');
        $page = intval(static::getVal($param, ['page'], 1));
        $param['parent_id'] = static::getVal($param, ['parent_id', 'parent'], 0);
        $order = static::getVal($param, ['order', 'orderBy'], 'created_at desc');
        if (strpos($order, '_') > 0) {
            $order = preg_replace('/_(desc|asc)/i', ' $1', $order);
        }
        if ($order === 'hot') {
            $order = 'agree_count desc';
        }
        $perPage = static::getVal($param, ['per_page', 'size', 'num', 'limit'], 20);
        $tags = ['user_id', 'parent_id', 'model_id', 'content_id'];
        // 通过标签提前过滤
        foreach ($param as $key => $value) {
            if (in_array($key, $tags)) {
                continue;
            }
            $data[$key] = $value;
        }
        return [$keywords, $data, $order, $extra, $page, $perPage];
    }

    public static function comments(array $params = []) {
        $data = self::getCommentQuery($params);
        return static::cache()->getOrSet(__FUNCTION__,
            md5(Json::encode($data)),
            function () use ($data) {
                if (!isset($data[1]['model_id'])) {
                    return new Page(0);
                }
                $scene = CMSRepository::scene()->setModel(self::model($data[1]['model_id']));
                return $scene->searchComment(...$data);
            });
    }

    public static function commentHidden(): string {
        return sprintf('%s%s%s%s', Form::hidden('model', static::$current['model']),
            Form::hidden('article', static::$current['content']), Form::hidden('category', static::$current['channel']), Form::token());
    }

    /**
     * 生成搜索链接
     * @param string $name
     * @param string $val
     * @return string
     * @throws \Exception
     */
    public static function search(string $name, string $val = ''): string {
        $data = request()->get();
        unset($data['page']);
        if (empty($val)) {
            unset($data[$name]);
        }
        $data[$name] = $val;
        return url('./category/list', $data);
    }

    /**
     *
     * @param string $name
     * @param string $val
     * @return string
     * @throws \Exception
     */
    public static function searchActive(string $name, string $val = '') {
        $request = request();
        if ($request->get($name, '') === $val) {
            return ' active';
        }
        return '';
    }

    /**
     * 搜索表单中的隐藏字段
     * @return string
     * @throws \Exception
     */
    public static function searchHidden() {
        $data = request()->get();
        unset($data['page'], $data['keywords']);
        $html = '';
        foreach ($data as $key => $value) {
            if (empty($value)) {
                continue;
            }
            if (!is_array($value)) {
                $html .= Form::hidden(HtmlHelper::text($key), HtmlHelper::text($value));
                continue;
            }
            foreach ($value as $val) {
                $html .= Form::hidden(HtmlHelper::text($key).'[]', HtmlHelper::text($val));
            }
        }
        return $html;
    }

    public static function regex(mixed $input, string $pattern, int|string $tag = 0): string {
        if (preg_match($pattern, (string)$input, $match)) {
            return $match[intval($tag)] ?? '';
        }
        return '';
    }

    public static function authGuest(): bool {
        return auth()->guest();
    }

    public static function authUser(): ?array {
        return self::cache()->getOrSet(__FUNCTION__, __FUNCTION__, function () {
            return UserRepository::getCurrentProfile('last_ip');
        });
    }

    public static function registerFunc(ParserCompiler $compiler, $class, $method = null) {
        if (empty($method)) {
            list($class, $method) = [static::class, $class];
        }
        foreach ((array)$method as $item) {
            $compiler->registerFunc($item, sprintf('%s::%s', $class, $item));
        }
        return $compiler;
    }

    public static function registerBlock(ParserCompiler $compiler, string $method, string $item = 'item') {
        return $compiler->registerFunc($method,
            function ($params = null) use ($method, $item, $compiler) {
            if ($params === -1) {
                return $compiler->block('endforeach;');
            }
            $i = count(static::$index);
            $box = sprintf('$_%s_%d', $method, $i);
            static::$index[] = $box;
            return $compiler->block('%s=%s::%s(%s); foreach(%s as $%s):', $box,
                static::class, $method, $params, $box, $item);
        }, true);
    }

    public static function register(ParserCompiler $compiler) {
        static::registerFunc($compiler, [
            'channel',
            'channelRoot',
            'channelActive',
            'content',
            'formContent',
            'field',
            'markdown',
            'formAction',
            'location',
            'previous',
            'next',
            'option',
            'contentUrl',
            'url',
            'search',
            'searchActive',
            'searchHidden',
            'commentHidden',
            'linkageText',
            'extendContent',
            'extendForm',
            'regex',
            'authGuest',
            'authUser'
        ]);
        static::registerBlock($compiler, 'comments', 'comment');
        static::registerBlock($compiler, 'channels', 'channel');
        static::registerBlock($compiler, 'contents', 'content');
        static::registerBlock($compiler, 'formColumns');
        static::registerBlock($compiler, 'linkage');
        static::registerBlock($compiler, 'range');
        static::registerBlock($compiler, 'contentPage', 'content')
            ->registerFunc('pager', function ($index = 0) use ($compiler) {
                if ($index < 1) {
                    $index = count(static::$index) + $index;
                }
                $tag = static::$index[$index - 1];
                if (strpos($tag, 'channel') > 0) {
                    return '';
                }
                return $compiler->echo('%s->getLink()', $tag);
            })
            ->registerFunc('redirect', '\Infrastructure\HtmlExpand::toUrl');
        return $compiler;
    }
}