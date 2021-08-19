<?php
declare(strict_types=1);
namespace Module\Book\Service\Api;

use Module\Book\Domain\Repositories\DownloadRepository;
use Zodream\Infrastructure\Contracts\Http\Output;

class DownloadController extends Controller {

    public function txtAction(int $id, Output $output) {
        try {
            return $output->file(DownloadRepository::txt($id));
        } catch (\Exception $ex) {
            $output->header->setContentDisposition('error.txt');
            return $output->custom($ex->getMessage(), 'txt');
        }
    }

    public function zipAction(int $id, Output $output) {
        try {
            return $output->file(DownloadRepository::zip($id));
        } catch (\Exception $ex) {
            $output->header->setContentDisposition('error.zip');
            return $output->custom($ex->getMessage(), 'zip');
        }
    }
}