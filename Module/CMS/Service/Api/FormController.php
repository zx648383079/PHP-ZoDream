<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api;

use Exception;
use Module\CMS\Domain\Repositories\FormRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class FormController extends Controller {

    public function indexAction(Input $input) {
        return $this->renderData(
            FormRepository::formInputList($input)
        );
    }

    public function saveAction(Input $input) {
        try {
            FormRepository::save($input);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }


}