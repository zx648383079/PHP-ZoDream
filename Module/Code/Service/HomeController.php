<?php
namespace Module\Code\Service;

use Domain\Model\SearchModel;
use Module\Code\Domain\Model\CodeModel;
use Module\Code\Domain\Model\TagModel;
use Module\Code\Domain\Repositories\CodeRepository;
use Module\ModuleController;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class HomeController extends ModuleController {

    public function rules() {
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
                $ids = SearchModel::searchWhere(TagModel::query(), ['content'])->pluck('code_id');
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
            return $this->renderFailure('发送过于频繁！');
        }
        $model = CodeRepository::create($request->get('content'), $request->get('tags'), $request->get('language'));
        if (!$model) {
            return $this->renderFailure('发送失败');
        }
        return $this->renderData([
            'url' => url('./')
        ]);

    }

    public function recommendAction($id) {
        if (!request()->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = CodeRepository::recommend($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model);
    }

    public function collectAction($id) {
        if (!request()->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = CodeRepository::collect($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model);
    }

    public function deleteAction($id) {
        if (!request()->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = CodeRepository::delete($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function suggestionAction($keywords = null) {
        $data = TagModel::when(!empty($keywords), function($query) {
            TagModel::searchWhere($query, ['content']);
        })->groupBy('content')->limit(4)->pluck('content');
        return $this->renderData($data);
    }

    public function findLayoutFile() {
        if ($this->httpContext()->make('action') !== 'index') {
            return false;
        }
        return app_path()->file('UserInterface/Home/layouts/main.php');
    }

    public function redirectWithAuth() {
        return $this->redirect([config('auth.home'), 'redirect_uri' => url('./')]);
    }
}