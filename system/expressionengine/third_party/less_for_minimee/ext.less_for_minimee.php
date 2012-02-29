<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
 * LESS for Minimee Extension
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Extension
 * @author		John D Wells
 * @link		http://johndwells.com
 */

class Less_for_minimee_ext {
	
	public $settings 		= array();
	public $description		= 'LESS for Minimee';
	public $docs_url		= 'http://johndwells.com/software/less-for-minimee';
	public $name			= 'LESS for Minimee';
	public $settings_exist	= 'n';
	public $version			= '1.0.0';
	
	private $EE;
	
	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	public function __construct($settings = '')
	{
		$this->EE =& get_instance();
		$this->settings = $settings;
	}// ----------------------------------------------------------------------
	
	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @see http://codeigniter.com/user_guide/database/index.html for
	 * more information on the db class.
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
	 * minimee_pre_minify_css
	 *
	 * @param 
	 * @return 
	 */
	public function minimee_pre_minify_css($css, $filename, $M)
	{
		$less = (preg_match('/0|false|off|no|n/i', $this->EE->TMPL->fetch_param('less'))) ? 'no' : 'yes';

		// only run if we want to and file appears to be a LESS file
		if ($less == 'yes')
		{
			if (strpos($filename, '.less') !== FALSE)
			{
				Minimee_helper::log('Running LESS on `' . $filename . '`.', 3);
	
				require_once PATH_THIRD . 'less_for_minimee/libraries/lessphp/lessc.inc.php';
	
				$less = new lessc(); // a blank lessc
	
				return $less->parse($css);
			}
		}
		
		// if we reach the end, simply return un-lessed css
		return $css;
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
}

/* End of file ext.less_for_minimee.php */
/* Location: /system/expressionengine/third_party/less_for_minimee/ext.less_for_minimee.php */