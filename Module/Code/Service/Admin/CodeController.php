<?php
declare(strict_types=1);
namespace Module\Code\Service\Admin;

use Module\Code\Domain\Model\CodeModel;
use Module\Code\Domain\Repositories\CodeRepository;

class CodeController extends Controller {

    public function indexAction(string $keywords = '') {
        $model_list  = CodeModel::with('user')
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $ids = CodeRepository::tag()->searchTag($keywords);
                if (empty($ids)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('id', $ids);
            })->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function deleteAction(int $id) {
        CodeRepository::delete($id);
        return $this->renderData([
            'url' => $this->getUrl('code')
        ]);
    }
}