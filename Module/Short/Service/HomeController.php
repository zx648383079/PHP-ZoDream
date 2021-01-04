<?php
namespace Module\Short\Service;

use Module\Short\Domain\Repositories\ShortRepository;


class HomeController extends Controller {

    public function rules() {
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
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'source_url' => $model->source_url,
            'short_url' => $model->complete_short_url
        ]);
    }

    public function findLayoutFile() {
        return app_path()->file('UserInterface/Home/layouts/main.php');
    }

}