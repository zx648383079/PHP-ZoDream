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
            $table->setComment('留言');
            $table->set('id')->pk(true);
            $table->set('name')->varchar(20)->notNull();
            $table->set('email')->varchar(50)->defaultVal('');
            $table->set('phone')->varchar(30)->defaultVal('');
            $table->set('content')->varchar()->defaultVal('');
            $table->set('status')->bool()->defaultVal(0);
            $table->timestamps();
        })->append(FriendLinkModel::tableName(), function (Table $table) {
            $table->setComment('友情链接');
            $table->set('id')->pk(true);
            $table->set('name')->varchar(20)->notNull();
            $table->set('url')->varchar(50)->notNull();
            $table->set('logo')->varchar(200)->defaultVal('');
            $table->set('brief')->varchar()->defaultVal('');
            $table->set('email')->varchar(100)->defaultVal('');
            $table->set('status')->bool()->defaultVal(0);
            $table->timestamps();
        })->append(SubscribeModel::tableName(), function (Table $table) {
            $table->setComment('邮箱订阅');
            $table->set('id')->pk(true);
            $table->set('email')->varchar(50)->notNull();
            $table->set('status')->bool()->defaultVal(0);
            $table->timestamps();
        });
        parent::up();
    }

}