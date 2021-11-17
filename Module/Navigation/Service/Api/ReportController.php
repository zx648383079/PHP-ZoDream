<?php
declare(strict_types=1);
namespace Module\Navigation\Service\Api;


use Module\Contact\Domain\Repositories\ReportRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

final class ReportController extends Controller {

    public function indexAction(Input $input) {
        try {
            $data = $input->validate([
                'email' => 'string:0,50',
                'item_type' => 'int:0,127',
                'item_id' => 'int',
                'type' => 'int:0,127',
                'title' => 'string:0,255',
                'content' => 'string:0,255',
                'files' => 'string:0,255',
            ]);
            ReportRepository::create($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}