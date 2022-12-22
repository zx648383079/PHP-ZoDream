<?php
declare(strict_types=1);
namespace Module\Contact\Service\Api\Admin;

use Module\Contact\Domain\Repositories\FriendLinkRepository;

class FriendLinkController extends Controller {

	public function indexAction(string $keywords = '') {
        return $this->renderPage(
            FriendLinkRepository::getList($keywords)
        );
	}

	public function toggleAction(int $id) {
        try {
            return $this->render(FriendLinkRepository::toggle($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(array|int $id) {
        try {
            FriendLinkRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}