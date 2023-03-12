<?php
declare(strict_types=1);
namespace Module\Template\Domain\VisualEditor;


interface IVisualStyle {

    public function render(VisualWeightProperty $property);
}