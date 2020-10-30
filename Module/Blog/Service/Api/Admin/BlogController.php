<?php
namespace Module\Blog\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\BlogPageModel;
use Module\Blog\Domain\Model\TagRelationshipModel;
use Module\Blog\Domain\Model\TermModel;
use Module\Blog\Domain\Events\BlogUpdate;
use Module\Blog\Domain\Repositories\BlogRepository;
use Module\Blog\Domain\Repositories\TagRepository;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\RestController;

class BlogController extends RestController {

    use AdminRole;

    public function indexAction($keywords = '', $term_id = 0, $type = 0) {
        $blog_list = BlogPageModel::with('term')
            ->where('user_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                BlogModel::searchWhere($query, 'title');
            })->when(!empty($term_id), function ($query) use ($term_id) {
                $query->where('term_id', intval($term_id));
            })->when($type > 0, function ($query) use ($type) {
                $query->where('type', $type - 1);
            })->orderBy('id', 'desc')->page();
        return $this->renderPage($blog_list);
    }

    public function detailAction($id, $language = null) {
        try {
            $model = BlogRepository::sourceBlog($id, $language);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveAction(Request $request, $id = 0) {
        try {
            $model = BlogRepository::save($request->get(), $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction($id) {
        try {
            BlogRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}