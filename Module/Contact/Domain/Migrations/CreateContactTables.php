<?php
namespace Module\Contact\Domain\Migrations;

use Module\Contact\Domain\Model\FeedbackModel;
use Module\Contact\Domain\Model\FriendLinkModel;
use Module\Contact\Domain\Model\SubscribeModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;


class CreateContactTables extends Migration {

    public function up() {
        $this->append(FeedbackModel::tableName(), function (Table $table) {
            $table->comment('留言');
            $table->id();
            $table->column('name')->varchar(20);
            $table->column('email')->varchar(50)->default('');
            $table->column('phone')->varchar(30)->default('');
            $table->column('content')->varchar()->default('');
            $table->column('status')->bool()->default(0);
            $table->timestamps();
        })->append(FriendLinkModel::tableName(), function (Table $table) {
            $table->comment('友情链接');
            $table->id();
            $table->column('name')->varchar(20);
            $table->column('url')->varchar(50);
            $table->column('logo')->varchar(200)->default('');
            $table->column('brief')->varchar()->default('');
            $table->column('email')->varchar(100)->default('');
            $table->column('status')->bool()->default(0);
            $table->timestamps();
        })->append(SubscribeModel::tableName(), function (Table $table) {
            $table->comment('邮箱订阅');
            $table->id();
            $table->column('email')->varchar(50);
            $table->column('status')->bool()->default(0);
            $table->timestamps();
        });
        parent::up();
    }

}