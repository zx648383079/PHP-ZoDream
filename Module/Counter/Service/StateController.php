<?php
namespace Module\Counter\Service;

use Module\Counter\Domain\Model\ClickLogModel;
use Module\Counter\Domain\Model\LoadTimeLogModel;
use Module\Counter\Domain\Model\PageLogModel;
use Module\Counter\Domain\Model\StayTimeLogModel;
use Module\Counter\Domain\Model\VisitorLogModel;
use Module\ModuleController as Controller;
use Zodream\Infrastructure\Http\Request;

class StateController extends Controller {

    public function indexAction(Request $request) {
        ClickLogModel::log($request);
        LoadTimeLogModel::log($request);
        PageLogModel::log($request);
        VisitorLogModel::log($request);
        StayTimeLogModel::log($request);
        return '';
    }
}