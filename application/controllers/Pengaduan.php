<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';

class Pengaduan extends BaseController {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('Mpengaduan');
    }


	public function index()
	{
		 $this->output->set_content_type('application/json')->set_output(json_encode(["Service KPPN Sijunjung Mobile V2.0"]));
	}

	public function sendPengaduan() {
		$postdata = json_decode(file_get_contents('php://input'), TRUE);
        $nama  = (isset($_POST['nama']) ? $_POST['nama'] : NULL);
        $email = (isset($_POST['email']) ? $_POST['email'] : NULL);
        $isi   = (isset($_POST['isi']) ? $_POST['isi'] : NULL);
        $judul = (isset($_POST['judul']) ? $_POST['judul'] : NULL);


        if(empty($email)){

	        http_response_code(400);
	        $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi alamat email kosong"]]));

	    } else {

	    	$data = array('pengaduan_nama'=>$nama, 'pengaduan_email'=>$email, 'pengaduan_judul'=>$judul, 'pengaduan_isi'=>$isi, 'pengaduan_waktu'=>date('Y-m-d H:i:s'));

	    	$resul = $this->Mpengaduan->addPengaduan($data);

	    	$this->sendVerificationInternal($email, $nama, $judul, $isi);
	    	$this->sendVerification($email, $nama, $judul, $isi);
	    	$response = [];
	    	if($resul > 0)
            {
                $response['code'] = "1";
                $response['message'] = "Pengaduan Berhasil Terkirim";  
            }
            else
            {
                $response['code'] = "0";
                $response['message'] = "Gagal Mengirim Pengaduan";    
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($response));

	    }

	}


	function sendVerification($email, $nama, $judul, $isi){

        if(!empty($email)){

            $data1["nama"] = $nama;
            $data1["email"] = $email;
            $data1["subject"] = "Pengaduan Telah Diterima KPPN Sijunjung";
            $data1["open_message"] = " Diberitahukan bahwa pengaduan anda melalui aplikasi KPPN Sijunjung Mobile telah diterima oleh pihak KPPN Sijunjung  ";

            $sendStatus = verificationMail($data1);

            if($sendStatus){
                return TRUE;
            } else {
                return FALSE;
            }
        }

    }


    function sendVerificationInternal($email, $nama, $judul, $isi){

        if(!empty($email)){

            $data1["nama"] = $nama;
            $data1["email"] = $email;
            $data1["judul"] = $judul;
            $data1["subject"] = "Pengaduan Dari " . $nama . "melalui KPPN Sijunjung Mobile";
            $data1["open_message"] = $isi;

            $sendStatus = verificationInternal($data1);

            if($sendStatus){
                return TRUE;
            } else {
                return FALSE;
            }
        }

    }


}
