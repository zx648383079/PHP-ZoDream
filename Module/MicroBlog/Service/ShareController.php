<?php
namespace Module\MicroBlog\Service;

use Module\MicroBlog\Domain\Repositories\MicroRepository;
use Module\ModuleController;
use Module\OpenPlatform\Domain\Repositories\OpenRepository;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Factory;

class ShareController extends ModuleController {

    protected function rules() {
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
            MicroRepository::share($request->get('title'),
                $request->get('summary'),
                $request->get('url'),
                $request->get('pics'), $request->get('content'));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./')
        ]);
    }

    public function findLayoutFile() {
        if ($this->action !== 'index') {
            return false;
        }
        return Factory::root()->file('UserInterface/Home/layouts/main.php');
    }
}