<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Repositories\ActivityRepository;
use Module\Shop\Service\Api\Controller;

class PresaleController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            ActivityRepository::getList(ActivityModel::TYPE_PRE_SALE, $keywords)
        );
    }

}