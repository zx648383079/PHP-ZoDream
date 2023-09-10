<?php
declare(strict_types=1);
namespace Module\Plugin\Domain;


interface IPlugin {

    /**
     * 初始化
     * @return void
     */
    public function initiate(): void;

    /**
     * 结束
     * @return void
     */
    public function destroy(): void;

    /**
     * 执行
     * @param array $configs
     * @return void
     */
    public function __invoke(array $configs = []): void;
}