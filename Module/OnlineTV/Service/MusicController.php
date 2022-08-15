<?php
declare(strict_types=1);
namespace Module\OnlineTV\Service;

use Module\OnlineTV\Domain\Repositories\MusicRepository;
use Module\OnlineTV\Domain\Repositories\TVRepository;
use Zodream\Infrastructure\Contracts\Http\Output;

class MusicController extends Controller {

    public function fileAction(int $id, Output $output) {
        $output->allowCors();
        try {
            return TvRepository::storage()->output($output, MusicRepository::download($id));
        } catch (\Exception $ex) {
            $output->header->setContentDisposition('error.txt');
            return $output->custom($ex->getMessage(), 'txt');
        }
    }
}