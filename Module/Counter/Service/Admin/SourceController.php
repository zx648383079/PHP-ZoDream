<?php
namespace Module\Counter\Service\Admin;

class SourceController extends Controller {
    public function indexAction() {
        return $this->show();
    }
}