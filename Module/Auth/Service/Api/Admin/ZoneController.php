<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Repositories\ZoneRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

final class ZoneController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(ZoneRepository::manageList($keywords));
    }

    public function saveAction(Input $request) {
        try {
            $model = ZoneRepository::manageSave($request->validate([
                'id' => 'int',
                'name' => 'required|string:0,32',
                'icon' => 'string:0,255',
                'description' => 'string:0,255',
                'is_open' => 'int:0,127',
                'status' => 'int:0,127',
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        ZoneRepository::manageRemove($id);
        return $this->renderData(true);
    }
}