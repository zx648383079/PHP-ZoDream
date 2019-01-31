<?php
namespace Module\CMS\Domain\Scene;


use Module\CMS\Domain\Model\ModelFieldModel;
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

    public function insert(array $data) {
        // TODO: Implement insert() method.
    }

    public function update($id, array $data)
    {
        // TODO: Implement update() method.
    }

    public function remove($id)
    {
        // TODO: Implement remove() method.
    }

    public function search($keywords, $page = 1, $per_page = 20, $fields = null)
    {
        // TODO: Implement search() method.
    }

    public function find($id) {
        // TODO: Implement find() method.
    }
}