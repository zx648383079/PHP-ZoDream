<?php
namespace Module\Finance\Domain\Repositories;

use Module\Finance\Domain\Model\BudgetModel;

class BudgetRepository {

    public static function getList() {
        return BudgetModel::auth()->where('deleted_at', 0)->orderBy('id', 'desc')->page();
    }

    public static function refreshSpent() {
        $items = BudgetModel::auth()->get();
        foreach ($items as $item) {
            $item->refreshSpent();
        }

    }
}