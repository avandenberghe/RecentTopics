<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @copyright (c) 2017 Sajaki
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Module migration: ACP module registration
 */

namespace avathar\recenttopics\migrations\basics;

class rt_module extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array(
			'\avathar\recenttopics\migrations\basics\rt_schema',
		);
	}

	public function update_data()
	{
		return array(
			// Clean up ALL old recenttopics modules (paybas + broken avathar attempts)
			array('custom', array(array($this, 'cleanup_old_modules'))),

			// ACP modules via phpBB's module.add (handles nested set tree correctly)
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

	/**
	 * Remove ALL old recenttopics modules and paybas remnants so module.add can work cleanly.
	 * Uses phpBB's module tool to maintain the nested set tree integrity.
	 */
	public function cleanup_old_modules()
	{
		// Remove old paybas/recenttopics from ext table
		$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "ext WHERE ext_name = 'paybas/recenttopics'");

		// Remove old paybas migration entries
		$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "migrations WHERE migration_name LIKE '%paybas%recenttopics%'");

		// Use phpBB's module tool for proper nested set handling
		$module_tool = $GLOBALS['phpbb_container']->get('migrator.tool.module');

		// Remove old paybas child module if it exists
		if ($module_tool->exists('acp', 'RECENT_TOPICS', '\\paybas\\recenttopics\\acp\\recenttopics_module'))
		{
			$module_tool->remove('acp', 'RECENT_TOPICS', array(
				'module_basename' => '\\paybas\\recenttopics\\acp\\recenttopics_module',
				'modes'           => array('recenttopics_config'),
			));
		}

		// Remove old avathar child module from previous broken attempts
		if ($module_tool->exists('acp', 'RECENT_TOPICS', '\\avathar\\recenttopics\\acp\\recenttopics_module'))
		{
			$module_tool->remove('acp', 'RECENT_TOPICS', array(
				'module_basename' => '\\avathar\\recenttopics\\acp\\recenttopics_module',
				'modes'           => array('recenttopics_config'),
			));
		}

		// Remove RECENT_TOPICS category if it exists
		if ($module_tool->exists('acp', 'ACP_CAT_DOT_MODS', 'RECENT_TOPICS'))
		{
			$module_tool->remove('acp', 'ACP_CAT_DOT_MODS', 'RECENT_TOPICS');
		}
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
