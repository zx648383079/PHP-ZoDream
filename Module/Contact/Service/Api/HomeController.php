<?php
declare(strict_types=1);
namespace Module\Contact\Service\Api;

use Infrastructure\Developer;
use Module\Contact\Domain\Repositories\ContactRepository;
use Module\Contact\Domain\Repositories\FeedbackRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class HomeController extends Controller {

    public function indexAction(int $per_page = 20) {
        return $this->renderPage(
            FeedbackRepository::getList('', $per_page)
        );
    }

    public function feedbackAction(Request $request) {
        try {
            ContactRepository::saveFeedback($request->validate([
                'name' => 'required|string:0,20',
                'email' => 'string:0,50',
                'phone' => 'string:0,30',
                'content' => 'required|string:0,255',
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function subscribeAction(Request $request) {
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'name' => 'string:0,30'
            ]);
            ContactRepository::saveSubscribe($data);
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

    public function developerAction() {
        return $this->render(array_merge(Developer::author(), [
            'skills' => array_map(function (array $item) {
                $item['formatted_proficiency'] = Developer::formatProficiency($item['proficiency']);
                return $item;
            }, Developer::skill())
            ])
        );
    }
}