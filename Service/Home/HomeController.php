<?php
namespace Service\Home;

class HomeController extends Controller {
    public function indexAction() {
        return $this->show();
    }

    public function notFoundAction() {
        view()->setDirectory(app_path()
            ->directory('UserInterface/Home'));
        response()->statusCode(404);
        return $this->show('/404');
    }
}