<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD . 'minimee_less/config.php';

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * Minimee+LESS Extension
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Extension
 * @author		John D Wells
 * @link		http://johndwells.com
 */

class Minimee_less_ext {
	
	/**
	 * EE, obviously
	 */
	private $EE;

	/**
	 * Logging levels
	 */
	protected $_levels = array(
		1 => 'ERROR',
		2 => 'DEBUG',
		3 => 'INFO'
	);

	/**
	 * Standard Extension stuff
	 */
	public $name			= MINIMEE_LESS_NAME;
	public $version			= MINIMEE_LESS_VER;
	public $description		= MINIMEE_LESS_DESC;
	public $docs_url		= MINIMEE_LESS_DOCS;
	public $settings 		= array();
	public $settings_exist	= 'y';
	

	/**
	 * Reference to our cache
	 */
	public $cache;


	// ----------------------------------------------------------------------


	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array - only passed when activating a hook
	 * @return void
	 */
	public function __construct($settings = array())
	{
		$this->EE =& get_instance();
		$this->settings = $settings;

		if( ! isset($this->EE->session->cache['minimee_less']))
		{
			$this->EE->session->cache['minimee_less'] = array();
		}
		$this->cache =& $this->EE->session->cache['minimee_less'];
	}
	// ----------------------------------------------------------------------


	/**
	 * Activate Extension
	 *
	 * @return void
	 */
	public function activate_extension()
	{
		// Setup custom settings in this array.
		$this->settings = array();
		
		$data = array(
			'class'		=> __CLASS__,
			'method'	=> 'minimee_pre_minify_css',
			'hook'		=> 'minimee_pre_minify_css',
			'settings'	=> serialize($this->settings),
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);

		$this->EE->db->insert('extensions', $data);			
		
	}	
	// ----------------------------------------------------------------------


	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 *
	 * @return void
	 */
	function disable_extension()
	{
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');
	}
	// ----------------------------------------------------------------------


	/**
	 * 'minimee_pre_minify_css' hook
	 *
	 * @param	String	CSS Contents to process
	 * @param	String	Filename of original file
	 * @param	String	Relative Path that Minimee passes to CSS Minify (not used)
	 * @param	Object	Instance of Minimee plugin
	 * @return	String	LESS'd CSS
	 */
	public function minimee_pre_minify_css($css, $filename, $rel, $M)
	{
		// grab settings from DB	
		$settings = $this->get_settings();
		
		// normalise settings
		$enable = (preg_match('/0|false|off|no|n/i', $settings['enable'])) ? 'no' : 'yes';
		$import_dirs = explode("\n", $settings['import_dirs']);
		
		// see if we need to prepend our base path to any dirs
		foreach($import_dirs as $key => $val)
		{
			if (strpos($val, '/') !== 0)
			{
				$import_dirs[$key] = $M->config->base_path . '/' . $val;
			}
		}

		// only run if we say so
		if ($enable == 'yes')
		{
			// better be a .less file
			if (strpos($filename, '.less') !== FALSE)
			{
				$this->_log('Running LESS on `' . $filename . '`.', 3);
	
				require_once PATH_THIRD . 'minimee_less/libraries/lessphp/lessc.inc.php';
				
				$less = new lessc();
				
				// Next 4 lines borrowed from Minimee_helper::replace_url_with()
				// protocol-agnostic URL
				$agnostic_url = substr($M->config->base_url, strpos($M->config->base_url, '//') + 2, strlen($M->config->base_url));
		
				// pattern search & replace
				$local = preg_replace('@(https?:)?\/\/' . $agnostic_url . '@', $M->config->base_path, $filename);
		
				// now fetch directory for our file
				$p = pathinfo($local);
				
				// merge guess with any provided directories, and pass to less
				$less->importDir = array_merge(array($p['dirname']), $import_dirs);
				
				// return our less'd contents
				$css = $less->parse($css);
			}
		}
		
		// return (maybe) less'd css
		return $css;
	}
	// ----------------------------------------------------------------------


	/**
	 * Retrieves settings from db
	 *
	 * @return void
	 */
	function get_settings()
	{
		// if settings are already in session cache, use those
		if ( ! isset($this->cache['settings']))
		{
			$this->EE->db
						->select('settings')
						->from('extensions')
						->where(array('enabled' => 'y', 'class' => __CLASS__ ))
						->limit(1);
			$query = $this->EE->db->get();
			
			if ($query->num_rows() > 0)
			{
				$settings = unserialize($query->row()->settings);
			}
			
			else
			{
				$settings = array();
			}
			
			$query->free_result();

			// set our cache, merged with defaults, so we always have a consistent array
			$default = array(
				'enable' => 'yes',
				'import_dirs' => ''
			);

			$this->cache['settings'] = array_merge($default, $settings);
		}
		
		return $this->cache['settings'];
	}
	// ----------------------------------------------------------------------


	/**
	 * Save settings
	 *
	 * @return 	void
	 */
	public function save_settings()
	{
		if (empty($_POST))
		{
			show_error($this->EE->lang->line('unauthorized_access'));
		}
		
		else
		{
			// grab our posted form
			$settings = array();
			
			$settings['enable'] = (isset($_POST['enable'])) ? ((preg_match('/0|false|off|no|n/i', $_POST['enable'])) ? 'no' : 'yes') : 'yes';
			$settings['import_dirs'] = (isset($_POST['import_dirs'])) ? trim($_POST['import_dirs']) : '';
			
			// update db
			$this->EE->db->where('class', __CLASS__)
						 ->update('extensions', array('settings' => serialize($settings)));
			
			// save the environment			
			unset($settings);

			// let frontend know we succeeeded
			$this->EE->session->set_flashdata(
				'message_success',
			 	$this->EE->lang->line('preferences_updated')
			);

			$this->EE->functions->redirect(BASE.AMP.'C=addons_extensions'.AMP.'M=extension_settings'.AMP.'file=minimee_less');
		}
	}
	// ------------------------------------------------------


	function settings_form($current)
	{
		$this->EE->load->helper('form');
		$this->EE->load->library('table');
		
		// grab settings from DB	
		$settings = $this->get_settings();

		$vars = array(
			'enable' => $settings['enable'],
			'import_dirs' => $settings['import_dirs'],
			'flashdata_success' => $this->EE->session->flashdata('message_success')
		);

		// return our view
		return $this->EE->load->view('settings_form', $vars, TRUE);			
	}
	// ----------------------------------------------------------------------


	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}
	}	
	
	// ----------------------------------------------------------------------


	/**
	 * Log method
	 *
	 * By default will pass message to log_message();
	 * Also will log to template if rendering a PAGE.
	 *
	 * @access  public
	 * @param   string      $message        The log entry message.
	 * @param   int         $severity       The log entry 'level'.
	 * @return  void
	 */
	protected function _log($message, $severity = 1)
	{
		// translate our severity number into text
		$severity = (array_key_exists($severity, $this->_levels)) ? $this->_levels[$severity] : $this->_levels[1];

		// basic EE logging
		log_message($severity, MINIMEE_LESS_NAME . ": {$message}");

		// If not in CP, let's also log to template
		if (REQ == 'PAGE')
		{
			get_instance()->TMPL->log_item(MINIMEE_LESS_NAME . " [{$severity}]: {$message}");
		}

		// If we are in CP and encounter an error, throw a nasty show_message()
		if (REQ == 'CP' && $severity == $this->_levels[1])
		{
			show_error(MINIMEE_LESS_NAME . " [{$severity}]: {$message}");
		}
	}
	// ------------------------------------------------------
}
// END CLASS

/* End of file ext.minimee_less.php */
/* Location: /system/expressionengine/third_party/minimee_less/ext.minimee_less.php */