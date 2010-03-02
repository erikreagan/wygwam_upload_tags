<?php if ( ! defined('APP_VER')) exit('No direct script access allowed');

/**
 * Wygwam Upload Tags
 * 
 * @author    Brandon Kelly <brandon@pixelandtonic.com>
 * @copyright Copyright (c) 2010 Brandon Kelly
 * @license   http://creativecommons.org/licenses/by-sa/3.0/ Attribution-Share Alike 3.0 Unported
 *
 * This is a conversion of Brandon's EE2 extension meant to be used with EE 1.x
 */

class Wygwam_upload_tags {

	var $name           = 'Wygwam Upload Tags';
	var $version        = '1.0';
	var $description    = 'Parses Wygwam\'s Upload Directory settings for {username}, {member_id}, and {group_id}';
	var $settings_exist = 'n';
	var $docs_url       = 'http://pixelandtonic.com/wygwam';

	/**
	 * Class Constructor
	 */
	function Wygwam_upload_tags()
	{
		// Make a local reference to the ExpressionEngine super object
		
	}

	// --------------------------------------------------------------------

	/**
	 * Activate Extension
	 */
	function activate_extension()
	{
	   global $DB;
		// add the row to exp_extensions
		$DB->query($DB->insert_string('exp_extensions', array(
			'class'    => 'Wygwam_upload_tags',
			'hook'     => 'wygwam_config',
			'method'   => 'wygwam_config',
			'priority' => 10,
			'version'  => $this->version,
			'enabled'  => 'y'
		)));
	}

	/**
	 * Update Extension
	 */
	function update_extension($current = '')
	{
		// Nothing to change...
		return FALSE;
	}

	/**
	 * Disable Extension
	 */
	function disable_extension()
	{
	   global $DB;
	   
		// Remove all Wygwam_upload_tags_ext rows from exp_extensions
		$DB->query('DELETE FROM exp_extensions WHERE class = "Wygwam_upload_tags"');
	}

	// --------------------------------------------------------------------

	/**
	 * wygwam_config hook
	 */
	function wygwam_config($config, $settings)
	{
	   global $EXT, $FNS, $SESS;
		// If another extension shares the same hook,
		// we need to get the latest and greatest config
		if ($EXT->last_call !== FALSE)
		{
			$config = $EXT->last_call;
		}
      
		// Does this field have an Upload Directory set?
		if ($settings['upload_dir'] && isset($config['filebrowserImageBrowseUrl']))
		{
			// Prepare the tags we wish to parse
			$vars = array(
				'username'  => $SESS->userdata['username'],
				'member_id' => $SESS->userdata['member_id'],
				'group_id'  => $SESS->userdata['group_id']
			);

			// Get a reference to the Upload Directory session array
			$sess =& $_SESSION['wygwam_'.$settings['upload_dir']];

			// Parse the Server Path and URL settings
			$sess['p'] = $FNS->var_swap($sess['p'], $vars); // Server Path
			$sess['u'] = $FNS->var_swap($sess['u'], $vars); // URL
		}
      

		// Return the (unmodified) config
		return $config;
	}
}

// End of file ext.wygwam_upload_tags.php */
// Location: ./system/expressionengine/third_party/wygwam_upload_tags/ext.wygwam_upload_tags.php
