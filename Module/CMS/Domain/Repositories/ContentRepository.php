<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Auth\Domain\Events\ManageAction;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Scene\SceneInterface;
use Zodream\Database\Relation;
use Zodream\Helpers\Time;

class ContentRepository {
    public static function getList(
        int $site, int $category, string $keywords = '',
        int $parent = 0, int $modelId = 0, int $page = 1, int $perPage = 20) {
        SiteRepository::apply($site);
        if ($modelId < 1) {
            $modelId = intval(CategoryModel::where('id', $category)
                ->value('model_id'));
            if ($modelId < 1) {
                throw new Exception('栏目不包含模型');
            }
        }
        $modelModel = ModelRepository::get($modelId);
        $scene = CMSRepository::scene()->setModel($modelModel, $site);
        $column = static::searchField($scene);
        $page = $scene->search($keywords, [
            'cat_id' => $category,
            'parent_id' => $parent
        ], 'id desc', $page, $perPage, implode(',', array_column($column, 'name')));
        $data = $page->toArray();
        if (!empty($data['data'])) {
            static::formatValue($data['data']);
            $data['data'] = Relation::create($data['data'], [
                'category' => [
                    'query' => CategoryModel::query()->select('id', 'title'),
                    'link' => ['cat_id', 'id'],
                    'type' => Relation::TYPE_ONE
                ],
                'user' => [
                    'query' => UserSimpleModel::query(),
                    'link' => ['user_id', 'id'],
                    'type' => Relation::TYPE_ONE
                ]
            ]);
        }
        $data['column'] = $column;
        $data['model'] = $modelModel;
        return $data;
    }

    protected static function formatValue(array &$items) {
        foreach ($items as &$item) {
            foreach ([
                'updated_at', 'created_at'
                     ] as $key) {
                if (isset($item[$key])) {
                    $item[$key] = Time::format($item[$key]);
                }
            }
        }
    }

    protected static function searchField(SceneInterface $scene) {
        $column = [
            [
                'name' => 'id',
                'label' => 'ID',
            ],
            [
                'name' => 'cat_id',
                'label' => '分类',
            ],
            [
                'name' => 'user_id',
                'label' => '会员',
            ]
        ];
        foreach ($scene->fieldList() as $item) {
            if ($item->is_disable) {
                continue;
            }
            if (($item->is_system && $item->field === 'title') || $item->is_search) {
                $column[] = [
                    'name' => $item->field,
                    'label' => $item->name,
                ];
            }
        }
        return array_merge($column, [
            [
                'name' => 'status',
                'label' => '状态',
            ],
            [
                'name' => 'view_count',
                'label' => '浏览数',
            ],
            [
                'name' => 'comment_count',
                'label' => '评论数',
            ],
            [
                'name' => 'created_at',
                'label' => '发布时间',
            ],
            [
                'name' => 'updated_at',
                'label' => '更新时间',
            ],
        ]);
    }

    public static function get(int $site, int $category, int $modelId, int $id = 0) {
        $scene = static::apply($site, $category, $modelId);
        return $scene->find($id);
    }

    public static function form(array $data, SceneInterface $scene) {
        $tabItems = ModelRepository::fieldGroupByTab($scene->modelId());
        foreach ($tabItems as $i => $group) {
            foreach ($group['items'] as $j => $item) {
                $tabItems[$i]['items'][$j] = $scene->toInput($item, $data, true);
            }
        }
        return $tabItems;
    }

    public static function getForm(int $site, int $category, int $modelId = 0, int $id = 0) {
        $data = $id < 1 ? compact('id') : static::get($site, $category, $modelId, $id);
        if ($id < 1) {
            static::apply($site, $category, $modelId);
        }
        $data['form_data'] = static::form($data, CMSRepository::scene());
        return $data;
    }

    public static function save(int $site, int $category, int $modelId, array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $scene = static::apply($site, $category, $modelId);
        if ($id > 0) {
            $scene->update($id, $data);
        } else {
            $id = $scene->insert($data);
        }
        if ($id) {
            event(new ManageAction('cms_content_edit', '', 33, $id));
        }
        if ($scene->hasError()) {
            throw new \Exception($scene->getFirstError());
        }
        $data['id'] = $id;
        return $data;
    }

    public static function remove(int $site, int $category, int $modelId, int $id) {
        $scene = static::apply($site, $category, $modelId);
        $scene->remove($id);
    }

    public static function apply(int $site, int $category, int $modelId) {
        SiteRepository::apply($site);
        if ($modelId < 1) {
            $modelId = intval(CategoryModel::where('id', $category)
                ->value('model_id'));
            if ($modelId < 1) {
                throw new Exception('栏目不包含模型');
            }
        }
        return CMSRepository::scene()->setModel(ModelRepository::get($modelId), $site);
    }

    public static function search(int $site, int $model = 0,
                                  string $keywords = '',
                                  int $channel = 0, array|int $id = [],
                                    int $page = 1, int $perPage = 20,
    ) {
        SiteRepository::apply($site);
        if ($model < 1) {
            $model = $channel > 0 ? intval(CategoryModel::where('id', $channel)
                ->value('model_id')) : 0;
            if ($model < 1) {
                throw new Exception('栏目不包含模型');
            }
        }
        $modelModel = ModelRepository::get($model);
        $scene = CMSRepository::scene()->setModel($modelModel, $site);
        return $scene->query()->where('model_id', $model)
            ->when($channel > 0, function ($query) use ($channel) {
            $query->where('cat_id', $channel);
        })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })->when(!empty($id), function ($query) use ($id) {
                if (is_array($id)) {
                    $query->whereIn('id', $id);
                    return;
                }
                $query->where('id', $id);
            })->select('id','title','thumb')
            ->orderBy('created_at', 'desc')->page($perPage, page: $page);
    }
}