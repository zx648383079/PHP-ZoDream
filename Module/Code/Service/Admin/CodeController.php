<?php
namespace Module\Code\Service\Admin;

use Domain\Model\SearchModel;
use Module\Code\Domain\Model\CodeModel;
use Module\Code\Domain\Model\TagModel;

class CodeController extends Controller {

    public function indexAction($keywords = null) {
        $model_list  = CodeModel::with('user')
            ->when(!empty($keywords), function ($query) {
                $ids = SearchModel::searchWhere(TagModel::query(), ['content'])->pluck('code_id');
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
        return $this->renderData([
            'url' => $this->getUrl('code')
        ]);
    }
}