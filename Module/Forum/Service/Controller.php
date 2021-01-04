<?php
namespace Module\Forum\Service;

use Module\ModuleController;


class Controller extends ModuleController {

    public function findLayoutFile() {
        return app_path()->file('UserInterface/Home/layouts/main.php');
    }
}