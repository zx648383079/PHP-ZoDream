<?php
declare(strict_types=1);
namespace Module\Template\Domain\Weights;


interface INode {

    /**
     * @return bool 是否支持嵌套
     */
    public function isNest(): bool;

    /**
     * 是否未全局
     * @return bool
     */
    public function isGlobe(): bool;

    /**
     * @param mixed $key
     * @param mixed $value
     * @return static|mixed
     */
    public function attr(mixed $key, mixed $value = null): mixed;

    public function render(string $type = ''): mixed;
}