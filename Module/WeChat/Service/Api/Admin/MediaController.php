<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api\Admin;

use Module\WeChat\Domain\Repositories\MediaRepository;

class MediaController extends Controller {


    public function indexAction(int $wid = 0, string $keywords = '', string $type = '') {
        try {
            return $this->renderPage(MediaRepository::manageList(
                $wid,
                $keywords, $type));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}