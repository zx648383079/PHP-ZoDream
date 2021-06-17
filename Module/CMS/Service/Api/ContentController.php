<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Repositories\CMSRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ContentController extends Controller {

    public function indexAction(Input $input, int|string $category) {
        $cat = FuncHelper::channel($category, true);
        if (empty($cat)) {
            return $this->renderFailure('栏目不存在');
        }
        FuncHelper::$current['channel'] = $cat->id;
        return $this->renderPage(FuncHelper::contents($input->all()));
    }

    public function detailAction(int $id, int|string $category, int|string $model) {
        $cat = FuncHelper::channel($category, true);
        if (empty($cat)) {
            return $this->renderFailure('栏目不存在');
        }
        FuncHelper::$current['channel'] = $cat->id;
        FuncHelper::$current['content'] = $id;
        $model = FuncHelper::model($model);
        if (empty($model)) {
            return $this->renderFailure('模型不存在');
        }
        FuncHelper::$current['model'] = $model->id;
        $scene = CMSRepository::scene()->setModel($model);
        $data = $scene->find($id);
        if (empty($data)) {
            return $this->renderFailure('内容不存在');
        }
        $data['view_count'] ++;
        $scene->update($id, ['view_count' => $data['view_count']]);
        return $this->render($data);
    }
}