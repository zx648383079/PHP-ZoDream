<?php
declare(strict_types=1);
namespace Service\Home;

use Module\SEO\Domain\Repositories\AgreementRepository;

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

    public function agreementAction(string $name = 'agreement') {
        $model = AgreementRepository::getByName($name);
        return $this->show(compact('model'));
    }

    public function notFoundAction() {
        if (request()->wantsJson()) {
            return $this->renderFailure('page not found');
        }
        view()->setDirectory(app_path()
            ->directory('UserInterface/Home'));
        response()->statusCode(404);
        return $this->show('/404');
    }
}