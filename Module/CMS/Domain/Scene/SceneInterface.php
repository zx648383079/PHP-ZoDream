<?php
namespace Module\CMS\Domain\Scene;

use Module\CMS\Domain\Model\ModelFieldModel;

interface SceneInterface {

    /**
     * 获取主表
     * @return mixed
     */
    public function getMainTable();

    /**
     * 获取附表（不参与搜索）
     * @return mixed
     */
    public function getExtendTable();

    /**
     * 初始化建立表
     * @return mixed
     */
    public function initTable();

    /**
     * 删除表
     * @return mixed
     */
    public function removeTable();

    /**
     * 新建字段
     * @param ModelFieldModel $field
     * @return mixed
     */
    public function addField(ModelFieldModel $field);

    /**
     * 更新字段
     * @param ModelFieldModel $field
     * @return mixed
     */
    public function updateField(ModelFieldModel $field);

    /**
     * 删除字段
     * @param ModelFieldModel $field
     * @return mixed
     */
    public function removeField(ModelFieldModel $field);

    public function insert(array $data, array $field_list);

    public function update($id, array $data, array $field_list);

    public function remove($id);

    public function search($keywords, $cat_id, $page = 1, $per_page = 20, $fields = null);

    public function find($id);



}