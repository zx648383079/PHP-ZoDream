<?php
declare(strict_types=1);
namespace Module\Document\Service\Api;

use Module\Document\Domain\CodeParser;
use Module\Document\Domain\Model\FieldModel;
use Module\Document\Domain\Repositories\ApiRepository;
use Module\Document\Domain\Repositories\MockRepository;
use Module\Document\Domain\Repositories\ProjectRepository;

class ApiController extends Controller {

    public function indexAction(int $id) {
        try {
            $model = ApiRepository::get($id);
            if (!ProjectRepository::canOpen($model->project_id)) {
                throw new \Exception('无权限浏览');
            }
            return $this->render(
                ApiRepository::getRead($model)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function languageAction() {
        return $this->renderData(
            (new CodeParser())->getLanguages()
        );
    }


    public function mockAction(int $id) {
        if (!ApiRepository::canOpen($id)) {
            return $this->renderFailure('无权限浏览');
        }
        return $this->renderData(
            MockRepository::getMockData($id)
        );
    }

    public function codeAction(int $id, string $lang, int $kind = FieldModel::KIND_RESPONSE) {
        if (!ApiRepository::canOpen($id)) {
            return $this->renderFailure('无权限浏览');
        }
        $coder = new CodeParser();
        if ($kind < 1) {
            $content = $coder->formatHttp($id, $lang);
        } else {
            $content = $coder->formatField($id, $kind, '', $lang);
        }
        return $this->renderData($content);
    }

}