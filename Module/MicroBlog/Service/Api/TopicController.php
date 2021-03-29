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
}