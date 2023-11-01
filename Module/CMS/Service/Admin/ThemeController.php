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
        $provider = new ThemeManager;
        $themes = $provider->loadThemes();
        foreach ($themes as $item) {
            if ($item['name'] === $current) {
                $current = $item;
                //unset($themes[$key]);
                break;
            }
        }
        $bakItems = $provider->packFiles(array_column($themes, 'description', 'name'));
        return $this->show(compact('themes', 'current', 'bakItems'));
    }

    public function marketAction() {
        return $this->show();
    }

    public function applyAction(string $theme) {
        try {
            (new ThemeManager())->apply($theme);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData([
            'url' => $this->getUrl('theme')
        ]);
    }

    public function bakAction() {
        try {
            (new ThemeManager())->pack();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData(true, '已备份到 data/bak 文件夹下');
    }

    public function bakRestoreAction(string $file) {
        try {
            (new ThemeManager())->unpack($file);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData(true, '回滚成功');
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