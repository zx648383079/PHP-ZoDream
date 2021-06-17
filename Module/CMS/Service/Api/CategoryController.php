<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api;

use Module\CMS\Domain\FuncHelper;
use Zodream\Infrastructure\Contracts\Http\Input;

class CategoryController extends Controller {

    public function indexAction(Input $input, int|string|array $id = 0) {
        if (!empty($id) && !is_array($id)) {
            return $this->detailAction($id);
        }
        return $this->renderData(FuncHelper::channels($input->all()));
    }

    public function detailAction(int|string $id) {
        $cat = FuncHelper::channel($id, true);
        if (empty($cat)) {
            return $this->renderFailure('栏目不存在');
        }
        FuncHelper::$current['channel'] = $cat->id;
        return $this->render($cat);
    }
}