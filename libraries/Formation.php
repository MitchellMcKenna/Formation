<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Formation
 *
 * A CodeIgniter library that creates forms via a config file.  It
 * also contains functions to allow for creation of forms on the fly.
 *
 * @package		CodeIgniter
 * @subpackage	Formation
 * @author		Dan Horrigan <http://dhorrigan.com>
 * @license		Apache License v2.0
 */

/**
 * Core Formation Class
 *
 * @subpackage	Formation
 */
class Formation
{
	/**
	 * Used to store the global CI instance
	 */
	private static $_ci;

	/**
	 * Used to store the configuration
	 */
	private static $_config = array();

	/**
	 * Used to store the forms
	 */
	private static $_forms = array();

	// --------------------------------------------------------------------

	/**
	 * Construct
	 *
	 * Imports the global config and custom config (if given).
	 *
	 * @access	public
	 * @param	array	$custom_config
	 */
	public function __construct($custom_config = array())
	{
		self::$_ci =& get_instance();

		// Include the formation config and ensure it is formatted
		if(file_exists(APPPATH . 'config/formation.php'))
		{
			include(APPPATH . 'config/formation.php');
			if(!isset($formation) OR !is_array($formation))
			{
				show_error('Formation config is not formatted correctly.');
			}
			self::add_config($formation);
		}
		else
		{
			show_error('Formation config file is missing.');
		}

		// Merge the custom config into the global config
		if(!empty($custom_config))
		{
			self::add_config($custom_config);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Add Config
	 *
	 * Merges a config array into the current config
	 *
	 * @access	public
	 * @param	array	$config
	 * @return	void
	 */
	public static function add_config($config)
	{
		self::$_config = array_merge_recursive(self::$_config, $config);

		// Add the forms from the config array
		if(isset(self::$_config['forms']) AND is_array(self::$_config['forms']))
		{
			foreach(self::$_config['forms'] as $form_name => $attributes)
			{
				$fields = $attributes['fields'];
				unset($attributes['fields']);

				self::add_form($form_name, $attributes, $fields);
			}
			unset(self::$_config['forms']);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Add Form
	 *
	 * Adds a form to the config
	 *
	 * @access	public
	 * @param	string	$form_name
	 * @param	array	$attributes
	 * @param	array	$fields
	 * @return	void
	 */
	public static function add_form($form_name, $attributes, $fields = array())
	{
		if(self::form_exists($form_name))
		{
			show_error(sprintf('Form "%s" already exists.  If you were trying to modify the form, please use Formation::modify_form("%s", $attributes).', $form_name, $form_name));
		}

		self::$_forms[$form_name]['attributes'] = $attributes;
		self::$_forms[$form_name]['fields'] = $fields;
	}

	// --------------------------------------------------------------------

	/**
	 * Get Form Array
	 *
	 * Returns the form with all fields and options as an array
	 *
	 * @access	public
	 * @param	string	$form_name
	 * @return	array
	 */
	public static function get_form_array($form_name)
	{
		if(!self::form_exists($form_name))
		{
			show_error(sprintf('Form "%s" does not exist.', $form_name));
		}

		return self::$_forms[$form_name];
	}

	// --------------------------------------------------------------------

	/**
	 * Add Field
	 *
	 * Adds a field to a given form
	 *
	 * @access	public
	 * @param	string	$form_name
	 * @param	string	$field_name
	 * @param	array	$attributes
	 * @return	void
	 */
	public static function add_field($form_name, $field_name, $attributes)
	{
		if(!self::form_exists($form_name))
		{
			show_error(sprintf('Form "%s" does not exist.  You must first add the form using Formation::add_form("%s", $attributes).', $form_name, $form_name));
		}
		if(self::field_exists($form_name, $field_name))
		{
			show_error(sprintf('Field "%s" already exists in form "%s".  If you were trying to modify the field, please use Formation::modify_field($form_name, $field_name, $attributes).', $field_name, $form_name));
		}

		self::$_forms[$form_name]['fields'][$field_name] = $attributes;
	}

	// --------------------------------------------------------------------

	/**
	 * Add Fields
	 *
	 * Allows you to add multiple fields at once.
	 *
	 * @access	public
	 * @param	string	$form_name
	 * @param	array	$fields
	 * @return	void
	 */
	public static function add_fields($form_name, $fields)
	{
		foreach($fields as $field_name => $attributes)
		{
			self::add_field($form_name, $field_name, $attributes);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Modify Field
	 *
	 * Allows you to modify a field.
	 *
	 * @access	public
	 * @param	string	$form_name
	 * @param	string	$field_name
	 * @param	array	$attributes
	 * @return	void
	 */
	public static function modify_field($form_name, $field_name, $attributes)
	{
		if(!self::field_exists($form_name, $field_name))
		{
			show_error(sprintf('Field "%s" does not exist in form "%s".', $field_name, $form_name));
		}
		self::$_forms[$form_name]['fields'][$field_name] = array_merge_recursive(self::$_forms[$form_name][$field_name], $attributes);
	}

	// --------------------------------------------------------------------

	/**
	 * Modify Fields
	 *
	 * Allows you to modify multiple fields at once.
	 *
	 * @access	public
	 * @param	string	$form_name
	 * @param	array	$fields
	 * @return	void
	 */
	public static function modify_fields($form_name, $fields)
	{
		foreach($fields as $field_name => $attributes)
		{
			self::modfy_field($form_name, $field_name, $attributes);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Form Exists
	 *
	 * Checks if a form exists
	 *
	 * @access	public
	 * @param	string	$form_name
	 * @return	bool
	 */
	public static function form_exists($form_name)
	{
		if(!isset(self::$_forms[$form_name]))
		{
			return FALSE;
		}
		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Field Exists
	 *
	 * Checks if a field exists.
	 *
	 * @param	string	$form_name
	 * @param	string	$field_name
	 * @return	bool
	 */
	public static function field_exists($form_name, $field_name)
	{
		if(!isset(self::$_forms[$form_name]['fields'][$field_name]))
		{
			return FALSE;
		}
		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Form
	 *
	 * Builds a form and returns well-formatted, valid XHTML for output.
	 *
	 * @access	public
	 * @param	string	$form_name
	 * @return	string
	 */
	public static function form($form_name)
	{
		$form = self::get_form_array($form_name);

		$return = self::open(NULL, $form['attributes']) . "\n";
		$return .= "\t" . self::$_config['form_wrapper_open'] . "\n";

		foreach($form['fields'] as $name => $properties)
		{
			if(!isset($properties['name']))
			{
				$properties['name'] = $name;
			}
			switch($properties['type'])
			{
				case 'hidden':
					$return .= "\t\t" . self::input($properties) . "\n";
					break;
				case 'radio': case 'checkbox':
					$return .= "\t\t" . self::$_config['input_wrapper_open'] . "\n";
					$return .= "\t\t\t" . sprintf(self::$_config['label_wrapper_open'], $name) . $properties['label'] . self::$_config['label_wrapper_close'] . "\n";
					if(isset($properties['items']))
					{
						$return .= "\t\t\t<span>\n";
						foreach($properties['items'] as $count => $element)
						{
							if(!isset($element['id']))
							{
								$element['id'] = $name . '_' . $count;
							}
							$element['type'] = $properties['type'];
							$element['name'] = $properties['name'];
							$return .= "\t\t\t\t" . sprintf(self::$_config['label_wrapper_open'], $element['id']) . $element['label'] . self::$_config['label_wrapper_close'] . "\n";
							$return .= "\t\t\t\t" . self::input($element) . "\n";
						}
						$return .= "\t\t\t</span>\n";
					}
					else
					{
						$return .= "\t\t\t" . sprintf(self::$_config['label_wrapper_open'], $name) . $properties['label'] . self::$_config['label_wrapper_close'] . "\n";
						$return .= "\t\t\t" . self::input($properties) . "\n";
					}
					$return .= "\t\t" . self::$_config['input_wrapper_close'] . "\n";
					break;
				case 'select':
					$return .= "\t\t" . self::$_config['input_wrapper_open'] . "\n";
					$return .= "\t\t\t" . sprintf(self::$_config['label_wrapper_open'], $name) . $properties['label'] . self::$_config['label_wrapper_close'] . "\n";
					$return .= "\t\t\t" . self::select($properties, 3) . "\n";
					$return .= "\t\t" . self::$_config['input_wrapper_close'] . "\n";
					break;
				case 'textarea':
					$return .= "\t\t" . self::$_config['input_wrapper_open'] . "\n";
					$return .= "\t\t\t" . sprintf(self::$_config['label_wrapper_open'], $name) . $properties['label'] . self::$_config['label_wrapper_close'] . "\n";
					$return .= "\t\t\t" . self::textarea($properties) . "\n";
					$return .= "\t\t" . self::$_config['input_wrapper_close'] . "\n";
					break;
				default:
					$return .= "\t\t" . self::$_config['input_wrapper_open'] . "\n";
					$return .= "\t\t\t" . sprintf(self::$_config['label_wrapper_open'], $name) . $properties['label'] . self::$_config['label_wrapper_close'] . "\n";
					$return .= "\t\t\t" . self::input($properties) . "\n";
					$return .= "\t\t" . self::$_config['input_wrapper_close'] . "\n";
					break;
			}
		}

		$return .= "\t" . self::$_config['form_wrapper_close'] . "\n";
		$return .= self::close() . "\n";

		return $return;
	}

	// --------------------------------------------------------------------

	/**
	 * Select
	 *
	 * Generates a <select> element based on the given parameters
	 *
	 * @access	public
	 * @param	array	$parameters
	 * @param	int		$indent_amount
	 * @return	string
	 */
	public static function select($parameters, $indent_amount = 0)
	{
		if(!isset($parameters['options']) OR !is_array($parameters['options']))
		{
			show_error(sprintf('Select element "%s" is either missing the "options" or "options" is not array.', $parameters['name']));
		}
		// Get the options then unset them from the array
		$options = $parameters['options'];
		unset($parameters['options']);

		// Get the selected options then unset it from the array
		$selected = $parameters['selected'];
		unset($parameters['selected']);

		$input = "<select " . self::attr_to_string($parameters) . ">\n";
		foreach($options as $key => $val)
		{
			if(is_array($val))
			{
				$input .= str_repeat("\t", $indent_amount + 1) . '<optgroup label="' . $key . '">' . "\n";
				foreach($val as $opt_key => $opt_val)
				{
					$extra = ($opt_key == $selected) ? ' selected="selected"' : '';
					$input .= str_repeat("\t", $indent_amount + 2);
					$input .= '<option value="' . $opt_key . '"' . $extra . '>' . self::prep_value($opt_val) . "</option>\n";
				}
				$input .= str_repeat("\t", $indent_amount + 1) . "</optgroup>\n";
			}
			else
			{
				$extra = ($key == $selected) ? ' selected="selected"' : '';
				$input .= str_repeat("\t", $indent_amount + 1);
				$input .= '<option value="' . $key . '"' . $extra . '>' . self::prep_value($val) . "</option>\n";
			}
		}
		$input .= str_repeat("\t", $indent_amount) . "</select>";

		return $input;
	}

	// --------------------------------------------------------------------

	/**
	 * Open
	 *
	 * Generates the opening <form> tag
	 *
	 * @access	public
	 * @param	string	$action
	 * @param	array	$options
	 * @return	string
	 */
	public static function open($action = '', $options = array())
	{
		isset($options['method']) OR $options['method'] = 'post';

		($action !== NULL) AND $options['action'] = $action;

		$options['action'] = (strpos($options['action'], '://') === FALSE) ? self::$_ci->config->site_url($options['action']) : $options['action'];

		$form = '<form ' . self::attr_to_string($options) . '>';

		return $form;
	}

	// --------------------------------------------------------------------

	/**
	 * Close
	 *
	 * Generates the closing </form> tag
	 *
	 * @access	public
	 * @return	string
	 */
	public static function close()
	{
		return '</form>';
	}

	// --------------------------------------------------------------------

	/**
	 * Label
	 *
	 * Generates a label based on given parameters
	 *
	 * @access	public
	 * @param	string	$value
	 * @param	string	$for
	 * @return	string
	 */
	public static function label($value, $for = NULL)
	{
		if($for === NULL)
		{
			return '<label>' . $value . '</label>';
		}
		else
		{
			return '<label for="' . $for . '">' . $value . '</label>';
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Input
	 *
	 * Generates an <input> tag
	 *
	 * @access	public
	 * @param	array	$options
	 * @return	string
	 */
	public static function input($options)
	{
		if(!isset($options['type']))
		{
			show_error('You must specify a type for the input.');
		}
		elseif(!in_array($options['type'], array('text', 'radio', 'checkbox', 'hidden', 'password', 'file', 'submit', 'cancel')))
		{
			show_error(sprintf('"%s" is not a valid input type.', $options['type']));
		}
		$input = '<input ' . self::attr_to_string($options) . ' />';

		return $input;
	}

	// --------------------------------------------------------------------

	/**
	 * Textarea
	 *
	 * Generates a <textarea> tag
	 *
	 * @access	public
	 * @param	array	$options
	 * @return	string
	 */
	public static function textarea($options)
	{
		$value = '';
		if(isset($options['value']))
		{
			$value = $options['value'];
			unset($options['value']);
		}
		$input = "<textarea " . self::attr_to_string($options) . '>';
		$input .= self::prep_value($value);
		$input .= '</textarea>';

		return $input;
	}


	// --------------------------------------------------------------------

	/**
	 * Attr to String
	 *
	 * Takes an array of attributes and turns it into a string for an input
	 *
	 * @access	private
	 * @param	array	$attr
	 * @return	string
	 */
	private function attr_to_string($attr)
	{
		$attr_str = '';

		if(!is_array($attr))
		{
			$attr = (array) $attr;
		}

		foreach($attr as $property => $value)
		{
			if($property == 'label')
			{
				continue;
			}
			if($property == 'value')
			{
				$value = self::prep_value($value);
			}
			$attr_str .= $property . '="' . $value . '" ';
		}

		// We strip off the last space for return
		return substr($attr_str, 0, -1);
	}

	// --------------------------------------------------------------------

	/**
	 * Prep Value
	 *
	 * Prepares the value for display in the form
	 *
	 * @access	public
	 * @param	string	$value
	 * @return	string
	 */
	public static function prep_value($value)
	{
		$value = htmlspecialchars($value);
		$value = str_replace(array("'", '"'), array("&#39;", "&quot;"), $value);

		return $value;
	}
}

/* End of file Formation.php */