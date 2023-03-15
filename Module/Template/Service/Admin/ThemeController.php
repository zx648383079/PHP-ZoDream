<?php
namespace Module\Template\Service\Admin;

use Domain\Repositories\FileRepository;
use Module\Template\Domain\Repositories\ThemeRepository;
use Module\Template\Domain\VisualEditor\VisualFactory;
use Module\Template\Domain\VisualEditor\VisualPage;
use Module\Template\Module;
use Zodream\Image\Image;
use Zodream\Infrastructure\Contracts\Http\Input;
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

    public function assetAction(Output $response, Input $input, string $file, string $folder = '') {
        $wantImage = str_contains($input->header('ACCEPT'), 'image');
        try {
            $file = ThemeRepository::assetFile($folder, $file);
        } catch (\Exception $ex) {
            if (!$wantImage) {
                return $response->custom($ex->getMessage(), 'txt');
            }
            $file = public_path(FileRepository::DEFAULT_IMAGE);
        }
        if (!$wantImage) {
            return $response->file($file);
        }
        $image = new Image();
        $image->instance()->open($file);
        if (!$image->getRealType()) {
            $image->instance()->open(public_path(FileRepository::DEFAULT_IMAGE));
        }
        return $response->image($image);
    }
}