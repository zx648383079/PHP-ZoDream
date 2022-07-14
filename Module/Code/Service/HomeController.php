<?php
declare(strict_types=1);
namespace Module\Code\Service;

use Module\Code\Domain\Model\CodeModel;
use Module\Code\Domain\Repositories\CodeRepository;
use Module\ModuleController;
use Zodream\Disk\File;
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
            })->when(!empty($keywords) && $id < 1, function ($query) use ($keywords) {
                $ids = CodeRepository::tag()->searchTag($keywords);
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

    public function recommendAction(int $id) {
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

    public function collectAction(int $id) {
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

    public function deleteAction(int $id) {
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

    public function suggestionAction(string $keywords = '') {
        $data = CodeRepository::tag()->suggest($keywords);
        return $this->renderData($data);
    }

    public function findLayoutFile(): File|string {
        if ($this->httpContext()->make('action') !== 'index') {
            return '';
        }
        return app_path()->file('UserInterface/Home/layouts/main.php');
    }

    public function redirectWithAuth() {
        return $this->redirect([config('auth.home'), 'redirect_uri' => url('./')]);
    }
}