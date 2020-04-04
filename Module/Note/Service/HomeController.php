<?php
namespace Module\Note\Service;

use Module\ModuleController;
use Module\Note\Domain\Model\NoteModel;
use Zodream\Service\Factory;

class HomeController extends ModuleController {

    public $layout = true;
	
	public function indexAction($keywords = null, $id = 0) {
	    $model_list = NoteModel::with('user')
            ->when($id > 0, function($query) use ($id) {
                $query->where('id', $id);
            })
            ->when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    NoteModel::search($query, 'content');
                });
            })->orderBy('id desc')->page();
        if (app('request')->isAjax()) {
            return $this->jsonSuccess([
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
        return $this->jsonSuccess($data);
    }

    public function findLayoutFile() {
        if ($this->layout === false) {
            return false;
        }
        return Factory::root()->file('UserInterface/Home/layouts/main.php');
    }
}