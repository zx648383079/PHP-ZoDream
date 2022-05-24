<?php
declare(strict_types=1);
namespace Module\OnlineTV\Service\Api\Admin;

use Module\OnlineTV\Domain\Repositories\LiveRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class LiveController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            LiveRepository::getList($keywords)
        );
    }

    public function saveAction(Input $input) {
        try {
            return $this->render(
                LiveRepository::save($input->validate([
                    'id' => 'int',
                    'title' => 'required|string:0,255',
                    'thumb' => 'string:0,255',
                    'source' => 'required|string:0,255',
                ]))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        LiveRepository::remove($id);
        return $this->renderData(true);
    }
}