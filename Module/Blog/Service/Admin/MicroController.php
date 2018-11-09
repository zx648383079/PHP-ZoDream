<?php
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Model\MicroBlogModel;

class MicroController extends Controller {

    public function indexAction($keywords = null) {
        $model_list  = MicroBlogModel::with('user')
            ->when(!empty($keywords), function ($query) {
                MicroBlogModel::search($query, ['content']);
            })->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function deleteAction($id) {
        MicroBlogModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('micro')
        ]);
    }
}