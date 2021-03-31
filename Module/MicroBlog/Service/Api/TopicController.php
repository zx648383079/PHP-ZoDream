<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service\Api;


use Module\MicroBlog\Domain\Repositories\TopicRepository;

class TopicController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            TopicRepository::newList($keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                TopicRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}