<?php
declare(strict_types=1);
namespace Module\SEO\Domain\Model;

use Domain\Model\Model;
use Zodream\Helpers\Json;

/**
 * Class AgreementModel
 * @package Module\SEO\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $language
 * @property string $description
 * @property string $content
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class AgreementModel extends Model {
    public static function tableName(): string {
        return 'seo_agreement';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,50',
            'language' => '',
            'title' => 'required|string:0,100',
            'description' => 'string',
            'content' => 'required',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function getContentAttribute() {
        $value = $this->getAttributeSource('content');
        return empty($value) ? [] : Json::decode($value);
    }

    public function setContentAttribute($value) {
        $this->setAttributeSource('content', is_array($value) ? Json::encode($value) : $value);
    }
}