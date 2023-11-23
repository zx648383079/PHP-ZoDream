<?php
declare(strict_types=1);
namespace Module\Template\Service\Admin;

use Domain\Repositories\FileRepository;
use Module\Template\Domain\Model\SiteComponentModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\SitePageModel;
use Module\Template\Domain\Repositories\PageRepository;
use Module\Template\Domain\Repositories\SiteRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class PageController extends Controller {

    public function indexAction(int $id) {
        $model = SitePageModel::findOrThrow($id);
        $site = SiteModel::findWithAuth($model->site_id);
        $model->is_default = $site->default_page_id == $model->id;
        return $this->show(compact('model'));
    }

    public function createAction(int $site_id, int $page_id = 0, int $type = 0, string $keywords = '') {
        // $site = SiteModel::findWithAuth($site_id);
        if ($page_id < 1) {
            $model_list = SiteRepository::selfGetComponent($site_id, $keywords);
            return $this->show('theme', compact('model_list', 'keywords', 'site_id', 'type'));
        }
        $theme = SiteComponentModel::where('component_id', $page_id)
            ->where('site_id', $site_id)->first();
        $model = new SitePageModel([
            'site_id' => $site_id,
            'type' => $type,
            'name' => 'new_page',
            'title' => 'New Page',
            'component_id' => $page_id,
            'thumb' => FileRepository::formatImage(),
            'position' => 99
        ]);
        return $this->show(compact('model', 'theme'));
    }

    public function editAction(int $id) {
        $model = SitePageModel::find($id);
        if (empty($model)) {
            return $this->redirectWithMessage($this->getUrl('site'), '');
        }
        $theme = SiteComponentModel::where('component_id', $model->component_id)
        ->where('site_id', $model->site_id)->first();
        return $this->show('create', compact('model', 'theme'));
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'site_id' => 'required|int',
                'name' => 'required|string:0,100',
                'title' => 'string:0,200',
                'keywords' => 'string:0,255',
                'thumb' => 'string:0,255',
                'description' => 'string:0,255',
                'settings' => '',
                'component_id' => 'required|int',
                'position' => 'int:0,127',
                'status' => 'int:0,127',
            ]);
            $model = PageRepository::save($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('site/page', ['id' => $model->site_id])
        ]);
    }

    public function deleteAction(int $id) {
        try {
            $siteId = PageRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('site/page', ['id' => $siteId])
        ]);
    }

    public function detailAction(int $id) {
        try {
            $data = PageRepository::getInfo($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($data);
    }

    public function searchAction(int $id) {
        try {
            $data = PageRepository::search(PageRepository::siteId($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($data);
    }
}