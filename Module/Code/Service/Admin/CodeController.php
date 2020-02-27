<?php
namespace Module\Code\Service\Admin;

use Module\Code\Domain\Model\CodeModel;
use Module\Code\Domain\Model\TagModel;

class MicroController extends Controller {

    public function indexAction($keywords = null) {
        $model_list  = CodeModel::with('user')
            ->when(!empty($keywords), function ($query) {
                $ids = TagModel::where(function($query) {
                    TagModel::search($query, ['content']);
                })->pluck('code_id');
                if (empty($ids)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('id', $ids);
            })->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function deleteAction($id) {
        CodeModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('code')
        ]);
    }
}