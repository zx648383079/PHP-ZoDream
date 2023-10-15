<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Repositories\LocalizeRepository;
use Exception;
use Module\Blog\Domain\Events\BlogUpdate;
use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\BlogPageModel;

final class PublishRepository {

    const PUBLISH_STATUS_DRAFT = 0; // 草稿
    const PUBLISH_STATUS_POSTED = 5; // 已发布
    const PUBLISH_STATUS_TRASH = 9; // 垃圾箱

    const TYPE_ORIGINAL = 0; // 原创
    const TYPE_REPRINT = 1; // 转载

    const EDIT_HTML = 0;
    const EDIT_MARKDOWN = 1; // markdown

    const OPEN_PUBLIC = 0; // 公开
    const OPEN_LOGIN = 1; // 需要登录
    const OPEN_PASSWORD = 5; // 需要密码
    const OPEN_BUY = 6; // 需要购买

    public static function getList(string $keywords = '', int $category = 0,
                                   int $status = 0, int $type = 0, string $language = '') {
        $items = BlogPageModel::with('term')
            ->where('user_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })->when($category > 0, function ($query) use ($category) {
                $query->where('term_id', $category);
            })->when($type > 0, function ($query) use ($type) {
                $query->where('type', $type - 1);
            })->when($status > 0, function ($query) use ($status) {
                $query->where('publish_status', $status - 1);
            })->when(!empty($language), function ($query) use ($language) {
                $query->where('language', $language);
            })->orderBy('id', 'desc')->page();
        $items->map(function ($item) {
            $isLocal = $item['parent_id'] > 0;
            if (!$isLocal) {
                $isLocal = BlogModel::where('parent_id', $item['id'])->count() > 0;
            }
            $item['is_localization'] = $isLocal;
            return $item;
        });
        return $items;
    }

    public static function get(int $id = 0, string $language = '') {
        $model = self::getBlog($id, $language);
        $tags = $model->isNewRecord ? [] : TagRepository::getTags($model->id);
        $data = $model->toArray();
        $data['tags'] = $tags;
        $data['open_rule'] = $model->open_rule;
        $data['languages'] = self::languageAll($model->parent_id > 0 ? $model->parent_id : $model->id);
        return array_merge($data, BlogMetaModel::getOrDefault($id));
    }

    public static function getOrNew(int $id = 0, string $language = ''): BlogModel {
        if ($id > 0) {
            /** @var BlogModel $model */
            $model = BlogModel::where('id', $id)
                ->where('user_id', auth()->id())->first();
            if (empty($model)) {
                throw new Exception(__('blog is not exist'));
            }
            if (empty($language) || $model->language === $language) {
                return $model;
            }
            $data = new BlogModel();
            $data->parent_id = $model->parent_id > 0 ? $model->parent_id : $model->id;
            $data->language = $language;
            return $data;
        }
        $model = BlogModel::where('user_id', auth()->id())
            ->where('publish_status', self::PUBLISH_STATUS_DRAFT)
            ->orderBy('parent_id', 'asc')
            ->first();
        if (empty($model)) {
            $data = new BlogModel();
            $data->language = LocalizeRepository::firstLanguage();
            return $data;
        }
        if (empty($language) || $model->language === $language) {
            return $model;
        }
        $data = new BlogModel();
        $data->parent_id = $model->parent_id > 0 ? $model->parent_id : $model->id;
        $data->language = $language;
        return $data;
    }

    /**
     * 获取所有的支持语言列表，显示在后台
     * @param int $id
     * @return array
     */
    public static function languageAll(int $id): array {
        $items = BlogModel::query()
            ->where('parent_id', $id)
            ->orWhere('id', $id)
            ->asArray()
            ->get('id', 'language');
        return LocalizeRepository::formatLanguageList($items, false);
    }

    private static function getBlog(int $id = 0, string $language = ''): BlogModel {
        if ($id > 0) {
            return self::getBlogById($id, $language);
        }
        if (empty($language)) {
            $model = BlogModel::where('user_id', auth()->id())
                ->where('publish_status', self::PUBLISH_STATUS_DRAFT)
                ->orderBy('parent_id', 'asc')
                ->first();
        } else {
            $model = BlogModel::where('user_id', auth()->id())
                ->where('publish_status', self::PUBLISH_STATUS_DRAFT)
                ->where('language', $language)->first();
        }
        if (empty($model)) {
            throw new Exception(__('blog is not exist'));
        }
        return $model;
    }

    private static function getBlogById(int $id, string $language = ''): BlogModel {
        /** @var BlogModel $model */
        $model = BlogModel::where('id', $id)
            ->where('user_id', auth()->id())->first();
        if (empty($model)) {
            throw new  Exception(__('blog is not exist'));
        }
        if (empty($language) || $language === $model->language) {
            return $model;
        }
        if ($model->parent_id > 0) {
            $model = BlogModel::where(function ($query) use ($model) {
                $query->where('parent_id', $model->parent_id)
                    ->orWhere('id', $model->parent_id);
            })->where('language', $language)->frist();
        } else {
            $model = BlogModel::where('parent_id', $model->id)
                ->where('language', $language)->frist();
        }
        if (empty($model)) {
            throw new  Exception(__('blog is not exist'));
        }
        return $model;
    }

    public static function save(array $data, int $id = 0) {
        if ($id < 1 && isset($data['id'])) {
            $id = intval($data['id']);
        }
        unset($data['id']);
        $userId = auth()->id();
        $model = $id > 0 ? BlogModel::where('id', $id)
            ->where('user_id', $userId)->first() : new BlogModel();
        $isNew = $model->isNewRecord;
        if (!$model->load($data, ['user_id'])) {
            throw new Exception(__('post data error'));
        }
        // 需要同步的字段
        $async_column = [
            'user_id',
            'term_id',
            'programming_language',
            'type',
            'thumb',
            'open_type',
            'open_rule',];
        $model->user_id = $userId;
        if ($model->parent_id > 0) {
            $parent = BlogModel::find($model->parent_id);
            if (!$model->language || $model->language == 'zh') {
                $model->language = 'en';
            }
            foreach ($async_column as $key) {
                $model->{$key} = $parent->{$key};
            }
        }
        $model->parent_id = intval($model->parent_id);
        if (!$model->saveIgnoreUpdate()) {
            throw new Exception($model->getFirstError());
        }
        if ($model->parent_id < 1) {
            TagRepository::addTag($model->id,
                isset($data['tags']) && !empty($data['tags']) ? $data['tags'] : []);
            $asyncData = [];
            foreach ($async_column as $key) {
                $asyncData[$key] = $model->getAttributeSource($key);
            }
            BlogModel::where('parent_id', $model->id)->update($asyncData);
        }
        BlogMetaModel::saveBatch($model->id, $data);
        event(new BlogUpdate($model->id, $isNew ? 0 : 1, time()));
        return $model;
    }

    /**
     * 保存为草稿箱
     * @param array $data
     * @param int $id
     * @return BlogModel
     * @throws Exception
     */
    public static function saveDraft(array $data, int $id = 0) {
        $data['publish_status'] = self::PUBLISH_STATUS_DRAFT;
        return self::save($data, $id);
    }

    public static function update(int $id, array $data) {
        $model = BlogModel::where('id', $id)->where('user_id', auth()->id())
            ->first();
        if (empty($model)) {
            throw new Exception(__('blog is not exist'));
        }
        $maps = ['type', 'publish_status'];
        foreach ($data as $action => $val) {
            if (is_int($action)) {
                if (empty($val)) {
                    continue;
                }
                list($action, $val) = [$val, $model->{$val} > 0 ? 0 : 1];
            }
            if (empty($action) || !in_array($action, $maps)) {
                continue;
            }
            $model->{$action} = intval($val);
        }
        $model->save();
        return $model;
    }

    public static function remove(int $id) {
        $model = BlogModel::where('id', $id)->where('user_id', auth()->id())
            ->first();
        if (empty($model)) {
            throw new Exception(__('blog is not exist'));
        }
        $model->delete();
        if ($model->parent_id < 1) {
            BlogModel::where('parent_id', $id)->delete();
        }
        BlogMetaModel::deleteBatch($id);
        event(new BlogUpdate($model->id, 2, time()));
    }
}