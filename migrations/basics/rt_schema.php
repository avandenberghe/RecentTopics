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

	static public function depends_on()
	{
		return array(
			'\phpbb\db\migration\data\v320\v320',
		);
	}

	private function column_exists($table, $column)
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . $table, $column);
	}

	public function update_schema()
	{
		$schema = array('add_columns' => array());

		if (!$this->column_exists('forums', 'forum_recent_topics'))
		{
			$schema['add_columns'][$this->table_prefix . 'forums'] = array(
				'forum_recent_topics' => array('TINT:1', 1),
			);
		}

		$user_columns = array(
			'user_rt_enable'          => array('BOOL', 1),
			'user_rt_sort_start_time' => array('BOOL', 0),
			'user_rt_unread_only'     => array('BOOL', 0),
			'user_rt_location'        => array('VCHAR:10', 'RT_TOP'),
			'user_rt_number'          => array('UINT', 5),
		);

		$missing_user_columns = array();
		foreach ($user_columns as $column => $definition)
		{
			if (!$this->column_exists('users', $column))
			{
				$missing_user_columns[$column] = $definition;
			}
		}

		if (!empty($missing_user_columns))
		{
			$schema['add_columns'][$this->table_prefix . 'users'] = $missing_user_columns;
		}

		if (empty($schema['add_columns']))
		{
			return array();
		}

		return $schema;
	}

	public function revert_schema()
	{
		return array(
			'drop_columns' => array(
				$this->table_prefix . 'forums' => array(
					'forum_recent_topics',
				),
				$this->table_prefix . 'users' => array(
					'user_rt_enable',
					'user_rt_sort_start_time',
					'user_rt_unread_only',
					'user_rt_location',
					'user_rt_number',
				),
			),
		);
	}
}
