<?php
declare(strict_types=1);
namespace Module\Forum\Service;

use Module\Forum\Domain\Repositories\ForumRepository;
use Module\Forum\Domain\Repositories\ThreadRepository;

class HomeController extends Controller {

    public function indexAction() {
        $forum_list = ForumRepository::children(0);
        return $this->show(compact('forum_list'));
    }

    public function suggestAction(string $keywords = '') {
        return $this->renderData(
            ThreadRepository::suggestion($keywords)
        );
    }
}