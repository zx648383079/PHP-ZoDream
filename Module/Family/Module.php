<?php
/*
 * @Author: zodream
 * @Date: 2020-02-02 12:02:28
 * @LastEditors: zodream
 * @LastEditTime: 2020-09-08 22:35:40
 */
namespace Module\Family;

use Zodream\Route\Controller\Module as BaseModule;
use Module\Family\Domain\Migrations\CreateFamilyTables;
/**
 * TODO 前台页面显示、ar化、人脸识别、关系称呼查询
 */
class Module extends BaseModule {

    public function getMigration() {
        return new CreateFamilyTables();
    }

}