<?php
namespace Module\CMS\Domain;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\GroupModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Module;
use Module\Template\Domain\Model\Base\OptionModel;
use Zodream\Disk\Directory;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Error\Exception;

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

    public function pack() {

    }

    protected function packOption() {

    }

    protected function packModel() {

    }

    protected function packField() {

    }

    protected function packChannel() {

    }

    protected function packContent() {

    }

    public function unpack() {
        $file = $this->src->file('theme.json');
        if (!$file->exist()) {
            return;
        }
        $this->setCache(GroupModel::pluck('id', 'name'), 'group');
        $this->setCache(ModelModel::pluck('id', 'table'), 'model');
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
            $maps = ['group' => 1, 'model' => 2, 'field' => 3, 'channel' => 4, 'content' => 5];
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
        if (!is_numeric($data['cat_id'])) {
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
        foreach ($data as $item) {
            OptionModel::insertOrUpdate($item['code'], $item['value'], function () use ($item) {
                if (isset($item['items'])) {
                    $item['default_value'] = implode("\n", $item['items']);
                }
                if (isset($item['default'])) {
                    $item['default_value'] = $item['default'];
                }
                return $item;
            });
        }
    }


}