<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';

class Welcome extends BaseController {


	public function index()
	{
		 $this->output->set_content_type('application/json')->set_output(json_encode(["Service KPPN Sijunjung Mobile V2.0"]));
	}
}
