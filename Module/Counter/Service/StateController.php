<?php
namespace Module\Counter\Service;

use Module\Counter\Domain\Events\CounterState;
use Module\Counter\Domain\Model\ClickLogModel;
use Module\Counter\Domain\Model\LoadTimeLogModel;
use Module\Counter\Domain\Model\PageLogModel;
use Module\Counter\Domain\Model\StayTimeLogModel;
use Module\Counter\Domain\Model\VisitorLogModel;
use Module\ModuleController as Controller;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class StateController extends Controller {

    public function indexAction(Request $request) {
        $state = CounterState::create($request);
        ClickLogModel::log($state);
        LoadTimeLogModel::log($state);
        PageLogModel::log($state);
        VisitorLogModel::log($state);
        StayTimeLogModel::log($state);
        return '';
    }
}