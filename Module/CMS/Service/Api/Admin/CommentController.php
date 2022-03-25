<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api\Admin;

use Module\CMS\Domain\Repositories\CommentRepository;

class CommentController extends Controller {

    public function indexAction(int $site, int $article, int|string $category, int|string $model, string $keywords = '',
                                int $parent_id = 0, int $user = 0) {
        return $this->renderPage(
            CommentRepository::getManageList($site, $article, $category, $model, $keywords, $parent_id, $user)
        );
    }


    public function deleteAction(int $site, int $id, int $article, int|string $category, int|string $model) {
        try {
            CommentRepository::manageRemove($site, $id, $article, $category, $model);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}