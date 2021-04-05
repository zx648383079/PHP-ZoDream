<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Listeners;

use Module\Auth\Domain\Events\ManageAction;

class ManageActionListener {

    public function __construct(ManageAction $event) {
        $model = $event->toLogModel();
        $model->save();
    }
}
