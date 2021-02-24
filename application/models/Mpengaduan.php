<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mpengaduan extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function addPengaduan($data)
    {
        $this->db->insert('pengaduan', $data);
        
        return $this->db->affected_rows();
    }
}