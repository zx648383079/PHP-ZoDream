<?php
declare(strict_types=1);
namespace Module\Chat\Service\Api;


use Module\Chat\Domain\Repositories\FriendRepository;

class FriendController extends Controller {

    public function indexAction() {
        return $this->renderData(
            FriendRepository::all()
        );
    }

    public function searchAction(string $keywords = '') {
        return $this->renderPage(
            FriendRepository::search($keywords)
        );
    }
}