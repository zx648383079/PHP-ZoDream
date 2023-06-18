<?php
declare(strict_types=1);
namespace Module\CMS\Service;

use Exception;
use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\FormRepository;
use Module\CMS\Domain\Repositories\LinkageRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class FormController extends Controller {

    public function indexAction(Input $input) {
        $model = FormRepository::getModel($input);
        $scene = CMSRepository::scene()->setModel($model);
        $field_list = FuncHelper::formData($model->id);
        return $this->show(compact('model', 'field_list', 'scene'));
    }

    public function saveAction(Input $input) {
        try {
            FormRepository::save($input);
        } catch (Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData([
            'url' => './'
        ]);
    }

    public function linkageAction(int $id) {
        return $this->renderData(LinkageRepository::idTree($id));
    }
}