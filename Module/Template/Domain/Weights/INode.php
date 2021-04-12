<?php
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
     * @param $key
     * @param null $value
     * @return static|mixed
     */
    public function attr($key, $value = null);

    public function render($type = null);
}