<?php
namespace Module\Note\Service;

use Module\ModuleController;
use Module\Note\Domain\Model\NoteModel;

class HomeController extends ModuleController {

    public $layout = 'main';
	
	public function indexAction($keywords = null) {
	    $model_list = NoteModel::with('user')
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
}