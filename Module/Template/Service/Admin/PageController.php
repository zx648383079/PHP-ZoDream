<?php
declare(strict_types=1);
namespace Module\Template\Service\Admin;

use Domain\Repositories\FileRepository;
use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\ThemeModel;
use Module\Template\Domain\Model\ThemePageModel;
use Module\Template\Domain\Repositories\PageRepository;
use Module\Template\Domain\Repositories\ThemeRepository;
use Module\Template\Domain\VisualEditor\VisualPage;
use Zodream\Infrastructure\Contracts\Http\Input;

class PageController extends Controller {

    public function indexAction(int $id) {
        $model = PageModel::findOrThrow($id);
        $site = SiteModel::findWithAuth($model->site_id);
        $model->is_default = $site->default_page_id == $model->id;
        $theme = ThemeModel::find($site->theme_id);
        $weight_list = ThemeRepository::weightGroups($site->theme_id);
        $style_list = ThemeRepository::styleList($site->theme_id);
        return $this->show(compact('model', 'style_list', 'weight_list', 'theme'));
    }

    public function templateAction(int $id, bool $edit = false) {
        $this->layout = '';
        app('debugger')->setShowBar(false);
        $model = PageModel::findOrThrow($id);
        $page = new VisualPage($model->site, $model, $edit);
        return $this->show(compact('model', 'page'));
    }

    public function createAction(int $site_id, int $page_id = 0, int $type = 0, string $keywords = '') {
        $site = SiteModel::findWithAuth($site_id);
        if ($page_id < 1) {
            $model_list = ThemeRepository::pageList($site->theme_id, $keywords);
            return $this->show('theme', compact('model_list', 'keywords', 'site_id', 'type'));
        }
        $theme = ThemePageModel::find($page_id);
        $model = new PageModel([
            'site_id' => $site_id,
            'type' => $type,
            'name' => 'new_page',
            'title' => 'New Page',
            'theme_page_id' => $page_id,
            'thumb' => FileRepository::formatImage(),
            'position' => 99
        ]);
        return $this->show(compact('model', 'theme'));
    }

    public function editAction(int $id) {
        $model = PageModel::find($id);
        if (empty($model)) {
            return $this->redirectWithMessage($this->getUrl('site'), '');
        }
        $theme = ThemePageModel::find($model->theme_page_id);
        return $this->show('create', compact('model', 'theme'));
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'site_id' => 'required|int',
                'type' => 'int:0,9',
                'name' => 'required|string:0,100',
                'title' => 'string:0,200',
                'keywords' => 'string:0,255',
                'thumb' => 'string:0,255',
                'description' => 'string:0,255',
                'settings' => '',
                'theme_page_id' => 'required|int',
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

}