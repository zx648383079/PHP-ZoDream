<?php
namespace Module\Exam\Domain\Model;

use Module\Exam\Domain\Entities\QuestionAnalysisEntity;

/**
 * Class QuestionOptionModel
 * @package Module\Exam\Domain\Model
 * @property integer $id
 * @property integer $question_id
 * @property integer $type
 * @property string $content
 */
class QuestionAnalysisModel extends QuestionAnalysisEntity {

    public static function batchSave(QuestionModel $model, array $options) {
        $items = static::where('question_id', $model->id)->pluck('id');
        $exist = [];
        foreach ($options as $item) {
            if (!isset($item['content']) || empty($item['content'])) {
                continue;
            }
            $data = [
                'content' => $item['content'],
                'question_id' => $model->id,
                'type' => isset($item['type']) ? intval($item['type']) : 0,
            ];
            $id = isset($item['id'])
            && $item['id'] > 0
            && in_array($item['id'], $items) ? intval($item['id']) : 0;
            if ($id > 0) {
                $exist[] = $id;
                static::where('question_id', $model->id)
                    ->where('id', $id)->update($data);
                return;
            }
            static::create($data);
        }
        if (empty($items)) {
            return;
        }
        $del = array_diff($items, $exist);
        if (empty($del)) {
            return;
        }
        static::where('question_id', $model->id)
            ->whereIn('id', $del)->delete();
    }
}