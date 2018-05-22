<?php
namespace Module\Cas\Domain\Model;


use Domain\Model\Model;
use Zodream\Helpers\Str;

class BaseModel extends Model {


    /**
     * @param integer  $totalLength
     * @param string   $prefix
     * @param callable $checkFunc
     * @param integer  $maxRetry
     * @return string|false
     */
    public static function generate($totalLength, $prefix, callable $checkFunc, $maxRetry) {
        $ticket = false;
        $flag   = false;
        for ($i = 0; $i < $maxRetry; $i++) {
            $ticket = static::generateOne($totalLength, $prefix);
            if (call_user_func_array($checkFunc, [$ticket])) {
                $flag = true;
                break;
            }
        }

        if (!$flag) {
            return false;
        }

        return $ticket;
    }

    /**
     * @param integer $totalLength
     * @param string  $prefix
     * @return string
     */
    public static function generateOne($totalLength, $prefix) {
        return $prefix.Str::random($totalLength - strlen($prefix));
    }
}