<?php
namespace Module\SEO\Service\Admin;

use Module\SEO\Domain\Repositories\SEORepository;

class SqlController extends Controller {

    public function indexAction() {
        $items = SEORepository::sqlFiles();
        return $this->show(compact('items'));
    }

    public function backUpAction() {
        try {
            SEORepository::backUpSql(true);
        } catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess(null, '备份完成');
    }

    public function clearAction() {
        SEORepository::clearSql();
        return $this->jsonSuccess(null, '已删除所有备份');
    }
}