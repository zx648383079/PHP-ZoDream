<?php
namespace Module\Game\ZaJinHua\Domain;

use Exception;

class Player {

    const STATUS_NONE = 0; // 未开始
    const STATUS_WAITE = 1; // 等待对手
    const STATUS_DO = 2; // 操作
    const STATUS_FAILURE = 3; // 失败
    const STATUS_WINNER = 4; // 胜利

    protected $name = '玩家';

    public $pokers = [];

    public $isWatch = false;

    public $status = self::STATUS_NONE;
    /**
     * @var Player
     */
    public $rival;

    /**
     * @var Room
     */
    public $room;

    public function begin(Room $room, $money) {
        $this->room = $room;
        $room->bet($this, $money);
    }


    public function getName() {
        return $this->name;
    }

    public function eq(Player $player) {
        return $player->getName() === $this->getName();
    }

    public function compare(Player $player) {
        $poker = new Poker();
        return $poker->compare($this->pokers, $player->pokers);
    }

    public function withAction() {
        $this->bet($this->room->min);
        $this->room->with($this);
    }

    public function fillAction($money) {
        $this->bet($money);
        $this->room->fill($this, $money);
    }

    public function bet($money) {
        if ($money < $this->room->min) {
            throw new Exception(sprintf('下注不能低于 %s ', $this->room->min));
        }
        if ($this->isWatch) {
            $money *= 2;
        }
        if ($money > 2000) {
            throw new Exception('您的余额不足');
        }
        $this->room->bet($this, $money);
    }

    public function compareAction() {
        if (count($this->room->all()) < 3) {
            return $this->openAction();
        }
        $this->bet($this, $this->room->min);
        $this->room->compare($this);
    }

    public function openAction() {
        if (count($this->room->all()) > 2) {
            throw new Exception('开牌失败');
        }
        $this->bet($this->room->min * 2);
        $this->room->compare($this);
    }

    public function invoke($action, $money = 0) {
        if ($this->status === self::STATUS_WAITE) {
            return;
        }
        if ($action === 'init') {
            if ($this->status === self::STATUS_DO) {
                return;
            }
            $room = new Room();
            $room->setBanker(new Banker())->addPlayer($this);
            $room->begin($money);
            $this->save();
            return;
        }
        if ($this->status === self::STATUS_WINNER
            || $this->status === self::STATUS_FAILURE
            || $this->status === self::STATUS_NONE
        ) {
            return;
        }
        if ($action === 'fold') {
            $this->room->abandon($this);
            return;
        }
        if ($action === 'check') {
            if ($this->isWatch) {
                return;
            }
            $this->room->watch($this);
            return;
        }
        if ($action === 'call') {
            $this->withAction();
            return;
        }
        if ($action === 'compare') {
            $this->compareAction();
            return;
        }
        if ($action === 'open') {
            $this->openAction();
            return;
        }
        if ($action === 'fill') {
            $this->fillAction($money);
        }

    }


    public function save() {
        session([
            'zjh_player' => $this
        ]);
    }

    public function clear() {
        $this->status = self::STATUS_NONE;
        $this->pokers = [];
        $this->room = null;
        $this->isWatch = false;
        $this->rival = null;
    }

    /**
     * @return mixed|Player
     * @throws \Exception
     */
    public static function load() {
        $key = 'zjh_player';
        $player = session($key);
        if (!empty($player)) {
            return $player;
        }
        $player = new Player();
        return $player;
    }
}