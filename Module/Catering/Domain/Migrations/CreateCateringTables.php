<?php
namespace Module\Catering\Domain\Migrations;

use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;
use Module\Catering\Domain\Model\BulletinModel;


class CreateCateringTables extends Migration {

    public function up() {
        $this->append(BulletinModel::tableName(), function(Table $table) {
            $table->set('id')->pk()->ai()->defaultVal('');
            $table->set('title')->varchar(100)->defaultVal('');
            $table->set('content')->varchar(255)->defaultVal('');
            $table->set('type')->tinyint(2)->notNull();
            $table->set('user_id')->int(11)->defaultVal('');
            $table->timestamp('created_at')->notNull();
            $table->timestamp('updated_at')->notNull();
        })->autoUp();
    }

}