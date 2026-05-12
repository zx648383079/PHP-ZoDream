<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Exception;
use Module\CMS\Domain\Contexts\SiteContextInterface;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ModelModel;
use Zodream\Helpers\Arr;
use Zodream\Html\Tree;

class CategoryRepository {
    public static function getList(int $site) {
        $context = SiteRepository::apply($site);
        $modelItems = Arr::pluck(ModelModel::query()->select('id', '`table`')
            ->get(), null, 'id');
        $items = $context->channelBuilder()->orderBy('position', 'asc')->get();
        $countKey = 'content_count';
        foreach ($items as &$item) {
            if ($item['type'] > 0 || !isset($modelItems[$item['model_id']])) {
                $item[$countKey] = 0;
                continue;
            }
            $item[$countKey] = $context->scene()->setModel($modelItems[$item['model_id']])
                ->query()->where('model_id', $item['model_id'])
                ->where('cat_id', $item['id'])->count();
        }
        unset($item);
        return (new Tree($items))
            ->makeTreeForHtml();
    }

    public static function get(int $site, int $id) {
        $context = SiteRepository::apply($site);
        return self::getOrThrow($context, $id);
    }

    private static function getOrCreate(SiteContextInterface $context, int $id): CategoryModel {
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
        $model = $context->channelBuilder()->where('id', $id)->first();
        if (empty($model)) {
            throw new Exception('数据有误');
        }
        return $model;
    }

    public static function save(int $site, array $data) {
        $context = SiteRepository::apply($site);
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = self::getOrCreate($context, $id);
        if (is_numeric($data['name'])) {
            $data['name'] = sprintf('%s%d',
                CMSRepository::generateTableName($data['title']),
                $data['name']);
        }
        $model->load($data);
        $context->channelSave($model);
        if ($model->hasError()) {
            throw new \Exception($model->getFirstError());
        }
        CacheRepository::onChannelUpdated(intval($model->id));
        return $model;
    }

    public static function remove(int $site, int $id) {
        $context = SiteRepository::apply($site);
        $context->channelBuilder()->where('id', $id)->delete();
        CacheRepository::onChannelUpdated($id);
    }

    public static function all(int $site) {
        $context = SiteRepository::apply($site);
        return (new Tree($context->channelBuilder()
            ->where('site_id', $site)->orderBy('position', 'asc')
            ->get('id', 'title', 'parent_id')))
            ->makeTreeForHtml();
    }

    public static function apply(int $site, int $id) {
        $context = SiteRepository::apply($site);
        $modelId = $context->channelBuilder()->where('id', $id)
            ->value('model_id');
        if ($modelId < 1) {
            throw new Exception('栏目不包含模型');
        }
        return $context->scene()->setModel(ModelRepository::get(intval($modelId)));
    }

    public static function batchSave(array $data): void {
        $context = CMSRepository::context();
        $parent = null;
        if (!empty($data['parent_id'])) {
            $parent = self::getOrThrow($context, $data['parent_id']);
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