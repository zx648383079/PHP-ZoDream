<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\ThemeManager;
use Zodream\Helpers\Json;
use Zodream\Infrastructure\Contracts\Http\Output;

class ThemeController extends Controller {
    public function indexAction() {
        $current = CMSRepository::theme();
        $themes = (new ThemeManager)->loadThemes();
        foreach ($themes as $key => $item) {
            if ($item['name'] === $current) {
                $current = $item;
                //unset($themes[$key]);
                break;
            }
        }
        return $this->show(compact('themes', 'current'));
    }

    public function marketAction() {
        return $this->show();
    }

    public function applyAction(string $theme) {
        (new ThemeManager())->apply($theme);
        return $this->renderData([
            'url' => $this->getUrl('theme')
        ]);
    }

    public function backAction() {
        (new ThemeManager())->pack();
        return $this->renderData([
            'url' => $this->getUrl('theme')
        ]);
    }

    public function installAction() {
    }

    public function coverAction(Output $output, string $theme) {
        $manager = new ThemeManager();
        $folder = $manager->getSrc()->directory($theme);
        $data = $folder->file('theme.json')->read();
        $data = Json::decode($data);
        $file = $folder->file($data['cover']);
        $output->header->setContentType($file->getExtension())
            ->setContentDisposition($file->getName());
        return $output->setParameter(
            $file
        );
    }
}