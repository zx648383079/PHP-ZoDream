<?php
namespace Service\Home;


class HomeController extends Controller {
    public function indexAction() {
        return $this->show();
    }

    public function aboutAction() {
        return $this->show();
    }

    public function friendLinkAction() {
        return $this->show();
    }

    public function agreementAction() {
        return $this->show();
    }

    public function notFoundAction() {
        view()->setDirectory(app_path()
            ->directory('UserInterface/Home'));
        app('response')->setStatusCode(404);
        return $this->show('/404');
    }
}