<?php
namespace Module\Auth\Domain\Entities\Concerns;


trait UserTrait {

    public static function tableName() {
        return 'user';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'email' => 'required|string:0,200',
            'password' => 'required|string:0,100',
            'sex' => 'int:0,9',
            'avatar' => 'string:0,255',
            'money' => 'int',
            'parent_id' => 'int',
            'token' => 'string:0,60',
            'status' => 'int:0,99',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '昵称',
            'email' => '邮箱',
            'password' => '密码',
            'sex' => '性别',
            'avatar' => '头像',
            'money' => '金币',
            'parent_id' => '邀请人',
            'token' => 'Token',
            'status' => '状态',
            'created_at' => '注册时间',
            'updated_at' => '更新时间',
        ];
    }
}