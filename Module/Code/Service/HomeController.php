<?php
namespace Module\Code\Service;

use Module\Code\Domain\Model\CodeModel;
use Module\Code\Domain\Model\TagModel;
use Module\Code\Domain\Repositories\CodeRepository;
use Module\ModuleController;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Config;
use Zodream\Service\Factory;

class HomeController extends ModuleController {

    protected function rules() {
        return [
            'recommend' => '@',
            'create' => '@',
            'collect' => '@',
            '*' => '*'
        ];
    }

    public function indexAction($sort = 'new', $keywords = null, $id = 0) {
        $code_list  = CodeModel::with('user', 'tags')
            ->when($id > 0, function($query) use ($id) {
                $query->where('id', $id);
            })
            ->when(!empty($sort), function ($query) use ($sort) {
                if ($sort == 'new') {
                    return $query->orderBy('created_at', 'desc');
                }
                if ($sort == 'recommend') {
                    return $query->orderBy('recommend_count', 'desc');
                }
            })->when(!empty($keywords) && $id < 1, function ($query) {
                $ids = TagModel::where(function($query) {
                    TagModel::search($query, ['content']);
                })->pluck('code_id');
                if (empty($ids)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('id', $ids);
            })
            ->page();
        return $this->show(compact('code_list', 'keywords'));
    }

    public function createAction(Request $request) {
        if (!CodeRepository::canPublish()) {
            return $this->jsonFailure('发送过于频繁！');
        }
        $model = CodeRepository::create($request->get('content'), $request->get('tags'), $request->get('language'));
        if (!$model) {
            return $this->jsonFailure('发送失败');
        }
        return $this->jsonSuccess([
            'url' => url('./')
        ]);

    }

    public function recommendAction($id) {
        if (!app('request')->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = CodeRepository::recommend($id);
        }catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess($model);
    }

    public function collectAction($id) {
        if (!app('request')->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = CodeRepository::collect($id);
        }catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess($model);
    }

    public function deleteAction($id) {
        if (!app('request')->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = CodeRepository::delete($id);
        }catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess();
    }

    public function suggestionAction($keywords = null) {
        $data = TagModel::when(!empty($keywords), function($query) {
            TagModel::searchWhere($query, ['content']);
        })->groupBy('content')->limit(4)->pluck('content');
        return $this->jsonSuccess($data);
    }

    public function findLayoutFile() {
        if ($this->action !== 'index') {
            return false;
        }
        return Factory::root()->file('UserInterface/Home/layouts/main.php');
    }

    public function redirectWithAuth() {
        return $this->redirect([Config::auth('home'), 'redirect_uri' => url('./')]);
    }
}