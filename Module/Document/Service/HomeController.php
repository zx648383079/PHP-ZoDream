<?php
declare(strict_types=1);
namespace Module\Document\Service;

use Module\Document\Domain\Repositories\ProjectRepository;

class HomeController extends Controller {

    public function indexAction() {
        $project_list = ProjectRepository::all();
        return $this->show(compact('project_list'));
    }
}