<?php
declare(strict_types=1);
namespace Module\OpenPlatform\Service\Api;

use Module\OpenPlatform\Domain\Model\PlatformSimpleModel;
use Module\OpenPlatform\Domain\Model\TokenPageModel;
use Module\OpenPlatform\Domain\Model\UserTokenModel;
use Module\OpenPlatform\Domain\Repositories\OpenRepository;

class AuthorizeController extends Controller {

    public function rules() {
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

    public function saveAction(int $platform_id, string $expired_at = '') {
        try {
            $model = OpenRepository::createToken($platform_id, $expired_at);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        UserTokenModel::where('id', $id)->where('user_id', auth()->id())->delete();
        return $this->renderData(true);
    }

    public function clearAction() {
        UserTokenModel::where('user_id', auth()->id())->delete();
        return $this->renderData(true);
    }

    public function platformAction() {
        return $this->renderData(
            PlatformSimpleModel::where('allow_self', 1)->where('status', 1)->get()
        );
    }
}