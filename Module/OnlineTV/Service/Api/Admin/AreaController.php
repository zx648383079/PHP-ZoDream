<?php
declare(strict_types=1);
namespace Module\OnlineTV\Service\Api\Admin;

use Module\OnlineTV\Domain\Repositories\AreaRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class AreaController extends Controller {

    public function indexAction() {
        return $this->renderData(
            AreaRepository::all()
        );
    }

    public function saveAction(Input $input) {
        try {
            return $this->render(
                AreaRepository::save($input->validate([
                  'id' => 'int',
                  'name' => 'required|string:0,40',
              ]))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        AreaRepository::remove($id);
        return $this->renderData(true);
    }

}