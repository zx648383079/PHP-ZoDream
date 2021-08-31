<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Models;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 *
 * @property integer $id
 * @property integer $site_id
 * @property integer $user_id
 * @property integer $score
 * @property integer $last_score
 * @property string $change_reason
 * @property integer $created_at
 */
class SiteScoringLogModel extends Model {
    public static function tableName() {
        return 'search_site_scoring_log';
    }

    protected function rules() {
        return [
            'site_id' => 'required|int',
            'user_id' => 'required|int',
            'score' => 'required|int:0,127',
            'last_score' => 'required|int:0,127',
            'change_reason' => 'string:0,255',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'site_id' => 'Site Id',
            'user_id' => 'User Id',
            'score' => 'Score',
            'last_score' => 'Last Score',
            'change_reason' => 'Change Reason',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

}
