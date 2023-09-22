<?php
declare(strict_types=1);
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\SiteRepository;

class ContentController extends Controller {

    public function indexAction(int $id, int|string $category, int|string $model) {
        $cat = FuncHelper::channel($category, true);
        if (empty($cat)) {
            return $this->redirect('./');
        }
        FuncHelper::$current['channel'] = $cat['id'];
        FuncHelper::$current['content'] = $id;
        $model = FuncHelper::model($model);
        if (empty($model)) {
            return $this->redirect('./');
        }
        FuncHelper::$current['model'] = $model['id'];
        $scene = CMSRepository::scene()->setModel($model);
        $data = $scene->find($id);
        $parent = null;
        if (empty($data)) {
            return $this->redirect('./');
        }
        if (!CMSRepository::isPreview() && $data['status'] != SiteRepository::PUBLISH_STATUS_POSTED) {
            return $this->redirect('./');
        }
        $data['view_count'] ++;
        $scene->update($id, ['view_count' => $data['view_count']]);
        $catModel = FuncHelper::model($cat['model_id']);
        if ($data['parent_id'] > 0 && $catModel) {
            $parent = CMSRepository::scene()
                ->setModel($catModel)->find($data['parent_id']);
        }
        CMSRepository::scene()->setModel($model);
        $title = $data['title'];
        if (!empty($parent)) {
            $title = sprintf('%s %s', $parent['title'], $data['title']);
        }
        return $this->show(
            $cat['model_id'] === $model['id']
                ? $cat['show_template']
                : $model['show_template'],
            compact('cat', 'data', 'title', 'model', 'parent'));
    }
}