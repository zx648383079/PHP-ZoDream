<?php
declare(strict_types=1);
namespace Module\Template\Service\Admin;


use Module\Auth\Domain\Concerns\CheckRole;
use Module\ModuleController;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\SitePageModel;
use Module\Template\Domain\VisualEditor\VisualLocalPage;
use Module\Template\Domain\VisualEditor\VisualPage;
use Zodream\Infrastructure\Contracts\Http\Output;
use Zodream\Infrastructure\Error\TemplateException;

final class VisualController extends ModuleController {
    use CheckRole;

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction(int $site, int $id) {
        try {
            $siteModel = SiteModel::findWithAuth($site);
            if (empty($siteModel)) {
                throw new \Exception('数据错误');
            }
            $model = SitePageModel::where('site_id', $site)->where('id', $id)->first();
            if (empty($model)) {
                throw new \Exception('数据错误');
            }
            return $this->show(compact('model'));
        } catch (\Exception $ex) {
            return $this->renderException($ex);
        }
    }

    public function previewAction(Output $output, int $site, int $id) {
        try {
            $siteModel = SiteModel::findWithAuth($site);
            if (empty($siteModel)) {
                throw new \Exception('数据错误');
            }
            $pageModel = SitePageModel::where('site_id', $site)->where('id', $id)->first();
            if (empty($pageModel)) {
                throw new \Exception('数据错误');
            }
            $renderer = new VisualPage($siteModel, $pageModel, false);
            return $output->html($renderer->render());
        } catch (\Exception $ex) {
            return $this->renderException($ex);
        }
    }

    public function templateAction(Output $output, int $id) {
        app('debugger')->setShowBar(false);
        try {
            $pageModel = SitePageModel::findOrThrow($id);
            $siteModel = SiteModel::findWithAuth($pageModel->site_id);
            if (empty($siteModel)) {
                throw new \Exception('数据错误');
            }
            $renderer = new VisualPage($siteModel, $pageModel, true);
            return $output->html($renderer->render());
        } catch (\Exception $ex) {
            return $this->renderException($ex);
        }
    }

    public function localAction(Output $output) {
        if (!app()->isDebug()) {
            return $this->showContent('');
        }
        app('debugger')->setShowBar(false);
        try {
            $renderer = new VisualLocalPage('default',
                'default/weights/shortcut', true);
            return $output->html($renderer->render());
        } catch (\Exception $ex) {
            return $this->renderException($ex);
        }
    }

    private function renderException(\Exception $ex) {
        if ($ex instanceof TemplateException) {
            return $this->showContent(sprintf('%s [%s -> %s]',
                $ex->getMessage(),
                $ex->getSourceFile(),
                $ex->getCompiledFile()));
        }
        return $this->showContent(sprintf('%s [%s:%d]',
            $ex->getMessage(),
            $ex->getFile(),
            $ex->getLine()));
    }
}