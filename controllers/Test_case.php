<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test_case extends Controller
{

	function __construct()
	{
		parent::Controller();
		$this->load->library('formation');
	}

	function index()
	{
		Formation::add_form('fly_form', array('action' => 'controller/method'));
		Formation::add_field('fly_form', 'name', array(
			'type' => 'text',
			'label' => 'Name'
		));
		Formation::add_field('fly_form', 'desc', array(
			'type' => 'textarea',
			'label' => 'Desc',
			'rows' => '6',
			'cols' => '40'
		));
		Formation::add_field('fly_form', 'submit', array(
			'type' => 'submit',
			'label' => 'Submit',
		));
		$this->load->view('test_view');
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */