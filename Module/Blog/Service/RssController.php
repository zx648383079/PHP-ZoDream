<?php
namespace Module\Blog\Service;

use Module\Blog\Domain\Model\BlogModel;
use Module\ModuleController;
use Zodream\Html\Rss\Rss;
use Zodream\Html\Rss\RssItem;
use Zodream\Infrastructure\Http\Response;

class RssController extends ModuleController {

    public function indexAction(Response $response) {
        $key = '__ZoDream_rss__';
        if (cache()->has($key)) {
            return $response->xml(cache($key));
        }
        $rss = new Rss();
        $rss->setTitle(__('site title'))
            ->setDescription(__('site description'))
            ->setLink(url('/'))
            ->setImage(url()->asset('assets/images/favicon.png'), url('/'));
        $model_list = BlogModel::query()->with('term')
            ->where('open_type', BlogModel::OPEN_PUBLIC)
            ->orderBy('id', 'desc')
            ->get('id', 'term_id', 'title', 'edit_type', 'content', 'programming_language', 'created_at');
        foreach ($model_list as $item) {
            $rssItem = new RssItem();
            $rssItem->setTitle($item->title)
                ->setLink(url('blog', ['id' => $item->id]))
                ->setPubDate($item->created_at)
                ->setDescription($item->toHtml())
                ->addTag('category', $item->term->name);
            $rss->addItem($rssItem);
        }
        $res = (string)$rss;
        cache()->set($key, $res, 3600);
        return $response->xml($res);
    }

}