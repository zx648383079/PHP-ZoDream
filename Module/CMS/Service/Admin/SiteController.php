<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\Repositories\CacheRepository;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\SiteRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class SiteController extends Controller {
    public function indexAction(string $keywords = '') {
        $model_list = SiteRepository::getList($keywords);
        return $this->show(compact('model_list'));
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
                'match_rule' => 'string:0,100',
                'is_default' => 'int:0,127',
                'status' => 'int:0,127',
                'language' => 'string:0,10',
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
            'url' => $this->getUrl('site'),
            'no_jax' => true
        ]);
    }

    public function changeAction(int $id) {
        CMSRepository::resetSite($id);
        return $this->renderData([
            'url' => $this->getUrl('site'),
            'no_jax' => true
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
            foreach ($options as $k => $item) {
                if (array_key_exists($item['code'], $option)) {
                    $options[$k]['value'] = $option[$item['code']];
                    continue;
                }
                unset($options[$k]);
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
        CacheRepository::flushOptionCache();
        return $this->renderData([
            'url' => $this->getUrl('site/option', ['id' => $id])
        ]);
    }
}