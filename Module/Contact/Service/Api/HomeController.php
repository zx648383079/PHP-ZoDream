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
            ContactRepository::saveFeedback($request->get());
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