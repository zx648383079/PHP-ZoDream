<?php
declare(strict_types=1);
namespace Module\AdSense\Domain\Weights;

use Module\AdSense\Domain\Repositories\AdRepository;
use Module\Template\Domain\Weights\INode;
use Module\Template\Domain\Weights\Node;

class AdSense extends Node implements INode {

    public function render(string $type = ''): mixed {
        return AdRepository::render((string)$this->attr('code'));
    }
}