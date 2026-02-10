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
            return $this->render(ContactRepository::applyFriendLink($request->validate([
                'name' => 'required|string:0,20',
                'url' => 'required|string:0,50',
                'logo' => 'string:0,200',
                'brief' => 'string:0,255',
                'email' => 'string:0,100',
            ])));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}