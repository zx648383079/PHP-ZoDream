<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Importers;

interface IImporter {

    /**
     * 判断是否是
     * @param resource $resource
     * @return bool
     */
    public function is($resource): bool;

    /**
     * 读取所有的数据
     * @param resource $resource
     * @return array
     */
    public function read($resource): array;

    /**
     * 边读取边执行
     * @param $resource
     * @param callable $cb
     * @return bool
     */
    public function readCallback($resource, callable $cb): bool;
}