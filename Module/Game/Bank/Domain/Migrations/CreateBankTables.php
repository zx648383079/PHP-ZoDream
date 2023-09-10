<?php
namespace Module\Game\Bank\Domain\Migrations;


use Module\Game\Bank\Domain\Model\BankLogModel;
use Module\Game\Bank\Domain\Model\BankProductModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateBankTables extends Migration {
    public function up(): void {
        $this->append(BankProductModel::tableName(), function (Table $table) {
            $table->comment('理财产品表');
            $table->id();
            $table->column('name')->varchar()->comment('产品名');
            $table->column('min_amount')
                ->short(4)->unsigned()->default(0)->comment('门槛');
            $table->column('cycle')->short(4)
                ->unsigned()
                ->default(0)->comment('周期/天');
            $table->column('earnings')->int()
                ->unsigned()->default(0)->comment('收益率/10000');
            $table->column('risk')->int()
                ->unsigned()->default(0)->comment('风险系数/10000');
        })->append(BankLogModel::tableName(), function (Table $table) {
            $table->comment('投资记录表');
            $table->id();
            $table->uint('user_id');
            $table->uint('product_id');
            $table->uint('money')->comment('金额');
            $table->uint('real_money')->comment('实际金额');
            $table->timestamp('end_at')->comment('到期时间');
            $table->uint('earnings')->default(0)->comment('收益率/10000');
            $table->uint('status', 2)->uint()->default(0)->comment('状态');
            $table->timestamps();
        })->autoUp();
    }
}