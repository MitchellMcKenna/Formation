<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Formation
 *
 * A CodeIgniter library that creates forms via a config file.  It
 * also contains functions to allow for creation of forms on the fly.
 *
 * @package		Formation
 * @author		Dan Horrigan <http://dhorrigan.com>
 * @license		Apache License v2.0
 * @copyright	2010 Dan Horrigan
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Test Case Controller
 *
 * @subpackage	Test Case
 */
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