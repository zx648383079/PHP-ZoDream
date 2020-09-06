<?php
namespace Module\CMS\Domain\Scene;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Query\Builder;
use Zodream\Html\Page;

interface SceneInterface {

    /**
     * 获取主表
     * @return string
     */
    public function getMainTable();

    /**
     * 获取附表（不参与搜索）
     * @return string
     */
    public function getExtendTable();

    /**
     * 初始化建立表
     * @return mixed
     */
    public function initTable();

    /**
     * 初始化表数据并建立表
     * @return mixed
     */
    public function initModel();

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

    public function insert(array $data);

    public function update($id, array $data);

    public function remove($id);

    /**
     * @param $keywords
     * @param $params
     * @param null $order
     * @param int $page
     * @param int $per_page
     * @param null $fields
     * @return Page
     */
    public function search($keywords, $params = [], $order = null, $page = 1, $per_page = 20, $fields = null);

    public function find($id);

    /**
     * @return Builder
     */
    public function query();

    public function extendQuery();




}