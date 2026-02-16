<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Migration to add rt_page_enable config setting
 */

namespace avathar\recenttopicsav\migrations\v300;

class rt_page_enable extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\avathar\recenttopicsav\migrations\v300\release_3_0_0'];
	}

	public function effectively_installed()
	{
		return isset($this->config['rt_page_enable']);
	}

	public function update_data()
	{
		return [
			['config.add', ['rt_page_enable', 1]],
		];
	}

	public function revert_data()
	{
		return [
			['config.remove', ['rt_page_enable']],
		];
	}
}
