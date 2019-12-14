<?php
namespace Module\Counter\Service\Admin;

class VisitController extends Controller {
    public function indexAction() {
        return $this->show();
    }
}