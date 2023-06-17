<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api\Admin;

use Module\CMS\Domain\Repositories\ContentRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ContentController extends Controller {

    public function indexAction(int $site, int $category,
                                string $keywords = '', int $parent = 0,
                                int $model = 0,
                                int $page = 1, int $per_page = 20) {
        return $this->render(
            ContentRepository::getList($site, $category, $keywords, $parent, $model, $page, $per_page)
        );
    }

    public function detailAction(int $site, int $category,
                                 int $model = 0, int $id = 0) {
        try {
            return $this->render(
                ContentRepository::getForm($site, $category, $model, $id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(int $site_id, int $cat_id, Input $input, int $model_id = 0,) {
        try {
            return $this->render(
                ContentRepository::save($site_id, $cat_id, $model_id, $input->get())
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
    }

    public function deleteAction(int $site, int $category, int $id, int $model = 0) {
        try {
            ContentRepository::remove($site, $category, $model, $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}