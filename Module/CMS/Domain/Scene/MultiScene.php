<?php
namespace Module\CMS\Domain\Scene;


use Module\CMS\Domain\Migrations\CreateCmsTables;
use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\DB;
use Zodream\Database\Schema\Table;

class MultiScene extends BaseScene {

    public function getMainTable() {
        return sprintf('cms_content_%s_%s', $this->site, $this->model->table);
    }

    public function getExtendTable() {
        return sprintf('%s_data', $this->getMainTable());
    }

    public function getTableByMain($is_main) {
        if ($is_main instanceof ModelFieldModel) {
            $is_main = $is_main->is_main;
        }
        return $is_main ? $this->getMainTable() : $this->getExtendTable();
    }



    /**
     * 初始化建立表
     * @return mixed
     */
    public function initModel() {
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
        ModelFieldModel::create([
            'name' => '内容',
            'field' => 'content',
            'model_id' => $this->model->id,
            'is_main' => 0,
            'is_system' => 1,
            'type' => 'editor',
        ]);
        return $this->initTable();
    }

    public function initTable() {
        $extend_list = array_filter($this->fieldList(), function ($item) {
            return $item->is_main < 1;
        });
        $field_list = array_filter($this->fieldList(), function ($item) {
            return $item->is_main > 0 && $item->is_system < 1;
        });
        CreateCmsTables::createTable($this->getMainTable(), function (Table $table) use ($field_list) {
            $table->id();
            $table->string('title', 100);
            $table->uint('cat_id');
            $table->uint('model_id');
            $table->uint('parent_id')->default(0);
            $table->uint('user_id')->default(0);
            $table->string('keywords')->default('');
            $table->string('thumb')->default('');
            $table->string('description')->default('');
            $table->bool('status')->default(0);
            $table->uint('view_count')->default(0);
            foreach ($field_list as $item) {
                static::converterTableField($table->column($item->field), $item);
            }
            $table->timestamps();
        });
        CreateCmsTables::createTable($this->getExtendTable(), function (Table $table) use ($extend_list) {
            $table->column('id')->int(10)->pk(true);
            foreach ($extend_list as $item) {
                static::converterTableField($table->column($item->field), $item);
            }
            $table->comment($this->model->name);
        });
        return true;
    }

    /**
     * 删除表
     * @return mixed
     */
    public function removeTable() {
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
    public function addField(ModelFieldModel $field) {
        if ($field->is_system > 0) {
            return true;
        }
        $table = new Table($this->getTableByMain($field->is_main));
        static::converterTableField($table->column($field->field), $field);
        CreateCmsTables::updateTable($table,
            $table->columns()
        );
        return true;
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
        if ($field->is_main == $field->getOldAttribute('is_main')) {
            $table = new Table($this->getTableByMain($field->is_main));
            static::converterTableField($table->column($field->getOldAttribute('field'))->name($field->field), $field);
            CreateCmsTables::updateTable($table,
                updateColumns: $table->columns()
            );
            return true;
        }
        $old_table = $this->getTableByMain($field->getOldAttribute('is_main'));
        $table = $this->getTableByMain($field->is_main);
        $this->addField($field);
        $data = DB::table($old_table)->pluck($field->getOldAttribute('field'), 'id');
        foreach ($data as $id => $value) {
            DB::table($table)->where('id', $id)->update([
                $field->field => $value
            ]);
        }
        $table = new Table($old_table);
        $table->column($field->getOldAttribute('field'));
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
    public function removeField(ModelFieldModel $field) {
        $table = new Table($this->getTableByMain($field->is_main));
        $table->column($field->field);
        CreateCmsTables::updateTable($table,
            dropColumns: $table->columns()
        );
        return true;
    }

    public function insert(array $data) {
        $count = $this->query()
            ->where('title', $data['title'])->count();
        if ($count > 0) {
            return $this->setError('title', '标题重复');
        }
        list($main, $extend) = $this->filterInput($data);
        $main['updated_at'] = $main['created_at'] = time();
        $main['cat_id'] = intval($data['cat_id']);
        $main['parent_id'] = isset($data['parent_id']) ? intval($data['parent_id']) : 0;
        $main['model_id'] = $this->model->id;
        $main['user_id'] = auth()->id();
        $id = $this->query()->insert($main);
        $extend['id'] = $id;
        $this->extendQuery()->insert($extend);
        return true;
    }

    public function update($id, array $data) {
        $count = $this->query()->where('id', '<>', $id)
            ->where('title', $data['title'])->count();
        if ($count > 0) {
            return $this->setError('title', '标题重复');
        }
        list($main, $extend) = $this->filterInput($data);
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

    /**
     * @param $keywords
     * @param array $params
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
                $this->addSearchQuery($query, $keywords);
            })->page($per_page);
    }
}