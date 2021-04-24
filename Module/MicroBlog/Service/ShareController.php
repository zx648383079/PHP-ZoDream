<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service;

use Module\MicroBlog\Domain\Repositories\MicroRepository;
use Module\OpenPlatform\Domain\Repositories\OpenRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class ShareController extends Controller {

    public function rules() {
        return [
            '*' => '@',
        ];
    }

    public function indexAction(string $appid, string $title = '',
                                string $summary = '', string $url = '',
                                string $pics = '', $sharesource = '') {
        try {
            OpenRepository::checkUrl($appid, $url);
        } catch (\Exception $ex) {
            return $this->redirectWithMessage('./', $ex->getMessage());
        }
        $pics = empty($pics) ? [] : explode(',', $pics);
        return $this->show(compact('title', 'summary', 'url', 'pics', 'appid', 'sharesource'));
    }

    public function saveAction(Request $request) {
        if (!MicroRepository::canPublish()) {
            return $this->renderFailure('发送过于频繁！');
        }
        try {
            OpenRepository::checkUrl($request->get('appid'), $request->get('url'));
            MicroRepository::share($request->get('title'),
                $request->get('summary'),
                $request->get('url'),
                $request->get('pics'),
                $request->get('content'), $request->get('sharesource'));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./')
        ]);
    }
}