<?php

use Module\Template\Domain\VisualEditor\IVisualStyle;
use Module\Template\Domain\VisualEditor\VisualWeightProperty;

class NoneStyle implements IVisualStyle {

    public function render(VisualWeightProperty $property) {
        $property->pushClass('none-style');
    }
}