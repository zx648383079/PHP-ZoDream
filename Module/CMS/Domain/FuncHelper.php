<?php
namespace Module\CMS\Domain;

use Infrastructure\HtmlExpand;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Module;
use Module\Document\Domain\Model\FieldModel;
use Module\Template\Domain\Model\Base\OptionModel;
use Zodream\Html\Page;
use Zodream\Html\Tree;
use Zodream\Infrastructure\Support\Html;
use Zodream\Template\Engine\ParserCompiler;
use Zodream\Helpers\Tree as TreeHelper;

class FuncHelper {

    public static $current = [
        'channel' => 0,
        'content' => 0,
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
        return static::getOrSet(__FUNCTION__, $code, function () use ($code) {
            return OptionModel::findCode($code);
        });
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
        $item = static::getOrSet(__FUNCTION__, $model, function () use ($model) {
            if (is_numeric($model)) {
                return ModelModel::find($model);
            }
            return ModelModel::where('`table`', $model)->first();
        });
        if (empty($item)) {
            return $item;
        }
        if (is_numeric($model)) {
            return static::getOrSet(__FUNCTION__, $item->table, $item);
        }
        return static::getOrSet(__FUNCTION__, $item->id, $item);
    }

    /**
     * @param array|null $params
     * @return Page
     */
    public static function contents(array $params = null) {
        $data = self::getContentsQuery($params);
        return static::getOrSet(__FUNCTION__,
            md5(json_encode($data)),
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
        $fields = static::getVal($param, ['field', 'fields']);
        $order = static::getVal($param, ['order', 'orderBy'], 'updated_at desc');
        if (strpos($order, '_') > 0) {
            $order = preg_replace('/_(desc|asc)/i', ' $1', $order);
        }
        if ($order === 'hot') {
            $order = 'view_count desc';
        }
        $per_page = static::getVal($param, ['per_page', 'size', 'num', 'limit'], 20);
        $tags = ['model', 'keywords', 'keyword', 'query', 'page', 'field', 'fields', 'order', 'orderBy', 'per_page', 'size', 'num', 'limit', 'category', 'cat_id', 'cat', 'channel'];
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
        $data = static::getOrSet(__FUNCTION__, function () {
            $cat = static::channel(static::$current['channel'], true);
            $scene = Module::scene()->setModel($cat->model);
            return $scene->query()->where('id', '<', static::$current['content'])
                ->orderBy('id', 'desc')->first();
        });
        return static::getContentValue($name, $data, static::$current['channel']);
    }

    public static function next($name = null) {
        $data = static::getOrSet(__FUNCTION__, function () {
            $cat = static::channel(static::$current['channel'], true);
            $scene = Module::scene()->setModel($cat->model);
            return $scene->query()->where('id', '>', static::$current['content'])
                ->orderBy('id', 'asc')->first();
        });
        return static::getContentValue($name, $data, static::$current['channel']);
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
                return ModelFieldModel::where('model_id', $model_id)->all();
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
        return self::getContentValue($name, $category, $data);
    }

    /**
     * @param $name
     * @param $category
     * @param $data
     * @return null|string|mixed
     * @throws \Exception
     */
    protected static function getContentValue($name, $data, $category) {
        if (empty($data)) {
            return null;
        }
        if ($name === true) {
            return $data;
        }
        if ($name === 'url') {
            return url('./content', ['id' => $data['id'], 'category' => $category]);
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

    public static function formAction($id) {
        return url('./form/save', compact('id'));
    }

    public static function register(ParserCompiler $compiler) {
        $compiler->registerFunc('channel', '\Module\CMS\Domain\FuncHelper::channel')
            ->registerFunc('channelActive', '\Module\CMS\Domain\FuncHelper::channelActive')
            ->registerFunc('channels', function ($params = null) {
                if ($params === -1) {
                    return '<?php endforeach; ?>';
                }
                $i = count(static::$index);
                $box = '$_channels_'.$i;
                $item = 'channel';
                static::$index[] = $box;
                return sprintf('<?php %s=\Module\CMS\Domain\FuncHelper::channels(%s); foreach(%s as $%s):?>', $box, $params, $box, $item);
            }, true)
            ->registerFunc('contents', function ($params = null) {
                if ($params === -1) {
                    return '<?php endforeach; ?>';
                }
                $i = count(static::$index);
                $box = '$_contents_'.$i;
                $item = 'content';
                static::$index[] = $box;
                return sprintf('<?php %s=\Module\CMS\Domain\FuncHelper::contents(%s); foreach(%s as $%s):?>', $box, $params, $box, $item);
            }, true)
            ->registerFunc('contentPage', function ($params = null) {
                if ($params === -1) {
                    return '<?php endforeach; ?>';
                }
                $i = count(static::$index);
                $box = '$_contents_page_'.$i;
                $item = 'content';
                static::$index[] = $box;
                return sprintf('<?php %s=\Module\CMS\Domain\FuncHelper::contentPage(%s); foreach(%s as $%s):?>', $box, $params, $box, $item);
            }, true)
            ->registerFunc('pager', function ($index = 0) {
                if ($index < 1) {
                    $index = count(static::$index) + $index;
                }
                $tag = static::$index[$index - 1];
                if (strpos($tag, 'channel') > 0) {
                    return '';
                }
                return sprintf('<?=%s->getLink()?>', $tag);
            })
            ->registerFunc('content', '\Module\CMS\Domain\FuncHelper::content')
            ->registerFunc('field', '\Module\CMS\Domain\FuncHelper::field')
            ->registerFunc('markdown', '\Module\CMS\Domain\FuncHelper::markdown')
            ->registerFunc('formAction', '\Module\CMS\Domain\FuncHelper::formAction')
            ->registerFunc('location', '\Module\CMS\Domain\FuncHelper::location')
            ->registerFunc('previous', '\Module\CMS\Domain\FuncHelper::previous')
            ->registerFunc('next', '\Module\CMS\Domain\FuncHelper::next')
            ->registerFunc('option', '\Module\CMS\Domain\FuncHelper::option')
            ->registerFunc('url', '<?= $this->url(%s, isset($_GET[\'preview\']) ? [\'preview\' => $_GET[\'preview\']] : []) ?>')
            ->registerFunc('redirect', '\Infrastructure\HtmlExpand::toUrl');
        return $compiler;
    }
}