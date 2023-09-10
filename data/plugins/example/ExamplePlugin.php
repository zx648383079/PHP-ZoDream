<?php
declare(strict_types=1);
namespace ZoDream\Example;

use Module\Plugin\Domain\IPlugin;

final class ExamplePlugin implements IPlugin {

    public function initiate(): void {

    }

    public function destroy(): void {

    }

    public function __invoke(array $configs = []): void {
        echo sprintf('Example is done: [%s]%s', $configs['tel'] ?? '', $configs['name'] ?? '');
    }
}