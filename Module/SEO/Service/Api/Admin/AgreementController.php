<?php
declare(strict_types=1);
namespace Module\SEO\Service\Api\Admin;

use Module\SEO\Domain\Repositories\AgreementRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class AgreementController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            AgreementRepository::getList($keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                AgreementRepository::detail($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,50',
                'language' => '',
                'title' => 'required|string:0,100',
                'description' => 'string',
                'content' => 'required',
                'status' => 'int:0,127',
            ]);
            return $this->render(
                AgreementRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
    }

    public function deleteAction(int $id) {
        try {
            AgreementRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}