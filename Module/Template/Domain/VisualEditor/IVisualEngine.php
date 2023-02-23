<?php
declare(strict_types=1);
namespace Module\Template\Domain\VisualEditor;

use Zodream\Template\ViewFactory;

interface IVisualEngine {

    /**
     * 是否允许编辑
     * @return bool
     */
    public function editable(): bool;

    /**
     * 是否能启用异步属性操作
     * @return bool
     */
    public function asyncable(): bool;

    /**
     * 获取当前组件的id
     * @return int
     */
    public function rowId(): int;

    public function pageId(): int;

    /**
     * 获取当前位置的组件
     * @param int $index
     * @return string
     */
    public function weight(int $index): string;

    /**
     * 渲染一行组件
     * @param int $parent_id
     * @param int $index
     * @return string
     */
    public function renderRow(int $parent_id, int $index = 0): string;

    /**
     * 获取渲染器
     * @return ViewFactory
     */
    public function renderer(): ViewFactory;

    /**
     * 重置渲染器
     * @return mixed
     */
    public function resetRenderer();
}