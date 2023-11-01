<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Scene;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\CMS\Domain\Fields\BaseField;
use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Migrations\CreateCmsTables;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\CommentRepository;
use Module\CMS\Domain\Repositories\ModelRepository;
use Module\CMS\Domain\Repositories\SiteRepository;
use Zodream\Database\Contracts\Column;
use Zodream\Database\DB;
use Zodream\Database\Model\Model;
use Zodream\Database\Query\Builder;
use Zodream\Database\Relation;
use Zodream\Database\Schema\Table;
use Zodream\Helpers\Str;
use Zodream\Html\Page;
use Zodream\Infrastructure\Support\MessageBag;
use Zodream\Validate\ValidationException;

abstract class BaseScene implements SceneInterface {

    protected int $site = 1;

    /**
     * @var Model|array
     */
    protected mixed $model = null;

    public function setModel(Model|array $model, int $site = 0): static {
        $this->model = $model;
        $this->site = $site > 0 ? $site : CMSRepository::siteId();
        return $this;
    }

    public function modelId(): int {
        return intval($this->model['id']);
    }

    /**
     * 是否是文章表单
     * @return bool
     */
    public function isArticleModel(): bool {
        return empty($this->model) || $this->model['type'] < 1;
    }

    public function remove(int|array|callable $id): bool {
        if (!is_callable($id)) {
            $this->removeId(ModelHelper::parseArrInt($id));
            return true;
        }
        $idItems = [];
        foreach ([
                     $this->query(),
                     $this->extendQuery(),
                 ] as $i => $query) {
            /** @var Builder $query */
            if ($i < 1) {
                $query->where('model_id', $this->modelId());
            }
            if (call_user_func($id, $query, $i < 1) === false) {
                return true;
            }
            $idItems = array_merge($idItems, $query->pluck('id'));
        }
        $this->removeId(array_unique($idItems));
        return true;
    }

    /**
     * 根据id删除信息
     * @param array $idItems
     * @return void
     * @throws \Exception
     */
    protected function removeId(array $idItems): void {
        if (empty($idItems)) {
            return;
        }
        foreach ([
                     $this->query(),
                     $this->extendQuery(),
                 ] as $query) {
            /** @var Builder $query */
            $query->whereIn('id', $idItems)->delete();
        }
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
                $query->where('model_id', $this->modelId());
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
            if (!array_key_exists($field['field'], $data)) {
                continue;
            }
            $res[$field['field']] = static::newField($field['type'])
                ->filterInput($data[$field['field']] ?? null, $field, $bag);
        }
        if (!$bag->isEmpty()) {
            throw new ValidationException($bag);
        }
        return $res;
    }

    public function insert(array $data): bool|int {
        $this->validateDataUnique($data);
        list($main, $extend) = $this->filterInput($data);
        $main['updated_at'] = $main['created_at'] = time();
        $main['cat_id'] = isset($data['cat_id']) ? intval($data['cat_id']) : 0;
        $main['parent_id'] = isset($data['parent_id']) ? intval($data['parent_id']) : 0;
        $main['model_id'] = $this->modelId();
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

    protected function validateDataUnique(array $data, int $id = 0) {
        if (!$this->isArticleModel()) {
            return;
        }
        if (empty($data['title'])) {
            return;
        }
        $count = $this->query()->when($id > 0, function ($query) use ($id) {
                $query->where('id', '<>', $id);
            })
            ->where('title', $data['title'])->count();
        if ($count > 0) {
            throw new \Exception('标题重复');
        }
    }

    public function update(int $id, array $data, bool $updateTime = true): bool {
        $this->validateDataUnique($data);
        list($main, $extend) = $this->filterInput($data, false);
        if ($updateTime) {
            $main['updated_at'] = time();
        }
        $main['model_id'] = $this->modelId();
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
     * @return array[]
     */
    public function fieldList(): array {
        return FuncHelper::fieldList($this->modelId());
    }

    public function searchComment(string $keywords, array $params = [], string $order = '', string $extra = '', int $page = 1, int $perPage = 20): Page {
        $items = $this->addWhereOrIn($this->commentQuery(), $params)->when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['content'], false, '', $keywords);
        })->when(!empty($order), function ($query) use ($order) {
            $query->orderBy($order);
        })->page($perPage, 'page', $page);
        if (empty($extra) || !str_contains($extra, 'children')) {
            return $items->setPage(Relation::create($items->getPage(), [
                'user' => Relation::make(UserSimpleModel::query(), 'user_id', 'id')
            ]));
        }
        $data = Relation::create($items->getPage(), [
            'children' => Relation::make($this->commentQuery(), 'id',
                'parent_id', Relation::TYPE_MANY)
        ]);
        return $items->setPage(ModelHelper::bindTwoRelation($data,
            [
                'user' => Relation::make(UserSimpleModel::query(), 'user_id', 'id')
            ]));
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
        $items = ['id', 'cat_id', 'model_id', 'user_id',
            'status',
            'updated_at', 'created_at', 'parent_id'];
        if (!$this->isArticleModel()) {
            return $items;
        }
        return array_merge($items, ['view_count', 'comment_count', 'comment_open',]);
    }

    protected function getGroupFieldName(): array {
        $field_list = $this->fieldList();
        $main = $this->mainDefaultField();
        $extra = [];
        foreach ($field_list as $item) {
            if ($item['is_main'] > 0) {
                $main[] = $item['field'];
                continue;
            }
            $extra[] = $item['field'];
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
                $order = $this->addExtraKey($order, $key);
            }
        }
        return [$order, $hasExtra];
    }

    /**
     * 添加 extra. 标志
     * @param string $val
     * @param string $search
     * @return string
     */
    private function addExtraKey(string $val, string $search): string {
        $len = strlen($search);
        $tags = ['', ',', '('];
        $i = 0;
        while (true) {
            $j = strpos($val, $search, $i);
            if ($j === false) {
                break;
            }
            if (($j > 0 && !in_array($val[$j - 1], $tags))
                || (($j + $len) < strlen($val) && !in_array($val[$j + $len], $tags))) {
                $i = $j + $len;
                continue;
            }
            $val = sprintf('%sextra.%s', substr($val, 0, $j),
                substr($val, $j));
            $i = $j + $len + 6;
        }
        return $val;
    }

    protected function isMultipleField(string $key): bool {
        foreach ($this->fieldList() as $item) {
            if ($item['field'] !== $key) {
                continue;
            }
            return $item['type'] === 'linkages';
        }
        return false;
    }

    private function addWhereIfMultiple(Builder $query, string $key, mixed $value): void {
        $items = BaseField::fromMultipleValue($value);
        if (empty($items)) {
            return;
        }
        $query->where($key, 'REGEXP', implode('|', array_map(function($item) {
            return BaseField::toMultipleValueQuery($item);
        }, $items)));
    }

    private function addWhereOrIn(Builder $query, array $params, string $prefix = ''): Builder {
        if (empty($params)) {
            return $query;
        }
        foreach ($params as $key => $item) {
            if ($this->isMultipleField($key)) {
                $this->addWhereIfMultiple($query, $prefix.$key, $item);
                continue;
            }
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
            if ($item['is_search'] < 1) {
                continue;
            }
            if ($item['is_main'] > 0) {
                $main[] = $item['field'];
                continue;
            }
            $extra[] = $item['field'];
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

    /**
     * @param string $keywords
     * @param array $params
     * @param string $order
     * @param int $page
     * @param int $perPage
     * @param string $fields
     * @return Page
     * @throws \Exception
     */
    public function search(string $keywords, array $params = [], string $order = '',
                           int $page = 1, int $perPage = 20, string $fields = '',
                           bool $isPage = true): Page {
        if (empty($fields)) {
            $fields = '*';
        }
        $query = $this->addQuery($this->query(), $params, $order, $fields)
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $this->addSearchQuery($query, $keywords);
            });
        if ($isPage) {
            return $query->page($perPage, 'page', $page);
        }
        return new Page($query->limit(max(($page - 1) * $page, 0), $perPage)->get(),
            $perPage, 'page', $page
        );
    }

    protected function getResultByField(Builder $query, string $field = '*') {

    }

    protected function initCommentTable(): void {
        CreateCmsTables::createTable($this->getCommentTable(), function (Table $table) {
            $table->id();
            $table->string('content');
            $table->string('extra_rule', 300)->default('')
                ->comment('内容的一些附加规则');
            $table->uint('parent_id')->default(0);
            $table->uint('position')->default(1);
            $table->uint('reply_count')->default(0);
            $table->uint('user_id')->default(0);
            if (CommentRepository::ALLOW_GUEST_COMMENT) {
                $table->string('name', 30)->default('');
                $table->string('email', 50)->default('');
                $table->string('url', 50)->default('');
            }
            $table->uint('model_id');
            $table->uint('content_id');
            $table->uint('agree_count')->default(0);
            $table->uint('disagree_count')->default(0);
            $table->timestamp('created_at');
        });
    }

    protected function initMainTableField(Table $table): void {
        $table->id();
        $table->uint('cat_id');
        $table->uint('model_id');
        $table->uint('parent_id')->default(0);
        $table->uint('user_id')->default(0);
        if ($this->isArticleModel()) {
            $table->string('title', 100);
            $table->string('keywords')->default('');
            $table->string('thumb')->default('');
            $table->string('description')->default('');
            $table->uint('view_count')->default(0);
            $table->uint('comment_count')->default(0);
            $table->bool('comment_open')->default(0);
            $table->string('seo_link')->default('')->comment('优雅链接');
        }
        $table->uint('status', 1)->default(SiteRepository::PUBLISH_STATUS_DRAFT);
        $table->timestamps();
    }

    /**
     * 存储优雅所有实体的内容
     * @param Table $table
     * @return void
     */
    protected function initSeoTableField(Table $table): void {
        $table->id();
        $table->string('title', 100);
        $table->uint('cat_id');
        $table->uint('model_id');
        $table->uint('parent_id')->default(0);
        $table->string('seo_link')->default('')->comment('优雅链接');
        $table->uint('status', 1)->default(SiteRepository::PUBLISH_STATUS_DRAFT);
        $table->timestamp(Model::CREATED_AT);
    }

    protected function initDefaultModelField(): void {
        if (!$this->isArticleModel()) {
            return;
        }
        ModelRepository::batchAddField([
            [
                'name' => '标题',
                'field' => 'title',
                'model_id' => $this->modelId(),
                'is_main' => 1,
                'is_system' => 1,
                'is_required' => 1,
                'type' => 'text'
            ],
            [
                'name' => '关键字',
                'field' => 'keywords',
                'model_id' => $this->modelId(),
                'is_main' => 1,
                'is_system' => 1,
                'is_required' => 0,
                'type' => 'text'
            ],
            [
                'name' => '简介',
                'field' => 'description',
                'model_id' => $this->modelId(),
                'is_main' => 1,
                'is_system' => 1,
                'is_required' => 0,
                'type' => 'textarea'
            ],
            [
                'name' => '缩略图',
                'field' => 'thumb',
                'model_id' => $this->modelId(),
                'is_main' => 1,
                'is_system' => 1,
                'is_required' => 0,
                'type' => 'image'
            ],
            [
                'name' => '开启评论',
                'field' => 'comment_open',
                'model_id' => $this->modelId(),
                'is_main' => 1,
                'is_system' => 1,
                'is_required' => 0,
                'type' => 'switch'
            ],
            [
                'name' => 'SEO优雅链接',
                'field' => 'seo_link',
                'model_id' => $this->modelId(),
                'is_main' => 1,
                'is_system' => 1,
                'is_required' => 0,
                'type' => 'text'
            ],
            [
                'name' => '内容',
                'field' => 'content',
                'model_id' => $this->modelId(),
                'is_main' => 0,
                'is_system' => 1,
                'type' => 'editor',
            ]
        ]);
    }

    public function toInput(ModelFieldModel|array $field, array $data = [], bool $isJson = false): array|string {
        if ($field['is_disable'] > 0) {
            return '';
        }
        return self::newField($field['type'])->toInput($data[$field['field']] ?? '', $field, $isJson);
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
            if ($field['is_disable']) {
                continue;
            }
            if (!$isNew && !array_key_exists($field['field'], $data)) {
                continue;
            }
            $value = static::newField($field['type'])
                ->filterInput($data[$field['field']] ?? null, $field, $bag);
            if ($field['is_main'] > 0) {
                $main[$field['field']] = $value;
                continue;
            }
            $extend[$field['field']] = $value;
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

    public static function converterTableField(Column $column, ModelFieldModel|array $field): void {
        static::newField($field['type'])->converterField($column, is_array($field) ? new ModelFieldModel($field) : $field);
    }
}