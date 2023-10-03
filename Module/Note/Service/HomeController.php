<?php
declare(strict_types=1);
namespace Module\Note\Service;

use Module\ModuleController;
use Module\Note\Domain\Repositories\NoteRepository;
use Zodream\Disk\File;

class HomeController extends ModuleController {

    protected File|string $layout = 'main';
	
	public function indexAction(string $keywords = '', int $id = 0, int $user = 0) {
	    $model_list = NoteRepository::getList($keywords, $user, $id);
        if (request()->isAjax()) {
            return $this->renderData([
                'html' => $this->renderHtml('page', compact('model_list', 'keywords')),
                'has_more' => $model_list->hasMore()
            ]);
        }
		return $this->show(compact('model_list', 'keywords'));
	}

    public function suggestionAction(string $keywords = '') {
        return $this->renderData(NoteRepository::suggestion($keywords));
    }

    public function findLayoutFile(): File|string {
        if ($this->layout === '') {
            return '';
        }
        return app_path()->file('UserInterface/Home/layouts/main.php');
    }
}