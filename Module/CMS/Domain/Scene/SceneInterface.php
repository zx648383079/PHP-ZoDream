<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Scene;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Model\Model;
use Zodream\Database\Query\Builder;
use Zodream\Html\Page;
use Zodream\Validate\ValidationException;

interface SceneInterface {

    public function setModel(Model|array $model, int $site = 0): static;

    /**
     * 获取主表
     * @return string
     */
    public function getMainTable(): string;

    /**
     * 获取附表（不参与搜索）
     * @return string
     */
    public function getExtendTable(): string;

    public function getCommentTable(): string;

    /**
     * 创建站点时触发
     * @return void
     */
    public function boot(): void;

    /**
     * 初始化建立表
     * @return mixed
     */
    public function initTable(): bool;

    /**
     * 初始化表数据并建立表
     * @return mixed
     */
    public function initModel(): bool;

    /**
     * 判断是否已经初始话了表
     * @return bool
     */
    public function initializedModel(): bool;

    /**
     * 删除表
     * @return mixed
     */
    public function removeTable(): bool;

    /**
     * 新建字段
     * @param ModelFieldModel|array $field
     * @return mixed
     */
    public function addField(ModelFieldModel|array $field): bool;

    /**
     * 更新字段
     * @param ModelFieldModel|array $field
     * @param ModelFieldModel|array $oldField
     * @return mixed
     */
    public function updateField(ModelFieldModel|array $field, ModelFieldModel|array $oldField): bool;

    /**
     * 删除字段
     * @param ModelFieldModel|array $field
     * @return mixed
     */
    public function removeField(ModelFieldModel|array $field): bool;

    /**
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function validate(array $data): array;

    public function insert(array $data): bool|int;

    public function update(int $id, array $data, bool $updateTime = true): bool;

    public function remove(int|array|callable $id): bool;

    /**
     * @param string $keywords
     * @param array $params
     * @param string $order
     * @param int $page
     * @param int $perPage
     * @param string $fields
     * @param bool $isPage 声明需不需要获取分页链接
     * @return Page
     */
    public function search(string $keywords, array $params = [],
                           string $order = '', int $page = 1,
                           int $perPage = 20,
                           string $fields = '', bool $isPage = true): Page;

    public function find(int|callable $id): array;

    public function searchComment(string $keywords, array $params = [], string $order = '', string $extra = '', int $page = 1, int $perPage = 20): Page;
    public function insertComment(array $data): bool|int;
    public function removeComment(int $id): bool;
    public function updateComment(int $id, array $data): bool;

    /**
     * @return Builder
     */
    public function query(): Builder;

    public function extendQuery(): Builder;

}