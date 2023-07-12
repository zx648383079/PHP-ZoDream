<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Adapters;

use Module\Game\GameMaker\Domain\Entities\CharacterEntity;
use Module\Game\GameMaker\Domain\Entities\ProjectEntity;

interface IGameAdapter {
    public function __construct(ProjectEntity $project, ?CharacterEntity $character);
    public function execute(string $command, array $data): mixed;
}