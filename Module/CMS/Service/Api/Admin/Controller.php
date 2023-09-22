<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api\Admin;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\Exam\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use CheckRole;
    public function rules() {
        return [
            '*' => CMSRepository::MANAGE_ROLE
        ];
    }

}