<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace avathar\recenttopicsav\migrations\v307;

class release_3_0_7 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['rt_version']) && version_compare($this->config['rt_version'], '3.0.7', '>=');
	}

	public static function depends_on()
	{
		return ['\avathar\recenttopicsav\migrations\v306\release_3_0_6'];
	}

	public function update_data()
	{
		return [
			['config.update', ['rt_version', '3.0.7']],
		];
	}
}
