<?php
declare(strict_types=1);
namespace Module\Blog\Service\Api;

use Module\Blog\Domain\Repositories\BlogRepository;

class ArchivesController extends Controller {

    public function indexAction() {
        return $this->renderData(BlogRepository::getArchives());
    }
}