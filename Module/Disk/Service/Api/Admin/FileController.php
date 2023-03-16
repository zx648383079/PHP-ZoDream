<?php
declare(strict_types=1);
namespace Module\Disk\Service\Api\Admin;

use Module\Disk\Domain\Repositories\ServerRepository;

final class FileController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->render(ServerRepository::fileList($keywords));
    }

    public function serverAction(string $keywords = '') {
        return $this->render(ServerRepository::serverList($keywords));
    }
}