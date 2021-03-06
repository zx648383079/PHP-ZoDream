<?php
declare(strict_types=1);
namespace Module\Contact\Service\Api;

use Module\Contact\Domain\Repositories\ContactRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class HomeController extends Controller {

    public function feedbackAction(Request $request) {
        try {
            ContactRepository::saveSubscribe($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function subscribeAction(Request $request) {
        try {
            ContactRepository::saveSubscribe($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render(['data' => true]);
    }

    public function unsubscribeAction(string $email) {
        try {
            ContactRepository::unsubscribe($email);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}