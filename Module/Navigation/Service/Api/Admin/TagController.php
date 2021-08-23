<?php
declare(strict_types=1);
namespace Module\Navigation\Service\Api\Admin;

use Module\Navigation\Domain\Repositories\Admin\TagRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

final class TagController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(TagRepository::getList($keywords));
    }


    public function saveAction(Input $input) {
        try {
            $data = $input->validate([

            ]);
            return $this->render(TagRepository::save($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            TagRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}