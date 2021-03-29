<?php
namespace Module\CMS\Domain\Scene;

use Module\CMS\Domain\Fields\BaseField;
use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Zodream\Database\Contracts\Column;
use Zodream\Database\DB;
use Zodream\Database\Query\Builder;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Concerns\ErrorTrait;

abstract class BaseScene implements SceneInterface {

    use ErrorTrait;

    protected $site = 1;

    /**
     * @var ModelModel
     */
    protected $model;

    public function setModel(ModelModel $model, $site = 0) {
        $this->model = $model;
        $this->site = $site > 0 ? $site : CMSRepository::siteId();
        return $this;
    }

    public function remove($id) {
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
                return;
            }
            $query->delete();
        }
    }

    public function find($id) {
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

    /**
     * @return Builder
     */
    public function query() {
        return DB::table($this->getMainTable());
    }

    /**
     * @return Builder
     */
    public function extendQuery() {
        return DB::table($this->getExtendTable());
    }

    /**
     * @return ModelFieldModel[]
     */
    public function fieldList() {
        return FuncHelper::fieldList($this->model->id);
    }

    protected function getGroupFieldName() {
        $field_list = $this->fieldList();
        $main = ['id', 'cat_id', 'model_id', 'user_id',
            'status', 'view_count',
            'updated_at', 'created_at', 'parent_id'];
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

    protected function splitByField(array $params) {
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
     * @param null $order
     * @param string $field
     * @return Builder
     */
    protected function addQuery(Builder $query, $params = [], $order = null, $field = '') {
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

    private function filterQuery($order) {
        if (empty($order)) {
            return [$order, false];
        }
        $hasExtra = false;
        list($mainNames, $extraNames) = $this->getGroupFieldName();
        foreach ($extraNames as $key) {
            if (strpos($order, $key) !== false) {
                $hasExtra = true;
                $order = str_replace($key, 'extra.' . $key, $order);
            }
        }
        return [$order, $hasExtra];
    }

    private function addWhereOrIn(Builder $query, array $params, $prefix = '') {
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

    protected function addSearchQuery(Builder $query, $keywords) {
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

    protected function getResultByField(Builder $query, $field = '*') {

    }

    public function toInput(ModelFieldModel $field, array $data) {
        if ($field->is_disable > 0) {
            return null;
        }
        return self::newField($field->type)->toInput(isset($data[$field->field])
            ? $data[$field->field] : '', $field);
    }

    /**
     * @param array $data
     * @param bool $isNew
     * @return array [main, extend]
     * @throws \Exception
     */
    public function filterInput(array $data, $isNew = true) {
        $field_list = $this->fieldList();
        if (empty($field_list)) {
            return [[], []];
        }
        $extend = $main = [];
        foreach ($field_list as $field) {
            if (!$isNew && !array_key_exists($field->field, $data)) {
                continue;
            }
            $value = static::newField($field->type)
                ->filterInput(isset($data[$field->field]) ? $data[$field->field]
                : null, $field);
            if ($field->is_main > 0) {
                $main[$field->field] = $value;
                continue;
            }
            $extend[$field->field] = $value;
        }
        return [$main, $extend];
    }

    /**
     * @param $type
     * @return BaseField
     * @throws \Exception
     */
    public static function newField($type) {
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