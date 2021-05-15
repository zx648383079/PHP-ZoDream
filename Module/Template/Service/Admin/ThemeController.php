<?php
namespace Module\Template\Service\Admin;

use Domain\Model\SearchModel;
use Module\Template\Domain\Model\ThemeModel;
use Module\Template\Module;
use Zodream\Image\Image;
use Zodream\Infrastructure\Contracts\Http\Output;

class ThemeController extends Controller {

    public function indexAction($keywords = null) {
        $model_list = ThemeModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->orderBy('id', 'desc')
            ->page();
        return $this->show(compact('model_list', 'keywords'));
    }

    public function refreshAction() {

        return $this->show();
    }

    public function installAction() {
        $data = ThemeModel::findTheme();
        foreach ($data as $item) {
            ThemeModel::install($item);
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function assetAction(Output $response, $file, $folder = null) {
        $dir = Module::templateFolder($folder);
        $file = $dir->isFile() ? $dir : $dir->file($file);
        if ($file->getExtension() === 'css' || $file->getExtension() === 'js') {
            return $response->custom($file->exist() ? $file->read() : '', $file->getExtension());
        }
        $image = new Image();
        $image->instance()->open($file->exist() ?
            $file : public_path('assets/images/thumb.jpg'));
        if (!$image->getRealType()) {
           $image->instance()->open(public_path('assets/images/thumb.jpg'));
        }
        return $response->image($image);
    }
}