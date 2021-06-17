<?php
namespace Module\Exam\Domain\Model;


use Module\Exam\Domain\Entities\QuestionEntity;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;

/**
 * Class QuestionModel
 * @package Module\Exam\Domain\Model
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property integer $course_id
 * @property integer $parent_id
 * @property integer $type
 * @property integer $easiness
 * @property string $content
 * @property string  $dynamic
 * @property string $answer
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $material_id
 */
class QuestionModel extends QuestionEntity {

    public static array $type_list = ['单选题', '多选题', '判断题', '简答题', '填空题'];


    public function course() {
        return $this->hasOne(CourseModel::class, 'id', 'course_id');
    }

    public function material() {
        return $this->hasOne(CourseModel::class, 'id', 'material_id');
    }

    public function optionItems() {
        return $this->hasMany(QuestionOptionModel::class, 'id', 'question_id')->orderBy('id', 'asc');
    }

    public function analysisItems() {
        return $this->hasMany(QuestionAnalysisModel::class, 'id', 'question_id')->orderBy('type', 'asc');
    }

    public function check($answer, $dynamic = null) {
        if (!is_array($dynamic)) {
            $dynamic = empty($dynamic) ? [] : Json::decode(base64_decode($dynamic));
        }
        if ($this->type < 1) {
            return $this->checkType0($answer);
        }
        if ($this->type == 1) {
            return $this->checkType1($answer);
        }
        if ($this->type == 2) {
            return $this->checkType2($answer);
        }
        if ($this->type == 3) {
            return $this->checkType3($answer, $dynamic);
        }
        if ($this->type == 4) {
            return $this->checkType4($answer, $dynamic);
        }
        return false;
    }

    private function checkType4($answer, array $dynamic) {
        $answer = (array)$answer;
        foreach (explode("\n", $this->answer) as $i => $line) {
            $line = trim($line);
            if (substr($line, 0, 1) === '=') {
                $line = self::compilerValue(substr($line, 1), $dynamic);
            }
            if (!isset($answer[$i]) || $answer[$i] !== $line) {
                return false;
            }
        }
        return true;
    }

    private function checkType3($answer, array $dynamic) {
        // 完全对比有问题
        return self::strReplace($this->answer, $dynamic) === $answer;
    }

    private function checkType2($answer) {
        return intval($this->answer) === intval($answer);
    }

    private function checkType1($answer) {
        $answer = (array)$answer;
        $items = QuestionOptionModel::where('question_id', $this->id)->where('is_right', 1)->pluck('id');
        return count($answer) === count($items) && count(array_diff($answer, $items)) === 0;
    }

    private function checkType0($answer) {
        return QuestionOptionModel::where('question_id', $this->id)
            ->where('id', intval($answer))->where('is_right', 1)->count() === 1;
    }

    public function format($order = null, $dynamicItems = null, $hasAnswer = false) {
        if (empty($dynamicItems)) {
            $dynamicItems = $this->generateDynamic();
        } elseif (is_string($dynamicItems)) {
            $dynamicItems = Json::decode(base64_decode($dynamicItems));
        }
        $data = [
            'order' => empty($order) ? $this->id : $order,
            'id' => $this->id,
            'title' => self::strReplace($this->title, $dynamicItems),
            'image' => $this->image,
            'content' => self::strReplace($this->content, $dynamicItems),
            'dynamic' => empty($dynamicItems) ?
                '' : base64_encode(Json::encode($dynamicItems)),
            'type' => intval($this->type),
        ];
        if ($this->material_id > 0 && $this->material) {
            $data['material'] = $this->material;
        }
        if ($hasAnswer) {
            $data['answer'] = $this->type == 4
                ? $this->getType4Answer($dynamicItems)
                : self::strReplace($this->answer, $dynamicItems);
            $data['analysis'] = self::strReplace(QuestionAnalysisModel::where('question_id', $this->id)->where('type', 0)
                ->value('content'), $dynamicItems);
        }
        if ($this->type < 2) {
            $option_list = QuestionOptionModel::where('question_id', $this->id)
                ->orderBy('id', 'asc')->get();
            shuffle($option_list);
            $i = 0;
            foreach ($option_list as $item) {
                $i ++;
                $option = [
                    'id' => $item['id'],
                    'content' => $item['type'] > 0 ? $item['content'] :
                        self::strReplace($item['content'], $dynamicItems),
                    'order' => self::intToChr($i)
                ];
                if ($hasAnswer) {
                    $option['is_right'] = $item->is_right > 0;
                }
                $data['option'][] = $option;
            }
        }
        return $data;
    }

    private function getType4Answer($dynamic) {
        $items = [];
        foreach ($this->optionItems() as $item) {
            $line = trim($item->content);
            if (substr($line, 0, 1) === '=') {
                $line = self::compilerValue(substr($line, 1), $dynamic);
            }
            $items[] = $line;
        }
        return $items;
    }

    public function generateDynamic() {
        $arg = $this->dynamic;
        if (empty($arg)) {
            return [];
        }
        $data = [];
        foreach (explode("\n", $arg) as $line) {
            $items = explode('=', trim($line), 2);
            if (count($items) < 2) {
                continue;
            }
            $data[$items[0]] = self::compilerValue($items[1], $data);
        }
        return $data;
    }

    public static function strReplace($val, array $data) {
        if (empty($data) || empty($val)) {
            return $val;
        }
        return strtr($val, $data);
    }

    public static function compilerValue($str, $data) {
        if (is_numeric($str)) {
            return $str;
        }
        if (preg_match('/^(.+?)([\>\<\=\!]{1,3})(.+?)\?(.+?):(.+?)$/', $str, $match)) {
            $match[1] = trim($match[1]);
            $match[3] = trim($match[3]);
            if (isset($data[$match[1]])) {
                $match[1] = $data[$match[1]];
            }
            if (isset($data[$match[3]])) {
                $match[3] = $data[$match[3]];
            }
            return self::compilerCon($match[1], $match[2], $match[3])
                ? self::compilerValue($match[4], $data) : self::compilerValue($match[5], $str);
        }
        if (preg_match('/^(.+?)([\+\-*\/]{1,3})(.+?)$/', $str, $match)) {
            $match[1] = trim($match[1]);
            $match[3] = trim($match[3]);
            if (isset($data[$match[1]])) {
                $match[1] = $data[$match[1]];
            }
            if (isset($data[$match[3]])) {
                $match[3] = $data[$match[3]];
            }
            return self::compilerCon($match[1], $match[2], $match[3]);
        }
        if (strpos($str, '...') > 0) {
            return Str::randomInt(...explode('...', $str));
        }
        $items = explode(',', $str);
        if (count($items) === 1) {
            return trim($str);
        }
        return trim($items[Str::randomInt(0, count($items) - 1)]);
    }

    public static function compilerCon($arg, $con, $val) {
        if ($con === '>') {
            return $arg > $val;
        }
        if ($con === '>=') {
            return $arg >= $val;
        }
        if ($con === '<') {
            return $arg < $val;
        }
        if ($con === '<=') {
            return $arg < $val;
        }
        if ($con === '<>' || $con === '!=') {
            return $arg != $val;
        }
        if ($con === '==' || $con === '===') {
            return $arg == $val;
        }
        if ($con === '+') {
            return $arg + $val;
        }
        if ($con === '-') {
            return $arg - $val;
        }
        if ($con === '*') {
            return $arg * $val;
        }
        if ($con === '/') {
            return $arg / $val;
        }
        return false;
    }

    public static function intToChr($int) {
        $str = '';
        if ($int > 26) {
            $str .= self::intToChr(floor($int / 26));
        }
        return $str . chr($int % 26 + 64);
    }
}