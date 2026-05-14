<?php
declare(strict_types=1);
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\SiteRepository;

class ContentController extends Controller {

    public function indexAction(int $id, int|string $category, int|string $model) {
        $channel = FuncHelper::channel($category, true);
        if (empty($channel)) {
            return $this->redirect('./');
        }
        FuncHelper::$current['channel'] = $channel['id'];
        FuncHelper::$current['content'] = $id;
        $model = FuncHelper::model($model);
        if (empty($model)) {
            return $this->redirect('./');
        }
        FuncHelper::$current['model'] = $model['id'];
        $scene = CMSRepository::context()->scene()->setModel($model);
        $article = $scene->find($id);
        $parent = null;
        if (empty($article)) {
            return $this->redirect('./');
        }
        if (!CMSRepository::isPreview() && $article['status'] != SiteRepository::PUBLISH_STATUS_POSTED) {
            return $this->redirect('./');
        }
        $article['view_count'] ++;
        $scene->update($id, ['view_count' => $article['view_count']], false);
        $catModel = FuncHelper::model($channel['model_id']);
        if ($article['parent_id'] > 0 && $catModel) {
            $parent = $scene
                ->setModel($catModel)->find($article['parent_id']);
        }
        $scene->setModel($model);
        $title = $article['title'];
        if (!empty($parent)) {
            $title = sprintf('%s %s', $parent['title'], $article['title']);
        }
        $formatted = $scene->format($article);
        return $this->show(
            $channel['model_id'] === $model['id']
                ? $channel['show_template']
                : $model['show_template'],
            compact('channel', 'article', 'formatted', 'title', 'model', 'parent'));
    }
}