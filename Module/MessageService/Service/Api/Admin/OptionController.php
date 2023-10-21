<?php
declare(strict_types=1);
namespace Module\MessageService\Service\Api\Admin;

use Module\MessageService\Domain\Repositories\MessageRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class OptionController extends Controller {
    public function mailAction() {
        return $this->renderData(MessageRepository::optionForm(true));
    }

    public function mailSaveAction(Input $input) {
        try {
            MessageRepository::optionSave($input, true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function smsAction() {
        return $this->renderData(MessageRepository::optionForm(false));
    }

    public function smsSaveAction(Input $input) {
        try {
            MessageRepository::optionSave($input, true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}