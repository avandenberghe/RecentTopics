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

namespace avathar\recenttopicsav\migrations\basics;

class rt_module extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		$sql = 'SELECT COUNT(*) as cnt FROM ' . $this->table_prefix . "ext WHERE ext_name = 'paybas/recenttopics'";
		$result = $this->db->sql_query($sql);
		$count = (int) $this->db->sql_fetchfield('cnt');
		$this->db->sql_freeresult($result);

		return $count === 0;
	}

	public static function depends_on()
	{
		return ['\avathar\recenttopicsav\migrations\basics\rt_schema'];
	}

	public function update_data()
	{
		return [
			['custom', [[$this, 'cleanup_old_data']]],
		];
	}

	/**
	 * Remove old paybas/recenttopics entries from ext, migrations tables and ACP modules.
	 */
	public function cleanup_old_data()
	{
		// Remove old ext and migration entries
		$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "ext WHERE ext_name = 'paybas/recenttopics'");
		$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "migrations WHERE migration_name LIKE '%paybas%recenttopics%'");

		// Remove old ACP modules (children first, then parent)
		$sql = 'DELETE FROM ' . $this->table_prefix . "modules
			WHERE module_class = 'acp'
				AND (module_basename LIKE '%paybas%recenttopics%' OR module_langname = 'RT_CONFIG')";
		$this->db->sql_query($sql);

		$sql = 'SELECT module_id FROM ' . $this->table_prefix . "modules
			WHERE module_class = 'acp'
				AND module_langname = 'RECENT_TOPICS'
				AND module_basename = ''";
		$result = $this->db->sql_query($sql);
		$module_id = $this->db->sql_fetchfield('module_id');
		$this->db->sql_freeresult($result);

		if ($module_id)
		{
			// Only remove the category if it has no remaining children
			$sql = 'SELECT COUNT(*) as cnt FROM ' . $this->table_prefix . "modules
				WHERE parent_id = " . (int) $module_id;
			$result = $this->db->sql_query($sql);
			$children = (int) $this->db->sql_fetchfield('cnt');
			$this->db->sql_freeresult($result);

			if ($children === 0)
			{
				$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "modules WHERE module_id = " . (int) $module_id);
			}
		}
	}
}
