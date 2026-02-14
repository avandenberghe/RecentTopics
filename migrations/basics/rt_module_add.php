<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @copyright (c) 2017 Sajaki
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Module migration: Add avathar/recenttopics ACP module
 */

namespace avathar\recenttopics\migrations\basics;

class rt_module_add extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array(
			'\avathar\recenttopics\migrations\basics\rt_module',
		);
	}

	public function update_data()
	{
		return array(
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'RECENT_TOPICS',
			)),
			array('module.add', array(
				'acp',
				'RECENT_TOPICS',
				array(
					'module_basename' => '\avathar\recenttopics\acp\recenttopics_module',
					'modes'           => array('recenttopics_config'),
				),
			)),
		);
	}

	public function revert_data()
	{
		return array(
			array('module.remove', array(
				'acp',
				'RECENT_TOPICS',
				array(
					'module_basename' => '\avathar\recenttopics\acp\recenttopics_module',
					'modes'           => array('recenttopics_config'),
				),
			)),
			array('module.remove', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'RECENT_TOPICS',
			)),
		);
	}
}
