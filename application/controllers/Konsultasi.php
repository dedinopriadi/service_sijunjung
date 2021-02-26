<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';

class Konsultasi extends BaseController {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('Mkonsultasi');
    }


	public function index()
	{
		 $this->output->set_content_type('application/json')->set_output(json_encode(["Service KPPN Sijunjung Mobile V2.0"]));
	}

	public function createKonsul() {
		$postdata = json_decode(file_get_contents('php://input'), TRUE);
        $idx    = (isset($_POST['idx']) ? $_POST['idx'] : NULL);
        $satker = (isset($_POST['satker']) ? $_POST['satker'] : NULL);
        $judul  = (isset($_POST['judul']) ? $_POST['judul'] : NULL);
        $tgl    = (isset($_POST['tgl']) ? $_POST['tgl'] : NULL);
        $jam    = (isset($_POST['jam']) ? $_POST['jam'] : NULL);
        $kontak = (isset($_POST['kontak']) ? $_POST['kontak'] : NULL);
        $ket    = (isset($_POST['ket']) ? $_POST['ket'] : NULL);
        $email  = (isset($_POST['email']) ? $_POST['email'] : NULL);


        if(empty($email)){
            $response = [];
	        $response['code'] = "2";
            $response['message'] = "Referensi email konsultan kosong";  
            $this->output->set_content_type('application/json')->set_output(json_encode($response));

	    } else if(empty($idx)){
            $response = [];
            $response['code'] = "2";
            $response['message'] = "Referensi ID konsultan kosong";  
            $this->output->set_content_type('application/json')->set_output(json_encode($response));

        } else if(empty($judul) || empty($tgl) || empty($jam) || empty($kontak)){
            $response = [];
            $response['code'] = "0";
            $response['message'] = "Mohon isi semua kolom";  
            $this->output->set_content_type('application/json')->set_output(json_encode($response));

        } else {

	    	$data = array('konsultan_id'=>$idx, 'syssatker_id'=>$satker, 'konsul_judul'=>$judul, 'konsul_tgl'=>$tgl, 'konsul_jam'=>$jam, 'konsul_kontak'=>$kontak, 'konsul_ket'=>$ket);

	    	$resul = $this->Mkonsultasi->addKonsultasi($data);

	    	$this->sendVerification($satker, $judul, $tgl, $jam, $kontak, $ket, $email);
	    	$response = [];
	    	if($resul > 0)
            {
                $response['code'] = "1";
                $response['message'] = "Jadwal Konsultasi Berhasil Dibuat";  
            }
            else
            {
                $response['code'] = "0";
                $response['message'] = "Gagal Membuat Jadwal Konsultasi";    
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($response));

	    }

	}


	function sendVerification($satker, $judul, $tgl, $jam, $kontak, $ket, $email){

        $info = $this->Mkonsultasi->getInfoUser($satker);

        if(!empty($info)){

            if(!empty($email)){

                $data1["nama"] = $info->syssatker_nama;
                $data1["email"] = $email;
                $data1["satker"] = $info->satker_nama;
                $data1["judul"] = $judul;
                $data1["tgl"] = $tgl;
                $data1["jam"] = $jam;
                $data1["kontak"] = $kontak;
                $data1["ket"] = $ket;
                $data1["subject"] = "Jadwal Konsultasi Baru";
                $data1["open_message"] = "";

                $sendStatus = verificationKonsul($data1);

                if($sendStatus){
                    return TRUE;
                } else {
                    return FALSE;
                }
            }

        }

    }


}
