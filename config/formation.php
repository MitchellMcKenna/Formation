<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| FORMATION CONFIG
| -------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Form Wrapper tags
|--------------------------------------------------------------------------
|
| These tags will wrap the different elements in the form.
|
| Example:
| $formation['form_wrapper_open']	= '<ul>';
| $formation['form_wrapper_close']	= '</ul>';
|
| $formation['input_wrapper_open']	= '<li>';
| $formation['input_wrapper_close']	= '</li>';
|
| $formation['label_wrapper_open']	= '<label for="%s">';
| $formation['label_wrapper_close']	= '</label>';
|
| Would result in the following form:
| <form action="" method="post">
| <ul>
|     <li>
|         <label for="first_name">First Name</label>
|         <input type="text" name="first_name" id="first_name" value="" />
|     </li>
| </ul>
| </form>
*/
$formation['form_wrapper_open']		= '<fieldset>';
$formation['form_wrapper_close']	= '</fieldset>';

$formation['input_wrapper_open']	= '<p>';
$formation['input_wrapper_close']	= '</p>';

$formation['label_wrapper_open']	= '<label for="%s">';
$formation['label_wrapper_close']	= '</label>';

$formation['forms']['create_user'] = array(
	'action'	=> 'users/create',
	'fields'	=> array(
		'id'	=> array(
			'type'		=> 'hidden',
			'value'		=> ''
		),
		'username'	 => array(
			'label'		=> 'Username',
			'type'		=> 'text',
			'size'		=> '40',
			'value'		=> ''
		),
		'first_name' => array(
			'label'		=> 'First Name',
			'type'		=> 'text',
			'size'		=> '40'
		),
		'last_name'	 => array(
			'label'		=> 'Last Name',
			'type'		=> 'text',
			'size'		=> '40',
			'value'		=> ''
		),
		'password'	 => array(
			'label'		=> 'Password',
			'type'		=> 'password',
			'size'		=> '40',
			'value'		=> ''
		),
		'public' => array(
			'type'		=> 'radio',
			'label'		=> 'Public?',
			'items'		=> array(
				array(
					'label'		=> 'Yes',
					'checked'	=> 'checked',
					'value'		=> '1',
				),
				array(
					'label'		=> 'No',
					'value'		=> '0',
				)
			)
		),
		'display_options' => array(
			'type'		=> 'checkbox',
			'label'		=> 'Display Options',
			'items'		=> array(
				array(
					'label'		=> 'Display Email',
					'checked'	=> 'checked',
					'value'		=> '1',
				),
				array(
					'label'		=> 'Display Real Name',
					'checked'	=> 'checked',
					'value'		=> '1',
				),
			)
		),
		'bio'	=> array(
			'label'		=> 'Bio',
			'type'		=> 'textarea',
			'rows'		=> '4',
			'cols'		=> '50',
			'value'		=> ''
		),
		'plan' => array(
			'type'		=> 'select',
			'label'		=> 'Plan',
			'selected'	=> '2',
			'options'	=> array(
				'1' => 'Basic',
				'2' => 'Standard',
				'3' => 'Advanced'
			)
		),
		'action'		 => array(
			'label'		=> '',
			'type'		=> 'submit',
			'value'		=> 'Create'
		)
	)
);

/* End of file formation.php */
