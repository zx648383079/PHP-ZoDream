<?php
namespace Module\Counter\Service\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\ModuleController;


class Controller extends ModuleController {

    use AdminRole;

    public $layout = '/Admin/layouts/main';

    protected function getUrl($path, $args = []) {
        return url('./@admin/'.$path, $args);
    }

    protected function getTimeInput(): array {
        $request = request();
        $start_at = $request->get('start_at', 'today');
        $end_at = $request->get('end_at');
        $time = strtotime(date('Y-m-d 00:00:00'));
        if ($start_at === 'today') {
            return [$time, $time + 86400];
        }
        if ($start_at === 'yesterday') {
            return [$time - 86400, $time];
        }
        if ($start_at === 'week') {
            return [$time - 6 * 85400, $time + 86400];
        }
        if ($start_at === 'month') {
            return [$time - 29 * 85400, $time + 86400];
        }
        return [is_numeric($start_at) ? $start_at : strtotime($start_at),
                is_numeric($end_at) ? $end_at : strtotime($end_at)];
    }
}