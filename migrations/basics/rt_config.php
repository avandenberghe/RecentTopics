<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @copyright (c) 2017 Sajaki
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Config migration: extension configuration values
 */

namespace avathar\recenttopicsav\migrations\basics;

class rt_config extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->config->offsetExists('rt_number');
	}

	public static function depends_on()
	{
		return ['\avathar\recenttopicsav\migrations\basics\rt_perms'];
	}

	public function update_data()
	{
		return [
			['config.add', ['rt_number', '5']],
			['config.add', ['rt_page_number', 0]],
			['config.add', ['rt_page_numbermax', '10']],
			['config.add', ['rt_anti_topics', 0]],
			['config.add', ['rt_parents', 1]],
			['config.add', ['rt_index', 1]],
			['config.add', ['rt_min_topic_level', 0]],
			['config.add', ['rt_sort_start_time', 0]],
			['config.add', ['rt_unread_only', 0]],
			['config.add', ['rt_location', 'RT_TOP']],
		];
	}

	public function revert_data()
	{
		return [
			['config.remove', ['rt_number']],
			['config.remove', ['rt_page_number']],
			['config.remove', ['rt_page_numbermax']],
			['config.remove', ['rt_anti_topics']],
			['config.remove', ['rt_parents']],
			['config.remove', ['rt_index']],
			['config.remove', ['rt_min_topic_level']],
			['config.remove', ['rt_sort_start_time']],
			['config.remove', ['rt_unread_only']],
			['config.remove', ['rt_location']],
		];
	}
}
