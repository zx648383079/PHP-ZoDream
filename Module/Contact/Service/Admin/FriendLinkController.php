<?php
declare(strict_types=1);
namespace Module\Contact\Service\Admin;


use Module\Contact\Domain\Repositories\FriendLinkRepository;

class FriendLinkController extends Controller {

	public function indexAction() {
	    $model_list = FriendLinkRepository::getList();
        return $this->show(compact('model_list'));
	}

	public function verifyAction(int $id) {
        try {
            FriendLinkRepository::toggle($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
	    return $this->renderData([
	       'refresh' => true
        ]);
    }

    public function removeAction(int $id) {
        return $this->verifyAction($id);
    }

    public function deleteAction(int $id) {
        try {
            FriendLinkRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

}