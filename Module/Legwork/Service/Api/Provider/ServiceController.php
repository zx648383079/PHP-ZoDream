<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Api\Provider;

use Module\Legwork\Domain\Repositories\ProviderRepository;
use Module\Legwork\Domain\Repositories\ServiceRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ServiceController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            ServiceRepository::getSelfList($keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(ServiceRepository::getSelf($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'name' => 'required|string:0,100',
                'cat_id' => 'int',
                'thumb' => 'string:0,200',
                'brief' => 'string:0,255',
                'price' => '',
                'content' => 'required',
                'form' => '',
                'region' => '',
            ]);
            return $this->render(
                ServiceRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            ServiceRepository::removeSelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function categoryAction(string $keywords = '', int $category = 0, int $status = 0, bool $all = false) {
        return $this->renderPage(
            ProviderRepository::categoryList($keywords, $category, $status, $all)
        );
    }

    public function waiterAction(int $id, string $keywords = '', int $status = 0) {
        return $this->renderPage(
            ServiceRepository::waiterList($id, $keywords, $status)
        );
    }

    public function waiterChangeAction(int $id, int|array $user_id, int $status = 0) {
        try {
            ServiceRepository::waiterChange($id, $user_id, $status);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function waiterAddAction(int $id, int|array $user_id, int $status = 0) {
        try {
            ServiceRepository::waiterAdd($id, $user_id, $status);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function waiterDeleteAction(int $id, int|array $user_id) {
        try {
            ServiceRepository::waiterRemove($id, $user_id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}