<?php
$query = new \Zodream\Infrastructure\Database\Query();
$query->select('*')->from('a')->where('b')->andWhere('c');
$query->addParam(array(
    ':a' => 'b'
)); //添加绑定值

$query->getSql();   //获取sql语句
$query->all();      //获取所有结果集
$query->one();      //获取第一条结果
$query->scalar();   //获取第一行第一列的值