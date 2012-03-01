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
	public $description		= MINIMEE_LESS_NAME;
	public $docs_url		= MINIMEE_LESS_DOCS;
	public $name			= MINIMEE_LESS_DESC;
	public $settings_exist	= 'n';
	public $version			= '1.0.1';
	
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
	public function minimee_pre_minify_css($css, $filename, $rel, $M)
	{
		$less = (preg_match('/0|false|off|no|n/i', $this->EE->TMPL->fetch_param('less'))) ? 'no' : 'yes';
		$lessImportDir = $this->EE->TMPL->fetch_param('lessImportDir');

		// only run if we say so
		if ($less == 'yes')
		{
			// better be a .less file
			if (strpos($filename, '.less') !== FALSE)
			{
				Minimee_helper::log('Running LESS on `' . $filename . '`.', 3);
	
				require_once PATH_THIRD . 'less_for_minimee/libraries/lessphp/lessc.inc.php';
				
				$less = new lessc();
				
				// guess our server location
				$importRel = str_ireplace($M->config->base_url, $M->config->base_path, $rel);
				
				// merge guess with any provided directories, and pass to less
				$less->importDir = array_merge(array($importRel), explode('|', $lessImportDir));
				
				// return our less'd contents
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