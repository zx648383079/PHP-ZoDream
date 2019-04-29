<?php
namespace Module\CMS\Domain;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\GroupModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\Template\Domain\Model\Base\OptionModel;
use Zodream\Disk\Directory;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;

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

    protected function getCacheId($name, $prefix) {
        $key = sprintf('@%s:%s', $prefix, $name);
        return isset($this->cache[$key]) ? $this->cache[$key] : 0;
    }

    protected function runScript($data) {
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

    }

    protected function runActionField($data) {

    }

    protected function runActionChannel($data) {

    }

    protected function runActionContent($data) {

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