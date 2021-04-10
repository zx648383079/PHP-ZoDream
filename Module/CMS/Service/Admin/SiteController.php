<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\SiteRepository;
use Module\CMS\Domain\ThemeManager;

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
        $themes = (new ThemeManager)->getAllThemes();
        return $this->show('edit', compact('model', 'themes'));
    }

    public function saveAction() {
        $model = new SiteModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        CMSRepository::generateSite($model);
        return $this->renderData([
            'url' => $this->getUrl('site')
        ]);
    }

    public function deleteAction(int $id) {
        $model = SiteModel::find($id);
        $model->delete();
        CMSRepository::removeSite($model);
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
        $model = SiteModel::find($id);
        $model->is_default = 1;
        $model->save();
        SiteModel::where('id', '<>', $id)->update([
            'is_default' => 0
        ]);
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