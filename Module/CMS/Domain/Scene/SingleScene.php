<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Scene;

use Module\CMS\Domain\Migrations\CreateCmsTables;
use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\DB;
use Zodream\Database\Schema\Table;

class SingleScene extends BaseScene {

    public function getMainTable(): string {
        return sprintf('cms_content_%s', $this->context->tableId());
    }

    public function getExtendTable(): string {
        return sprintf('%s_%s', $this->getMainTable(), $this->model['table']);
    }

    public function getCommentTable(): string {
        return sprintf('cms_comment_%d', $this->context->tableId());
    }

    public function boot(): void {
        if ($this->context->isOwer()) {
            CreateCmsTables::createTable($this->getMainTable(), function (Table $table) {
                $this->initMainTableField($table);
            });
            $this->initCommentTable();
        }
    }

    /**
     * 初始化建立表
     * @return mixed
     * @throws \Exception
     */
    public function initModel(): bool {
        $this->initDefaultModelField();
        return $this->initTable();
    }

    public function initializedModel(): bool {
        return DB::tableExist($this->getExtendTable());
    }

    public function initTable(): bool {
        if ($this->context->isOwer()) {
            $field_list = array_filter($this->fieldList(), function ($item) {
                return $item['is_system'] < 1;
            });
            CreateCmsTables::createTable($this->getExtendTable(), function (Table $table) use ($field_list) {
                $table->column('id')->int(10)->pk(true);
                foreach ($field_list as $item) {
                    static::converterTableField($table->column($item['field']), $item, $this->context);
                }
                $table->comment($this->model['name']);
            });
        }
        return true;
    }

    /**
     * 删除表
     * @return mixed
     */
    public function removeTable(): bool {
        $items = $this->query()->where('model_id', $this->modelId())
                ->where('site_id', $this->site)->pluck('id');
        if (!empty($items)) {
            $this->query()->where('model_id', $this->modelId())
                ->where('site_id', $this->site)->delete();
            $this->extendQuery()->whereIn('id', $items)->delete();
            $this->commentQuery()->whereIn('id', $items)->delete();
        }
        if ($this->context->isOwer()) {
            CreateCmsTables::dropTable($this->getExtendTable());
        }
        return true;
    }

    public function destroy(): void {
        if ($this->context->isOwer()) {
            CreateCmsTables::dropTable(ContentModel::tableName());
            CreateCmsTables::dropTable($this->getCommentTable());
        } else {
            $this->query()->where('site_id', $this->site)->delete();
            $this->commentQuery()->where('site_id', $this->site)->delete();
        }
    }

    /**
     * 新建字段
     * @param ModelFieldModel $field
     * @return mixed
     * @throws \Exception
     */
    public function addField(ModelFieldModel|array $field): bool {
        if ($field['is_system'] > 0) {
            return true;
        }
        $table = new Table($this->getExtendTable());
        static::converterTableField($table->column($field['field']), $field, $this->context);
        CreateCmsTables::updateTable($table,
            $table->columns()
        );
        return true;
    }

    /**
     * 更新字段
     * @param ModelFieldModel|array $field
     * @param ModelFieldModel|array $oldField
     * @return mixed
     */
    public function updateField(ModelFieldModel|array $field, ModelFieldModel|array $oldField): bool {
        if ($field['is_system'] > 0) {
            return true;
        }
        $table = new Table($this->getExtendTable());
        static::converterTableField($table->column(
            $oldField['field'])->name($field['field']), $field, $this->context);
        CreateCmsTables::updateTable($table,
            updateColumns: $table->columns()
        );
        return true;
    }

    /**
     * 删除字段
     * @param ModelFieldModel $field
     * @return mixed
     * @throws \Exception
     */
    public function removeField(ModelFieldModel|array $field): bool {
        if ($field['is_system'] > 0) {
            return true;
        }
        $table = new Table($this->getExtendTable());
        $table->column($field['field']);
        CreateCmsTables::updateTable($table,
            dropColumns: $table->columns()
        );
        return true;
    }

}