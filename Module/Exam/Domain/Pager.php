<?php
declare(strict_types=1);
namespace Module\Exam\Domain;

use Module\Exam\Domain\Model\QuestionModel;
use Exception;
use Zodream\Infrastructure\Contracts\ArrayAble;

class Pager implements ArrayAble {

    private int $id = 0;
    private string $title = '';
    private int $limitTime = 120;
    private array $items = [];
    private int $index = 0;
    public bool $finished = false;

    /**
     * @param array $items
     * @return Pager
     */
    public function setItems(array $items) {
        $this->items = $items;
        return $this;
    }

    /**
     * @param int $id
     * @return Pager
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $title
     * @return Pager
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param int $time
     * @return Pager
     */
    public function setLimitTime(int $time)
    {
        $this->limitTime = $time;
        return $this;
    }

    public function selectedId($id) {
        if (empty($id)) {
            return;
        }
        foreach ($this->items as $i => $item) {
            if ($item['id'] == $id) {
                $this->index = $i;
                return;
            }
        }
    }

    public function count() {
        return count($this->items);
    }

    public function append($id) {
        $this->items[] = compact('id');
        return $this;
    }

    public function answer($id, $answer, $dynamic = null) {
        $this->selectedId($id);
        return $this->judge($answer, $dynamic);
    }

    public function judge($answer, $dynamic = null) {
        $model = $this->getQuestion();
        $right = $model->check($answer, $dynamic) ? 1 : -1;
        $this->merge(compact('answer', 'dynamic', 'right'));
        return $right > 0;
    }

    public function finish() {
        $this->finished = true;
        foreach ($this->items as $i => $item) {
            if (!isset($item['right'])) {
                $this->items[$i]['right'] = -1;
                $this->items[$i]['answer'] = '';
            }
        }
        return $this;
    }

    public function getPage($page = 1, $per_page = 1) {
        if ($page < 1) {
            $page = 1;
        }
        $start = ($page  - 1) * $per_page;
        $items = [];
        $len = count($this->items);
        $end = min($per_page, $len - $start);
        for ($i = 0; $i < $end; $i ++) {
            $start += $i;
            $items[] = $this->format($start);
        }
        $previous = $next = null;
        if ($page > 1) {
            $previous = $page - 1;
        }
        if ($start < $len - 1) {
            $next = $page + 1;
        }
        return compact('items', 'previous', 'next');
    }

    public function getReport() {
        $wrong = $right = 0;
        foreach ($this->items as $item) {
            if (!isset($item['right'])) {
                continue;
            }
            if ($item['right'] > 0) {
                $right ++;
                continue;
            }
            if ($item['right'] < 0) {
                $wrong ++;
                continue;
            }
        }
        $scale = 100;
        if ($wrong > 0 || $right > 0) {
            $scale = round($right * 100 / ($wrong + $right), 2);
        }
        return compact('wrong', 'right', 'scale');
    }

    public function getCard() {
        $data = [];
        foreach ($this->items as $i => $item) {
            $data[] = [
                'order' => $i + 1,
                'id' => $item['id'],
                'right' => $item['right'] ?? 0
            ];
        }
        return $data;
    }

    private function format(int $i = -1) {
        if ($i < 0) {
            $i = $this->index;
        }
        if (!isset($this->items[$i])) {
            throw new Exception('题目错误');
        }
        $model = $this->getQuestion($i);
        $data = $model->format($i + 1,
            $this->items[$i]['dynamic'] ?? null,
            $this->finished);
        foreach (['right', 'answer' => 'your_answer'] as $key => $name) {
            if (is_integer($key)) {
                $key = $name;
            }
            if (isset($this->items[$i][$key])) {
                $data[$name] = $this->items[$i][$key];
            }
        }
        return $data;
    }

    private function merge(array $data, $i = -1) {
        if ($i < 0) {
            $i = $this->index;
        }
        if (!isset($this->items[$i])) {
            throw new Exception('题目错误');
        }
        $this->items[$i] = array_merge($this->items[$i], $data);
        return $this;
    }

    /**
     * @param int $i
     * @return bool|QuestionModel
     * @throws \Exception
     */
    private function getQuestion(int $i = -1) {
        if ($i < 0) {
            $i = $this->index;
        }
        if (!isset($this->items[$i])) {
            throw new Exception('题目错误');
        }
        if (isset($this->items[$i]['model'])) {
            return $this->items[$i]['model'];
        }
        return $this->items[$i]['model'] = QuestionModel::find($this->items[$i]['id']);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     * @throws Exception
     */
    public function toArray() {
        $items = [];
        for ($i = 0; $i < count($this->items); $i ++) {
            $items[] = $this->format($i);
        }
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'time' => $this->limitTime,
            'finished' => $this->finished,
            'data' => $items
        ];
        if ($this->finished) {
            $data['report'] = $this->getReport();
        }
        return $data;
    }

    public function __sleep() {
        foreach ($this->items as $i => $item) {
            unset($this->items[$i]['model']);
        }
        return ['id', 'title', 'limitTime', 'items', 'index', 'finished'];
    }

    public static function create(int $course, int $type = 0) {
        if ($type < 2) {
            return static::createId(QuestionModel::where('course_id', $course)
                ->orderBy('id', 'asc')->pluck('id'), $type > 0)
                ->setTitle($type < 1 ? '顺序练习' : '随机练习');
        }
        if ($type === 3) {
            return static::createId(QuestionModel::where('course_id', $course)
                ->where('easiness', '>', 5)
                ->orderBy('id', 'asc')->pluck('id'), true)
                ->setTitle('难题练习');
        }
        $rules = [];
        foreach ([10, 5, 5, 3, 2] as $i => $amount) {
            $rules[] = [
                'course' => $course,
                'type' => $i,
                'amount' => $amount
            ];
        }
        return static::createId(static::questionByRule($rules), false)
            ->setTitle('全真模拟');
    }

    public static function questionByRule(array $data) {
        $items = [];
        foreach ($data as $item) {
            if ($item['amount'] < 1) {
                continue;
            }
            $args = QuestionModel::query()
                ->where('course_id', $item['course'])
                ->where('type', $item['type'])
                ->whereNotIn('id', $items)
                ->orderBy('RAND()')
                ->limit($item['amount'])
                ->pluck('id');
            $items = array_merge($items, $args);
        }
        return $items;
    }

    public static function createId(array $items, $shuffle = false) {
        if ($shuffle) {
            shuffle($items);
        }
        return (new static())->setItems(array_map(function ($id) {
            return compact('id');
        }, $items));
    }

    public static function formatQuestion(QuestionModel $model,
                                          $answer,
                                          $dynamic = null) {
        $data = $model->format(null,
            $dynamic,
            true);
        $data['your_answer'] = $answer;
        $data['right'] = $model->check($answer, $dynamic) ? 1 : -1;
        return $data;
    }
}
