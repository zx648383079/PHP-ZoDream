<?php
namespace Module\CMS\Domain;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Module;
use Module\Template\Domain\Model\Base\OptionModel;
use Zodream\Html\Page;
use Zodream\Html\Tree;
use Zodream\Template\Engine\ParserCompiler;

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
        if (isset($params['parent'])) {
            $data = array_filter($data, function ($item) use ($params) {
                return $item['parent_id'] == $params['parent'];
            });
        } elseif (isset($params['tree'])) {
            $data = static::getOrSet(__FUNCTION__, 'tree',
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
        if (isset($params['group'])) {
            $data = array_filter($data, function ($item) use ($params) {
                $groups = is_array($item['groups']) ? $item['groups'] : explode(',', $item['groups']);
                return in_array($params['group'], $groups);
            });
        }
        return $data;
    }

    public static function contents(array $params = null) {
        $category = static::getVal($params, ['category', 'cat_id', 'cat', 'channel']);
        if (empty($category)) {
            $category = static::$current['channel'];
        }
        $keywords = static::getVal($params, ['keywords', 'keyword', 'query']);
        $page = intval(static::getVal($params, ['page']));
        $fields = static::getVal($params, ['fields']);
        $per_page = static::getVal($params, ['per_page', 'size', 'num', 'limit'], 20);
        return static::getOrSet(__FUNCTION__,
            sprintf('%s-%s-%s-%s-%s', $category, $keywords, $page, $per_page, $fields),
            function () use ($category, $keywords, $page, $per_page, $fields) {
            $cat = static::channel($category, true);
            if (empty($cat) || !$cat->model) {
                return new Page(0);
            }
            $scene = Module::scene()->setModel($cat->model);
            return $scene->search($keywords, $category, $page, $per_page, $fields);
        });
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
        if ($name === true) {
            return $data;
        }
        return isset($data[$name]) ? $data[$name] : null;
    }

    protected static function getVal(array $data, $columns, $default = null) {
        foreach ((array)$columns as $column) {
            if (!empty($column) && isset($data[$column])) {
                return $data[$column];
            }
        }
        return $default;
    }

    public static function register(ParserCompiler $compiler) {
        $compiler->registerFunc('channel', '\Module\CMS\Domain\FuncHelper::channel')
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
            ->registerFunc('option', '\Module\CMS\Domain\FuncHelper::option');
        return $compiler;
    }
}