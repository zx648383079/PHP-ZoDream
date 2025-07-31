<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Api;

use Module\ResourceStore\Domain\Repositories\ResourceRepository;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\Infrastructure\Contracts\Http\Output;

class HomeController extends Controller {

    public function indexAction(string $keywords = '', int $user = 0, int $category = 0,
                                string $tag = '',
                                string $sort = 'created_at',
                                string|int|bool $order = 'desc', int $per_page = 20) {
        return $this->renderPage(
            ResourceRepository::getList($keywords, $category, $user, $tag, $sort, $order, $per_page)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ResourceRepository::getFull($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function previewAction(int $id) {
        try {
            return $this->render(
                ResourceRepository::getPreview($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function downloadAction(int $id, Output $output, int $file = 0) {
        try {
            return $output
                ->file(ResourceRepository::download($id, $file));
        } catch (\Exception $ex) {
            $output->header->setContentDisposition('error.txt');
            return $output->custom($ex->getMessage(), 'txt');
        }
    }

    public function catalogAction(int $id) {
        try {
            return $this->renderData(ResourceRepository::getCatalog($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function suggestAction(string $keywords) {
        return $this->renderData(
            ResourceRepository::suggestion($keywords)
        );
    }
}