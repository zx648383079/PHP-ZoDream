<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service\Admin;

use Domain\Model\SearchModel;
use Module\MicroBlog\Domain\Model\MicroBlogModel;
use Module\MicroBlog\Domain\Repositories\MicroRepository;

class MicroController extends Controller {

    public function indexAction(string $keywords = '') {
        $model_list  = MicroBlogModel::with('user')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['content']);
            })->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function deleteAction(int $id) {
        MicroRepository::remove($id);
        return $this->renderData([
            'url' => $this->getUrl('micro')
        ]);
    }
}