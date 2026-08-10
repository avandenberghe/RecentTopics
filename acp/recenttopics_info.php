<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

namespace avathar\recenttopics\acp;

/**
 * ACP module declaration for Recent Topics.
 *
 * phpBB reads this when the module is added — by the extension's migration on install, or by an
 * admin adding it manually — to learn which class serves the page and what to call it.
 *
 * @package avathar\recenttopics\acp
 */
class recenttopics_info
{
	/**
	 * Declare the module's class, title and modes.
	 *
	 * The single 'recenttopics_config' mode maps to recenttopics_module::main(). Its auth string
	 * requires both that this extension is enabled and that the admin holds a_board.
	 *
	 * @return array Module definition for phpBB's module manager
	 */
	public function module()
	{
		return array(
		'filename'    => '\avathar\recenttopics\acp\recenttopics_module',
		'title'        => 'RECENT_TOPICS',
		'modes'        => array(
		'recenttopics_config' => array('title' => 'RT_CONFIG', 'auth' => 'ext_avathar/recenttopics && acl_a_board', 'cat' => array('RECENT_TOPICS')),
		),
		);
	}
}
