<?php 
namespace App\Model;
	
	
class QuoteModel extends Model
{
	protected $table = 'quote';
	public $_columns = array (
		'id',
		'content',
		'aid',
		'uid',
		'duration',
		'days',
		'likes',
		'shares',
		'status',
		'udate',
		'cdate',
		'isdelete'
	);
	
	function findAll() {
        $sql = array(
			'select' => 't.*, a.filename, a2.filename AS filebefore, a3.filename AS fileafter',
			'from' => 'quote t',
			'left' => array(
				'attachments a',
				'(t.aid = a.id ',
				'and' => 'a.isdelete = 0)'
			),
			'left`' => array(
				'attachments a2',
				'(t.id = a2.cid ',
				'and' => 'a2.type = 20',
				'and' => 'a.isdelete = 0)'
			),
			'left``' => array(
				'attachments a3',
				'(t.id = a3.cid ',
				'and' => 'a3.type = 30',
				'and' => 'a.isdelete = 0)'
			),
			'where' => array(
				't.isdelete = 0',
				'a.filename IS NOT NULL',
				'a2.filename IS NOT NULL',
				'a3.filename IS NOT NULL'
			),
			'order' => array(
				'desc' => 't.cdate'
			)
		);
        return $this->findByHelper($sql);
    }
}