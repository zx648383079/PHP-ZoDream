<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\CMS\Domain\Contexts\SiteContextInterface;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ModelModel;
use Zodream\Helpers\Arr;
use Zodream\Html\Tree;
use Zodream\Helpers\Tree as TreeHelper;

class CategoryRepository {
    public static function getList(int|SiteContextInterface $site, string $keywords = '') {
        $context = $site instanceof SiteContextInterface ? $site : SiteRepository::apply($site);
        $modelItems = Arr::pluck(ModelModel::query()->select('id', '`table`')
            ->get(), null, 'id');
        $items = $context->channelBuilder()->where('site_id', $context->id())
            ->when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, ['title', 'keywords', 'description', 'content'], false, '', $keywords);
            })->orderBy('position', 'asc')->get();
        $countKey = 'content_count';
        foreach ($items as &$item) {
            if ($item['type'] > 0 || !isset($modelItems[$item['model_id']])) {
                $item[$countKey] = 0;
                continue;
            }
            $item[$countKey] = $context->scene()->setModel($modelItems[$item['model_id']])
                ->query()->where('site_id', $context->id())->where('model_id', $item['model_id'])
                ->where('cat_id', $item['id'])->count();
        }
        unset($item);
        if (!empty($keywords)) {
            return $items;
        }
        return (new Tree($items))
            ->makeTreeForHtml();
    }

    public static function get(int|SiteContextInterface $site, int $id) {
        $context = $site instanceof SiteContextInterface ? $site : SiteRepository::apply($site);
        return self::getOrThrow($context, $id);
    }

    public static function getOrCreate(SiteContextInterface $context, int $id): CategoryModel {
        if ($id === 0) {
            return new CategoryModel();
        }
        $model = $context->channelBuilder()->where('id', $id)->first();
        if (empty($model)) {
            return new CategoryModel();
        }
        return $model;
    }

    private static function getOrThrow(SiteContextInterface $context, int $id): CategoryModel {
        $model = $context->channelBuilder()->where('site_id', $context->id())->where('id', $id)->first();
        if (empty($model)) {
            throw new Exception('数据有误');
        }
        return $model;
    }

    public static function save(int|SiteContextInterface $site, array $data) {
        $context = $site instanceof SiteContextInterface ? $site : SiteRepository::apply($site);
        $id = intval($data['id'] ?? 0);
        unset($data['id']);
        $locale_group_id = intval($data['locale_group_id']);
        
        if ($id === 0 && $locale_group_id > 0) {
            if (intval($data['site_id']) !== $context->id()) {
                $context = CMSRepository::contextFrom(intval($data['site_id']));
            }
            // 尝试进行合并
            $model = empty($data['name']) ? null : $context->channelBuilder()
                ->where('site_id', $context->id())
                ->where('parent_id', intval($data['parent_id']))
                ->where('name', $data['name'])
                ->where('locale_group_id', 0)->first();
            if (!$model) {
                $model = new CategoryModel();
            }
        } else {
            $model = self::getOrCreate($context, $id);
        }
        if (is_numeric($data['name'])) {
            $data['name'] = sprintf('%s%d',
                CMSRepository::generateTableName($data['title']),
                $data['name']);
        }
        $model->load($data);
        if (!$model->type && !$model->model_id) {
            if ($model->pareent_id > 0) {
                $model->model_id = intval($context->channelBuilder()->where('id', $model->pareent_id)
                ->value('model_id'));
            }
            if (!$model->model_id) {
                throw new Exception('请选择模型');
            }
        }
        $model->site_id = $context->id();
        $context->channelSave($model);
        if ($model->hasError()) {
            throw new \Exception($model->getFirstError());
        }
        if ($locale_group_id > 0) {
            LocaleRepository::channelBinding($context, intval($model->id), $locale_group_id);
        }
        CacheRepository::onChannelUpdated(intval($model->id));
        return $model;
    }

    public static function remove(int|SiteContextInterface $site, int $id) {
        $context = $site instanceof SiteContextInterface ? $site : SiteRepository::apply($site);
        $items = self::getChildrenWithParent($context, $id);
        $modelIds = $context->channelBuilder()->where('site_id', $context->id())->whereIn('id', $items)
            ->where('model_id', '>', 0)
            ->pluck('model_id');
        if (empty($modelIds)) {
            $model_list = ModelModel::whereIn('id', $modelIds)
                ->get();
            foreach ($model_list as $model) {
                $scene = $context->scene()->setModel($model);
                $ids = $scene->query()->where('site_id', $context->id())->whereIn('cat_id', $items)->pluck('id');
                $scene->remove($ids);
            }
        }
        $context->channelBuilder()->where('site_id', $context->id())->whereIn('id', $items)->delete();
        LocaleRepository::channelUnlink($context, $id);
        CacheRepository::onChannelUpdated($id);
    }

    public static function all(int|SiteContextInterface $site) {
        $context = $site instanceof SiteContextInterface ? $site : SiteRepository::apply($site);
        return (new Tree($context->channelBuilder()
            ->where('site_id', $context->id())->orderBy('position', 'asc')
            ->get('id', 'title', 'parent_id', 'type', 'model_id')))
            ->makeTreeForHtml();
    }


    public static function getChildrenWithParent(SiteContextInterface $context, int $id) {
        $data = TreeHelper::getTreeChild($context->channelBuilder()
            ->where('site_id', $context->id())->orderBy('parent_id', 'asc')
            ->orderBy('id', 'asc')->get('id', 'parent_id'), $id);
        $data[] = $id;
        return $data;
    }

    public static function apply(int $site, int $id) {
        $context = SiteRepository::apply($site);
        $modelId = $context->channelBuilder()->where('site_id', $site)->where('id', $id)
            ->value('model_id');
        if ($modelId < 1) {
            throw new Exception('栏目不包含模型');
        }
        return $context->scene()->setModel(ModelRepository::get(intval($modelId)));
    }

    public static function batchSave(SiteContextInterface $context, array $data): void {
        $parent = null;
        if (!empty($data['parent_id'])) {
            $parent = self::getOrThrow($context, intval($data['parent_id']));
        }
        foreach ([
                     'category_template',
                     'list_template',
                     'show_template',
                 ] as $key) {
            if (!empty($data[$key]) && $data[$key] === '@parent') {
                $data[$key] = empty($parent) ? '' : $parent[$key];
            }
        }
        if (!empty($data['open_comment'])) {
            $data['setting'] = [
                'open_comment' => true
            ];
        }
        if (empty($data['model_id'])) {
            if (empty($parent)) {
                $data['type'] = 1;
            } else {
                $data['type'] = $parent['type'];
                $data['model_id'] = $parent['model_id'];
            }
        }
        unset($data['open_comment']);
        $levelItems = [intval($data['parent_id'])];
        $orderItems = [];
        $last = -1;
        $level = -1;
        $siteId = $context->id();
        foreach (explode("\n", $data['content']) as $line) {
            list($c, $title) = self::splitTitleTag($line);
            if (empty($title)) {
                continue;
            }
            if ($c > $last) {
                $orderItems[] = $last;
                $last = $c;
                $level ++;
            } elseif ($c < $last) {
                for ($i = count($orderItems) - 1; $i >= 0; $i --) {
                    if ($orderItems[$i] < $c) {
                        $last = $orderItems[$i];
                        array_splice($orderItems, $i, count($orderItems) - $i);
                        $level = count($orderItems);
                        break;
                    }
                }
            }
            $parentId = $levelItems[$level];
            $model = $context->channelSave(array_merge($data, [
                'site_id' => $siteId,
                'parent_id' => $parentId,
                'title' => $title,
                'name' => sprintf('%s%d', CMSRepository::generateTableName($title), $level)
            ]));
            $levelItems[$level + 1] = $model->id;
        }
    }

    protected static function splitTitleTag(string $val): array {
        $val = trim($val);
        $count = 0;
        for ($i = 0; $i < strlen($val); $i ++) {
            if (substr($val, $i, 1) !== '-') {
                break;
            }
            $count ++;
        }
        return [$count, ltrim(substr($val, $count))];
    }
}