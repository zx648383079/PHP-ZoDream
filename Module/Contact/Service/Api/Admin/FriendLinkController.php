<?php
declare(strict_types=1);
namespace Module\Contact\Service\Api\Admin;

use Module\Contact\Domain\Repositories\FriendLinkRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class FriendLinkController extends Controller {

	public function indexAction(string $keywords = '') {
        return $this->renderPage(
            FriendLinkRepository::getList($keywords)
        );
	}

	public function toggleAction(int $id, string $remark = '') {
        try {
            return $this->render(FriendLinkRepository::toggle($id, $remark));
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

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,20',
                'url' => 'required|string:0,50',
                'logo' => 'string:0,200',
                'brief' => 'string:0,255',
                'email' => 'string:0,100',
            ]);
            return $this->render(FriendLinkRepository::save($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}