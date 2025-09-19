<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Entities\Concerns;


trait UserTrait {

    public static function tableName(): string {
        return 'user';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'email' => 'required|string:0,200',
            'mobile' => 'string:0,20',
            'password' => 'required|string:0,100',
            'sex' => 'int:0,127',
            'avatar' => 'string:0,255',
            'birthday' => 'string',
            'money' => 'int',
            'credits' => 'int',
            'parent_id' => 'int',
            'token' => 'string:0,60',
            'status' => 'int:0,127',
            'activated_at' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }


    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '昵称',
            'email' => '邮箱',
            'mobile' => '手机号',
            'password' => '密码',
            'sex' => '性别',
            'avatar' => '头像',
            'money' => '金币',
            'parent_id' => '邀请人',
            'birthday' => '生日',
            'token' => 'Token',
            'status' => '状态',
            'activated_at' => '最后活跃时间',
            'created_at' => '注册时间',
            'updated_at' => '更新时间',
        ];
    }
}