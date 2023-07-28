<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Scene;

use Module\CMS\Domain\Migrations\CreateCmsTables;
use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\DB;
use Zodream\Database\Schema\Table;

class MultiScene extends BaseScene {

    public function getMainTable(): string {
        return sprintf('cms_content_%s_%s', $this->site, $this->model['table']);
    }

    public function getExtendTable(): string {
        return sprintf('%s_data', $this->getMainTable());
    }

    public function getCommentTable(): string {
        return sprintf('cms_comment_%d_%s', $this->site, $this->model['table']);
    }

    public function getTableByMain(mixed $isMain): string {
        if ($isMain instanceof ModelFieldModel) {
            $isMain = $isMain->is_main;
        }
        return $isMain ? $this->getMainTable() : $this->getExtendTable();
    }


    public function boot(): void {

    }


    /**
     * 初始化建立表
     * @return mixed
     */
    public function initModel(): bool {
        $this->initDefaultModelField();
        return $this->initTable();
    }

    public function initializedModel(): bool {
        return DB::tableExist($this->getMainTable());
    }

    public function initTable(): bool {
        $extend_list = array_filter($this->fieldList(), function ($item) {
            return $item['is_main'] < 1;
        });
        $field_list = array_filter($this->fieldList(), function ($item) {
            return $item['is_main'] > 0 && $item['is_system'] < 1;
        });
        CreateCmsTables::createTable($this->getMainTable(), function (Table $table) use ($field_list) {
            $this->initMainTableField($table);
            foreach ($field_list as $item) {
                static::converterTableField($table->column($item['field']), $item);
            }
        });
        CreateCmsTables::createTable($this->getExtendTable(), function (Table $table) use ($extend_list) {
            $table->column('id')->int(10)->pk(true);
            foreach ($extend_list as $item) {
                static::converterTableField($table->column($item['field']), $item);
            }
            $table->comment($this->model['name']);
        });
        return true;
    }

    /**
     * 删除表
     * @return mixed
     */
    public function removeTable(): bool {
        CreateCmsTables::dropTable($this->getMainTable());
        CreateCmsTables::dropTable($this->getExtendTable());
        return true;
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
        $table = new Table($this->getTableByMain($field['is_main']));
        static::converterTableField($table->column($field['field']), $field);
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
     * @throws \Exception
     */
    public function updateField(ModelFieldModel|array $field, ModelFieldModel|array $oldField): bool {
        if ($field['is_system'] > 0) {
            return true;
        }
        if ($field['is_main'] == $oldField['is_main']) {
            $table = new Table($this->getTableByMain($field['is_main']));
            static::converterTableField($table->column(
                $oldField['field'])->name($field['field']), $field);
            CreateCmsTables::updateTable($table,
                updateColumns: $table->columns()
            );
            return true;
        }
        $old_table = $this->getTableByMain($oldField['is_main']);
        $table = $this->getTableByMain($field['is_main']);
        $this->addField($field);
        $data = DB::table($old_table)->pluck($oldField['field'], 'id');
        foreach ($data as $id => $value) {
            DB::table($table)->where('id', $id)->update([
                $field['field'] => $value
            ]);
        }
        $table = new Table($old_table);
        $table->column($oldField['field']);
        CreateCmsTables::updateTable($table,
            dropColumns: $table->columns()
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
        $table = new Table($this->getTableByMain($field['is_main']));
        $table->column($field['field']);
        CreateCmsTables::updateTable($table,
            dropColumns: $table->columns()
        );
        return true;
    }

    protected function onDataUpdated(int $id, array $main, array $extend): void {
        if (empty($main['comment_open'])) {
            return;
        }
        $this->initCommentTable();
    }

}