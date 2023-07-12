<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Repositories;

use Module\Game\GameMaker\Domain\Adapters\GameAdapter;
use Module\Game\GameMaker\Domain\Adapters\IGameAdapter;
use Module\Game\GameMaker\Domain\Entities\CharacterEntity;
use Module\Game\GameMaker\Domain\Entities\ProjectEntity;

final class GameRepository {

    public static function enter(int $project, int $character = 0): IGameAdapter {
        $model = ProjectEntity::where('id', $project)->where('status', 1)
            ->first();
        if (empty($model)) {
            throw new \Exception('Game is error');
        }
        $account = null;
        if ($character > 0) {
            $account = CharacterEntity::where('project_id', $project)
                ->where('id', $character)
                ->where('user_id', auth()->id())->first();
            if (empty($account)) {
                throw new \Exception('Character is error');
            }
        }
        return new GameAdapter($model, $account);
    }

    public static function execute(IGameAdapter $store, string $command, mixed $data = []): array {
        if (empty($command)) {
            return [
                'command' => $command,
                'message' => 'command is empty',
            ];
        }
        if (!is_array($data)) {
            $data = [];
        }
        try {
            $res = $store->execute($command, $data);
             return is_array($res) && isset($res['command']) ? $res : [
                 'command' => $command,
                 'data' => $res
             ];
        } catch (\Exception $ex) {
            return [
                'command' => $command,
                'message' => $ex->getMessage()
            ];
        }
    }

}