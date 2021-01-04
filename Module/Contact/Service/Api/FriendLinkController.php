<?php
declare(strict_types=1);
namespace Module\Contact\Service\Api;

use Module\Contact\Domain\Repositories\ContactRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class FriendLinkController extends Controller {

    public function indexAction() {
        return $this->renderData(ContactRepository::friendLink());
    }

    public function applyAction(Request $request) {
        try {
            $this->render(ContactRepository::applyFriendLink($request->get()));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}