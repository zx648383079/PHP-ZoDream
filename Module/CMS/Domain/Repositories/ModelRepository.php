<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\Auth\Domain\Events\ManageAction;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\Scene\SceneInterface;
use Module\CMS\Domain\Scene\SingleScene;

class ModelRepository {

    const FIELD_TYPE_ITEMS = [
        'text' => '文本字段',
        'textarea' => '多行文本',
        'markdown' => 'Markdown',
        'editor' => '编辑器',
        'radio' => '单选按钮',
        'select' => '下拉选择',
        'switch' => '开关',
        'checkbox' => '复选框',
        'color' => '颜色选取',
        'email' => '邮箱字段',
        'password' => '密码字段',
        'url' => '链接字段',
        'ip' => 'IP字段',
        'number' => '数字字段',
        'date' => '日期时间',
        'file' => '单文件',
        'image' => '单图',
        'images' => '多图',
        'files' => '多文件',
        'linkage' => '联动菜单',
        'linkages' => '多联动菜单',
        'location' => '定位',
        'map' => '地图',
        'model' => '关联实体模型',
    ];

    public static function getList(string $keywords = '', int $type = 0) {
        $items = ModelModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title']);
            })->orderBy('position', 'asc')
            ->orderBy('id', 'desc')->page();
        if ($items->isEmpty()) {
            return $items;
        }
        return ModelHelper::bindCount($items, ModelFieldModel::query(),
            'id', 'model_id', 'field_count');
    }

    public static function get(int $id) {
        return ModelModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? ModelModel::findOrThrow($id) : new ModelModel();
        if (empty($model)) {
            throw new \Exception('');
        }
        if ($id > 0) {
            unset($data['table']);
        } elseif (ModelModel::where('`table`', $data['table'])->count() > 0) {
            throw new \Exception('表名已存在');
        }
        $model->load($data);
        if ($model->type > 0 && $model->setting('is_extend_auth')) {
            $model->setting['is_only'] = 1;
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        event(new ManageAction('cms_model_edit', '', 32, intval($id)));
        if ($id < 1) {
            CMSRepository::scene()->setModel($model)->initModel();
        }
        CacheRepository::onModelUpdated(intval($model->id));
        return $model;
    }

    public static function remove(int $id) {
        $model = ModelModel::where('id', $id)->first();
        if (!$model) {
            throw new \Exception('模型不存在');
        }
        $model->delete();
        ModelFieldModel::where('model_id', $id)->delete();
        CMSRepository::removeModel($model);
        CacheRepository::onModelUpdated($id);
    }

    public static function all(int $type = 0) {
        return ModelModel::query()
            ->where('type', $type)
            ->get('id', 'name');
    }

    public static function fieldList(int $model) {
        return ModelFieldModel::query()
            ->where('model_id', $model)->orderBy('position', 'asc')
            ->orderBy('is_system', 'desc')
            ->orderBy('id', 'asc')->get();
    }

    public static function field(int $id) {
        return ModelFieldModel::findOrThrow($id, '数据有误');
    }

    public static function fieldSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = ModelFieldModel::findOrNew($id);
        if ($model->is_system > 0) {
            $model->name = $data['name'];
            $model->save();
            return $model;
        }
        if (ModelFieldModel::where('`field`', $model->field)
                ->where('id', '<>', $id)
                ->where('model_id', $model->model_id)
                ->count() > 0) {
            throw new \Exception('字段已存在');
        }
        $old = $id > 0 ? $model->get() : [];
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        SiteRepository::mapTemporary(function (SceneInterface $scene, SiteModel $site) use ($id, $model, $old) {
            $scene->setModel($model->model, intval($site['id']));
            if (!$scene->initializedModel()) {
                return;
            }
            if ($id > 0) {
                $scene->updateField($model, $old);
            } else {
                $scene->addField($model);
            }
        });
        CacheRepository::onModelUpdated(intval($model->model_id));
        return $model;
    }

    public static function fieldRemove(int $id) {
        $model = ModelFieldModel::find($id);
        if (!$model) {
            throw new \Exception('字段不存在');
        }
        if ($model->is_system > 0) {
            throw new \Exception('系统自带字段禁止删除');
        }
        SiteRepository::mapTemporary(function (SceneInterface $scene, SiteModel $site) use ($model) {
            $scene->setModel($model->model, intval($site['id']));
            if (!$scene->initializedModel()) {
                return;
            }
            $scene->removeField($model);
        });
        $model->delete();
        CacheRepository::onModelUpdated(intval($model->model_id));
        return $model;
    }

    public static function fieldToggle(int $id, array $data) {
        $model = ModelFieldModel::findOrThrow($id);
        return ModelHelper::updateField($model, ['is_disable'], $data);
    }

    /**
     * 对属性进行分组
     * @param int $model
     * @return array
     */
    public static function fieldGroupByTab(int $model) {
        $tab_list = self::fieldTab($model);
        $data = [];
        foreach ($tab_list as $i => $item) {
            $data[$item] = [
                'name' => $item,
                'active' => $i < 1,
                'items' => []
            ];
        }
        $field_list = ModelFieldModel::where('model_id', $model)->orderBy([
            'position' => 'asc',
            'id' => 'asc'
        ])->get();
        foreach ($field_list as $item) {
            $name = $item->tab_name;
            if (empty($name) || !in_array($name, $tab_list)) {
                $name = $item->is_main > 0 ? $tab_list[0] : $tab_list[1];
            }
            $data[$name]['items'][] = $item;
        }
        return array_values($data);
    }

    /**
     * 获取所有的分组标签
     * @param int $model
     * @return string[]
     */
    public static function fieldTab(int $model) {
        $tab_list = ModelFieldModel::where('model_id', $model)->pluck('tab_name');
        $data = ['基本', '高级'];
        foreach ($tab_list as $item) {
            $item = trim($item);
            if (empty($item) || in_array($item, $data)) {
                continue;
            }
            $data[] = $item;
        }
        return $data;
    }

    public static function fieldType(): array {
        $items = static::FIELD_TYPE_ITEMS;
        $data = [];
        foreach ($items as $value => $name) {
            $data[] = compact('name', 'value');
        }
        return $data;
    }

    public static function fieldOption(string $type, int $field) {
        $model = ModelFieldModel::findOrNew($field);
        $field = SingleScene::newField($type);
        $data = $field->options($model, true);
        return empty($data) || !is_array($data) ? [] : $data;
    }

    public static function batchAddField(array $items): void {
        $add = [];
        foreach ($items as $item) {
            $model = ModelFieldModel::where('field', $item['field'])
                ->where('model_id', $item['model_id'])->first();
            if (empty($model)) {
                $add[] = $item;
                continue;
            }
            if (empty($item['name']) || $item['name'] === $model->name) {
                continue;
            }
            ModelFieldModel::where('id', $model->id)
                ->update(['name' => $item['name']]);
        }
        if (empty($add)) {
            return;
        }
        ModelFieldModel::query()->insert($add);
    }

    public static function restart(int $id) {
        $model = ModelModel::findOrThrow($id);
        $scene = CMSRepository::scene()->setModel($model);
        $scene->removeTable();
        $scene->initTable();
    }
}