<?php
declare(strict_types=1);
namespace Module\Template\Service\Admin;

use Domain\Repositories\FileRepository;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Repositories\PageRepository;
use Module\Template\Domain\Repositories\SiteRepository;
use Module\Template\Domain\Repositories\ThemeRepository;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\Infrastructure\Contracts\Http\Output;

class SiteController extends Controller {

    public function indexAction() {
        $items = SiteRepository::getList();
        return $this->show(compact('items'));
    }

    /**
     * @param $id
     * @return Output
     * @throws \Exception
     */
    public function pageAction(int $id) {
        $site = SiteModel::findWithAuth($id);
        $items = PageRepository::getList($id);
        return $this->show(compact('site', 'items'));
    }

    public function createAction() {
        $model = new SiteModel([
            'name' => 'new_site',
            'title' => 'New Site',
            'thumb' => FileRepository::formatImage(),
            'user_id' => auth()->id(),
        ]);
        return $this->show(compact('model'));
    }

    public function editAction(int $id) {
        $model = SiteModel::findWithAuth($id);
        if (empty($model)) {
            return $this->redirectWithMessage('./', '');
        }
        return $this->show('create', compact('model'));
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,100',
                'user_id' => 'required|int',
                'title' => 'string:0,200',
                'keywords' => 'string:0,255',
                'thumb' => 'string:0,255',
                'description' => 'string:0,255',
                'domain' => 'string:0,50',
                'status' => 'int:0,127',
            ]);
            $data['user_id'] = auth()->id();
            $model = SiteRepository::save($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('site/page', ['id' => $model->id])
        ]);
    }

    public function deleteAction(int $id) {
        try {
            SiteRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('site')
        ]);
    }

    public function componentAction(int $site, string $keywords = '', int $type = 0) {
        $items = SiteRepository::selfGetComponent($site, $keywords, $type);
        $type_list = ['页面', '组件'];
        return $this->show(compact('items',
            'keywords', 'type', 'type_list'));
    }
}