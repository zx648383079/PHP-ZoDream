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
            ->where('deleted_at', '>', 0)
            ->offset($offset)->limit($length)
            ->asArray()->all();
        return $this->jsonSuccess($data);
    }

    public function resetAction() {
        $user = Auth::id();
        $id = Request::post('id');
        $row = DiskModel::whereIn('id', (array)$id)
            ->where('user_id', $user)->update([
                'deleted_at' => 0
            ]);
        if (empty($row)) {
            return $this->jsonFailure('服务器错误!');
        }
        return $this->jsonSuccess();
    }

    public function deleteAction() {
        $user = Auth::id();
        $id = Request::post('id');
        DiskModel::whereIn('id', (array)$id)
            ->where('user_id', $user)
            ->where('deleted_at', '>', 0)
            ->delete();
        return $this->jsonSuccess();
    }

    public function clearAction() {
        $user = Auth::id();
        DiskModel::where('user_id', $user)
            ->where('deleted_at', '>', 0)
            ->delete();
        return $this->jsonSuccess();
    }
}