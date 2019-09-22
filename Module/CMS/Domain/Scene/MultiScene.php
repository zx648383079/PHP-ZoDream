<?php
namespace Module\CMS\Domain\Scene;


use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\DB;
use Zodream\Database\Query\Builder;
use Zodream\Database\Schema\Schema;
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
    public function initTable() {
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
        $content = ModelFieldModel::create([
            'name' => '内容',
            'field' => 'content',
            'model_id' => $this->model->id,
            'is_main' => 0,
            'is_system' => 1,
            'type' => 'editor',
        ]);
        Schema::createTable($this->getMainTable(), function (Table $table) {
            $table->set('id')->pk()->ai();
            $table->set('title')->varchar(100)->notNull();
            $table->set('cat_id')->int()->notNull();
            $table->set('model_id')->int()->notNull();
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('user_id')->int()->defaultVal(0);
            $table->set('keywords')->varchar();
            $table->set('thumb')->varchar();
            $table->set('description')->varchar();
            $table->set('status')->bool()->defaultVal(0);
            $table->set('view_count')->int()->defaultVal(0);
            $table->timestamps();
        });
        return Schema::createTable($this->getExtendTable(), function (Table $table) use ($content) {
            $table->set('id')->int(10)->pk()->ai();
            static::converterTableField($table->set('content'), $content);
            $table->setComment($this->model->name);
        });
    }

    /**
     * 删除表
     * @return mixed
     */
    public function removeTable() {
        Schema::dropTable($this->getMainTable());
        return Schema::dropTable($this->getExtendTable());
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
        $table = new Table($this->getTableByMain($field->is_main));
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
        if ($field->is_main == $field->getOldAttribute('is_main')) {
            $table = new Table($this->getTableByMain($field->is_main));
            static::converterTableField($table->set($field->field)
                ->setOldField($field->getOldAttribute('field')), $field);
            return $table->alert();
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
        $table->set($field->getOldAttribute('field'));
        return $table->dropColumn();
    }

    /**
     * 删除字段
     * @param ModelFieldModel $field
     * @return mixed
     * @throws \Exception
     */
    public function removeField(ModelFieldModel $field) {
        $table = new Table($this->getTableByMain($field->is_main));
        $table->set($field->field);
        return $table->dropColumn();
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

    public function remove($id) {
        $this->query()
            ->where('id', $id)->delete();
        $this->extendQuery()
            ->where('id', $id)->delete();
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
        return $this->addWhere($this->query(), $params)->when(!empty($keywords), function ($query) use ($keywords) {
            $query->where('title', 'like', '%'.$keywords.'%');
        })->select($fields)
            ->when(!empty($order), function ($query) use ($order) {
                $query->orderBy($order);
            })->page($per_page);
    }

    public function find($id) {
        if ($id < 1) {
            return [];
        }
        $data = $this->query()
            ->where('id', $id)->one();
        if (empty($data)) {
            return [];
        }
        $extend = $this->extendQuery()->where('id', $id)
            ->one();
        // 主表数据更重要
        return array_merge((array)$extend, $data);
    }

    /**
     * @return Builder
     */
    public function query() {
        return DB::table($this->getMainTable());
    }

    public function extendQuery() {
        return DB::table($this->getExtendTable());
    }
}