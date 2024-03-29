<?php
namespace Module\Exam\Domain\Model;

use Module\Exam\Domain\Entities\QuestionOptionEntity;

/**
 * Class QuestionOptionModel
 * @package Module\Exam\Domain\Model
 * @property integer $id
 * @property string $content
 * @property integer $question_id
 * @property integer $type
 * @property integer $is_right
 */
class QuestionOptionModel extends QuestionOptionEntity {


    public static function batchSave(QuestionModel $model, array $options) {
        if ($model->type > 1 && $model->type < 4) {
            static::where('question_id', $model->id)->delete();
            return;
        }
        $items = static::where('question_id', $model->id)->pluck('id');
        $hasRight = false;
        $exist = [];
        foreach ($options as $item) {
            if (!isset($item['content']) || empty($item['content'])) {
                continue;
            }
            $data = [
                'content' => $item['content'],
                'question_id' => $model->id,
                'type' => isset($item['type']) ? intval($item['type']) : 0,
                'is_right' => isset($item['is_right']) && $item['is_right'] > 0
                    ? 1 : 0
            ];
            if ($model->type < 1 && $hasRight && $data['is_right'] > 0) {
                $data['is_right'] = 0;
            }
            if ($data['is_right'] > 0) {
                $hasRight = true;
            }
            $id = isset($item['id'])
                && $item['id'] > 0
                && in_array($item['id'], $items) ? intval($item['id']) : 0;
            if ($id > 0) {
                $exist[] = $id;
                static::where('question_id', $model->id)
                    ->where('id', $id)->update($data);
                continue;
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