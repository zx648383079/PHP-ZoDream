<?php
namespace Module\SEO\Service\Admin;

class CacheController extends Controller {

    public function indexAction() {
        return $this->show();
    }

    public function clearAction($store = null) {
        if (empty($store)) {
            cache()->delete();
        } else {
            foreach ($store as $item) {
                if (empty($item)) {
                    continue;
                }
                if ($item === 'default') {
                    cache()->delete();
                    continue;
                }
                cache()->store($item)->delete();
            }
        }
        return $this->jsonSuccess(null, '清理完成');
    }
}