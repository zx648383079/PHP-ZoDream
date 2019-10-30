<?php
namespace Module\Task\Service;

class RecordController extends Controller {

    public function indexAction($type) {
        return $this->show();
    }
}