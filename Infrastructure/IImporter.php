<?php
declare(strict_types=1);
namespace Infrastructure;

interface IImporter {

    /**
     * 判断是否是
     * @param string $fileName 文件的真实名字带后缀
     * @return bool 
     */
    public function open(string $fileName): bool;
    public function close(): void;

    /**
     * 读取所有的数据
     * @param resource $resource
     * @return array
     */
    public function readToEnd(): array;

    /**
     * 边读取边执行
     * @param $resource
     * @param callable $cb
     * @return bool
     */
    public function readCallback(callable $cb): bool;
}