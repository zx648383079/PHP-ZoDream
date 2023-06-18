<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Scene;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\CMS\Domain\Fields\BaseField;
use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Migrations\CreateCmsTables;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\ModelRepository;
use Zodream\Database\Contracts\Column;
use Zodream\Database\DB;
use Zodream\Database\Query\Builder;
use Zodream\Database\Relation;
use Zodream\Database\Schema\Table;
use Zodream\Helpers\Str;
use Zodream\Html\Page;
use Zodream\Infrastructure\Concerns\ErrorTrait;
use Zodream\Infrastructure\Support\MessageBag;
use Zodream\Validate\ValidationException;

abstract class BaseScene implements SceneInterface {

    protected int $site = 1;

    /**
     * @var ModelModel
     */
    protected mixed $model = null;

    public function setModel(ModelModel $model, int $site = 0) {
        $this->model = $model;
        $this->site = $site > 0 ? $site : CMSRepository::siteId();
        return $this;
    }

    public function modelId(): int {
        return $this->model['id'];
    }

    public function remove(int|array|callable $id): bool {
        $main = null;
        foreach ([
                     $this->query(),
                     $this->extendQuery()
                 ] as $i => $query) {
            /** @var Builder $query */
            if (is_array($id)) {
                $query->whereIn('id', $id)->delete();
                continue;
            }
            if (!is_callable($id)) {
                $query->where('id', $id)->delete();
                continue;
            }
            if ($i < 1) {
                $query->where('model_id', $this->model->id);
            }
            if (($main = call_user_func($id, $query, $main, $i)) === false) {
                return true;
            }
            $query->delete();
        }
        return true;
    }

    public function find(int|callable $id): array {
        $data = [];
        if (!is_callable($id) && $id < 1) {
            return [];
        }
        $main = null;
        foreach ([
                     $this->query(),
                     $this->extendQuery()
                 ] as $i => $query) {
            /** @var Builder $query */
            if (!is_callable($id)) {
                $data[] = $query->where('id', $id)->first();
                continue;
            }
            if ($i < 1) {
                $query->where('model_id', $this->model->id);
            }
            if (call_user_func($id, $query, $main, $i) === false) {
                return $data;
            }
            $data[] = $main = $query->first();
        }
        // 主表数据更重要
        return array_merge(...array_filter($data, function ($item) {
            return !empty($item);
        }));
    }

    public function validate(array $data): array {
        $field_list = $this->fieldList();
        if (empty($field_list)) {
            return [];
        }
        $res = [];
        $bag = new MessageBag();
        foreach ($field_list as $field) {
            if (!array_key_exists($field->field, $data)) {
                continue;
            }
            $res[$field->field] = static::newField($field->type)
                ->filterInput($data[$field->field] ?? null, $field, $bag);
        }
        if (!$bag->isEmpty()) {
            throw new ValidationException($bag);
        }
        return $res;
    }

    public function insert(array $data): bool|int {
        $count = $this->query()
            ->where('title', $data['title'])->count();
        if ($count > 0) {
            throw new \Exception('标题重复');
        }
        list($main, $extend) = $this->filterInput($data);
        $main['updated_at'] = $main['created_at'] = time();
        $main['cat_id'] = isset($data['cat_id']) ? intval($data['cat_id']) : 0;
        $main['parent_id'] = isset($data['parent_id']) ? intval($data['parent_id']) : 0;
        $main['model_id'] = $this->model->id;
        $main['user_id'] = auth()->id();
        $id = intval($this->query()->insert($main));
        if ($id <= 0) {
            return false;
        }
        $extend['id'] = $id;
        $this->extendQuery()->insert($extend);
        $this->onDataUpdated($id, $main, $extend);
        return $id;
    }

    protected function onDataUpdated(int $id, array $main, array $extend): void {

    }

    public function update(int $id, array $data): bool {
        if (isset($data['title'])) {
            $count = $this->query()->where('id', '<>', $id)
                ->where('title', $data['title'])->count();
            if ($count > 0) {
                throw new \Exception('标题重复');
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
        $this->onDataUpdated($id, $main, $extend);
        return true;
    }

    /**
     * @return Builder
     */
    public function query(): Builder {
        return DB::table($this->getMainTable());
    }

    /**
     * @return Builder
     */
    public function extendQuery(): Builder {
        return DB::table($this->getExtendTable());
    }

    public function commentQuery(): Builder {
        return DB::table($this->getCommentTable());
    }

    /**
     * @return ModelFieldModel[]
     */
    public function fieldList(): array {
        return FuncHelper::fieldList($this->model->id);
    }

    public function searchComment(string $keywords, array $params = [], string $order = '', string $extra = '', int $page = 1, int $perPage = 20): Page {
        $items = $this->addWhereOrIn($this->commentQuery(), $params)->when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['content'], false, '', $keywords);
        })->when(!empty($order), function ($query) use ($order) {
            $query->orderBy($order);
        })->page($perPage, 'page', $page);
        $linkItems = [
            'user' => Relation::make(UserSimpleModel::query(), 'user_id', 'id')
        ];
        if (!empty($extra) && str_contains($extra, 'children')) {
            $linkItems['children'] = Relation::make($this->commentQuery(), 'id', 'parent_id');
        }
        $items->setPage(Relation::create($items->getPage(), $linkItems));
        return $items;
    }
    public function insertComment(array $data): bool|int {
        $id = $this->commentQuery()->insert($data);
        return empty($id) ? false : intval($id);
    }
    public function removeComment(int $id): bool {
        $this->commentQuery()->where('id', $id)->delete();
        return true;
    }
    public function updateComment(int $id, array $data): bool {
        $this->commentQuery()->where('id', $id)->update($data);
        return true;
    }

    /**
     * 主表一些默认的字段名 这里的字段不会进行转化
     * @return string[]
     */
    protected function mainDefaultField(): array {
        return ['id', 'cat_id', 'model_id', 'user_id',
            'status', 'view_count', 'comment_count', 'comment_open',
            'updated_at', 'created_at', 'parent_id'];
    }

    protected function getGroupFieldName(): array {
        $field_list = $this->fieldList();
        $main = $this->mainDefaultField();
        $extra = [];
        foreach ($field_list as $item) {
            if ($item->is_main > 0) {
                $main[] = $item->field;
                continue;
            }
            $extra[] = $item->field;
        }
        return [$main, $extra];
    }

    protected function splitByField(array $params): array {
        list($mainNames, $extraNames) = $this->getGroupFieldName();
        $main = [];
        $extra = [];
        foreach ($params as $key => $item) {
            if (in_array($key, $mainNames)) {
                $main[$key] = $item;
                continue;
            }
            if (in_array($key, $extraNames)) {
                $extra[$key] = $item;
                continue;
            }
        }
        return [$main, $extra];
    }

    /**
     * 添加
     * @param Builder $query
     * @param array $params
     * @param string $order
     * @param string $field
     * @return Builder
     */
    protected function addQuery(Builder $query, array $params = [], string $order = '', string $field = ''): Builder {
        list($order, $hasExtra) = $this->filterQuery($order);
        list($field, $hasExtra2) = $this->filterQuery($field);
        $hasExtra = $hasExtra || $hasExtra2;
        if (!empty($order)) {
            $query->orderBy($order);
        }
        if (empty($field)) {
            $field = '*';
        }
        if ($hasExtra) {
            $prefix = $this->getMainTable().'.';
            $field = implode(',', array_map(function($key) use ($prefix) {
                return in_array($key, ['*', 'id']) ? $prefix. $key : $key;
            }, explode(',', $field)));
            $query->leftJoin($this->getExtendTable().' extra', $prefix.'id', 'extra.id');
        }
        $query->select($field);
        if (empty($params)) {
            return $query;
        }
        list($main, $extra) = $this->splitByField($params);
        if (!empty($extra)) {
            if ($hasExtra) {
                $this->addWhereOrIn($query, $extra, 'extra.');
            } else {
                $main['id'] =
                    $this->addWhereOrIn($this->extendQuery(), $extra)->pluck('id');
            }
        }
        return $this->addWhereOrIn($query, $main);
    }

    private function filterQuery(string $order): array {
        if (empty($order)) {
            return [$order, false];
        }
        $hasExtra = false;
        list($mainNames, $extraNames) = $this->getGroupFieldName();
        foreach ($extraNames as $key) {
            if (str_contains($order, $key)) {
                $hasExtra = true;
                $order = str_replace($key, 'extra.' . $key, $order);
            }
        }
        return [$order, $hasExtra];
    }

    private function addWhereOrIn(Builder $query, array $params, string $prefix = ''): Builder {
        if (empty($params)) {
            return $query;
        }
        foreach ($params as $key => $item) {
            if (is_array($item)) {
                $query->whereIn($prefix.$key, $item);
                continue;
            }
            $query->where($prefix.$key, $item);
        }
        return $query;
    }

    protected function addSearchQuery(Builder $query, string $keywords): Builder {
        if (empty($keywords)) {
            return $query;
        }
        $field_list = $this->fieldList();
        $main = ['title', 'keywords'];
        $extra = [];
        foreach ($field_list as $item) {
            if ($item->is_search < 1) {
                continue;
            }
            if ($item->is_main > 0) {
                $main[] = $item->field;
                continue;
            }
            $extra[] = $item->field;
        }
        $query->where(function ($query) use ($keywords, $main) {
            $items = explode(' ', $keywords);
            foreach ($items as $item) {
                $item = trim(trim($item), '%');
                if (empty($item)) {
                    continue;
                }
                foreach ($main as $column) {
                    $query->orWhere($column, 'like', '%'.$item.'%');
                }
            }
        });
        if (empty($extra)) {
            return $query;
        }
        $ids = $this->extendQuery()->where(function ($query) use ($keywords, $extra) {
            $items = explode(' ', $keywords);
            foreach ($items as $item) {
                $item = trim(trim($item), '%');
                if (empty($item)) {
                    continue;
                }
                foreach ($extra as $column) {
                    $query->orWhere($column, 'like', '%'.$item.'%');
                }
            }
        })->pluck('id');
        if (empty($ids)) {
            $query->isEmpty();
            return $query;
        }
        return $query->whereIn($this->getMainTable().'.id', $ids);
    }

    protected function getResultByField(Builder $query, string $field = '*') {

    }

    protected function initCommentTable() {
        CreateCmsTables::createTable($this->getCommentTable(), function (Table $table) {
            $table->id();
            $table->string('content');
            $table->string('extra_rule', 300)->default('')
                ->comment('内容的一些附加规则');
            $table->uint('parent_id')->default(0);
            $table->uint('position')->default(1);
            $table->uint('reply_count')->default(0);
            $table->uint('user_id');
            $table->uint('model_id');
            $table->uint('content_id');
            $table->uint('agree_count')->default(0);
            $table->uint('disagree_count')->default(0);
            $table->timestamp('created_at');
        });
    }

    protected function initMainTableField(Table $table) {
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
        $table->uint('comment_count')->default(0);
        $table->bool('comment_open')->default(0);
        $table->timestamps();
    }

    protected function initDefaultModelField() {
        ModelRepository::batchAddField([
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
            [
                'name' => '开启评论',
                'field' => 'comment_open',
                'model_id' => $this->model->id,
                'is_main' => 1,
                'is_system' => 1,
                'is_required' => 0,
                'type' => 'switch'
            ],
            [
                'name' => '内容',
                'field' => 'content',
                'model_id' => $this->model->id,
                'is_main' => 0,
                'is_system' => 1,
                'type' => 'editor',
            ]
        ]);
    }

    public function toInput(ModelFieldModel $field, array $data = [], bool $isJson = false) {
        if ($field->is_disable > 0) {
            return null;
        }
        return self::newField($field->type)->toInput($data[$field->field] ?? '', $field, $isJson);
    }

    /**
     * @param array $data
     * @param bool $isNew
     * @return array [main, extend]
     * @throws \Exception
     */
    public function filterInput(array $data, bool $isNew = true): array {
        $extend = $main = [];
        foreach ($this->mainDefaultField() as $key) {
            if (!array_key_exists($key, $data)) {
                continue;
            }
            $main[$key] = $data[$key];
        }
        $field_list = $this->fieldList();
        if (empty($field_list)) {
            return [$main, $extend];
        }
        $bag = new MessageBag();
        foreach ($field_list as $field) {
            if (!$isNew && !array_key_exists($field->field, $data)) {
                continue;
            }
            $value = static::newField($field->type)
                ->filterInput($data[$field->field] ?? null, $field, $bag);
            if ($field->is_main > 0) {
                $main[$field->field] = $value;
                continue;
            }
            $extend[$field->field] = $value;
        }
        if (!$bag->isEmpty()) {
            throw new ValidationException($bag);
        }
        return [$main, $extend];
    }

    /**
     * @param string $type
     * @return BaseField
     * @throws \Exception
     */
    public static function newField(string $type) {
        $maps = [
            'switch' => 'SwitchBox',
        ];
        if (isset($maps[$type])) {
            $type = $maps[$type];
        }
        $class = 'Module\CMS\Domain\Fields\\'.Str::studly($type);
        if (class_exists($class)) {
            return new $class;
        }
        throw new \Exception(
            __('Field "{type}" not exist!', compact('type'))
        );
    }

    public static function converterTableField(Column $column, ModelFieldModel $field) {
        static::newField($field->type)->converterField($column, $field);
    }
}