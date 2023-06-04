<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api\Member;

use Module\WeChat\Domain\Repositories\TemplateRepository;
use Module\WeChat\Service\Api\Admin\Controller;

class TemplateController extends Controller {

    public function indexAction(string $keywords = '', int $type = 0, int $category = 0) {
        return $this->renderPage(
            TemplateRepository::getList($keywords, $type, $category)
        );
    }

}