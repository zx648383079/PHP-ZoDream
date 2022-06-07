<?php
declare(strict_types=1);
namespace Module\OnlineTV\Service\Api;

use Module\OnlineTV\Domain\Repositories\MusicRepository;
use Module\OnlineTV\Domain\Repositories\TVRepository;
use Zodream\Html\Page;
use Zodream\Infrastructure\Contracts\Http\Output;

class MusicController extends Controller {

    public function indexAction(string $keywords = '', array|int $id = 0) {
        $data = MusicRepository::search($keywords, $id);
        if ($data instanceof Page) {
            return $this->renderPage(
                $data
            );
        }
        return $this->renderData($data);
    }

    public function suggestionAction(string $keywords) {
        return $this->renderData(
            MusicRepository::suggestion($keywords)
        );
    }

    public function downloadAction(int $id, Output $output) {
        try {
            return TvRepository::storage()->output($output, MusicRepository::download($id));
        } catch (\Exception $ex) {
            $output->header->setContentDisposition('error.txt');
            return $output->custom($ex->getMessage(), 'txt');
        }
    }
}