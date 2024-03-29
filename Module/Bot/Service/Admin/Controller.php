<?php
namespace Module\Bot\Service\Admin;

use Module\ModuleController;
use Zodream\Disk\File;
use Zodream\Infrastructure\Contracts\HttpContext;

class Controller extends ModuleController {

    protected File|string $layout = '/Admin/layouts/main';

    public function __construct() {
        parent::__construct();
        $this->middleware(function (HttpContext $context, callable $next) {
            if ($this->rules()['*'] === 'w' && empty($this->weChatId())) {
                return $this->redirect($this->getUrl('manage'));
            }
            return $next($context);
        });
    }

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    protected function getUrl($path, $args = []) {
        return url('./@admin/'.$path, $args);
    }

    public function botId(int $id = -1) {
        static $bot_id = 0;
        if ($id > 0) {
            session([
                'bid' => $id
            ]);
            return $bot_id = $id;
        }
        if ($bot_id < 1) {
            $bot_id = intval(session('bid'));
        }
        return $bot_id;
    }

}