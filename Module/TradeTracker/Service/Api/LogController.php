<?php
declare(strict_types=1);
namespace Module\TradeTracker\Service\Api;

use Module\TradeTracker\Domain\Repositories\TrackRepository;

class LogController extends Controller {

    public function indexAction(string $keywords = '', int $channel = 0, int $project = 0, int $product = 0) {
        return $this->renderPage(
            TrackRepository::latestList($keywords, $channel, $project, $product)
        );
    }

}