<?php
declare(strict_types=1);
namespace Module\Short\Service;

use Module\Short\Domain\Repositories\ShortRepository;
use Zodream\Disk\File;
use Zodream\Infrastructure\Contracts\Http\Input;


class HomeController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

	public function indexAction() {
		return $this->show();
	}

	public function createAction(Input $input) {
        try {
            $model = ShortRepository::saveSelf($input->validate([
                'id' => 'int',
                'title' => 'string:0,30',
                'source_url' => 'required|string:0,60',
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'source_url' => $model->source_url,
            'short_url' => $model->complete_short_url
        ]);
    }

    public function findLayoutFile(): File|string {
        return app_path()->file('UserInterface/Home/layouts/main.php');
    }

}