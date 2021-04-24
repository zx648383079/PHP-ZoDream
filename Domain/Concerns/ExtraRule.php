<?php
declare(strict_types=1);
namespace Domain\Concerns;

use Zodream\Helpers\Json;

trait ExtraRule {
    protected string $extraRuleKey = 'extra_rule';

    public function getExtraRuleAttribute() {
        $value = $this->getAttributeValue($this->extraRuleKey);
        return empty($value) ? [] : Json::decode($value);
    }

    public function setExtraRuleAttribute($value) {
        $this->__attributes[$this->extraRuleKey] = is_array($value) ? Json::encode($value) : $value;
    }
}