<?php
declare(strict_types=1);
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;
use Zodream\Helpers\Html;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class CategoryController extends Controller {

    public function indexAction(Request $request, int|string $id) {
        $channel = FuncHelper::channel($id, true);
        if (empty($channel)) {
            return $this->redirect('./');
        }
        FuncHelper::$current['channel'] = $channel['id'];
        $items = null;
        if ($channel['type'] < 1) {
            $queries = $request->get();
            unset($queries['id']);
            $items = FuncHelper::contents($queries);
        }
        $title = $channel['title'];
        return $this->show((string)$channel['category_template'],
            compact('channel', 'items', 'title'));
    }

    public function listAction(Request $request) {
        $id = $request->get('category');
        if (empty($id)) {
            $id = $request->get('id');
        }
        FuncHelper::$current['channel'] = intval($id);
        $channel = FuncHelper::channel($id, true);
        if (empty($channel)) {
            return $this->redirect('./');
        }
        $queries = $request->get();
        unset($queries['id'], $queries['category']);
        if (!isset($queries['field'])) {
            $queries['field'] = FuncHelper::searchField($channel['id']);
        }
        $items = FuncHelper::contents($queries);
        $title = FuncHelper::translate('{0} list page', [$channel['title']]);
        $keywords = isset($queries['keywords']) ? Html::text($queries['keywords']) : '';
        return $this->show($channel['list_template'],
            compact('channel', 'items',  'title', 'keywords'));
    }
}