<?php
namespace Module\Note\Service\Admin;

use Module\Note\Domain\Model\NoteModel;

class NoteController extends Controller {

    public function indexAction(string $keywords = '') {
        $model_list = NoteModel::with('user')
            ->when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    NoteModel::search($query, 'content');
                });
            })->orderBy('id desc')->page();
        return $this->show(compact('model_list'));
    }

    public function deleteAction($id) {
        NoteModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('note')
        ]);
    }
}