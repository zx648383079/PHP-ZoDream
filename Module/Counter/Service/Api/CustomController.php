<?php
declare(strict_types=1);
namespace Module\Counter\Service\Api;

class CustomController extends Controller {
    public function indexAction() {
        return $this->renderData([]);
    }

    public function pageClickAction() {
        return $this->renderData([]);
    }
}