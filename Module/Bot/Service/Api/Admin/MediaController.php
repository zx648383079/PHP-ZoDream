<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api\Admin;

use Module\Bot\Domain\Repositories\MediaRepository;

class MediaController extends Controller {


    public function indexAction(int $bot_id = 0, string $keywords = '', string $type = '') {
        try {
            return $this->renderPage(MediaRepository::manageList(
                $bot_id,
                $keywords, $type));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}