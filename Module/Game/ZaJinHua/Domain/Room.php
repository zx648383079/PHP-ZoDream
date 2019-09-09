<?php
namespace Module\Game\ZaJinHua\Domain;



class Room {

    const TYPE_BEGIN = 0;
    const TYPE_WITH = 1; // 跟注
    const TYPE_WATCH = 2;  //看牌
    const TYPE_ADD = 3;   // 加注
    const TYPE_COMPARE = 4;  // 比牌
    const TYPE_ABANDON = 5;  // 弃牌
    const TYPE_END = 6;

    /**
     * @var Banker
     */
    public $banker;

    /**
     * @var Player[]
     */
    public $players = [];

    public $pool = 0;

    public $min = 0;

    public $records = [];

    /**
     * @param Banker $banker
     * @return Room
     */
    public function setBanker(Banker $banker) {
        $this->banker = $banker;
        return $this;
    }

    public function addPlayer(Player $player) {
        $this->players[] = $player;
        return $this;
    }

    public function removePlayer(Player $player) {
        foreach ($this->players as $i => $item) {
            if ($item->eq($player)) {
                unset($this->players[$i]);
                break;
            }
        }
        return $this;
    }

    /**
     * 以多少钱开局
     * @param $money
     * @return bool
     */
    public function begin($money) {
        foreach ($this->players as $player) {
            if ($player->begin($this, $money) === false) {
                $this->removePlayer($player);
            }
        }
        if (empty($this->players)) {
            return false;
        }
        $this->banker->begin($this, $money);
        $this->addRecord($this->banker, self::TYPE_BEGIN, $money, '加入底注');
        return true;
    }

    public function abandon(Player $player) {
        $this->addRecord($player, self::TYPE_ABANDON, 0, '弃牌');
        $this->removePlayer($player);
        if (empty($this->players)) {
            return true;
        }
    }

    public function with(Player $player) {
        $this->addRecord($player, self::TYPE_WITH, $this->min, '跟注');
    }


    public function addRecord(Player $player, $type, $money, $remark) {
        $player = $player->getName();
        $this->records[] = compact('player', 'type', 'money', 'remark');
    }


    public static function createSingle($money) {
        $room = new Room();
        $player = new Player();
        $room->setBanker(new Banker())->addPlayer($player);
        if (!$room->begin($money)) {
            return false;
        }
        return $player;
    }
}