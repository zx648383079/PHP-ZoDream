<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service;

use Module\ResourceStore\Domain\Repositories\ResourceRepository;
use Module\ResourceStore\Domain\Repositories\UploadRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Contracts\Http\Output;

class DownloadController extends Controller {
    public function indexAction(int $id, Output $output, int $file = 0) {
        try {
            return $output
                ->file(ResourceRepository::download($id, $file));
        } catch (\Exception) {
            return $this->redirect('./');
        }
    }

}