<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @copyright (c) 2017 Sajaki
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Schema migration: adds columns to forums and users tables
 */

namespace avathar\recenttopics\migrations\basics;

class rt_schema extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'users', 'user_rt_enable')
			&& $this->db_tools->sql_column_exists($this->table_prefix . 'forums', 'forum_recent_topics');
	}

	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v320\v320'];
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'forums' => [
					'forum_recent_topics' => ['TINT:1', 1],
				],
				$this->table_prefix . 'users' => [
					'user_rt_enable'          => ['BOOL', 1],
					'user_rt_sort_start_time' => ['BOOL', 0],
					'user_rt_unread_only'     => ['BOOL', 0],
					'user_rt_location'        => ['VCHAR:10', 'RT_TOP'],
					'user_rt_number'          => ['UINT', 5],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns' => [
				$this->table_prefix . 'forums' => [
					'forum_recent_topics',
				],
				$this->table_prefix . 'users' => [
					'user_rt_enable',
					'user_rt_sort_start_time',
					'user_rt_unread_only',
					'user_rt_location',
					'user_rt_number',
				],
			],
		];
	}
}
