<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service\Api\Admin;

use Module\MicroBlog\Domain\Repositories\TopicRepository;

class TopicController extends Controller {
    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            TopicRepository::newList($keywords)
        );
    }

    public function deleteAction(int $id) {
        try {
            TopicRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}