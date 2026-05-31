<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @copyright (c) 2017 Sajaki
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Release 3.0.0 — squashed migration combining the historical chain
 * (paybas 2.x through avathar 3.0.9). Adds all rt_* configs and the
 * user_rt_viewforum_location column beyond what basics/ already sets up.
 * Canonical version lives in ext::RT_VERSION; not in phpbb_config.
 */

namespace avathar\recenttopics\migrations\v300;

class release_3_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'users', 'user_rt_viewforum_location');
	}

	public static function depends_on()
	{
		return ['\avathar\recenttopics\migrations\basics\rt_config'];
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'users' => [
					'user_rt_viewforum_location' => ['VCHAR:10', 'RT_TOP'],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns' => [
				$this->table_prefix . 'users' => [
					'user_rt_viewforum_location',
				],
			],
		];
	}

	public function update_data()
	{
		return [
			['config.add', ['rt_page_enable', 1]],
			['config.add', ['rt_topic_link_to', 0]],
			['config.add', ['rt_ads_enable', 0]],
			['config.add', ['rt_show_likes', 1]],
			['config.add', ['rt_side_show_date', 1]],
			['config.add', ['rt_viewforum', 0]],
			['config.add', ['rt_viewforum_location', 'RT_TOP']],
		];
	}

	public function revert_data()
	{
		return [
			['config.remove', ['rt_page_enable']],
			['config.remove', ['rt_topic_link_to']],
			['config.remove', ['rt_ads_enable']],
			['config.remove', ['rt_show_likes']],
			['config.remove', ['rt_side_show_date']],
			['config.remove', ['rt_viewforum']],
			['config.remove', ['rt_viewforum_location']],
		];
	}
}
