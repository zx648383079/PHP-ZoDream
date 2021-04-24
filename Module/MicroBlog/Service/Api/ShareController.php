<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service\Api;

use Module\MicroBlog\Domain\Repositories\MicroRepository;
use Module\OpenPlatform\Domain\Repositories\OpenRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class ShareController extends Controller {

    public function rules() {
        return [
            '*' => '@',
        ];
    }

    public function indexAction(string $shareappid, string $url = '') {
        try {
            OpenRepository::checkUrl($shareappid, $url);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function saveAction(Request $request) {
        if (!MicroRepository::canPublish()) {
            return $this->renderFailure('发送过于频繁！');
        }
        try {
            OpenRepository::checkUrl($request->get('shareappid'), $request->get('url'));
            $model = MicroRepository::share($request->get('title'),
                $request->get('summary'),
                $request->get('url'),
                $request->get('pics'), $request->get('content'), $request->get('sharesource'));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }
}