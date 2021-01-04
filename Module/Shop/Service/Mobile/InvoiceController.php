<?php
namespace Module\Shop\Service\Mobile;


use Module\Shop\Domain\Models\InvoiceTitleModel;

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
        return $this->show('edit', compact('model'));
    }

    public function createAction() {
        return $this->editAction(0);
    }
}