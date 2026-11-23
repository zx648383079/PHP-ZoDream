<?php
declare(strict_types=1);
namespace Module\Counter\Service;

use Module\Counter\Domain\Events\CounterState;
use Module\Counter\Domain\Listeners\StateListener;
use Module\ModuleController as Controller;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class StateController extends Controller {

    public function indexAction(Request $request) {
        $state = CounterState::create($request);
        new StateListener($state);
        return '';
    }
}