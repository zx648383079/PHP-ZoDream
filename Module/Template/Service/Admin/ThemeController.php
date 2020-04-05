<?php
namespace Module\Template\Service\Admin;

use Module\Template\Domain\Model\ThemeModel;
use Module\Template\Module;
use Zodream\Image\ImageStatic;
use Zodream\Infrastructure\Http\Response;

class ThemeController extends Controller {

    public function indexAction($keywords = null) {
        $model_list = ThemeModel::when(!empty($keywords), function ($query) {
            ThemeModel::searchWhere($query, ['name']);
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
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

    public function assetAction(Response $response, $file, $folder = null) {
        $dir = Module::templateFolder($folder);
        $file = $dir->isFile() ? $dir : $dir->file($file);
        if ($file->getExtension() === 'css' || $file->getExtension() === 'js') {
            return $response->custom($file->exist() ? $file->read() : '', $file->getExtension());
        }
        $img = ImageStatic::make($file->exist() ?
            $file : public_path('assets/images/thumb.jpg'));
        if (!$img->getRealType()) {
            $img = ImageStatic::make(public_path('assets/images/thumb.jpg'));
        }
        return $response->image($img);
    }
}