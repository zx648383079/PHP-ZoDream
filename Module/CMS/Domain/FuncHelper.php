<?php
namespace Module\CMS\Domain;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Module;
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

    protected static function getOrSet($func, $key, callable $callback) {
        if (!isset(static::$cache[$func])) {
            static::$cache[$func] = [];
        }
        if (isset(static::$cache[$func][$key])
            || array_key_exists($key, static::$cache[$func])) {
            return static::$cache[$func][$key];
        }
        return static::$cache[$func][$key] = call_user_func($callback);
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
                $data = CategoryModel::query()->all();
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

    public static function contents(array $params = null) {
        $category = static::getVal($params, ['category', 'cat_id', 'cat', 'channel']);
        if (!empty($category) && !is_numeric($category)) {
            $category = static::getChannelId($category);
        }
        if (empty($category)) {
            $category = static::$current['channel'];
        }
        $keywords = static::getVal($params, ['keywords', 'keyword', 'query']);
        $page = intval(static::getVal($params, ['page']));
        $fields = static::getVal($params, ['fields']);
        $order = static::getVal($params, ['order', 'orderBy'], 'updated_at desc');
        if ($order === 'hot') {
            $order = 'view_count desc';
        }
        $per_page = static::getVal($params, ['per_page', 'size', 'num', 'limit'], 20);
        return static::getOrSet(__FUNCTION__,
            sprintf('%s-%s-%s-%s-%s-%s', $category, $keywords, $page, $per_page, $fields, $order),
            function () use ($category, $keywords, $page, $per_page, $fields, $order) {
            $children = static::channels(['children' => $category]);
            $cat = static::channel($category, true);
            if (empty($cat) || !$cat->model) {
                return new Page(0);
            }
            $children[] = $category;
            $scene = Module::scene()->setModel($cat->model);
            return $scene->search($keywords, $children, $order, $page, $per_page, $fields);
        });
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

    public static function next() {
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
        $id = intval($id);
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

    public static function channelActive($id) {
        if (intval($id) === intval(static::$current['channel'])) {
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
            ->registerFunc('formAction', '\Module\CMS\Domain\FuncHelper::formAction')
            ->registerFunc('location', '\Module\CMS\Domain\FuncHelper::location')
            ->registerFunc('previous', '\Module\CMS\Domain\FuncHelper::previous')
            ->registerFunc('next', '\Module\CMS\Domain\FuncHelper::next')
            ->registerFunc('option', '\Module\CMS\Domain\FuncHelper::option');
        return $compiler;
    }
}