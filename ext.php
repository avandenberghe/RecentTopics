<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

namespace avathar\recenttopicsav;

/**
 * Extension class for custom enable/disable/purge actions
 */
class ext extends \phpbb\extension\base
{
	/**
	 * Check whether or not the extension can be enabled.
	 *
	 * Requires phpBB 3.3.0 or higher.
	 *
	 * @return bool|array
	 * @access public
	 */
	public function is_enableable()
	{
		if (phpbb_version_compare(PHPBB_VERSION, '3.3.0', '>='))
		{
			return true;
		}

		$language = $this->container->get('language');
		$language->add_lang('recenttopics', 'avathar/recenttopicsav');
		return [$language->lang('EXTENSION_REQUIRES_330')];
	}
}
