<?php
namespace Module\Template\Service\Admin;

use Module\Template\Domain\Repositories\ThemeRepository;
use Module\Template\Domain\VisualEditor\VisualFactory;
use Module\Template\Domain\VisualEditor\VisualPage;
use Module\Template\Module;
use Zodream\Image\Image;
use Zodream\Infrastructure\Contracts\Http\Output;

class ThemeController extends Controller {

    public function indexAction(string $keywords = '') {
        $model_list = ThemeRepository::getList($keywords);
        return $this->show(compact('model_list', 'keywords'));
    }

    public function refreshAction() {
        return $this->show();
    }

    public function installAction() {
        try {
            ThemeRepository::installAllThemes();
        } catch (\Exception) {}
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function assetAction(Output $response, string $file, string $folder = '') {
        $dir = VisualFactory::templateFolder($folder);
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