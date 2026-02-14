<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @copyright (c) 2017 Sajaki
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Module migration: Remove old paybas/recenttopics modules
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
			// Clean up old paybas/recenttopics ext and migration entries
			array('custom', array(array($this, 'cleanup_old_data'))),

			// Remove old child module first (children before parent)
			// module.remove silently returns if the module doesn't exist
			array('module.remove', array('acp', 'RECENT_TOPICS', 'RT_CONFIG')),

			// Remove old RECENT_TOPICS category (now empty)
			array('module.remove', array('acp', 'ACP_CAT_DOT_MODS', 'RECENT_TOPICS')),
		);
	}

	/**
	 * Remove old paybas/recenttopics entries from ext and migrations tables.
	 */
	public function cleanup_old_data()
	{
		$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "ext WHERE ext_name = 'paybas/recenttopics'");
		$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "migrations WHERE migration_name LIKE '%paybas%recenttopics%'");
	}
}
