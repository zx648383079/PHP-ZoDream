<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Repositories\Admin\GoodsRepository;
use Module\Shop\Domain\Repositories\IssueRepository;
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

    public function cardAction(int $goods, string $keywords = '') {
        return $this->renderPage(GoodsRepository::cardList($goods, $keywords));
    }

    public function cardGenerateAction(int $goods, int $amount = 1) {
        GoodsRepository::cardGenerate($goods, $amount);
        return $this->renderData(true);
    }

    public function cardDeleteAction(int $id) {
        try {
            GoodsRepository::cardRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function cardImportAction(int $goods) {

    }

    public function cardExportAction(int $goods) {

    }

    public function previewAction(int $id) {
        try {
            $data = GoodsRepository::preview($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($data);
    }

    public function issueAction(int $goods = 0, string $keywords = '') {
        return $this->renderPage(IssueRepository::manageList($goods, $keywords));
    }

    public function issueSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'goods_id' => 'required|int',
                'question' => 'required|string:0,255',
                'answer' => 'string:0,255',
                'status' => 'int:0,127',
            ]);
            return $this->render(IssueRepository::manageSave($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function issueDeleteAction(array|int $id) {
        try {
            IssueRepository::manageRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function crawlAction(Input $input) {
        try {
            $data = $input->validate([
                'cat_id' => 'required|int',
                'brand_id' => 'required|int',
                'name' => 'required|string:0,100',
                'series_number' => 'required|string:0,100',
                'keywords' => 'string:0,200',
                'thumb' => 'string:0,200',
                'picture' => 'required|string:0,200',
                'description' => 'string:0,200',
                'brief' => 'string:0,200',
                'content' => 'required',
                'price' => '',
                'market_price' => '',
                'stock' => 'int',
                'attribute_group_id' => 'int',
                'weight' => '',
                'shipping_id' => 'int',
                'sales' => 'int',
                'type' => 'int:0,99',
                'attr' => '',
                'products' => '',
                'gallery' => '',
            ]);
            return $this->render(GoodsRepository::crawlSave($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}