<?php
namespace Module\Exam\Domain\Model;

use Module\Exam\Domain\Entities\QuestionOptionEntity;

class QuestionOptionModel extends QuestionOptionEntity {


    public static function batchSave(QuestionModel $model, array $options) {
        if ($model->type > 1) {
            static::where('question_id', $model->id)->delete();
            return;
        }
        $items = static::where('question_id', $model->id)->pluck('id');
        $hasRight = false;
        $exist = [];
        foreach ($options['content'] as $i => $content) {
            if (empty($content)) {
                continue;
            }
            $data = [
                'content' => $content,
                'question_id' => $model->id,
                'type' => isset($options['type'][$i]) ? intval($options['type'][$i]) : 0,
                'is_right' => isset($options['is_right'][$i]) && $options['is_right'][$i] > 0
                    ? 1 : 0
            ];
            if ($model->type < 1 && $hasRight && $data['is_right'] > 0) {
                $data['is_right'] = 0;
            }
            if ($data['is_right'] > 0) {
                $hasRight = true;
            }
            $id = isset($options['id'][$i])
                && $options['id'][$i] > 0
                && in_array($options['id'][$i], $items)? intval($options['id'][$i]) : 0;
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