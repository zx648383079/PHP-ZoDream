<?php
declare(strict_types=1);
namespace Module\MessageService\Service\Admin;

use Module\MessageService\Domain\Repositories\MessageRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class OptionController extends Controller {
    public function mailAction() {
        $items = MessageRepository::optionForm(true);
        return $this->show(compact('items'));
    }

    public function mailSaveAction(Input $input) {
        try {
            MessageRepository::optionSave($input, true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true, '保存成功');
    }

    public function smsAction() {
        $items = MessageRepository::optionForm(false);
        return $this->show(compact('items'));
    }

    public function smsSaveAction(Input $input) {
        try {
            MessageRepository::optionSave($input, false);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true, '保存成功');
    }
}