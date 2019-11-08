<?php
namespace Module\Exam\Domain\Model;


use Module\Exam\Domain\Entities\QuestionEntity;
use Module\Exam\Domain\Model\CourseModel;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;

class QuestionModel extends QuestionEntity {

    public static $type_list = ['单选题', '多选题', '判断题', '简答题', '填空题'];


    public function course() {
        return $this->hasOne(CourseModel::class, 'id', 'course_id');
    }

    public function check($answer, $dynamic = null) {
    }

    public function format($order = null) {
        $dynamicItems = $this->generateDynamic();
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
        if ($this->type < 2) {
            $option_list = QuestionOptionModel::where('question_id', $this->id)
                ->orderBy('id', 'asc')->get();
            shuffle($option_list);
            $i = 0;
            foreach ($option_list as $item) {
                $i ++;
                $data['option'][] = [
                    'id' => $item['id'],
                    'content' => $item['type'] > 0 ? $item['content'] :
                        self::strReplace($item['content'], $dynamicItems),
                    'order' => self::intToChr($i)
                ];
            }
        }
        return $data;
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