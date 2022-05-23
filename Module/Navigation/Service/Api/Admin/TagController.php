<?php
declare(strict_types=1);
namespace Module\Navigation\Service\Api\Admin;

use Module\Navigation\Domain\Repositories\SiteRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

final class TagController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(SiteRepository::tag()->getList($keywords));
    }


    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,30',
            ]);
            return $this->render(SiteRepository::tag()->save($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            SiteRepository::tag()->remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}