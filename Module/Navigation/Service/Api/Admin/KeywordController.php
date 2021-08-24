<?php
declare(strict_types=1);
namespace Module\Navigation\Service\Api\Admin;

use Module\Navigation\Domain\Repositories\Admin\KeywordRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

final class KeywordController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(KeywordRepository::getList($keywords));
    }


    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'word' => 'required|string:0,30',
                'type' => 'int:0,127',
            ]);
            return $this->render(KeywordRepository::save($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            KeywordRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}