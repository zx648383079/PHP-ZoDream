<?php
namespace Module\CMS\Domain\Scene;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\DB;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class SingleScene extends BaseScene {

    public function getMainTable() {
        return sprintf('cms_content_%s', $this->site);
    }

    public function getExtendTable() {
        return sprintf('%s_%s', $this->getMainTable(), $this->model->table);
    }


    /**
     * 初始化建立表
     * @return mixed
     * @throws \Exception
     */
    public function initTable() {
        ModelFieldModel::query()->insert([
            [
                'name' => '标题',
                'field' => 'title',
                'model_id' => $this->model->id,
                'is_main' => 1,
                'is_system' => 1,
                'is_required' => 1,
                'type' => 'text'
            ],
            [
                'name' => '关键字',
                'field' => 'keywords',
                'model_id' => $this->model->id,
                'is_main' => 1,
                'is_system' => 1,
                'is_required' => 0,
                'type' => 'text'
            ],
            [
                'name' => '简介',
                'field' => 'description',
                'model_id' => $this->model->id,
                'is_main' => 1,
                'is_system' => 1,
                'is_required' => 0,
                'type' => 'textarea'
            ],
            [
                'name' => '缩略图',
                'field' => 'thumb',
                'model_id' => $this->model->id,
                'is_main' => 1,
                'is_system' => 1,
                'is_required' => 0,
                'type' => 'image'
            ],
        ]);
        $content = ModelFieldModel::create([
            'name' => '内容',
            'field' => 'content',
            'model_id' => $this->model->id,
            'is_main' => 0,
            'is_system' => 1,
            'type' => 'editor',
        ]);
        return Schema::createTable($this->getExtendTable(), function (Table $table) use ($content) {
            $table->set('id')->int(10)->pk()->ai();
            static::converterTableField($table->set('content'), $content);
            $table->setComment($this->model->name);
        });
    }

    /**
     * 删除表
     * @return mixed
     */
    public function removeTable() {
        return Schema::dropTable($this->getExtendTable());
    }

    /**
     * 新建字段
     * @param ModelFieldModel $field
     * @return mixed
     * @throws \Exception
     */
    public function addField(ModelFieldModel $field) {
        if ($field->is_system > 0) {
            return true;
        }
        $table = new Table($this->getExtendTable());
        static::converterTableField($table->set($field->field), $field);
        return $table->alert();
    }

    /**
     * 更新字段
     * @param ModelFieldModel $field
     * @return mixed
     * @throws \Exception
     */
    public function updateField(ModelFieldModel $field) {
        if ($field->is_system > 0) {
            return true;
        }
        $table = new Table($this->getExtendTable());
        static::converterTableField($table->set($field->field)
            ->setOldField($field->getOldAttribute('field')), $field);
        return $table->alert();
    }

    /**
     * 删除字段
     * @param ModelFieldModel $field
     * @return mixed
     * @throws \Exception
     */
    public function removeField(ModelFieldModel $field) {
        $table = new Table($this->getExtendTable());
        $table->set($field->field);
        return $table->dropColumn();
    }

    public function insert(array $data) {
        $count = $this->query()
            ->where('title', $data['title'])->count();
        if ($count > 0) {
            return $this->setError('title', '标题重复');
        }
        list($main, $extend) = $this->filterInput($data);
        $main['updated_at'] = $main['created_at'] = time();
        $main['cat_id'] = isset($data['cat_id']) ? intval($data['cat_id']) : 0;
        $main['parent_id'] = isset($data['parent_id']) ? intval($data['parent_id']) : 0;
        $main['model_id'] = $this->model->id;
        $main['user_id'] = auth()->id();
        $id = $this->query()->insert($main);
        $extend['id'] = $id;
        $this->extendQuery()->insert($extend);
        return true;
    }

    public function update($id, array $data) {
        if (isset($data['title'])) {
            $count = $this->query()->where('id', '<>', $id)
                ->where('title', $data['title'])->count();
            if ($count > 0) {
                return $this->setError('title', '标题重复');
            }
        }
        list($main, $extend) = $this->filterInput($data, false);
        $main['updated_at'] = time();
        $main['model_id'] = $this->model->id;
        $this->query()
            ->where('id', $id)->update($main);
        if (!empty($extend)) {
            $this->extendQuery()
                ->where('id', $id)->update($extend);
        }
        return true;
    }

    public function remove($id) {
        $this->query()
            ->where('id', $id)->delete();
        $this->extendQuery()
            ->where('id', $id)->delete();
    }

    /**
     * @param $keywords
     * @param $params
     * @param null $order
     * @param int $page
     * @param int $per_page
     * @param null $fields
     * @return \Zodream\Html\Page
     * @throws \Exception
     */
    public function search($keywords, $params = [], $order = null, $page = 1, $per_page = 20, $fields = null) {
        if (empty($fields)) {
            $fields = '*';
        }
        return $this->addQuery($this->query(), $params, $order, $fields)
            ->when(!empty($keywords), function ($query) use ($keywords) {
            $query->where('title', 'like', '%'.$keywords.'%');
        })->page($per_page);
    }

    public function find($id) {
        if ($id < 1) {
            return [];
        }
        $data = $this->query()
            ->where('id', $id)->one();
        if (empty($data)) {
            return [];
        }
        $extend = $this->extendQuery()->where('id', $id)
            ->one();
        // 主表数据更重要
        return array_merge((array)$extend, $data);
    }

    public function query() {
        return DB::table($this->getMainTable());
    }

    public function extendQuery() {
        return DB::table($this->getExtendTable());
    }
}