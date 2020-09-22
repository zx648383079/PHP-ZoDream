<?php
namespace Module\OpenPlatform\Service\Api;

use Module\OpenPlatform\Domain\Model\TokenPageModel;
use Module\OpenPlatform\Domain\Model\UserTokenModel;
use Module\OpenPlatform\Domain\Repositories\OpenRepository;
use Zodream\Route\Controller\RestController;

class AuthorizeController extends RestController {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        $model_list = TokenPageModel::with('platform')
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')->page();
        return $this->renderPage($model_list);
    }

    public function saveAction(int $platform_id) {
        try {
            $model = OpenRepository::createToken($platform_id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction($id) {
        UserTokenModel::where('id', $id)->where('user_id', auth()->id())->delete();
        return $this->renderData(true);
    }

    public function clearAction() {
        UserTokenModel::where('user_id', auth()->id())->delete();
        return $this->renderData(true);
    }
}