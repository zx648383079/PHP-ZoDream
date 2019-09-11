<?php
namespace Module\Game\ZaJinHua\Domain;

class Poker {

    const COLORS = ['♠', '♥', '♣', '♦'];
    const POKERS = ['A', 'K', 'Q', 'J', '10', '9', '8', '7', '6', '5', '4', '3', '2'];

    public function get() {
        $args = [];
        foreach (self::COLORS as $i) {
            foreach (self::POKERS as $j) {
                $args[] = $i . $j;
            }
        }
        shuffle($args);
        return $args;
    }

    public function assign(array $args, $num = 2) {
        $data = [];
        for ($i = 0; $i < $num; $i ++) {
            $data[] = [
                $args[$i],
                $args[$i + $num],
                $args[$i + $num * 2]
            ];
        }
        return $data;
    }

    public function getSingleScore($item) {
        $color = mb_substr($item, 0, 1);
        $poker = mb_substr($item, 1);
        return [4 - array_search($color, self::COLORS), (13 - array_search($poker, self::POKERS))];
    }

    public function getScore(array $pokers) {
        $pokers = array_map(function ($item) {
            list($color, $poker) = $this->getSingleScore($item);
            return [
                'text' => $item,
                'color' => $color,
                'poker' => $poker,
                'score' => $color + $poker * 4
            ];
        }, $pokers);
        usort($pokers, function ($a, $b) {
            if ($a['score'] < $b['score']) {
                return 1;
            }
            return $a['score'] > $b['score'] ? -1 : 0;
        });
        $total = array_sum(array_column($pokers, 'score'));
        if ($pokers[0]['poker'] === $pokers[1]['poker']) {
            if ($pokers[0]['poker'] === $pokers[2]['poker']) {
                // 豹子
                return [
                    'score' => $total + 1000000,
                    'pokers' => $pokers,
                ];
            }
            // 对子
            return [
                'score' => $total + 100,
                'pokers' => $pokers,
            ];
        }
        if ($pokers[1]['poker'] === $pokers[2]['poker']) {
            // 对子
            return [
                'score' => $total + 100,
                'pokers' => $pokers,
            ];
        }
        $is_A23 = $pokers[0]['poker'] === 13 && $pokers[1]['poker'] === 2 && $pokers[2]['poker'] === 1;
        if (($pokers[0]['poker'] - $pokers[1]['poker'] === 1 && $pokers[1]['poker'] - $pokers[2]['poker'] === 1)
            || $is_A23) {
            // A23 第二大
            if ($pokers[0]['color'] === $pokers[1]['color'] && $pokers[1]['color'] === $pokers[2]['color']) {
                // 顺金
                return [
                    'score' => (!$is_A23 ? $total * 4 : ($total - 67 + 577)) + 100000,
                    'pokers' => $pokers,
                ];
            }
            if ($pokers[0]['color'] !== $pokers[1]['color']
                && $pokers[1]['color'] !== $pokers[2]['color']
                && $pokers[0]['color'] !== $pokers[2]['color']) {
                // 顺子
                return [
                    'score' => (!$is_A23 ? $total * 4 : ($total - 67 + 577)) + 1000,
                    'pokers' => $pokers,
                ];
            }
        }
        if ($pokers[0]['color'] === $pokers[1]['color'] && $pokers[1]['color'] === $pokers[2]['color']) {
            // 金花
            return [
                'score' => $total + 10000,
                'pokers' => $pokers,
            ];
        }
        if ($pokers[0]['color'] !== $pokers[1]['color']
            && $pokers[1]['color'] !== $pokers[2]['color']
            && $pokers[0]['color'] !== $pokers[2]['color']
            && $pokers[0]['poker'] === 4 && $pokers[1]['poker'] === 2 && $pokers[2]['poker'] === 1) {
            // 特殊牌
            return [
                'special' => true,
                'score' => $total,
                'pokers' => $pokers,
            ];
        }
        return [
            'score' => $total,
            'pokers' => $pokers,
        ];
    }

    public function getScoreList(array $pokers) {
        $has_bao = false;
        $has_spec = false;
        foreach ($pokers as &$poker) {
            $poker = $this->getScore($poker);
            if ($poker['score'] >= 1000000) {
                $has_bao = true;
                continue;
            }
            if (isset($poker['special'])) {
                $has_spec = true;
            }
        }
        unset($poker);
        if ($has_spec && $has_bao) {
            // 修正特殊牌
            $pokers = array_map(function ($item) {
                if (isset($item['special'])) {
                    $item['score'] += 10000000;
                }
                return $item;
            }, $pokers);
        }
        return $pokers;
    }

    public function getWinner(array $pokers) {
        $pokers = $this->getScoreList($pokers);
        $winner = -1;
        $score = 0;
        for($i = count($pokers) - 1; $i >= 0; $i --) {
            if ($winner < 0 || $pokers[$i]['score'] > $score) {
                $winner = $i;
                $score = $pokers[$i]['score'];
            }
        }
        return $winner;
    }

    public function compare($one, $two) {
        return $this->getWinner([$one, $two]) < 1;
    }
}