<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Repositories\Admin\BrandRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class BrandController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(BrandRepository::getList($keywords));
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                BrandRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,100',
                'keywords' => 'string:0,200',
                'description' => 'string:0,200',
                'logo' => 'string:0,200',
                'app_logo' => 'string:0,200',
                'url' => 'string:0,200',
            ]);
            return $this->render(
                BrandRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            BrandRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function refreshAction() {
        BrandRepository::refresh();
        return $this->renderData(true);
    }

    public function allAction() {
        return $this->renderData(
            BrandRepository::all()
        );
    }

    public function searchAction(string $keywords = '', int|array $id = 0) {
        return $this->renderData(BrandRepository::search($keywords, $id));
    }
}