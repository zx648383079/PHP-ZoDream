<?php
declare(strict_types=1);
namespace Module\Short\Service\Api\Admin;

use Module\Short\Domain\Repositories\ShortRepository;
use Zodream\Infrastructure\Contracts\Http\Input;


class HomeController extends Controller {


    public function indexAction(string $keywords = '', int $user = 0) {
        return $this->renderPage(ShortRepository::getManageList($keywords, $user));
    }

    public function saveAction(Input $input) {
        try {
            return $this->render(
                ShortRepository::save($input->validate([
                    'id' => 'int',
                    'title' => 'required|string:0,30',
                    'short_url' => 'required|string:0,60',
                    'status' => 'int:0,127',
                    'is_system' => 'int:0,127',
                ]))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            ShortRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}