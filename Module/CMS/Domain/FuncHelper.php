<?php
declare(strict_types=1);
namespace Module\CMS\Domain;

use Domain\Providers\MemoryCacheProvider;
use Domain\Repositories\FileRepository;
use Infrastructure\HtmlExpand;
use Module\Auth\Domain\Repositories\UserRepository;
use Module\CMS\Domain\Fields\BaseField;
use Module\CMS\Domain\Middleware\CMSSeoMiddleware;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Repositories\CacheRepository;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\LinkageRepository;
use Module\CMS\Domain\Repositories\SiteRepository;
use Module\CMS\Domain\Scene\BaseScene;
use Zodream\Database\Query\Builder;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;
use Zodream\Html\Form;
use Zodream\Html\Page;
use Zodream\Html\Tree;
use Zodream\Infrastructure\Support\Html;
use Zodream\Template\Engine\ParserCompiler;
use Zodream\Helpers\Tree as TreeHelper;
use Zodream\Helpers\Html as HtmlHelper;
use Zodream\Template\ViewFactory;

class FuncHelper {

    public static array $current = [
        'channel' => 0,
        'content' => 0,
        'model' => 0,
    ];

    public static array $translateItems = [];
    protected static array $index = [];

    public static function cache(): MemoryCacheProvider {
        return MemoryCacheProvider::getInstance();
    }

    /**
     * 翻译
     * @param string $format
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public static function translate(string $format, array $data = []): mixed {
        if (isset(static::$translateItems[$format])) {
            return trans()->format(static::$translateItems[$format], $data);
        }
        return trans()->translate($format, $data);
    }

    public static function option(string $code, string $type = '') {
        if (empty($code)) {
            return null;
        }
        if ($code === 'mapApiKey') {
            return config('thirdparty.baidu.map');
        }
        $options = static::cache()->getOrSet(__FUNCTION__, 'all', function () {
            return CacheRepository::getOptionCache();
        });
        $val = $options[$code] ?? null;
        if ($type === 'number') {
            return static::toNumber($val);
        }
        return $val;
    }

    protected static function toNumber(mixed $value): string|int {
        if (is_numeric($value)) {
            return $value;
        }
        if (preg_match('/\d+/', (string)$value, $match)) {
            return $match[0];
        }
        return intval($value);
    }

    /**
     * 转化为内容使用的id
     * @param string|int $name
     * @param 'model'|'channel'|'linkage' $type
     * @return int
     */
    public static function mapId(string|int $name, string $type = 'model'): int {
        if (is_numeric($name)) {
            return intval($name);
        }
        $map = static::cache()->getOrSet(__FUNCTION__, 'all', function () {
            return CacheRepository::getMapCache();
        });
        return intval($map[$type][$name] ?? 0);
    }

    public static function channels(array $params = null): mixed {
        /** @var TreeObject $data */
        $data = static::cache()->getOrSet(__FUNCTION__, 'all',
            function () {
                $items = CacheRepository::getChannelCache();
                static::setChannel(...$items);
                return $items;
            });
        if (empty($params)) {
            return $data;
        }
        if (isset($params['children'])) {
            return $data->getChildrenId(static::getChannelId($params['children']), false);
        }
        if (isset($params['group'])) {
            $data = array_filter($data instanceof TreeObject ? $data->all() : $data, function ($item) use ($params) {
                $groups = is_array($item['groups']) ? $item['groups'] : explode(',', $item['groups']);
                return in_array($params['group'], $groups);
            });
        }
        if (isset($params['parent'])) {
            $params['parent'] = static::getChannelId($params['parent']);
            $data = array_filter($data instanceof TreeObject ? $data->all() : $data, function ($item) use ($params) {
                return $item['parent_id'] == $params['parent'];
            });
        } elseif (isset($params['tree'])) {
            $data = static::cache()->getOrSet(__FUNCTION__,
                sprintf('tree-%s', $params['group'] ?? ''),
                function () use ($data) {
                    return $data instanceof TreeObject ? $data->toIdTree() : (new Tree($data))->makeIdTree();
                });
        }
        if (isset($params['id'])) {
            $ids = array_map('intval', explode(',', $params['id']));
            $data = array_filter($data instanceof TreeObject ? $data->all() : $data,
                function ($item) use ($ids) {
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
     * @return array
     */
    public static function model(string|int $model): array {
        $modelId = self::mapId($model, 'model');
        return static::cache()->getOrSet(__FUNCTION__, $modelId, function () use ($modelId) {
            return CacheRepository::getModelCache($modelId);
        });
    }

    protected static function setModel(...$data): void {
        foreach ($data as $item) {
            static::cache()->trySet('model', $item['id'], $item);
            static::cache()->trySet('model', $item['table'], $item);
        }
    }

    /**
     * @param array|null $params
     * @return Page
     */
    public static function contents(array $params = null): Page {
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

    /**
     * 提前过滤查询参数
     * @param array $param
     * @return array
     */
    protected static function getContentsQuery(array $param): array {
        $data = [];
        if (isset($param['model'])) {
            $data['model_id'] = self::mapId($param['model'], 'model');
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
        $isPage = true;
        if (isset($param['num']) || isset($param['limit'])) {
            $isPage = false;
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
            if (in_array($key, $tags) || empty($value)) {
                continue;
            }
            $data[$key] = $value;
        }
        $modelId = $data['model_id'] ?? 0;
        if ($modelId < 1) {
            $modelId = intval(static::channel($data['cat_id'], 'model_id'));
        }
        $fieldItems = static::fieldList($modelId);
        foreach ($fieldItems as $item) {
            if (!isset($data[$item['field']])) {
                continue;
            }
            if ($item['is_disable']) {
                unset($data[$item['field']]);
                continue;
            }
            if ($item['type'] === 'linkage' ||
                $item['type'] === 'linkages') {
                $data[$item['field']] = self::treeChildId(
                    CacheRepository::getLinkageCache(
                        static::mapId(BaseField::fieldSetting($item,
                            'option', 'linkage_id'), 'linkage')
                    ), intval($data[$item['field']]));
            }
        }
        if (!CMSRepository::isPreview()) {
            $data['status'] = SiteRepository::PUBLISH_STATUS_POSTED;
        }
        return [$keywords, $data, $order, $page, $per_page, $fields, $isPage];
    }

    /**
     * 获取自身及子孙id
     * @param array|TreeObject $data
     * @param int|string $parent
     * @return array
     */
    protected static function treeChildId(array|TreeObject $data, int|string $parent): array {
        if ($data instanceof TreeObject) {
            return $data->getChildrenId(intval($parent), true);
        }
        if (empty($data)) {
            return [$parent];
        }
        $items = TreeHelper::getTreeChild($data, $parent);
        $items[] = $parent;
        return $items;
    }

    /**
     * 获取分页内容
     * @param array|null $params
     * @return mixed
     * @throws \Exception
     */
    public static function contentPage(array $params = null): mixed {
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
            $path = static::channels()->getParentId(static::$current['channel']);
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

    public static function previous(string|bool $name = true) {
        $data = static::cache()->getOrSet(__FUNCTION__, static::$current['content'], function () {
            $cat = static::channel(static::$current['channel'], true);
            $catModel = static::model($cat['model_id']);
            $scene = CMSRepository::scene()->setModel($catModel);
            return $scene->query()->where('id', '<', static::$current['content'])
                ->orderBy('id', 'desc')->first();
        });
        return static::getContentValue($name, $data);
    }

    public static function next(string|bool $name = true) {
        $data = static::cache()->getOrSet(__FUNCTION__, static::$current['content'], function () {
            $cat = static::channel(static::$current['channel'], true);
            $catModel = static::model($cat['model_id']);
            $scene = CMSRepository::scene()->setModel($catModel);
            return $scene->query()->where('id', '>', static::$current['content'])
                ->orderBy('id', 'asc')->first();
        });
        return static::getContentValue($name, $data);
    }

    protected static function getChannelId(string|int $val): int {
        return self::mapId($val, 'channel');
    }

    protected static function setChannel(...$data): void {
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
     * @return array[]
     */
    public static function fieldList(string|int $model_id): array {
        return static::cache()->getOrSet(__FUNCTION__,
            $model_id,
            function () use ($model_id) {
                $data = CacheRepository::getModelCache($model_id);
                return $data['field_items'] ?? [];
            });
    }

    /**
     * 获取摸一个字段
     * @param string|int $model_id
     * @param string $field
     * @return array|ModelFieldModel|null
     */
    public static function getField(string|int $model_id, string $field): array|null|ModelFieldModel {
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
                    if ($item['is_disable']) {
                        continue;
                    }
                    if (!$item['is_system'] && $item['is_search'] && !in_array($item['field'], $fields)) {
                        $fields[] = $item['field'];
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
            if (isset($id['name']) && is_null($name)) {
                $name = $id['name'];
            }
            $id = $id['id'];
        }
        if (!is_numeric($id)) {
            $id = static::getChannelId($id);
        }
        if ($id < 1) {
            return null;
        }
        $data = static::cache()->getOrSet(__FUNCTION__, $id, function () use ($id) {
            return CategoryModel::find($id);
        });
        if ($name === 'url') {
            if ($data['type'] > 1) {
                return empty($data['url']) ? 'javascript:;' : static::patchUrl($data['url']);
            }
            return CMSSeoMiddleware::encodeUrl($id, false);
        }
        $data['model'] = self::model($data['model_id']);
        if ($name === true) {
            return $data;
        }
        return $data[$name] ?? '';
    }

    /**
     * 根据地址自动填充内容
     * @param string $path
     * @return string
     */
    public static function patchUrl(string $path): string {
        if (!str_starts_with($path, './')) {
            return self::urlEncode($path);
        }
        $args = parse_url($path);
        if (empty($args) || !isset($args['path'])) {
            return 'javascript:;';
        }
        $path = $args['path'];
        if (empty($args['query'])) {
            return self::urlEncode($path);
        }
        $request = request();
        $data = [];
        parse_str($args['query'], $data);
        foreach ($data as $k => $value) {
            if ($value !== '') {
                continue;
            }
            $value = $request->get($k);
            if (is_null($value) || $value === '') {
                unset($data[$k]);
                continue;
            }
            $data[$k] = $value;
        }
        return self::urlEncode($path, $data);
    }

    public static function channelRoot(mixed $id) {
        $id = static::getChannelId($id);
        if (empty($id)) {
            return null;
        }
        return static::channel(static::channels()->getRootId($id), true);
    }

    /**
     * @param array|integer $id
     * @param null|string $name
     * @param string|int $category
     * @return array|string
     */
    public static function content(mixed $id, mixed $name = null, string|int $category = 0): mixed {
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
        if (!BaseField::fieldSetting($model,  'is_show')) {
            return null;
        }
        if (BaseField::fieldSetting($model,  'is_only') && $user < 1) {
            $user = auth()->id();
        }
        $data = static::cache()->getOrSet(__FUNCTION__, sprintf('%s:%s:%s', $category,
            $model['id'], $user),
            function () use ($model, $category, $user) {
                $scene = CMSRepository::scene()->setModel($model);
                return $scene->find(
                    function (Builder $query, $pre, $i) use ($category, $user, $model) {
                        if (!empty($pre) && isset($pre['id'])) {
                            $query->where('id', $pre['id']);
                            return null;
                        }
                        if ($i > 0 && empty($pre)) {
                            return false;
                        }
                    if (!empty($category)) {
                        $query->where('cat_id', self::channel($category, 'id'));
                    }
                    if (!empty($user) || BaseField::fieldSetting($model,  'is_only')) {
                        $query->where('user_id', intval($user));
                    }
                    return null;
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
        if ($name === 'user_name') {
            if (empty($data['user_id'])) {
                return '';
            }
            $user = self::cache()->getOrSet('user', $data['user_id'], function () use ($data) {
                return UserRepository::getPublicProfile(intval($data['user_id']));
            });
            return empty($user) ? '' : $user['name'];
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

    public static function hasChannelLink(mixed $item, mixed $target): int {
        return static::channels()->hasLink(is_array($item) ? $item['id'] : $item,
            is_array($target) ? $target['id'] : $target);
    }

    public static function hasLinkageLink(mixed $item, mixed $target, int|string $linkageId): int {
        return static::linkageData($linkageId)->hasLink(is_array($item) ? $item['id'] : $item,
            is_array($target) ? $target['id'] : intval($target));
    }

    public static function linkageData(int|string $id): TreeObject {
        $id = static::mapId($id, 'linkage');
        return static::cache()->getOrSet(__FUNCTION__, $id, function () use ($id) {
            return CacheRepository::getLinkageCache($id);
        });
    }

    public static function linkage(int|string $id, int $dataId = 0, string $key = ''): mixed {
        $id = static::mapId($id, 'linkage');
        if (empty($id)) {
            return empty($dataId) ? [] : null;
        }
        if (empty($dataId)) {
            return static::cache()->getOrSet(__FUNCTION__, $id, function () use ($id) {
                return LinkageRepository::dataTree($id);
            });
        }
        $data = static::cache()->getOrSet('linkageDataItem', $dataId, function () use ($id, $dataId) {
           $items = static::linkageData($id);
           foreach ($items as $item) {
               if ($item['id'] === $id) {
                   return $item;
               }
           }
           return null;
        });
        if (empty($data)) {
            return null;
        }
        if (empty($key)) {
            return $data;
        }
        return $data[$key] ?? '';
    }

    public static function linkageText(int|string $id): string {
        if (empty($id)) {
            return '';
        }
        return static::cache()->getOrSet(__FUNCTION__, $id, function () use ($id) {
            return (string)LinkageDataModel::where('id', $id)->value('full_name');
        });
    }

    /**
     * 生成一段连续数字数组
     * @param string|int $start
     * @param string|int $end
     * @param string|int $step
     * @return array
     */
    public static function range(string|int $start, string|int $end, string|int $step = 1): array {
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
        return self::urlEncode('./form/save', [
            is_numeric($id) ? 'id' : 'model' => $id
        ]);
    }

    /**
     * 追加数据，只针对附属表的数据追加
     * @param $data
     * @param mixed ...$fields
     * @throws \Exception
     */
    public static function extendContent(&$data, ...$fields): void {
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
        if (empty($model) || $model['type'] != 1) {
            return '';
        }
        $fileName = BaseField::fieldSetting($model,  'form_template');
        if (empty($fileName)) {
            return '';
        }
        $id = $model['id'];
        return CMSRepository::viewTemporary(function (ViewFactory $factory) use ($fileName, $id) {
            $form_action = self::formAction($id);
            $field_list = self::formData($id);
            $token = session()->token();
            $factory->setLayout('');
            return $factory->render(sprintf('Form/%s', $fileName),
                compact('form_action',
                    'field_list', 'token'));
        });
    }

    public static function formData(int|string $id): array {
        $model = self::model($id);
        if (empty($model) || $model['type'] != 1) {
            return [];
        }
        return static::cache()->getOrSet(__FUNCTION__,
            $model['id'],
            function () use ($model) {
                $items = self::fieldList($model['id']);
                $data = [];
                foreach ($items as $item) {
                    if ($item['is_disable']) {
                        continue;
                    }
                    $data[$item['field']] = [
                        'id' => $item['id'],
                        'name' => $item['name'],
                        'model_id' => $item['model_id'],
                        'field' => $item['field'],
                        'type' => $item['type'],
                        'length' => $item['length'],
                        'is_disable' => $item['is_disable'],
                        'is_required' => $item['is_required'],
                        'match' => $item['match'],
                        'tip_message' => $item['tip_message'],
                        'error_message' => $item['error_message'],
                        'setting' => $item['setting']
                    ];
                }
                return $data;
            });
    }

    public static function formColumns(int|string $id): array {
        return self::formData($id);
    }

    public static function formInput(mixed $data): string {
        if (!(is_array($data) || $data instanceof ModelFieldModel)) {
            return '';
        }
        if ($data['type'] === 'model') {
            return sprintf('<input type="hidden" name="%s" value="%d">',
                $data['field'], static::$current['content']);
        }
        return CMSRepository::scene()->setModel(static::model(intval($data['model_id'])))
            ->toInput($data);
    }

    public static function formRender(array|string|int $model, string|int $id): string {
        if (!is_array($model)) {
            $model = static::model($model);
        }
        $scene = CMSRepository::scene()->setModel($model);
        $data = $scene->find(intval($id));
        if (empty($data)) {
            return '';
        }
        $fileName = BaseField::fieldSetting($model,  'show_template');
        if (empty($fileName)) {
            return '';
        }
        $id = $model['id'];
        return CMSRepository::viewTemporary(function (ViewFactory $factory) use ($fileName,
            $id, $data) {
            $field_list = self::formData($id);
            foreach ($field_list as $k => $item) {
                $field_list[$k]['value'] = BaseScene::newField($item['type'])
                    ->toText($data[$item['field']], $item);
            }
            $factory->setLayout('');
            return $factory->render(sprintf('Content/%s', $fileName),
                compact('field_list'));
        });
    }

    public static function contentUrl(array $data): string {
        return CMSSeoMiddleware::encodeUrl($data, true);
    }

    public static function url(mixed $data): string {
        if (is_array($data)) {
            return self::contentUrl($data);
        }
        $args = [];
        if (!is_null($data) && Str::endWith($data, ',false')) {
            return self::urlEncode(substr($data, 0, strlen($data) - 6), $args, null, false);
        }
        return self::urlEncode($data, $args);
    }

    /**
     * 增加传递预览值
     * @param mixed|null $path
     * @param array $parameters
     * @param bool|null $secure
     * @param bool $encode
     * @return string
     * @throws \Exception
     */
    protected static function urlEncode(mixed $path = null, array $parameters = [],
                                        ?bool $secure = null, bool $encode = true): string {
        if (CMSRepository::isPreview()) {
            $parameters[CMSRepository::PREVIEW_KEY] = $_GET[CMSRepository::PREVIEW_KEY];
        }
        return url($path, $parameters, $secure, $encode);
    }

    protected static function getCommentQuery(array $param): array {
        $data = [];
        $contentId = static::getVal($param, ['content_id', 'article'], '');
        if (!empty($contentId)) {
            $param['content_id'] = $contentId;
        }
        if (static::hasVal($param, ['model', 'model_id'])) {
            if (isset($param['model'])) {
                $data['model_id'] = self::mapId($param['model'], 'model');
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
            Form::hidden('article', static::$current['content']),
            Form::hidden('category', static::$current['channel']),
            Form::token());
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
        return self::urlEncode('./category/list', $data);
    }

    /**
     *
     * @param string $name
     * @param string $val
     * @return string
     * @throws \Exception
     */
    public static function searchActive(string $name, string $val = ''): string {
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
    public static function searchHidden(): string {
        $data = request()->get();
        unset($data['page'], $data['keywords']);
        if (self::$current['channel'] > 0) {
            $data['category'] = (string)self::$current['channel'];
        }
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

    /**
     * 获取上传文件的原始文件名
     * @param mixed $url
     * @return string
     */
    public static function fileName(mixed $url): string {
        if (empty($url)) {
            return '';
        }
        try {
            $provider = FileRepository::storage();
            $model = $provider->get($provider->fromPublicUrl($url));
            return (string)$model['name'];
        } catch (\Exception $ex) {
            return '';
        }
    }

    public static function regex(mixed $input, string $pattern, int|string $tag = 0): string {
        if (preg_match($pattern, (string)$input, $match)) {
            return $match[intval($tag)] ?? '';
        }
        return '';
    }

    public static function split(mixed $input, string $tag = ','): array {
        if (empty($input)) {
            return [];
        }
        return explode($tag, (string)$input);
    }

    public static function authGuest(): bool {
        return auth()->guest();
    }

    public static function isAjax(): bool {
        return request()->isAjax();
    }

    public static function authUser(): ?array {
        return self::cache()->getOrSet(__FUNCTION__, __FUNCTION__, function () {
            // 获取拓展表单的信息
            return UserRepository::getCurrentProfile('last_ip');
        });
    }

    public static function registerFunc(ParserCompiler $compiler, $class, $method = null): ParserCompiler {
        if (empty($method)) {
            list($class, $method) = [static::class, $class];
        }
        foreach ((array)$method as $item) {
            $compiler->registerFunc($item, sprintf('%s::%s', $class, $item));
        }
        return $compiler;
    }

    public static function registerBlock(ParserCompiler $compiler,
                                         string $method, string $item = 'item'): ParserCompiler {
        return $compiler->registerFunc($method,
            function ($params = null) use ($method, $item, $compiler) {
            if ($params === -1) {
                return 'endforeach;';
            }
            $i = count(static::$index);
            $box = sprintf('$_%s_%d', $method, $i);
            $lastKey = sprintf('$_last_%s', $method);
            static::$index[] = $box;
            return sprintf('%s=%s=%s::%s(%s); foreach(%s as $%s):', $lastKey, $box,
                static::class, $method, $params, $box, $item);
        }, true);
    }

    public static function register(ParserCompiler $compiler): ParserCompiler {
        static::registerFunc($compiler, [
            'channel',
            'channelRoot',
            'channelActive',
            'hasChannelLink',
            'hasLinkageLink',
            'content',
            'formContent',
            'field',
            'markdown',
            'formAction',
            'formInput',
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
            'authUser',
            'fileName',
            'isAjax',
        ]);
        $compiler->registerFunc('__', sprintf('%s::%s', static::class, 'translate'));
        static::registerBlock($compiler, 'comments', 'comment');
        static::registerBlock($compiler, 'channels', 'channel');
        static::registerBlock($compiler, 'contents', 'content');
        static::registerBlock($compiler, 'formColumns');
        static::registerBlock($compiler, 'linkage');
        static::registerBlock($compiler, 'range');
        static::registerBlock($compiler, 'split');
        static::registerBlock($compiler, 'contentPage', 'content')
            ->registerFunc('pager', function ($index = 0) use ($compiler) {
                if ($index < 1) {
                    $index = count(static::$index) + $index;
                }
                $tag = static::$index[$index - 1];
                if (strpos($tag, 'channel') > 0) {
                    return '';
                }
                return sprintf('%s->getLink()', $tag);
            })
            ->registerFunc('redirect', '\Infrastructure\HtmlExpand::toUrl');
        return $compiler;
    }
}