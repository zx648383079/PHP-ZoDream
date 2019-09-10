<?php
namespace Module\Game\ZaJinHua\Domain;



use IteratorAggregate;
use ArrayIterator;

class Room implements IteratorAggregate {

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
        foreach ($this->all() as $player) {
            if ($player->begin($this, $money) === false) {
                $this->removePlayer($player);
            }
        }
        if (empty($this->players)) {
            return false;
        }
        $this->addRecord($this->banker, self::TYPE_BEGIN, $money, '加入底注');
        $this->min = $money;
        $this->deal();
        $this->players[0]->status = Player::STATUS_DO;
        return true;
    }

    /**
     * 发牌
     */
    public function deal() {
        $poker = new Poker();
        $args = $poker->assign($poker->get(), count($this->players) + 1);
        foreach ($this->all() as $i => $item) {
            $item->pokers = $args[$i];
        }
    }

    public function abandon(Player $player) {
        $this->addRecord($player, self::TYPE_ABANDON, 0, '弃牌');
        $player->status = Player::STATUS_FAILURE;
        //$next = $this->getNextPlayer($player);
        $this->removePlayer($player);
        if (!empty($this->players)) {
            return true;
        }
        //$this->winner();
    }

    public function watch(Player $player) {
        $player->isWatch = true;
        $this->addRecord($player, self::TYPE_WATCH, 0, '看牌');
    }

    public function with(Player $player) {
        $this->addRecord($player, self::TYPE_WITH, $this->min, '跟注');
        if ($player->eq($this->banker)) {
            return;
        }
        $this->banker->with();
    }

    public function fill(Player $player, $money) {
        $this->addRecord($player, self::TYPE_ADD, $money, '加注');
        $this->min = $money;
        if ($player->eq($this->banker)) {
            return;
        }
        $this->banker->with();
    }

    public function compare(Player $player, Player $other = null) {
        if (!$other) {
            $other = $this->banker;
        }
        if (!$player->compare($other)) {
            $player->status = Player::STATUS_FAILURE;
            $this->addRecord($player, self::TYPE_COMPARE, 0,
                sprintf('与 【%s】 比牌失败', $other->getName()));
            return;
        }
        $player->status = Player::STATUS_WINNER;
        $this->addRecord($player, self::TYPE_COMPARE, 0,
            sprintf('与 【%s】 比牌胜利', $other->getName()));
    }

    public function bet(Player $player, $money) {
        $this->pool += $money;
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

    /**
     * @return Player[]
     */
    public function all() {
        $items = $this->players;
        $items[] = $this->banker;
        return $items;
    }

    /**
     * @return ArrayIterator|\Traversable
     */
    public function getIterator() {
        return new ArrayIterator($this->all());
    }
}