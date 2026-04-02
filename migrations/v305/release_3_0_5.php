<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace avathar\recenttopicsav\migrations\v305;

class release_3_0_5 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['rt_version']) && version_compare($this->config['rt_version'], '3.0.5', '>=');
	}

	public static function depends_on()
	{
		return ['\avathar\recenttopicsav\migrations\v304\release_3_0_4'];
	}

	public function update_data()
	{
		return [
			['config.update', ['rt_version', '3.0.5']],
		];
	}
}
