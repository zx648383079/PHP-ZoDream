<?php
namespace Module\Demo\Service;

class PreviewController extends Controller {
    public function indexAction($id) {
        return $this->show();
    }

    public function viewAction($id) {
        $this->layout = false;
    }
}