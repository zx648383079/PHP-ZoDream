<?php
namespace Module\SEO\Service\Api;

use Module\SEO\Domain\Repositories\SEORepository;

class SqlController extends Controller {

    public function indexAction() {
        $items = SEORepository::sqlFiles();
        return $this->renderData(compact('items'));
    }

    public function backUpAction() {
        try {
            SEORepository::backUpSql(true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function clearAction() {
        SEORepository::clearSql();
        return $this->renderData(true);
    }
}