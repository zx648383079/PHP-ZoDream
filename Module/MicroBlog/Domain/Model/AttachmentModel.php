<?php
namespace Module\MicroBlog\Domain\Model;

use Domain\Model\Model;
/**
 * Class AttachmentModel
 * @property integer $id
 * @property integer $micro_id
 * @property string $thumb
 * @property string $file
 */
class AttachmentModel extends Model {

	public static function tableName(): string {
        return 'micro_attachment';
    }

    protected function rules(): array {
        return [
            'micro_id' => 'required|int',
            'thumb' => 'required|string:0,255',
            'file' => 'required|string:0,255',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'micro_id' => 'Micro Id',
            'thumb' => 'Thumb',
            'file' => 'File',
        ];
    }
}