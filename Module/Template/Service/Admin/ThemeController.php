<?php
namespace Module\Template\Service\Admin;

use Module\Template\Domain\Model\ThemeModel;
use Module\Template\Module;
use Zodream\Image\ImageStatic;

class ThemeController extends Controller {

    public function indexAction() {
        $model_list = ThemeModel::orderBy('id', 'desc')
            ->page();
        return $this->show(compact('model_list'));
    }

    public function refreshAction() {

        return $this->show();
    }

    public function installAction() {
        $data = ThemeModel::findTheme();
        foreach ($data as $item) {
            ThemeModel::install($item);
        }
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

    public function coverAction($id) {
        $model = ThemeModel::find($id);
        $thumb = Module::templateFolder($model->path)->file($model->thumb);
        return app('response')->image(ImageStatic::make($thumb));
    }
}