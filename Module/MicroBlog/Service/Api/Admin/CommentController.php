<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service\Api\Admin;


use Module\MicroBlog\Domain\Repositories\MicroRepository;

class CommentController extends Controller {
    public function indexAction(string $keywords = '', int $user = 0, int $micro = 0) {
        return $this->renderPage(
            MicroRepository::comment()->search($keywords, $user, $micro)
        );
    }

    public function deleteAction(int $id) {
        try {
            MicroRepository::comment()->remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}