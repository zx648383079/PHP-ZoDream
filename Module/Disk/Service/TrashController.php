<?php
namespace Module\Disk\Service;

use Module\Disk\Domain\Model\DiskModel;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request;

/**
 * Created by PhpStorm.
 * User: ZoDream
 * Date: 2017/8/21
 * Time: 22:31
 */
class TrashController extends Controller {

    public function indexAction() {
        return $this->show();
    }

    public function listAction($offset = 0, $length = 20) {
        $data = DiskModel::where('user_id', Auth::id())
            ->where('deleted_at', '>', 100)
            ->offset($offset)->limit($length)
            ->asArray()->all();
        return $this->jsonSuccess($data);
    }

    public function resetAction() {
        $id = Request::post('id');
        $model_list = DiskModel::auth()->whereIn('id', (array)$id)->where('deleted_at', '>', 0)->all();
        foreach ($model_list as $item) {
            $item->resetThis();
        }
        return $this->jsonSuccess();
    }

    public function deleteAction() {
        $id = Request::post('id');
        $model_list = DiskModel::auth()->whereIn('id', (array)$id)->where('deleted_at', '>', 0)->all();
        foreach ($model_list as $item) {
            $item->deleteThis();
        }
        return $this->jsonSuccess();
    }

    public function clearAction() {
        DiskModel::auth()
            ->where('deleted_at', '>', 0)
            ->delete();
        return $this->jsonSuccess();
    }
}