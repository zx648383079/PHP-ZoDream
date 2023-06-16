<?php
declare(strict_types=1);
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;
use Zodream\Helpers\Html;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class CategoryController extends Controller {

    public function indexAction(int|string $id) {
        $cat = FuncHelper::channel($id, true);
        if (empty($cat)) {
            return $this->redirect('./');
        }
        FuncHelper::$current['channel'] = $cat['id'];
        $page = null;
        if ($cat['type'] < 1) {
            $page = FuncHelper::contents([]);
        }
        $title = $cat['title'];
        return $this->show((string)$cat['category_template'],
            compact('cat', 'page', 'title'));
    }

    public function listAction(Request $request, int $id) {
        FuncHelper::$current['channel'] = $id;
        $cat = FuncHelper::channel($id, true);
        $queries = $request->get();
        unset($queries['id']);
        if (!isset($queries['field'])) {
            $queries['field'] = FuncHelper::searchField($cat['id']);
        }
        $page = FuncHelper::contents($queries);
        $title = $cat['title'].'列表页';
        $keywords = isset($queries['keywords']) ? Html::text($queries['keywords']) : '';
        return $this->show($cat->list_template,
            compact('cat', 'page',  'title', 'keywords'));
    }
}