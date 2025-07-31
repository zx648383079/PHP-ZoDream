<?php
declare(strict_types=1);
namespace Module\OnlineTV\Service\Api;

use Module\OnlineTV\Domain\Repositories\MovieRepository;
use Module\OnlineTV\Domain\Repositories\TVRepository;
use Zodream\Infrastructure\Contracts\Http\Output;

class MovieController extends Controller {

    public function indexAction(string $keywords = '', int $category = 0, int $area = 0,
                                int $age = 0) {
        return $this->renderPage(
            MovieRepository::search($keywords, $category, $area, $age)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                MovieRepository::getFull($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function seriesAction(int $movie) {
        try {
            return $this->render(
                MovieRepository::seriesFull($movie)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function suggestAction(string $keywords) {
        return $this->renderData(
            MovieRepository::suggestion($keywords)
        );
    }

    public function downloadAction(int $id, Output $output) {
        try {
            return TvRepository::storage()->output($output, MovieRepository::download($id));
        } catch (\Exception $ex) {
            $output->header->setContentDisposition('error.txt');
            return $output->custom($ex->getMessage(), 'txt');
        }
    }
}