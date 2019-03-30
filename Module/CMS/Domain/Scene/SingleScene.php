<?php
namespace Module\CMS\Domain\Scene;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\DB;
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
     */
    public function initTable() {
        ModelFieldModel::query()->insert([
            [
                'name' => '标题',
                'field' => 'title',
                'model_id' => $this->model->id,
                'is_main' => 1,
                'is_system' => 1,
                'type' => 'text'
            ],
            [
                'name' => '关键字',
                'field' => 'keywords',
                'model_id' => $this->model->id,
                'is_main' => 1,
                'is_system' => 1,
                'type' => 'text'
            ],
            [
                'name' => '简介',
                'field' => 'description',
                'model_id' => $this->model->id,
                'is_main' => 1,
                'is_system' => 1,
                'type' => 'textarea'
            ],
            [
                'name' => '缩略图',
                'field' => 'thumb',
                'model_id' => $this->model->id,
                'is_main' => 1,
                'is_system' => 1,
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
        $table = new Table($this->getExtendTable());
        $table->set('id')->int(10)->pk()->ai();
        static::converterTableField($table->set('content'), $content);
        return $table->setComment($this->model->name)
            ->create();
    }

    /**
     * 删除表
     * @return mixed
     */
    public function removeTable() {
        $table = new Table($this->getExtendTable());
        return $table->drop();
    }

    /**
     * 新建字段
     * @param ModelFieldModel $field
     * @return mixed
     * @throws \Exception
     */
    public function addField(ModelFieldModel $field) {
        if ($field->is_system > 0) {
            return;
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
            return;
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

    public function insert(array $data, array $field_list) {
        list($main, $extend) = $this->filterInput($data, $field_list);
        $main['updated_at'] = $main['created_at'] = time();
        $main['cat_id'] = intval($data['cat_id']);
        $id = DB::table($this->getMainTable())->insert($main);
        $extend['id'] = $id;
        DB::table($this->getExtendTable())->insert($extend);
    }

    public function update($id, array $data, array $field_list) {
        list($main, $extend) = $this->filterInput($data, $field_list);
        $main['updated_at'] = time();
        DB::table($this->getMainTable())
            ->where('id', $id)->update($main);
        if (!empty($extend)) {
            DB::table($this->getExtendTable())
                ->where('id', $id)->update($extend);
        }
    }

    public function remove($id) {
        DB::table($this->getMainTable())
            ->where('id', $id)->delete();
        DB::table($this->getExtendTable())
            ->where('id', $id)->delete();
    }

    /**
     * @param $keywords
     * @param $cat_id
     * @param int $page
     * @param int $per_page
     * @param null $fields
     * @return \Zodream\Html\Page
     * @throws \Exception
     */
    public function search($keywords, $cat_id, $page = 1, $per_page = 20, $fields = null) {
        if (empty($fields)) {
            $fields = '*';
        }
        return DB::table($this->getMainTable())->when(!empty($keywords), function ($query) use ($keywords) {
            $query->where('title', 'like', '%'.$keywords.'%');
        })->when($cat_id > 0, function ($query) use ($cat_id) {
            if (is_array($cat_id)) {
                $query->whereIn('cat_id', $cat_id);
                return;
            }
            $query->where('cat_id', $cat_id);
        })->select($fields)->page($per_page);
    }

    public function find($id) {
        if ($id < 1) {
            return [];
        }
        $data = DB::table($this->getMainTable())
            ->where('id', $id)->one();
        if (empty($data)) {
            return [];
        }
        $extend = DB::table($this->getExtendTable())->where('id', $id)
            ->one();
        // 主表数据更重要
        return array_merge((array)$extend, $data);
    }
}