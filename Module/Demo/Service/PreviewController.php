<?php
namespace Module\Demo\Service;

class PreviewController extends Controller {
    public function indexAction($page = null) {
        return $this->show($page);
    }
}