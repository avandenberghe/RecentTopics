<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace avathar\recenttopicsav\migrations\v308;

class release_3_0_8 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['rt_version']) && version_compare($this->config['rt_version'], '3.0.8', '>=');
	}

	public static function depends_on()
	{
		return ['\avathar\recenttopicsav\migrations\v307\release_3_0_7'];
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
			['config.add', ['rt_viewforum', 0]],
			['config.add', ['rt_viewforum_location', 'RT_TOP']],
			['config.update', ['rt_version', '3.0.8']],
		];
	}
}
