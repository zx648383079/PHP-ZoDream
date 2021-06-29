<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Repositories\UserRepository;
use Module\Exam\Domain\Model\PageEvaluateModel;
use Module\Exam\Domain\Model\PageModel;
use Module\Exam\Domain\Model\PageQuestionModel;
use Module\Exam\Domain\Model\QuestionModel;

class PageRepository {
    public static function getList(string $keywords = '', int $user = 0) {
        return PageModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', auth()->id());
            })->orderBy('end_at', 'desc')->page();
    }

    public static function selfList(string $keywords = '') {
        return static::getList($keywords, auth()->id());
    }

    public static function get(int $id, int $user = 0) {
        $model = PageModel::findOrThrow($id, '数据有误');
        if ($user > 0 && $model->user_id !== $user) {
            throw new \Exception('数据有误');
        }
        $rules = $model->rule_value;
        if ($model->rule_type > 0 && !empty($rules)) {
            $model->rule_value = QuestionModel::whereIn('id', $rules)
                ->get('id', 'type', 'title');
        }
        return $model;
    }

    public static function getSelf(int $id) {
        return static::get($id);
    }

    public static function save(array $data, int $user = 0) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = PageModel::findOrNew($id);
        if ($id > 0 && $user > 0 && $model->user_id !== $user) {
            throw new \Exception('无法保存');
        }
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function selfSave(array $data) {
        $items = isset($data['question_items']) && !empty($data['question_items']) ? $data['question_items'] : [];
        if (!empty($items)) {
            $data['rule_type'] = 1;
            $data['rule_value'] = '';
        }
        $model = static::save($data, auth()->id());
        if (empty($items)) {
            return $model;
        }
        $idItems = [];
        foreach ($items as $item) {
            $q = QuestionRepository::selfSave($item);
            if ($q && $q->id) {
                $idItems[] = $q->id;
            }
        }
        $model->rule_value = $idItems;
        $model->save();
        return $model;
    }

    public static function remove(int $id, int $user = 0) {
        $model = PageModel::where('id', $id)->when($user > 0, function ($query) use ($user) {
            $query->where('user_id', $user);
        })->first();
        if (!empty($model)) {
            throw new \Exception('无权限删除');
        }
        $model->delete();
        PageQuestionModel::where('page_id', $id)->delete();
        PageEvaluateModel::where('page_id', $id)->delete();
    }

    public static function selfRemove(int $id) {
        static::remove($id, auth()->id());
    }

    public static function evaluateList(int $id, string $keywords = '') {
        return PageEvaluateModel::with('user')
            ->where('page_id', $id)
            ->when(!empty($keywords), function ($query) use ($id, $keywords) {
                $users = UserRepository::searchUserId($keywords,
                    PageEvaluateModel::where('page_id', $id)
                        ->selectRaw('DISTINCT user_id')
                        ->pluck('user_id'), true);
                if (empty($users)) {
                    return $query->isEmpty();
                }
                $query->whereIn('user_id', $users);
            })
            ->orderBy('created_at', 'desc')->page();
    }

    public static function evaluateRemove(int $id, int $user = 0) {
        $model = PageEvaluateModel::findOrThrow($id, '无权限删除');
        if ($user > 0 && !static::can($model->page_id)) {
            throw new \Exception('无权限删除');
        }
        $model->delete();
    }

    public static function selfEvaluateRemove(int $id) {
        static::evaluateRemove($id, auth()->id());
    }

    public static function can(int $id) {
        return PageModel::where('id', $id)->where('user_id', auth()->id())->count() > 0;
    }

    public static function suggestion(string $keywords) {
        return PageModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->orderBy('end_at', 'desc')->limit(5)->get();
    }
}