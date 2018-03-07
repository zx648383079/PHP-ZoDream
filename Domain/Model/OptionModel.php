<?php
namespace Domain\Model;
use Zodream\Database\Command;
use Zodream\Database\DB;


/**
* Class OptionModel
* @property string $name
* @property string $value
* @property string $autoload
*/
class OptionModel extends Model {
	public static function tableName() {
        return 'options';
    }

    protected $primaryKey = array (
		'name',
	);

	protected function rules() {
		return array (
		  'name' => 'required|string:1,255',
		  'value' => '',
		  'autoload' => 'string:0,20',
		);
	}

	protected function labels() {
		return array (
		  'name' => 'Name',
		  'value' => 'Value',
		  'autoload' => 'Autoload',
		);
	}

	/**
	 * FIND ALL TO ASSOC ARRAY
	 * @param array|string $where
	 * @return array
	 */
	public static function findOption($where = array()) {
		$data = static::where($where)->all();
		$result = [];
		foreach ($data as $item) {
			$result[$item['name']] = $item['value'];
		}
		return $result;
	}

	public function insert() {
        if (!$this->validate()) {
            return false;
        }
        return Command::getInstance()->setTable(static::tableName())->insertOrUpdate(
            'name, value, autoload',
            ':name, :value, :autoload',
            'value = :value, autoload = :autoload',
            array(
                ':name' => $this->name,
                ':value' => $this->value,
                ':autoload' => $this->get('autoload', 'yes')
            ));
    }
}