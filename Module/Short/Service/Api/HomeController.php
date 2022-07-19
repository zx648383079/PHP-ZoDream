<?php
declare(strict_types=1);
namespace Module\Short\Service\Api;

use Module\Short\Domain\Repositories\ShortRepository;
use Zodream\Infrastructure\Contracts\Http\Input;


class HomeController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

	public function indexAction(string $keywords = '') {
		return $this->renderPage(ShortRepository::getList($keywords, auth()->id()));
	}

	public function saveAction(Input $input) {
        try {
            return $this->render(
                ShortRepository::saveSelf($input->validate([
                    'id' => 'int',
                    'title' => 'string:0,30',
                    'source_url' => 'required|string:0,60',
                ]))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            ShortRepository::removeSelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}