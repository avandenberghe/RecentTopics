<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Drops the rt_version config row. From 3.0.9 onward the canonical
 * version is ext::RT_VERSION (class constant), not the DB. config.remove
 * silently no-ops when the key is already absent, so this migration is
 * safe to run on installs that previously had rt_version removed.
 */

namespace avathar\recenttopicsav\migrations\v309;

class release_3_0_9 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return !isset($this->config['rt_version']);
	}

	public static function depends_on()
	{
		return ['\avathar\recenttopicsav\migrations\v308\release_3_0_8'];
	}

	public function update_data()
	{
		return [
			['config.remove', ['rt_version']],
		];
	}
}
