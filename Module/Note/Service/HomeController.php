<?php
namespace Module\Note\Service;

use Domain\Model\SearchModel;
use Module\ModuleController;
use Module\Note\Domain\Model\NoteModel;


class HomeController extends ModuleController {

    public $layout = true;
	
	public function indexAction($keywords = null, $id = 0) {
	    $model_list = NoteModel::with('user')
            ->when($id > 0, function($query) use ($id) {
                $query->where('id', $id);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'content');
            })->orderBy('id desc')->page();
        if (request()->isAjax()) {
            return $this->renderData([
                'html' => $this->renderHtml('page', compact('model_list', 'keywords')),
                'has_more' => $model_list->hasMore()
            ]);
        }
		return $this->show(compact('model_list', 'keywords'));
	}

    public function suggestionAction($keywords = null) {
        $data = NoteModel::when(!empty($keywords), function($query) {
            NoteModel::searchWhere($query, ['content']);
        })->groupBy('content')->limit(4)->pluck('content');
        return $this->renderData($data);
    }

    public function findLayoutFile() {
        if ($this->layout === false) {
            return false;
        }
        return app_path()->file('UserInterface/Home/layouts/main.php');
    }
}