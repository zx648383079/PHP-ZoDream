<?php
namespace Domain\Model\Waste;

use Domain\Model\Model;
/**
* Class WasteCompanyModel
* @property integer $waste_id
* @property integer $company_id
*/
class WasteCompanyModel extends Model {
	public static $table = 'waste_company';

	protected $primaryKey = array ();

	protected function rules() {
		return array (
		  'waste_id' => 'int',
		  'company_id' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'waste_id' => 'Waste Id',
		  'company_id' => 'Company Id',
		);
	}
}