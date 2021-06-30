<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Repositories\Admin\GoodsRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class GoodsController extends Controller {

    public function indexAction(int|array $id = 0,
                                int $category = 0,
                                int $brand = 0,
                                string $keywords = '',
                                int $per_page = 20, string $sort = '', string $order = '', bool $trash = false) {
        if (!is_array($id) && $id > 0) {
            return $this->detailAction($id);
        }
        $page = GoodsRepository::getList(!is_array($id) ? [] : $id,
            $category, $brand, $keywords, $per_page, $sort, $order, $trash);
        return $this->renderPage($page);
    }

    public function detailAction(int $id) {
        try {
            return $this->render(GoodsRepository::getFull($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            return $this->render(GoodsRepository::save($input->all()));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id, bool $trash = false) {
        try {
            GoodsRepository::remove($id, $trash);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function toggleAction(int $id, string $name) {
        try {
            return $this->render(GoodsRepository::goodsAction($id, [$name]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function generateSnAction() {
        return $this->renderData(GoodsRepository::generateSn());
    }

    public function clearAction() {
        GoodsRepository::clearTrash();
        return $this->renderData(true);
    }

    public function restoreAction(int $id = 0) {
        GoodsRepository::restoreTrash($id);
        return $this->renderData(true);
    }

    public function attributeAction(int $group_id, int $goods_id = 0) {
        return $this->render(
            GoodsRepository::attributeList($group_id, $goods_id)
        );
    }

    public function searchAction(string $keywords = '',
                                 int $category = 0, int $brand = 0,
                                 int|array $id = 0) {
        return $this->renderPage(GoodsRepository::search($keywords, $category, $brand, $id));
    }

    public function refreshAction() {
        GoodsRepository::sortOut();
        return $this->renderData(true);
    }

    public function cardAction(int $id, string $keywords = '') {
        return $this->renderPage(GoodsRepository::cardList($id, $keywords));
    }

    public function cardGenerateAction(int $id, int $amount = 1) {
        GoodsRepository::cardGenerate($id, $amount);
        return $this->renderData(true);
    }

    public function cardDeleteAction(int $id) {
        GoodsRepository::cardRemove($id);
        return $this->renderData(true);
    }

    public function cardImportAction(int $id) {

    }

    public function cardExportAction(int $id) {

    }


}