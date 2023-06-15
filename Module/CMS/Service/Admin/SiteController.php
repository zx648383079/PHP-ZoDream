<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\SiteRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class SiteController extends Controller {
    public function indexAction(string $keywords = '') {
        $model_list = SiteRepository::getList($keywords);
        $current = CMSRepository::siteId();
        return $this->show(compact('model_list', 'current'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = SiteModel::findOrNew($id);
        $themes = SiteRepository::themeList();
        return $this->show('edit', compact('model', 'themes'));
    }

    public function saveAction(Input $input) {
        try {
            SiteRepository::save($input->validate([
                'id' => 'int',
                'title' => 'required|string:0,255',
                'keywords' => 'string:0,255',
                'description' => 'string:0,255',
                'logo' => 'string:0,255',
                'theme' => 'required|string:0,100',
                'match_type' => 'int:0,127',
                'match_rule' => 'string:0,100',
                'is_default' => 'int:0,127',
                'options' => '',
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData([
            'url' => $this->getUrl('site')
        ]);
    }

    public function deleteAction(int $id) {
        SiteRepository::remove($id);
        return $this->renderData([
            'url' => $this->getUrl('site')
        ]);
    }

    public function changeAction(int $id) {
        CMSRepository::resetSite($id);
        return $this->renderData([
            'url' => $this->getUrl('site')
        ]);
    }

    public function defaultAction(int $id) {
        try {
            SiteRepository::setDefault($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('site')
        ]);
    }

    public function optionAction(int $id) {
        $model = SiteModel::find($id);
        return $this->show(compact('model'));
    }

    public function saveOptionAction(int $id, array $option = [], array $field = []) {
        $model = SiteModel::find($id);
        $options = $model->options;
        if (!empty($option)) {
            foreach ($options as &$item) {
                if (array_key_exists($item['code'], $option)) {
                    $item['value'] = $option[$item['code']];
                    continue;
                }
                unset($item);
            }
        } else {
            $options = [];
        }
        unset($item);
        if (!empty($field) && !empty($field['code'])) {
            $options[] = $field;
        }
        $model->options = $options;
        $model->save();
        return $this->renderData([
            'url' => $this->getUrl('site/option', ['id' => $id])
        ]);
    }
}