<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin\Plugin;

use Module\Shop\Domain\Plugin\Affiliate\AffiliateRepository;
use Module\Shop\Service\Api\Admin\Controller;
use Zodream\Infrastructure\Contracts\Http\Input;

class AffiliateController extends Controller {
    public function indexAction(string $keywords = '', int $user = 0, int $status = 0) {
        return $this->renderPage(AffiliateRepository::getList($keywords, $user, $status));
    }

    public function optionAction() {
        return $this->renderData(AffiliateRepository::option());
    }

    public function saveOptionAction(Input $input) {
        try {
            $data = $input->validate([
                'by_user' => 'int',
                'by_user_next' => 'int',
                'by_user_grade' => '',
                'by_order' => 'int',
                'by_order_scale' => 'int',
            ]);
            return $this->renderData(
                AffiliateRepository::saveOption($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function statisticsAction() {
        return $this->render(AffiliateRepository::statistics());
    }
}