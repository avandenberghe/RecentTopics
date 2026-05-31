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

class rt_module_add extends \phpbb\db\migration\container_aware_migration
{
	public function effectively_installed()
	{
		$sql = 'SELECT module_id
			FROM ' . $this->table_prefix . "modules
			WHERE module_class = 'acp'
				AND module_basename = '\avathar\recenttopics\acp\recenttopics_module'";
		$result = $this->db->sql_query($sql);
		$module_id = $this->db->sql_fetchfield('module_id');
		$this->db->sql_freeresult($result);

		return $module_id !== false;
	}

	public static function depends_on()
	{
		return ['\avathar\recenttopics\migrations\basics\rt_module'];
	}

	public function update_data()
	{
		return [
			['custom', [[$this, 'add_modules']]],
		];
	}

	public function add_modules()
	{
		$module_tool = $this->container->get('migrator.tool.module');

		$module_tool->add('acp', 'ACP_CAT_DOT_MODS', 'RECENT_TOPICS');
		$module_tool->add('acp', 'RECENT_TOPICS', [
			'module_basename' => '\avathar\recenttopics\acp\recenttopics_module',
			'modes'           => ['recenttopics_config'],
		]);
	}

	public function revert_data()
	{
		return [
			['module.remove', [
				'acp',
				'RECENT_TOPICS',
				[
					'module_basename' => '\avathar\recenttopics\acp\recenttopics_module',
					'modes'           => ['recenttopics_config'],
				],
			]],
			['module.remove', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'RECENT_TOPICS',
			]],
		];
	}
}
