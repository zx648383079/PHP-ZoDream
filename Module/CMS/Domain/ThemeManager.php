<?php
namespace Module\CMS\Domain;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\GroupModel;
use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Scene\MultiScene;
use Module\CMS\Module;
use Module\Forum\Domain\Model\ForumModel;
use Module\Template\Domain\Model\Base\OptionModel;
use Zodream\Database\Relation;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;
use Zodream\Disk\Directory;
use Zodream\Disk\ZipStream;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Error\Exception;
use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;

class ThemeManager {

    /**
     * @var Directory
     */
    protected $src;
    /**
     * @var Directory
     */
    protected $dist;

    protected $cache = [];

    public function __construct() {
        $this->src = new Directory(dirname(__DIR__).'/UserInterface');
        $this->dist = public_path();
    }

    /**
     * @return Directory
     */
    public function getSrc() {
        return $this->src;
    }

    public function pack() {
        $this->src = $this->src->directory('default_'.time());
        $data = [
            'name' => 'default',
            'description' => '默认主题',
            'author' => 'zodream',
            'cover' => 'assets/images/screenshot.png',
            'script' => [
                [
                    'action' => 'copy',
                    'src' => 'assets',
                    'dist' => 'assets'
                ],
            ]
        ];
        $data[] = $this->packOption();
        $data['script'] = array_merge($data['script'], $this->packModel(), $this->packChannel(), $this->packContent());
        $this->src->create();
        $this->src->addFile('theme.json', Json::encode($data));
        $zip = ZipStream::create($this->dist->file('theme.zip'));
        $zip->addDirectory($data['name'], $this->src);
        $zip->comment($data['description'])->close();
    }

    protected function packOption() {
        $data = [];
        $args = OptionModel::query()->orderBy('parent_id', 'asc')->all();
        foreach ($args as $item) {
            if ($item['parent_id'] < 1) {
                unset($item['parent_id']);
            } else {
                $item['parent_id'] = $this->getCacheId($item['parent_id'], 'option');
            }
            $this->setCache([
                $item['id'] => '@option:'.$item['code']
            ], 'option');
            unset($item['id']);
            $data[] = $item;
        }
        return [
            'action' => 'option',
            'data' => $data
        ];
    }

    protected function packModel() {
        $data = [];
        $model_list = ModelModel::query()->asArray()->all();
        foreach ($model_list as $item) {
            $item['action'] = 'model';
            $item['setting'] = Json::decode($item['setting']);
            $item['fields'] = $this->packFields($item['id']);
            $this->setCache([
                $item['id'] => '@model:'.$item['table']
            ], 'model');
            unset($item['id']);
            $data[] = $item;

        }
        return $data;
    }

    protected function packFields($model_id) {
        $data = [];
        $fields = ModelFieldModel::query()->where('model_id', $model_id)->asArray()->all();
        foreach ($fields as $item) {
            $item['setting'] = Json::decode($item['setting']);
            unset($item['model_id'], $item['id']);
            if (!in_array($item['field'], ['title', 'keywords', 'description', 'thumb', 'content'])) {
                $data[] = $item;
                continue;
            }
            if ($item['is_disable'] > 0) {
                $data[] = [
                    'action' => 'disable',
                    'field' => $item['field']
                ];
            }
        }
        return $data;
    }

    protected function packChannel($parent_id = 0) {
        $data = [];
        $model_list = CategoryModel::query()->where('parent_id', $parent_id)
            ->asArray()->all();
        foreach ($model_list as $item) {
            $item['action'] = 'channel';
            $item['setting'] = Json::decode($item['setting']);
            $children = $this->packChannel($item['id']);
            if (!empty($children)) {
                $item['children'] = $children;
            }
            $item['type'] = $this->packChannelType($item);
            $this->setCache([
                $item['id'] => '@channel:'.$item['name']
            ], 'channel');
            unset($item['model_id'], $item['parent_id'], $item['id']);
            $data[] = $item;

        }
        return $data;
    }

    protected function packChannelType($item) {
        if ($item['type'] === CategoryModel::TYPE_LINK) {
            return 'link';
        }
        if ($item['type'] === CategoryModel::TYPE_PAGE) {
            return 'page';
        }
        return $this->getCacheId($item['model_id'], 'model');
    }

    protected function packContent() {
        $data = [];
        $model_list = ModelModel::query()->all();
        foreach ($model_list as $model) {
            $scene = Module::scene()->setModel($model);
            $cats = CategoryModel::where('model_id', $model->id)->pluck('id');
            if (empty($cats)) {
                continue;
            }
            $args = $scene->query()->whereIn('cat_id', $cats)->all();
            $args = Relation::create($args, [
                'extend' => [
                    'query' => $scene->extendQuery(),
                    'link' => [
                        'id' => 'id'
                    ],
                    'type' => Relation::TYPE_ONE
                ],
            ]);
            foreach ($args as $item) {
                if (isset($item['extend']) && !empty($item['extend'])) {
                    $item = array_merge($item['extend'], $item);
                }
                $item['cat_id'] = $this->getCacheId($item['cat_id'], 'channel');
                unset($item['extend'], $item['id']);
                $item['action'] = 'content';
                $data[] = $item;
            }
        }
        return $data;
    }

    public function unpack() {
        $zip = new ZipStream($this->src->file('theme.zip'));
        $zip->extractTo($this->src);
        $this->apply('theme');
    }

    public function apply($theme = null) {
        if (!empty($theme)) {
            $this->src = $this->src->directory($theme);
        }
        $file = $this->src->file('theme.json');
        if (!$file->exist()) {
            return;
        }
        $this->setCache(GroupModel::pluck('id', 'name'), 'group');
        $this->setCache(ModelModel::query()->pluck('id', 'table'), 'model');
        $this->setCache(CategoryModel::pluck('id', 'name'), 'channel');
        $configs = Json::decode($file->read());
        $this->runScript($configs['script']);
    }

    protected function setCache($data, $prefix) {
        foreach ($data as $name => $id) {
            $this->cache[sprintf('@%s:%s', $prefix, $name)] = $id;
        }
    }

    protected function getCacheId($name, $prefix = null) {
        $key = empty($prefix) && substr($name, 0, 1) === '@' ? $name : sprintf('@%s:%s', $prefix, $name);
        return isset($this->cache[$key]) ? $this->cache[$key] : 0;
    }

    protected function runScript($data) {
        usort($data, function ($pre, $next) {
            $maps = ['group' => 1, 'linkage' => 2, 'model' => 3, 'form' => 4, 'field' => 5, 'channel' => 6, 'content' => 7];
            if (!isset($maps[$pre['action']])) {
                return -1;
            }
            if (!isset($maps[$next['action']])) {
                return 1;
            }
            if ($maps[$pre['action']] > $maps[$next['action']]) {
                return 1;
            }
            return $maps[$pre['action']] < $maps[$next['action']] ? -1 : 0;
        });
        foreach ($data as $item) {
            $method = 'runAction'.Str::studly($item['action']);
            if (method_exists($this, $method)) {
                $this->{$method}($item);
            }
        }
    }



    protected function runActionCopy($data) {
        $this->src->directory($data['src'])->copy($this->dist->directory($data['dist']));
    }

    protected function runActionGroup($data) {
        if (!isset($data['data'])) {
            $this->insertGroup($data);
        }
    }

    protected function runActionForm($data) {
        $data['type'] = 1;
        return $this->runActionModel($data);
    }

    protected function runActionLinkage($data) {
        $items = isset($data['data']) ? $data['data'] : [];
        unset($data['data'], $data['action']);
        $model = LinkageModel::create($data);
        if (!$model) {
            throw new Exception('数据错误');
        }
        $this->setCache([$model->code => $model->id, $model->id => $model], 'linkage');
        $this->runActionLinkageData($items, 0, '', $model->id);
    }

    public function runActionLinkageData(array $data, $parent_id, $prefix, $linkage_id) {
        foreach ($data as $item) {
            $children = isset($item['children']) ? $item['children'] : [];
            unset($item['children']);
            $item['parent_id'] = $parent_id;
            $item['linkage_id'] = $linkage_id;
            $item['full_name'] = $prefix.' '.$item['name'];
            $model = LinkageDataModel::create($item);
            if (!$model || empty($children)) {
                continue;
            }
            $this->runActionLinkageData($children, $model->id, $item['full_name'], $linkage_id);
        }
    }

    protected function runActionModel($data) {
        $fields = isset($data['fields']) ? $data['fields'] : [];
        unset($data['fields'], $data['action']);
        if (isset($data['setting']) && is_array($data['setting'])) {
            $data['setting'] = Json::encode($data['setting']);
        }
        $model = ModelModel::create($data);
        if (!$model) {
            throw new Exception('数据错误');
        }
        $this->setCache([$model->table => $model->id, $model->id => $model], 'model');
        Module::scene()->setModel($model)->initTable();
        foreach ($fields as $field) {
            $field['model_id'] = $model->id;
            $this->runActionField($field);
        }
    }

    protected function runActionField($data) {
        if (isset($data['model'])) {
            $data['model_id'] = $this->getCacheId($data['model']);
        }
        if (isset($data['action']) && $data['action'] === 'disable') {
            ModelFieldModel::query()->where('model_id', $data['model_id'])
                ->where('field', $data['field'])
                ->updateBool('is_disable');
            return;
        }
        unset($data['model'], $data['action']);
        if (isset($data['setting']) && is_array($data['setting'])) {
            $data['setting'] = Json::encode($data['setting']);
        }
        if (!isset($data['type'])) {
            $data['type'] = 'text';
        } elseif (strpos($data['type'], '@model:') === 0) {
            $data['setting']['option']['model'] = $this->getCacheId($data['type']);
            $data['type'] = 'model';
        } elseif (strpos($data['type'], '@linkage:') === 0) {
            $data['setting']['option']['linkage_id'] = $this->getCacheId($data['type']);
            $data['type'] = 'linkage';
        }
        $model = ModelFieldModel::create($data);
        if (!$model) {
            throw new Exception('数据错误');
        }
        $scene = Module::scene()->setModel($this->getCacheId($model->model_id, 'model'));
        $scene->addField($model);
    }

    protected function runActionChannel($data) {
        $type = isset($data['type']) ? $data['type'] : null;
        if (empty($type)) {

        } elseif ($type === 'page') {
            $data['type'] = CategoryModel::TYPE_PAGE;
        } elseif ($type === 'link') {
            $data['type'] = CategoryModel::TYPE_LINK;
        } else {
            $data['model_id'] = $this->getCacheId($type);
            $data['type'] = CategoryModel::TYPE_CONTENT;
        }
        $children = isset($data['children']) ? $data['children'] : [];
        if (isset($data['setting']) && is_array($data['setting'])) {
            $data['setting'] = Json::encode($data['setting']);
        }
        if (isset($data['group'])) {
            $data['groups'] = implode(',', (array)$data['group']);
        }
        $model = CategoryModel::create($data);
        if (!$model) {
            throw new Exception('数据错误');
        }
        $this->setCache([$model->name => $model->id, $model->id => $model], 'channel');
        foreach ($children as $item) {
            if (isset($item['action']) && $item['action'] === 'content') {
                $item['cat_id'] = $model->id;
                $this->runActionContent($item);
                continue;
            }
            if (isset($item['type'])) {
                $item['type'] = $type;
            }
            $item['parent_id'] = $model->id;
            $this->runActionChannel($item);
        }
    }

    protected function runActionContent($data) {
        if (isset($data['cat_id']) && !is_numeric($data['cat_id'])) {
            $data['cat_id'] = $this->getCacheId($data['cat_id']);
        } elseif (isset($data['type'])) {
            $data['cat_id'] = $this->getCacheId($data['type']);
        }
        unset($data['type'], $data['action']);
        $cat = $this->getCacheId($data['cat_id'], 'channel');
        $field_list = ModelFieldModel::where('model_id', $cat->model_id)->all();
        $scene = Module::scene()->setModel($this->getCacheId($cat->model_id, 'model'));
        $scene->insert($data, $field_list);
    }

    protected function insertGroup($item) {
        if ($this->getCacheId($item['name'], 'group') > 0) {
            return;
        }
        $model = GroupModel::create($item);
        if ($model) {
            $this->setCache([$model->name => $model->id], 'group');
        }
    }

    protected function runActionOption($data) {
        foreach ($data['data'] as $item) {
            OptionModel::insertOrUpdate($item['code'], $item['value'], function () use ($item) {
                if (isset($item['items'])) {
                    $item['default_value'] = implode("\n", $item['items']);
                }
                if (isset($item['default'])) {
                    $item['default_value'] = $item['default'];
                }
                if (isset($item['parent_id']) && !is_numeric($item['parent_id'])) {
                    $item['parent_id'] = $this->getOptionParent($item['parent_id']);
                }
                unset($item['default'], $item['items'], $item['id']);
                return $item;
            });
        }
    }

    protected function getOptionParent($code) {
        $code = substr($code, 8);
        return intval(OptionModel::where('code', $code)->value('id'));
    }

    public function getAllThemes() {
        $data = [];
        $this->src->map(function ($file) use (&$data) {
            if (!$file instanceof Directory) {
                return;
            }
            $json = $file->file('theme.json');
            if (!$json->exist()) {
                return;
            }
            $item = Json::decode($json->read());
            $data[] = [
                'name' => $item['name'],
                'description' => $item['description'],
                'author' => $item['author'],
                'cover' => $item['cover'],
            ];
        });
        return $data;
    }

    public static function clear() {
        $truncateTables = [
            ModelModel::tableName(),
            ModelFieldModel::tableName(),
            CategoryModel::tableName(),
            GroupModel::tableName(),
            LinkageModel::tableName(),
            LinkageDataModel::tableName(),
            ForumModel::tableName()
        ];
        $model_list = ModelModel::query()->all();
        foreach ($model_list as $model) {
            $scene = Module::scene()->setModel($model);
            Schema::dropTable($scene->getExtendTable());
            if ($scene instanceof MultiScene) {
                Schema::dropTable($scene->getMainTable());
                continue;
            }
            if (in_array($scene->getMainTable(), $truncateTables)) {
                continue;
            }
            $truncateTables[] = $scene->getMainTable();
        }
        foreach($truncateTables as $table) {
            (new Table($table))->truncate();
        }
    }
}