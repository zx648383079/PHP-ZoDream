<?php
namespace Module\CMS\Domain;

use Infrastructure\HtmlExpand;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\LinkageModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Module;
use Module\SEO\Domain\Option;
use Zodream\Database\Query\Builder;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;
use Zodream\Html\Page;
use Zodream\Html\Tree;
use Zodream\Infrastructure\Support\Html;
use Zodream\Template\Engine\ParserCompiler;
use Zodream\Helpers\Tree as TreeHelper;

class FuncHelper {

    public static $current = [
        'channel' => 0,
        'content' => 0,
        'model' => 0,
    ];

    protected static $index = [];

    protected static $cache = [];

    protected static function getOrSet($func, $key, $callback) {
        if (!isset(static::$cache[$func])) {
            static::$cache[$func] = [];
        }
        if (isset(static::$cache[$func][$key])
            || array_key_exists($key, static::$cache[$func])) {
            return static::$cache[$func][$key];
        }
        return static::$cache[$func][$key] = is_callable($callback) ?
            call_user_func($callback) : $callback;
    }

    public static function option($code) {
        if (empty($code)) {
            return null;
        }
        return Option::value($code);
    }

    public static function channels(array $params = null) {
        $data = static::getOrSet(__FUNCTION__, 'all',
            function () {
                $data = CategoryModel::query()->orderBy('position', 'asc')->all();
                static::setChannel(...$data);
                return $data;
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
            $data = static::getOrSet(__FUNCTION__,
                sprintf('tree-%s', isset($params['group']) ? $params['group'] : ''),
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
    public static function model($model) {
        static::getOrSet(__FUNCTION__, 'all', function () use ($model) {
            $data = ModelModel::query()->all();
            static::setModel(...$data);
            return $data;
        });
        $item = static::getOrSet(__FUNCTION__, $model, function () use ($model) {
            if (is_numeric($model)) {
                return ModelModel::find($model);
            }
            return ModelModel::query()->where('`table`', $model)->first();
        });
        if (empty($item)) {
            return $item;
        }
        if (is_numeric($model)) {
            return static::getOrSet(__FUNCTION__, $item->table, $item);
        }
        return static::getOrSet(__FUNCTION__, $item->id, $item);
    }

    protected static function setModel(...$data) {
        foreach ($data as $item) {
            if (!isset(static::$cache['model'][$item['id']])) {
                static::$cache['model'][$item['id']] = $item;
            }
            if (!isset(static::$cache['model'][$item['table']])) {
                static::$cache['model'][$item['table']] = $item;
            }
        }
    }

    /**
     * @param array|null $params
     * @return Page
     */
    public static function contents(array $params = null) {
        $data = self::getContentsQuery($params);
        return static::getOrSet(__FUNCTION__,
            md5(Json::encode($data)),
            function () use ($data) {
            if (isset($data[1]['model_id'])) {
                $scene = Module::scene()->setModel(self::model($data[1]['model_id']));
                return $scene->search(...$data);
            }
            $category = $data[1]['cat_id'];
            $children = static::channels(['children' => $category]);
            $cat = static::channel($category, true);
            if (empty($cat) || !$cat->model) {
                return new Page(0);
            }
            $children[] = $category;
            $data[1]['cat_id'] = $children;
            $scene = Module::scene()->setModel($cat->model);
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
        $keywords = static::getVal($param, ['keywords', 'keyword', 'query']);
        $page = intval(static::getVal($param, ['page'], 1));
        $param['parent_id'] = static::getVal($param, ['parent_id', 'parent'], 0);
        $fields = static::getVal($param, ['field', 'fields']);
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
        $params['page'] = max(intval(app('request')->get('page')), 1);
        if (!isset($params['per_page'])) {
            $params['per_page'] = intval(app('request')->get('per_page', 20));
        }
        return static::contents($params);
    }

    public static function location() {
        $path = static::getOrSet(__FUNCTION__, __FUNCTION__, function () {
            $path = TreeHelper::getTreeParent(static::channels(), static::$current['channel']);
            $path[] = static::$current['channel'];
            return $path;
        });
        return implode('', array_map(function ($id) {
            return Html::li(Html::a(static::channel($id, 'title'),
                static::channel($id, 'url')));
        }, $path));
    }

    public static function previous($name = null) {
        $data = static::getOrSet(__FUNCTION__, static::$current['content'], function () {
            $cat = static::channel(static::$current['channel'], true);
            $scene = Module::scene()->setModel($cat->model);
            return $scene->query()->where('id', '<', static::$current['content'])
                ->orderBy('id', 'desc')->first();
        });
        return static::getContentValue($name, $data);
    }

    public static function next($name = null) {
        $data = static::getOrSet(__FUNCTION__, static::$current['content'], function () {
            $cat = static::channel(static::$current['channel'], true);
            $scene = Module::scene()->setModel($cat->model);
            return $scene->query()->where('id', '>', static::$current['content'])
                ->orderBy('id', 'asc')->first();
        });
        return static::getContentValue($name, $data);
    }

    protected static function getChannelId($val, $key = 'name') {
        $data = static::channels();
        foreach ($data as $item) {
            if ($item[$key] === $val) {
                return $item['id'];
            }
        }
        return 0;
    }

    protected static function setChannel(...$data) {
        foreach ($data as $item) {
            if (isset(static::$cache['channel'][$item['id']])) {
                return;
            }
            $item['model'] = self::model($item['model_id']);
            static::$cache['channel'][$item['id']] = $item;
        }
    }

    /***
     * 获取字段集合
     * @param $model_id
     * @return mixed
     */
    public static function fieldList($model_id) {
        return static::getOrSet(__FUNCTION__,
            $model_id,
            function () use ($model_id) {
                return ModelFieldModel::query()->where('model_id', $model_id)->all();
            });
    }

    /**
     * 获取摸一个字段
     * @param $model_id
     * @param $field
     * @return bool
     */
    public static function getField($model_id, $field) {
        $field_list = static::fieldList($model_id);
        foreach ($field_list as $item) {
            if ($item['field'] == $field) {
                return $item;
            }
        }
        return false;
    }

    public static function field(array $params) {
        $category = self::getCategoryId($params);
        $field = static::getOrSet(__FUNCTION__,
            sprintf('%s-%s', $category, $params['field']),
            function () use ($category, $params) {
                $cat = static::channel($category, true);
                if (empty($cat) || $cat->model_id < 1) {
                    return null;
                }
                return static::getField($cat->model_id, $params['field']);
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

    public static function markdown($content) {
        return HtmlExpand::toHtml($content, true);
    }

    /**
     * @param $id
     * @param null $name
     * @return CategoryModel|string
     * @throws \Exception
     */
    public static function channel($id, $name = null) {
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
        $data = static::getOrSet(__FUNCTION__, $id, function () use ($id) {
            return CategoryModel::find($id);
        });
        $data['model'] = self::model($data->model_id);
        if ($name === true) {
            return $data;
        }
        return $data[$name];
    }

    /**
     * @param array|integer $id
     * @param null|string $name
     * @param integer $category
     * @return array|string
     * @throws \Exception
     */
    public static function content($id, $name = null, $category = null) {
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
        $data = static::getOrSet(__FUNCTION__, sprintf('%s:%s', $category, $id),
            function () use ($id, $category) {
                $cat = static::channel($category, true);
                $scene = Module::scene()->setModel($cat->model);
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
        $data = static::getOrSet(__FUNCTION__, sprintf('%s:%s:%s', $category, $model->id, $user),
            function () use ($model, $category, $user) {
                $scene = Module::scene()->setModel($model);
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
     * @param $name
     * @param $data
     * @return null|string|mixed
     */
    protected static function getContentValue($name, $data) {
        if (empty($data)) {
            return null;
        }
        if ($name === true) {
            return $data;
        }
        if ($name === 'url') {
            return self::url($data);
        }
        return isset($data[$name]) ? $data[$name] : null;
    }

    public static function isChildren($parent_id, $id) {
        if ($parent_id === $id) {
            return 0;
        }
        $children = static::channels(['children' => $parent_id]);
        if (!empty($children) && in_array($id, $children)) {
            return 1;
        }
        return -1;
    }

    public static function channelActive($id) {
        $id = intval($id);
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

    public static function linkage($id) {
        if (!is_numeric($id)) {
            $id = LinkageModel::query()->where('code', $id)->value('id');
        }
        if (empty($id)) {
            return [];
        }
        return LinkageModel::idTree($id);
    }

    public static function range($start, $end, $step = 1) {
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
     * @param $id
     * @return string
     */
    public static function formAction($id) {
        return url('./form/save', compact('id'));
    }

    public static function contentUrl(array $data) {
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

    public static function url($data) {
        if (is_array($data)) {
            return self::contentUrl($data);
        }
        $args = [];
        if (isset($_GET['preview'])) {
            $args['preview'] = $_GET['preview'];
        }
        if (Str::endWith($data, ',false')) {
            return url(substr($data, 0, strlen($data) - 6), $args, true, false);
        }
        return url($data, $args);
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

    public static function registerBlock(ParserCompiler $compiler, $method, $item = 'item') {
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
        ]);
        static::registerBlock($compiler, 'channels', 'channel');
        static::registerBlock($compiler, 'contents', 'content');
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