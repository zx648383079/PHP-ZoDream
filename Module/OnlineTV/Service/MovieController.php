<?php
declare(strict_types=1);
namespace Module\OnlineTV\Service;

use Module\OnlineTV\Domain\Repositories\MovieRepository;
use Module\OnlineTV\Domain\Repositories\TVRepository;
use Zodream\Infrastructure\Contracts\Http\Output;

class MovieController extends Controller {

    public function fileAction(int $id, Output $output) {
        $output->allowCors();
        try {
            return TvRepository::storage()->output($output, MovieRepository::download($id));
        } catch (\Exception $ex) {
            $output->header->setContentDisposition('error.txt');
            return $output->custom($ex->getMessage(), 'txt');
        }
    }
}