<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mkonsultasi extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function addKonsultasi($data)
    {
        $this->db->insert('konsultasi', $data);
        
        return $this->db->affected_rows();
    }

    function getInfoUser($id)
    {
    	$this->db->select('*');
        $this->db->from('v_syssatker');
        $this->db->where('syssatker_id', $id);
        $result = $this->db->get();

        return $result->row();
    }


}