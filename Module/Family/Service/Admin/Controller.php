<?php
namespace Module\Family\Service\Admin;

use Module\ModuleController;
use Zodream\Disk\File;


class Controller extends ModuleController {

    public File|string $layout = '/Admin/layouts/main';

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    protected function getUrl(mixed $path, array $args = []): string {
        return url('./@admin/'.$path, $args);
    }


    protected function processCustomRule($role) {
        if (auth()->user()->hasRole($role)) {
            return true;
        }
        return $this->redirectWithMessage('/',
            __('Not Role！')
            , 4,403);
    }
}