<?php
namespace Module\Shop\Service\Mobile;


use Module\Shop\Domain\Model\InvoiceTitleModel;

class InvoiceController extends Controller {

    public function indexAction() {
        return $this->show();
    }

    public function titleAction() {
        return $this->show();
    }

    public function logAction() {
        return $this->show();
    }

    public function applyAction() {
        return $this->show();
    }

    public function editAction($id = 0) {
        $model = new InvoiceTitleModel();
        return $this->show(compact('model'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => 0]);
    }
}