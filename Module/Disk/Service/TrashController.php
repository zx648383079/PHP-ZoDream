<?php
namespace Module\Disk\Service;

use Module\Disk\Domain\Model\DiskModel;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\ModuleController;

/**
 * Created by PhpStorm.
 * User: ZoDream
 * Date: 2017/8/21
 * Time: 22:31
 */
class TrashController extends ModuleController {

    public function indexAction() {
        return $this->show();
    }

    public function listAction($offset = 0, $length = 20) {
        $data = DiskModel::where(['user_id' => Auth::id()])
            ->whereNotNull('deleted_at')
            ->offset($offset)->limit($length)->asArray()->all();
        return $this->jsonSuccess($data);
    }

    public function resetAction() {
        $user = Auth::id();
        $id = Request::post('id');
        $row = DiskModel::where([
                'id' => ['in', (array)$id],
                'user_id' => $user
            ])->update([
                'deleted_at' => null
            ]);
        if (empty($row)) {
            return $this->jsonFailure('服务器错误!');
        }
        return $this->jsonSuccess();
    }

    public function deleteAction() {
        $user = Auth::id();
        $id = Request::post('id');
        DiskModel::where([
                'id' => ['in', (array)$id],
                'user_id' => $user
            ])->whereNotNull('deleted_at')
            ->delete();
        return $this->jsonSuccess();
    }

    public function clearAction() {
        $user = Auth::id();
        DiskModel::where([
            'user_id' => $user
        ])->whereNotNull('deleted_at')
            ->delete();
        return $this->jsonSuccess();
    }
}