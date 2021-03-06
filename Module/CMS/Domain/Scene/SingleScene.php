<?php
namespace Module\CMS\Domain\Scene;

use Module\CMS\Domain\Migrations\CreateCmsTables;
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
     * @throws \Exception
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
        $field_list = array_filter($this->fieldList(), function ($item) {
            return $item->is_main < 1;
        });
        CreateCmsTables::createTable($this->getExtendTable(), function (Table $table) use ($field_list) {
            $table->column('id')->int(10)->pk(true);
            foreach ($field_list as $item) {
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
        $table = new Table($this->getExtendTable());
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
        $table = new Table($this->getExtendTable());
        static::converterTableField($table->column($field->getOldAttribute('field'))->name($field->field), $field);
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
    public function removeField(ModelFieldModel $field) {
        $table = new Table($this->getExtendTable());
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
        $main['cat_id'] = isset($data['cat_id']) ? intval($data['cat_id']) : 0;
        $main['parent_id'] = isset($data['parent_id']) ? intval($data['parent_id']) : 0;
        $main['model_id'] = $this->model->id;
        $main['user_id'] = auth()->id();
        $id = $this->query()->insert($main);
        $extend['id'] = $id;
        $this->extendQuery()->insert($extend);
        return true;
    }

    public function update($id, array $data) {
        if (isset($data['title'])) {
            $count = $this->query()->where('id', '<>', $id)
                ->where('title', $data['title'])->count();
            if ($count > 0) {
                return $this->setError('title', '标题重复');
            }
        }
        list($main, $extend) = $this->filterInput($data, false);
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
     * @param $params
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