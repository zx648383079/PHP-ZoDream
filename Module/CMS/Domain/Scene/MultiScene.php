<?php
namespace Module\CMS\Domain\Scene;


use Module\CMS\Domain\Model\ModelFieldModel;

class MultiScene  extends BaseScene {

    public function getMainTable() {
        return sprintf('cms_content_%s_%s', $this->site, $this->model->table);
    }

    public function getExtendTable() {
        return sprintf('%s_data', $this->getExtendTable());
    }

    /**
     * 初始化建立表
     * @return mixed
     */
    public function initTable()
    {
        // TODO: Implement initTable() method.
    }

    /**
     * 删除表
     * @return mixed
     */
    public function removeTable()
    {
        // TODO: Implement removeTable() method.
    }

    /**
     * 新建字段
     * @param ModelFieldModel $field
     * @return mixed
     */
    public function addField(ModelFieldModel $field)
    {
        // TODO: Implement addField() method.
    }

    /**
     * 更新字段
     * @param ModelFieldModel $fieldModel
     * @return mixed
     */
    public function updateField(ModelFieldModel $fieldModel)
    {
        // TODO: Implement updateField() method.
    }

    /**
     * 删除字段
     * @param ModelFieldModel $fieldModel
     * @return mixed
     */
    public function removeField(ModelFieldModel $fieldModel)
    {
        // TODO: Implement removeField() method.
    }

    public function insert(array $data)
    {
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

    public function find($id)
    {
        // TODO: Implement find() method.
    }
}