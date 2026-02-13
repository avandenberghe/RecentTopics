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

namespace avathar\recenttopics\migrations\basics;

class rt_config extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array(
			'\avathar\recenttopics\migrations\basics\rt_perms',
		);
	}

	public function update_data()
	{
		return array(
			array('config.add', array('rt_number', '5')),
			array('config.add', array('rt_page_number', 0)),
			array('config.add', array('rt_page_numbermax', '10')),
			array('config.add', array('rt_anti_topics', 0)),
			array('config.add', array('rt_parents', 1)),
			array('config.add', array('rt_index', 1)),
			array('config.add', array('rt_min_topic_level', 0)),
			array('config.add', array('rt_on_newspage', 0)),
			array('config.add', array('rt_sort_start_time', 0)),
			array('config.add', array('rt_unread_only', 0)),
			array('config.add', array('rt_location', 'RT_TOP')),
		);
	}

	public function revert_data()
	{
		return array(
			array('config.remove', array('rt_number')),
			array('config.remove', array('rt_page_number')),
			array('config.remove', array('rt_page_numbermax')),
			array('config.remove', array('rt_anti_topics')),
			array('config.remove', array('rt_parents')),
			array('config.remove', array('rt_index')),
			array('config.remove', array('rt_min_topic_level')),
			array('config.remove', array('rt_on_newspage')),
			array('config.remove', array('rt_sort_start_time')),
			array('config.remove', array('rt_unread_only')),
			array('config.remove', array('rt_location')),
		);
	}
}
