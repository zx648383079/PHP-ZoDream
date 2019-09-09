<?php
namespace Module\Game\ZaJinHua\Domain;

class Player {

    protected $name = '玩家';

    public $pokers = [];

    public $isWatch = false;
    /**
     * @var Room
     */
    protected $room;

    public function begin(Room $room, $money) {
        $this->room = $room;
    }



    public function getName() {
        return $this->name;
    }

    public function eq(Player $player) {
        return $player->getName() === $this->getName();
    }

    public static function load() {

    }
}