<?php
declare(strict_types=1);
namespace Service\Home;

use Module\SEO\Domain\Repositories\AgreementRepository;
use Zodream\Route\Response\RestResponse;

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
        $request = request();
        $response = response();
        if (str_starts_with($request->path(), '/open')) {
            $response->statusCode(404);
            $response->allowCors();
            return RestResponse::createWithAuto([
                'code' => 404,
                'message' => 'page not found',
            ]);
        }
        if (!$request->wantsJson()) {
            view()->setDirectory(app_path()
                ->directory('UserInterface/Home'));
            $response->statusCode(404);
            return $this->show('/404');
        }
        return $this->renderFailure('page not found');
    }
}