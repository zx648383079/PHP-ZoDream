<?php
declare(strict_types=1);
namespace Module\Counter\Service\Api;

use Zodream\Infrastructure\Contracts\Http\Output;

class HomeController extends Controller {



    public function mapAction(Output $output, int $type) {
        $fileName = $type > 0 ? 'china.json' : 'world.json';
        return $output->file(public_path('assets/charts/'. $fileName));
    }
}