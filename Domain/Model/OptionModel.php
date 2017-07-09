<?php
namespace Domain\Model;

use Zodream\Infrastructure\Database\Command;

/**
* Class OptionModel
* @property string $name
* @property string $value
* @property string $autoload
*/
class OptionModel extends Model {
	public static function tableName() {
        return 'option';
    }

    protected $primaryKey = array (
		'name',
	);

	protected function rules() {
		return array (
		  'name' => 'required|string:3-255',
		  'value' => '',
		  'autoload' => '|string:3-20',
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
        return Command::getInstance()->setTable(static::$table)->insertOrUpdate(
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