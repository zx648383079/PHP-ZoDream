<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Api\Member;

use Module\ResourceStore\Domain\Repositories\ResourceRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ResourceController extends Controller {

    public function indexAction(string $keywords = '', int $category = 0) {
        return $this->renderPage(
            ResourceRepository::selfList($keywords, $category)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ResourceRepository::selfEdit($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'title' => 'required|string:0,200',
                'description' => 'string:0,255',
                'keywords' => 'string:0,255',
                'thumb' => 'string:0,255',
                'content' => 'required',
                'size' => 'int',
                'preview_type' => 'int:0,127',
                'preview_file' => '',
                'cat_id' => 'required|int',
                'price' => 'int',
                'is_commercial' => 'int:0,127',
                'is_reprint' => 'int:0,127',
            ]);
            return $this->render(
                ResourceRepository::save($data, $input->get('tags', []), $input->get('files', []))
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            ResourceRepository::selfRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}