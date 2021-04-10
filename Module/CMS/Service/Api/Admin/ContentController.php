<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api\Admin;

use Module\CMS\Domain\Repositories\ContentRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ContentController extends Controller {

    public function indexAction(int $site, int $category, string $keywords = '', int $parent = 0) {
        return $this->render(
            ContentRepository::getList($site, $category, $keywords, $parent)
        );
    }

    public function detailAction(int $site, int $category, int $id = 0) {
        try {
            return $this->render(
                ContentRepository::get($site, $category, $id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(int $site, int $category, Input $input) {
        try {
            return $this->render(
                ContentRepository::save($site, $category, $input->get())
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $site, int $category, int $id) {
        try {
            ContentRepository::remove($site, $category, $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}