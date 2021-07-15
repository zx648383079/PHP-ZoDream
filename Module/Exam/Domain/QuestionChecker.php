<?php
declare(strict_types=1);
namespace Module\Exam\Domain;

use Module\Exam\Domain\Entities\QuestionEntity;
use Module\Exam\Domain\Model\QuestionOptionModel;
use Zodream\Helpers\Json;

class QuestionChecker {
    public static function check(QuestionEntity $question, $answer, $dynamic = null): float {
        if (!is_array($dynamic)) {
            $dynamic = empty($dynamic) ? [] : Json::decode(base64_decode($dynamic));
        }
        if ($question->type < 1) {
            return self::checkType0($question, $answer);
        }
        if ($question->type == 1) {
            return self::checkType1($question, $answer);
        }
        if ($question->type == 2) {
            return self::checkType2($question, $answer);
        }
        if ($question->type == 3) {
            return self::checkType3($question, $answer, $dynamic);
        }
        if ($question->type == 4) {
            return self::checkType4($question, $answer, $dynamic);
        }
        return 0;
    }

    private static function checkType0(QuestionEntity $question, $answer): float {
        return QuestionOptionModel::where('question_id', $question->id)
                ->where('id', intval($answer))->where('is_right', 1)->count() === 1 ? 1 : 0;
    }

    private static function checkType1(QuestionEntity $question, $answer): float
    {
        $answer = (array)$answer;
        $items = QuestionOptionModel::where('question_id', $question->id)->where('is_right', 1)->pluck('id');
        return count($answer) === count($items) && count(array_diff($answer, $items)) === 0 ? 1 : 0;
    }

    private static function checkType2(QuestionEntity $question, $answer): float
    {
        return intval($question->answer) === intval($answer) ? 1 : 0;
    }

    private static function checkType3(QuestionEntity $question, $answer, $dynamic): float
    {
        // 完全对比有问题
        return QuestionCompiler::strReplace($question->answer, $dynamic) === $answer ? 1 : 0;
    }

    private static function checkType4(QuestionEntity $question, $answer, $dynamic): float {
        $answer = (array)$answer;
        $items = explode("\n", $question->answer);
        $total = count($items);
        $right = 0;
        foreach ($items as $i => $line) {
            $line = trim($line);
            if (substr($line, 0, 1) === '=') {
                $line = QuestionCompiler::compilerValue(substr($line, 1), $dynamic);
            }
            if (!isset($answer[$i]) || $answer[$i] !== $line) {
                continue;
            }
            $right ++;
        }
        return $right / $total;
    }
}