<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\ThemeManager;

class SiteController extends Controller {
    public function indexAction() {
        $model_list = SiteModel::orderBy('id', 'asc')->page();
        $current = CMSRepository::siteId();
        return $this->show(compact('model_list', 'current'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => 0]);
    }

    public function editAction($id) {
        $model = SiteModel::findOrNew($id);
        $themes = (new ThemeManager)->getAllThemes();
        return $this->show(compact('model', 'themes'));
    }

    public function saveAction() {
        $model = new SiteModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        CMSRepository::generateSite($model);
        return $this->jsonSuccess([
            'url' => $this->getUrl('site')
        ]);
    }

    public function deleteAction($id) {
        $model = SiteModel::find($id);
        $model->delete();
        CMSRepository::removeSite($model);
        return $this->jsonSuccess([
            'url' => $this->getUrl('site')
        ]);
    }

    public function changeAction($id) {
        CMSRepository::resetSite($id);
        return $this->jsonSuccess([
            'url' => $this->getUrl('site')
        ]);
    }

    public function defaultAction($id) {
        $model = SiteModel::find($id);
        $model->is_default = 1;
        $model->save();
        SiteModel::where('id', '<>', $id)->update([
            'is_default' => 0
        ]);
        return $this->jsonSuccess([
            'url' => $this->getUrl('site')
        ]);
    }

    public function optionAction($id) {
        $model = SiteModel::find($id);
        return $this->show(compact('model'));
    }

    public function saveOptionAction($id, $option = [], $field = []) {
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
        return $this->jsonSuccess([
            'url' => $this->getUrl('site/option', ['id' => $id])
        ]);
    }
}