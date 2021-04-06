<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Module\Finance\Domain\Repositories\ChannelRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class ChannelController extends Controller {

    public function indexAction(int $id = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        return $this->renderData(ChannelRepository::all());
    }

    public function detailAction(int $id) {
        try {
            $model = ChannelRepository::get($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveAction(Request $request) {
        try {
            $model = ChannelRepository::save($request->validate([
                'id' => 'int',
                'name' => 'required|string:0,50',
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        ChannelRepository::remove($id);
        return $this->renderData(true);
    }
}