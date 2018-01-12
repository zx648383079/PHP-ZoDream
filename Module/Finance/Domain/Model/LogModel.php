<?php
namespace Module\Finance\Domain\Model;

use Domain\Model\Model;

class LogModel extends Model {
    public static function tableName() {
        return 'log';
    }

    public function get_month_balance_payments($conditions=array(),$limit=''){
        $this->db->select('*');
        $this->db->from(self::BALANCE_PAYMENTS);
        if(!empty($conditions) && isset($conditions['start_time'])){
            $this->db->where('created >=',$conditions['start_time']);
        }
        if(!empty($conditions) && isset($conditions['end_time'])){
            $this->db->where('created <=',$conditions['end_time']);
        }
        if(!empty($conditions) && isset($conditions['type'])){
            $this->db->where('type',$conditions['type']);
        }
        if($limit){
            $this->db->limit($limit);
        }
        $this->db->order_by('id desc');
        return $this->db->get()->result_array();
    }
    public function month($limit=6,$type=2){
        $this->db->select('*');
        $this->db->from(self::BALANCE_PAYMENTS);
        if($type <2){
            $this->db->where('type',$type);
        }
        $this->db->where('created >=',date("Y-m-01"));
        $this->db->where('created <=',date("Y-m-d"));
        if($limit){
            $this->db->limit($limit);
        }
        $this->db->order_by('id desc');
        return $this->db->get()->result_array();
    }
    public function getAll($conditions=array()){
        $this->db->select('*');
        $this->db->from(self::BALANCE_PAYMENTS);
        if (is_array($conditions) && count($conditions) > 0) {
            foreach ($conditions as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        $this->db->order_by('id desc');
        return $this->db->get()->result_array();
    }
    public function pre_data($conditions=array(),$baseTime){
        $this->db->select('sum(num) count_data,created');
        $this->db->from(self::BALANCE_PAYMENTS);
        if (is_array($conditions) && count($conditions) > 0) {
            foreach ($conditions as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        $this->db->where("created >=",date("Y-m-01",$baseTime));
        $this->db->where("created <=",date('Y-m-31',$baseTime));
        $this->db->group_by('created');
        return $this->db->get()->result_array();
    }
    //分页列表
    public function pageList($conditions = array(), $fields = '*', $limit = 0, $offset = 0, $order_by = '') {
        if($conditions) {
            if (is_array ( $conditions )) {
                $this->db->where ( $conditions );
            } else {
                $this->db->where ( $conditions, NULL, FALSE );
            }
        }
        if ($fields) {
            $this->db->select ( $fields );
        }
        if ($order_by) {
            $this->db->order_by ( $order_by );
        }
        if ($limit) {
            $this->db->limit ( $limit, $offset );
        }
        $this->db->order_by('created desc');
        $query = $this->db->get(self::BALANCE_PAYMENTS);
        $result = $query->result();
        $query->free_result();

        return $result;
    }
    public function count($conditions = array()) {
        if($conditions) {
            if (is_array ( $conditions )) {
                $this->db->where ( $conditions );
            } else {
                $this->db->where ( $conditions, NULL, FALSE );
            }
        }

        $query = $this->db->get(self::BALANCE_PAYMENTS);
        $count = $query->num_rows();

        return $count;
    }
}