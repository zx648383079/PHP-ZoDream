<?php
declare(strict_types=1);
namespace Module\Document\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\FieldModel;
use Module\Document\Domain\Model\PageModel;
use Module\Document\Domain\Model\ProjectModel;
use Module\Document\Domain\Model\ProjectVersionModel;

class ProjectRepository {

    public static function getList(string $keywords = '', int $type = 0) {
        return ProjectModel::when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })->where(function ($query) {
                $query->where('status', ProjectModel::STATUS_PUBLIC);
                if (auth()->guest()) {
                    return;
                }
                $query->orWhere('user_id', auth()->id());
            })
            ->when($type > 0, function ($query) use ($type) {
                $query->where('type', $type - 1);
            })
            ->orderBy('id', 'desc')->page();
    }

    public static function getSelfList(string $keywords = '', int $type = 0) {
        return ProjectModel::where('user_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })->when($type > 0, function ($query) use ($type) {
                $query->where('type', $type - 1);
            })->orderBy('id', 'desc')->page();
    }

    /**
     * @param int $id
     * @return ProjectModel
     * @throws Exception
     */
    public static function get(int $id) {
        $model = ProjectModel::where('id', $id)
            ->where(function ($query) {
                $query->where('status', ProjectModel::STATUS_PUBLIC);
                if (auth()->guest()) {
                    return;
                }
                $query->orWhere('user_id', auth()->id());
            })->first();
        if (empty($model)) {
            throw new Exception('项目文档不存在');
        }
        return $model;
    }

    public static function getSelf(int $id) {
        $model = ProjectModel::findWithAuth($id);
        if (empty($model)) {
            throw new Exception('项目文档不存在');
        }
        return $model;
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = $id > 0 ? self::getSelf($id) : new ProjectModel();
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function removeSelf(int $id) {
        $model = static::getSelf($id);
        $model->delete();
        ProjectVersionModel::where('project_id', $id)->delete();
        PageModel::where('project_id', $id)->delete();
        $apiId = ApiModel::where('project_id', $id)->pluck('id');
        if (empty($apiId)) {
            return;
        }
        FieldModel::whereIn('api_id', $apiId)->delete();
        ApiModel::where('project_id', $id)->delete();
    }

    public static function createVersion(int $id, int $srcVersion, string $version) {
        if (empty($version)) {
            throw new Exception('请输入正确的版本号');
        }
        $count = ProjectVersionModel::where('project_id', $id)
            ->where('name', $version)
            ->count();
        if ($count > 0) {
            throw new Exception('版本号已存在');
        }
        $project = static::getSelf($id);
        $model = ProjectVersionModel::create([
            'project_id' => $id,
            'name' => $version,
        ]);
        if (empty($model)) {
            throw new Exception('创建版本号失败');
        }
        if ($project->type < 1) {
            static::copyPageVersion($project->id, $srcVersion, $model->id);
            return $model;
        }
        static::copyApiVersion($project->id, $srcVersion, $model->id);
        return $model;
    }

    protected static function copyPageVersion(int $project, int $srcVersion, int $distVersion) {
        $mapId = [];
        $items = PageModel::query()->where('project_id', $project)
            ->where('version_id', $srcVersion)
            ->orderBy('parent_id', 'asc')->asArray()->get();
        foreach ($items as $item) {
            $item['version_id'] = $distVersion;
            if ($item['parent_id'] > 0) {
                $item['parent_id'] = isset($mapId[$item['parent_id']]) ? $mapId[$item['parent_id']] : 0;
            }
            $id = $item['id'];
            unset($item['id']);
            $mapId[$id] = PageModel::query()->insert($item);
        }
    }

    protected static function copyApiVersion(int $project, int $srcVersion, int $distVersion) {
        $mapId = [];
        $items = ApiModel::query()->where('project_id', $project)
            ->where('version_id', $srcVersion)
            ->orderBy('parent_id', 'asc')->asArray()->get();
        foreach ($items as $item) {
            $item['version_id'] = $distVersion;
            if ($item['parent_id'] > 0) {
                $item['parent_id'] = isset($mapId[$item['parent_id']]) ? $mapId[$item['parent_id']] : 0;
            }
            $id = $item['id'];
            unset($item['id']);
            $mapId[$id] = ApiModel::query()->insert($item);
        }
        if (empty($mapId)) {
            return;
        }
        $fieldMap = [];
        $items = FieldModel::whereIn('api_id', array_keys($mapId))
            ->orderBy('parent_id', 'asc')->asArray()->get();
        foreach ($items as $item) {
            $item['api_id'] = $mapId[$item['api_id']];
            if ($item['parent_id'] > 0) {
                $item['parent_id'] = isset($fieldMap[$item['parent_id']]) ? $fieldMap[$item['parent_id']] : 0;
            }
            $id = $item['id'];
            unset($item['id']);
            $mapId[$id] = FieldModel::query()->insert($item);
        }
    }

    public static function versionRemove(int $project, int $id = 0) {
        if ($id > 0) {
            ProjectVersionModel::where('id', $id)->delete();
        }
        PageModel::where('project_id', $project)
            ->where('version_id', $id)->delete();
        $apiId = ApiModel::where('project_id', $project)
            ->where('version_id', $id)->pluck('id');
        if (empty($apiId)) {
            return;
        }
        FieldModel::whereIn('api_id', $apiId)->delete();
        ApiModel::where('project_id', $project)
            ->where('version_id', $id)->delete();
    }

    public static function all() {
        return ProjectModel::orderBy('id', 'asc')
            ->get('id', 'name');
    }

    public static function allSelf() {
        return ProjectModel::where('user_id', auth()->id())
            ->orderBy('id', 'asc')
            ->get('id', 'name');
    }

    public static function versionAll($id) {
        $items = ProjectVersionModel::where('project_id', $id)
            ->orderBy('id', 'asc')
            ->get();
        array_unshift($items, ['id' => 0, 'name' => 'main']);
        return $items;
    }

    public static function catalog(int $id, int $version) {
        $project = static::get($id);
        return $project->type > 0 ? ApiRepository::tree($id, $version)
            : PageRepository::tree($id, $version);
    }

    public static function canOpen(int $id): bool {
        return ProjectModel::where('id', $id)
            ->where(function ($query) {
                $query->where('status', ProjectModel::STATUS_PUBLIC);
                if (auth()->guest()) {
                    return;
                }
                $query->orWhere('user_id', auth()->id());
            })->count() > 0;
    }

    public static function page(int $project, int $id) {
        $model = ProjectRepository::get($project);
        if ($model->type < 1) {
            $page = PageRepository::get($id);
            if ($page->project_id !== $project) {
                throw new Exception('文档错误');
            }
            return PageRepository::getRead($page);
        }
        $api = ApiRepository::get($id);
        if ($api->project_id !== $project) {
            throw new Exception('文档错误');
        }
        return ApiRepository::getRead($api);
    }
}