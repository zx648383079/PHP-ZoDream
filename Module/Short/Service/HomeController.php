<?php
namespace Module\Short\Service;

use Module\Short\Domain\Repositories\ShortRepository;
use Zodream\Service\Factory;

class HomeController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

	public function indexAction() {
		return $this->show();
	}

	public function createAction(string $source_url) {
        try {
            $model = ShortRepository::create($source_url);
        } catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess([
            'source_url' => $model->source_url,
            'short_url' => $model->complete_short_url
        ]);
    }

    public function findLayoutFile() {
        return Factory::root()->file('UserInterface/Home/layouts/main.php');
    }

}