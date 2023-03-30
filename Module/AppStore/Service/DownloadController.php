<?php
declare(strict_types=1);
namespace Module\AppStore\Service;

use Module\AppStore\Domain\Repositories\AppRepository;
use Zodream\Infrastructure\Contracts\Http\Output;

class DownloadController extends Controller {
    public function indexAction(int $id, Output $output) {
        try {
            return AppRepository::storage()->output($output, AppRepository::download($id));
        } catch (\Exception $ex) {
            $output->header->setContentDisposition('error.txt');
            return $output->custom($ex->getMessage(), 'txt');
        }
    }

}