<?php
namespace Module\SEO\Service\Admin;

class CacheController extends Controller {

    public function indexAction() {
        return $this->show();
    }

    public function clearAction() {
        cache()->delete();
        return $this->jsonSuccess(null, '清理完成');
    }
}