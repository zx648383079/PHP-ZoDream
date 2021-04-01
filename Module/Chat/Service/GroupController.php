<?php
declare(strict_types=1);
namespace Module\Chat\Service;

use Module\Chat\Domain\Repositories\GroupRepository;

class GroupController extends Controller {

    public function indexAction() {
        return $this->renderData(
            GroupRepository::all()
        );
    }
}