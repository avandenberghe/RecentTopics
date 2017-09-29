<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

namespace paybas\recenttopics\core;

use part3\topicprefixes\core\topicprefixes;
use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\content_visibility;
use phpbb\db\driver\driver_interface;
use phpbb\event\dispatcher_interface;
use phpbb\pagination;
use phpbb\request\request_interface;
use phpbb\template\template;

/**
 * Class recenttopics
 *
 * @package paybas\recenttopics\core
 */
class recenttopics
{
	/**
	* @var auth
	*/
	protected $auth;

	/**
	* @var config
	*/
	protected $config;

	/**
	* @var \phpbb\cache\service
	*/
	protected $cache;

	/**
	* @var content_visibility
	*/
	protected $content_visibility;

	/**
	* @var driver_interface
	*/
	protected $db;

	/**
	* @var dispatcher_interface
	*/
	protected $dispatcher;

	/**
	* @var pagination
	*/
	protected $pagination;

	/**
	* @var request_interface
	*/
	protected $request;

	/**
	* @var template
	*/
	protected $template;

	/**
	* @var \phpbb\user
	*/
	protected $user;

	/**
	* @var string phpBB root path
	*/
	protected $root_path;

	/**
	* @var string PHP extension
	*/
	protected $phpEx;

	/**
	* @var topicprefixes
	*/
	private $topicprefixes;

	/**
	 * @var manager
	 */
	private $prefixed;

	/**
	* array of allowable forum id's
	*
	* @var array
	*/
	private $forum_ids;

	/**
	* array of topics to show
	*
	* @var array
	*/
	private $topic_list;

	private $unread_only;

	/**
	* show a forum icon ?
	*
	* @var boolean
	*/
	private $obtain_icons;

	/**
	* forum objects we need
	*
	* @var array
	*/
	private $forums;

	/**
	 * recenttopics constructor.
	 *
	 * @param auth                 $auth
	 * @param \phpbb\cache\service $cache
	 * @param config               $config
	 * @param content_visibility   $content_visibility
	 * @param driver_interface     $db
	 * @param dispatcher_interface $dispatcher
	 * @param pagination           $pagination
	 * @param request_interface    $request
	 * @param template             $template
	 * @param \phpbb\user          $user
	 * @param $root_path
	 * @param $phpEx
	 * @param topicprefixes|NULL   $topicprefixes
	 */
	public function __construct(auth $auth,
		\phpbb\cache\service $cache,
		config $config,
		content_visibility $content_visibility,
		driver_interface $db,
		dispatcher_interface $dispatcher,
		pagination $pagination,
		request_interface $request,
		template $template,
		\phpbb\user $user,
		$root_path,
		$phpEx,
		topicprefixes $topicprefixes = null,
		\imkingdavid\prefixed\core\manager $prefixed = null
	)
	{
		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->content_visibility = $content_visibility;
		$this->db = $db;
		$this->dispatcher = $dispatcher;
		$this->pagination = $pagination;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->root_path = $root_path;
		$this->phpEx = $phpEx;
		$this->topicprefixes = $topicprefixes;
		$this->prefixed = $prefixed;
	}

	/**
	 * @param string $tpl_loopname
	 * @param int    $spec_forum_id
	 * @param bool   $include_subforums
	 */
	public function display_recent_topics($tpl_loopname = 'recent_topics', $spec_forum_id = 0, $include_subforums = true)
	{
		// can view rt ?
		if ($this->auth->acl_get('u_rt_view') == '0')
		{
			return;
		}

		// if user can enable recent topics and it is not enabled then return
		if ($this->auth->acl_get('u_rt_enable') && isset($this->user->data['user_rt_enable']) && !$this->user->data['user_rt_enable'])
		{
			return;
		}

		$location = $this->config['rt_location'];
		// if user can set location and it is set then use the preference
		if ($this->auth->acl_get('u_rt_location') && isset($this->user->data['user_rt_location']))
		{
			$location = $this->user->data['user_rt_location'];
		}

		$sort_topics = $this->config['rt_sort_start_time'] ? 'topic_time' : 'topic_last_post_time';
		// if user can set recent topic sorting order and it is set then use the preference
		if ($this->auth->acl_get('u_rt_sort_start_time') && isset($this->user->data['user_rt_sort_start_time']))
		{
			$sort_topics = $this->user->data['user_rt_sort_start_time'] ? 'topic_time' : 'topic_last_post_time';
		}

		//load language
		$this->user->add_lang_ext('paybas/recenttopics', 'recenttopics');

		$topics_per_page = (int) $this->config['rt_number'];
		$rt_page_numbermax = (int) $this->config['rt_page_numbermax'];
		$nolimitpages = (int) $this->config['rt_page_number'];
		if ($nolimitpages == 0)
		{
			$total_topics_limit = $topics_per_page * $rt_page_numbermax;
		}
		else
		{
			// max 100 pages
			$total_topics_limit = $topics_per_page * 100;
		}

		$display_parent_forums = $this->config['rt_parents'];

		$this->unread_only = $this->config['rt_unread_only'];
		if ($this->auth->acl_get('u_rt_unread_only') && isset($this->user->data['user_rt_unread_only']))
		{
			$this->unread_only = $this->user->data['user_rt_unread_only'];
		}

		$start = $this->request->variable($tpl_loopname . '_start', 0);

		if (!function_exists('display_forums'))
		{
			include( $this->root_path . 'includes/functions_display.' . $this->phpEx);
		}

		$this->getforumlist();
		// No forums to display
		if (sizeof($this->forum_ids) == 0)
		{
			return;
		}

		$topics_count = $this->gettopiclist(max(0, min($start,$total_topics_limit)) , $topics_per_page, $total_topics_limit, $sort_topics);

		// If topics to display
		if (sizeof($this->topic_list))
		{
			// Grab icons
			if ($this->obtain_icons)
			{
				$icons = $this->cache->obtain_icons();
			}
			else
			{
				$icons = array();
			}

			// Borrowed from search.php
			foreach ($this->forums as $forum_id => $forum)
			{
				if ($this->user->data['is_registered'] && $this->config['load_db_lastread'])
				{
					$topic_tracking_info[$forum_id] = get_topic_tracking($forum_id, $forum['topic_list'], $forum['rowset'], array($forum_id => $forum['mark_time']), $forum_id ? false : $forum['topic_list']);
				}
				else if ($this->config['load_anon_lastread'] || $this->user->data['is_registered'])
				{
					$tracking_topics = $this->request->variable($this->config['cookie_name'] . '_track', '', true, request_interface::COOKIE);
					$tracking_topics = $tracking_topics ? tracking_unserialize($tracking_topics) : array();

					$topic_tracking_info[$forum_id] = get_complete_topic_tracking($forum_id, $forum['topic_list'], $forum_id ? false : $forum['topic_list']);

					if (!$this->user->data['is_registered'])
					{
						if (isset($tracking_topics['l']))
						{
							$this->user->data['user_lastmark'] =  ( (int) base_convert($tracking_topics['l'], 36, 10) + (int) $this->config['board_startdate']);
						}
						else
						{
							$this->user->data['user_lastmark'] = 0;
						}
					}
				}
			}

			// Now only pull the data of the requested topics
			$sql_array = array(
				'SELECT'    => 't.*, tp.topic_posted, f.forum_name',
				'FROM'      => array(TOPICS_TABLE => 't'),
				'LEFT_JOIN' => array(
					array(
						'FROM' => array(TOPICS_POSTED_TABLE => 'tp'),
						'ON'   => 't.topic_id = tp.topic_id AND tp.user_id = ' . (int) $this->user->data['user_id'],
					),
					array(
						'FROM' => array(FORUMS_TABLE => 'f'),
						'ON'   => 'f.forum_id = t.forum_id',
					),
				),
				'WHERE'     => $this->db->sql_in_set('t.topic_id', $this->topic_list),
				'ORDER_BY'  => 't.' . $sort_topics . ' DESC',
			);

			if ($display_parent_forums)
			{
				$sql_array['SELECT'] .= ', f.parent_id, f.forum_parents, f.left_id, f.right_id';
			}

			/**
			 * Event to modify the SQL query before the topics data is retrieved
			 *
			 * @event paybas.recenttopics.sql_pull_topics_data
			 * @var   array    sql_array        The SQL array
			 * @since 2.0.0
			 */
			extract(
				$this->dispatcher->trigger_event(
					'paybas.recenttopics.sql_pull_topics_data',
					array('sql_array' => $sql_array )
				)
			);

			$sql = $this->db->sql_build_query('SELECT', $sql_array);
			$result = $this->db->sql_query_limit($sql, $topics_per_page);

			$rowset = $topic_icons = array();

			while ($row = $this->db->sql_fetchrow($result))
			{
				$rowset[] = $row;
			}
			$this->db->sql_freeresult($result);

			// if topics returned by DB
			if (sizeof($rowset))
			{
				/**
				 * Event to modify the topics list data before we start the display loop
				 *
				 * @event paybas.recenttopics.modify_topics_list
				 * @var   array    topic_list        Array of all the topic IDs
				 * @var   array    rowset            The full topics list array
				 * @since 2.0.1
				 */
				extract(
					$this->dispatcher->trigger_event(
						'paybas.recenttopics.modify_topics_list',
						array( 'topic_list' => $this->topic_list,'rowset' => $rowset)
					)
				);

				foreach ($rowset as $row)
				{
					$topic_id = $row['topic_id'];
					$forum_id = $row['forum_id'];

					$s_type_switch_test = ($row['topic_type'] == POST_ANNOUNCE || $row['topic_type'] == POST_GLOBAL) ? 1 : 0;
					//$replies = ($this->auth->acl_get('m_approve', $forum_id)) ? $row['topic_replies_real'] : $row['topic_replies'];
					$replies = $this->content_visibility->get_count('topic_posts', $row, $forum_id) - 1;

					// Get folder img, topic status/type related information
					$folder_img = $folder_alt = $topic_type = '';

					if ($this->unread_only)
					{
						topic_status($row, $replies, true, $folder_img, $folder_alt, $topic_type);
						$unread_topic = true;
					}
					else
					{
						if (isset($topic_tracking_info[$forum_id][$row['topic_id']]) && $row['topic_last_post_time'] > $topic_tracking_info[$forum_id][$row['topic_id']])
						{
							topic_status($row, $replies, true, $folder_img, $folder_alt, $topic_type);
						}
						else
						{
							topic_status($row, $replies, false, $folder_img, $folder_alt, $topic_type);
						}

						if (isset($topic_tracking_info[$forum_id][$row['topic_id']]) && $row['topic_last_post_time'] > $topic_tracking_info[$forum_id][$row['topic_id']])
						{
							$unread_topic = true;
						}
						else
						{
							$unread_topic = false;
						}
					}

					$view_topic_url = append_sid("{$this->root_path}viewtopic.$this->phpEx", 'f=' . $forum_id . '&amp;t=' . $topic_id);
					$view_forum_url = append_sid("{$this->root_path}viewforum.$this->phpEx", 'f=' . $forum_id);

					$topic_unapproved = ($row['topic_visibility'] == ITEM_UNAPPROVED && $this->auth->acl_get('m_approve', $forum_id));
					$posts_unapproved = ($row['topic_visibility'] == ITEM_APPROVED && $row['topic_posts_unapproved'] && $this->auth->acl_get('m_approve', $forum_id));

					$u_mcp_queue = ($topic_unapproved || $posts_unapproved) ? append_sid("{$this->root_path}mcp.$this->phpEx", 'i=queue&amp;mode=' . ($topic_unapproved ? 'approve_details' : 'unapproved_posts') . "&amp;t=$topic_id", true, $this->user->session_id) : '';
					$s_type_switch = ($row['topic_type'] == POST_ANNOUNCE || $row['topic_type'] == POST_GLOBAL) ? 1 : 0;

					if (!empty($icons[$row['icon_id']]))
					{
						$topic_icons[] = $topic_id;
					}

					topic_status($row, $replies, $unread_topic, $folder_img, $folder_alt, $topic_type);

					$topic_title = censor_text($row['topic_title']);

					$prefix = '';
					if ($this->topicprefixes !== null)
					{
						// Topic Prefix extension Stathis
						if (!empty($row['topic_prefix']))
						{
							$prefix = '[' . $row['topic_prefix'] . '] ';
						}
					}

					/**
					 * Event to modify the topic title
					 *
					 * @event paybas.recenttopics.modify_topictitle
					 * @var   array    row      the forum row
					 * @var   string    prefix  the topic title prefix
					 * @since 2.1.3
					 */
					$vars = array('row', 'prefix');
					extract($this->dispatcher->trigger_event('paybas.recenttopics.modify_topictitle', compact($vars)));

					//fallback if there is no listener
					if (!$this->is_listening('imkingdavid\prefixed\event\listener', 'paybas.recenttopics.modify_topictitle'))
					{
						if ($this->prefixed !== null)
						{
							// pre:fixed extension
							$prefix_instances = $this->prefixed->get_prefix_instances();
							foreach ($prefix_instances as $key1)
							{
								if ($row['topic_id'] == $key1['topic'])
								{
									$prefixes = $this->prefixed->get_prefixes();
									$prefix = '[' . $prefixes[$key1['prefix']]['title'] . '] ';
								}
							}
						}

					}

					$topic_title = $prefix === '' ? $topic_title : $prefix . ' ' . $topic_title;

					list($topic_author, $topic_author_color, $topic_author_full, $u_topic_author, $last_post_author, $last_post_author_colour, $last_post_author_full, $u_last_post_author) = $this->getusernamestrings($row);

					$tpl_ary = array(
						'FORUM_ID'                => $forum_id,
						'TOPIC_ID'                => $topic_id,
						'TOPIC_AUTHOR'            => $topic_author,
						'TOPIC_AUTHOR_COLOUR'     => $topic_author_color,
						'TOPIC_AUTHOR_FULL'       => $topic_author_full,
						'U_TOPIC_AUTHOR'          => $u_topic_author,
						'FIRST_POST_TIME'         => $this->user->format_date($row['topic_time']),

						'LAST_POST_SUBJECT'       => censor_text($row['topic_last_post_subject']),
						'LAST_POST_TIME'          => $this->user->format_date($row['topic_last_post_time']),
						'LAST_VIEW_TIME'          => $this->user->format_date($row['topic_last_view_time']),
						'LAST_POST_AUTHOR'        => $last_post_author,
						'LAST_POST_AUTHOR_COLOUR' => $last_post_author_colour,
						'LAST_POST_AUTHOR_FULL'   => $last_post_author_full,
						'U_LAST_POST_AUTHOR'      => $u_last_post_author,

						'REPLIES'                 => $replies,
						'VIEWS'                   => $row['topic_views'],
						'TOPIC_TITLE'             => $topic_title,
						'FORUM_NAME'              => $row['forum_name'],

						'TOPIC_TYPE'              => $topic_type,
						'TOPIC_IMG_STYLE'         => $folder_img,
						'TOPIC_FOLDER_IMG'        => $this->user->img($folder_img, $folder_alt),
						'TOPIC_FOLDER_IMG_ALT'    => $this->user->lang[$folder_alt],

						//'NEWEST_POST_IMG'		=> $this->user->img('icon_topic_newest', 'VIEW_NEWEST_POST'), // dupe?
						'TOPIC_ICON_IMG'          => (!empty($icons[$row['icon_id']])) ? $icons[$row['icon_id']]['img'] : '',
						'TOPIC_ICON_IMG_WIDTH'    => (!empty($icons[$row['icon_id']])) ? $icons[$row['icon_id']]['width'] : '',
						'TOPIC_ICON_IMG_HEIGHT'   => (!empty($icons[$row['icon_id']])) ? $icons[$row['icon_id']]['height'] : '',
						'ATTACH_ICON_IMG'         => ($this->auth->acl_get('u_download') && $this->auth->acl_get('f_download', $forum_id) && $row['topic_attachment']) ? $this->user->img('icon_topic_attach', $this->user->lang['TOTAL_ATTACHMENTS']) : '',
						'UNAPPROVED_IMG'          => ($topic_unapproved || $posts_unapproved) ? $this->user->img('icon_topic_unapproved', $topic_unapproved ? 'TOPIC_UNAPPROVED' : 'POSTS_UNAPPROVED') : '',
						'REPORTED_IMG'            => ($row['topic_reported'] && $this->auth->acl_get('m_report', $forum_id)) ? $this->user->img('icon_topic_reported', 'TOPIC_REPORTED') : '',
						'S_HAS_POLL'              => $row['poll_start'] ? true : false,

						'S_TOPIC_TYPE'            => $row['topic_type'],
						'S_USER_POSTED'           => isset($row['topic_posted']) && $row['topic_posted'],
						'S_UNREAD_TOPIC'          => $unread_topic,
						'S_TOPIC_REPORTED'        => $row['topic_reported'] && $this->auth->acl_get('m_report', $forum_id),
						'S_TOPIC_UNAPPROVED'      => $topic_unapproved,
						'S_POSTS_UNAPPROVED'      => $posts_unapproved,
						'S_POST_ANNOUNCE'         => $row['topic_type'] == POST_ANNOUNCE,
						'S_POST_GLOBAL'           => $row['topic_type'] == POST_GLOBAL,
						'S_POST_STICKY'           => $row['topic_type'] == POST_STICKY,
						'S_TOPIC_LOCKED'          => $row['topic_status'] == ITEM_LOCKED,
						'S_TOPIC_MOVED'           => $row['topic_status'] == ITEM_MOVED,
						'S_TOPIC_TYPE_SWITCH'     => ($s_type_switch == $s_type_switch_test) ? -1 : $s_type_switch_test,

						'U_NEWEST_POST'           => $view_topic_url . '&amp;view=unread#unread',
						'U_LAST_POST'             => $view_topic_url . '&amp;p=' . $row['topic_last_post_id'] . '#p' . $row['topic_last_post_id'],
						'U_VIEW_TOPIC'            => $view_topic_url,
						'U_VIEW_FORUM'            => $view_forum_url,
						'U_MCP_REPORT'            => append_sid("{$this->root_path}mcp.$this->phpEx", 'i=reports&amp;mode=reports&amp;f=' . $forum_id . '&amp;t=' . $topic_id, true, $this->user->session_id),
						'U_MCP_QUEUE'             => $u_mcp_queue,
					);

					/**
					 * Modify the topic data before it is assigned to the template
					 *
					 * @event paybas.recenttopics.modify_tpl_ary
					 * @var   array    row            Array with topic data
					 * @var   array    tpl_ary        Template block array with topic data
					 * @since 2.0.0
					 */
					$vars = array('row', 'tpl_ary');
					extract($this->dispatcher->trigger_event('paybas.recenttopics.modify_tpl_ary', compact($vars)));

					$this->template->assign_block_vars($tpl_loopname, $tpl_ary);

					$this->pagination->generate_template_pagination($view_topic_url, $tpl_loopname . '.pagination', 'start', $replies + 1, $this->config['posts_per_page'], 1, true, true);

					if ($display_parent_forums)
					{
						$forum_parents = get_forum_parents($row);

						foreach ($forum_parents as $parent_id => $data)
						{
							$this->template->assign_block_vars(
								$tpl_loopname . '.parent_forums', array(
									'FORUM_ID'     => $parent_id,
									'FORUM_NAME'   => $data[0],
									'U_VIEW_FORUM' => append_sid("{$this->root_path}viewforum.$this->phpEx", 'f=' . $parent_id),
								)
							);
						}
					}
				}// end rowsset

				// Get URL-parameters for pagination
				$url_params = explode('&', $this->user->page['query_string']);
				$append_params = array();
				foreach ($url_params as $param)
				{
					if (!$param)
					{
						continue;
					}
					if (strpos($param, '=') === false)
					{
						// Fix MSSTI Advanced BBCode MOD
						$append_params[$param] = '1';
						continue;
					}
					list($name, $value) = explode('=', $param);
					if ($name != $tpl_loopname . '_start')
					{
						$append_params[$name] = $value;
					}
				}

				$pagination_url = append_sid($this->root_path . $this->user->page['page_name'], $append_params);
				$this->pagination->generate_template_pagination($pagination_url, 'pagination', $tpl_loopname . '_start', $topics_count, $topics_per_page, max(0, min($start,$total_topics_limit)));

				$this->template->assign_vars(
					array(
						'S_TOPIC_ICONS'                        => sizeof($topic_icons) ? true : false,
					)
				);

			}// topics found
		}//endif

		$this->template->assign_vars(
			array(
				'RT_SORT_START_TIME'                   => $sort_topics === 'topic_time',
				'S_RECENT_TOPICS'                      => true,
				'S_LOCATION_TOP'                       => $location == 'RT_TOP',
				'S_LOCATION_BOTTOM'                    => $location == 'RT_BOTTOM',
				'S_LOCATION_SIDE'                      => $location == 'RT_SIDE',
				'NEWEST_POST_IMG'                      => $this->user->img('icon_topic_newest', 'VIEW_NEWEST_POST'),
				'LAST_POST_IMG'                        => $this->user->img('icon_topic_latest', 'VIEW_LATEST_POST'),
				'POLL_IMG'                             => $this->user->img('icon_topic_poll', 'TOPIC_POLL'),
				strtoupper($tpl_loopname) . '_DISPLAY' => true,
			)
		);
	}


	/**
	 * Get the forums we take our topics from
	 */
	private function getforumlist()
	{
		// Get the allowed forums
		$forum_ary = array();
		$forum_read_ary = $this->auth->acl_getf('f_read');
		foreach ($forum_read_ary as $forum_id => $allowed)
		{
			if ($allowed['f_read'])
			{
				$forum_ary[] = (int) $forum_id;
			}
		}
		$this->forum_ids = array_unique($forum_ary);

		if (sizeof($this->forum_ids) > 1)
		{
			$sql = 'SELECT forum_id
					FROM ' . FORUMS_TABLE . '
					WHERE ' . $this->db->sql_in_set('forum_id', $this->forum_ids) . '
					AND forum_recent_topics = 1';

			$result = $this->db->sql_query($sql);

			$this->forum_ids = array();
			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->forum_ids[] = $row['forum_id'];
			}
			$this->db->sql_freeresult($result);
			$this->forum_ids = array_unique($this->forum_ids);

		}
	}

	/**
	 * Get the topic list
	 *
	 * @param  $start
	 * @param  $topics_per_page
	 * @param  $total_topics_limit
	 * @param  $sort_topics
	 * @return int
	 */
	private function gettopiclist($start, $topics_per_page, $total_topics_limit, $sort_topics)
	{
		$this->forums = $this->topic_list = array();
		$topics_count = 0;
		$this->obtain_icons = false;
		$excluded_topics = explode(',', $this->config['rt_anti_topics']);
		$min_topic_level = $this->config['rt_min_topic_level'];

		// Either use the phpBB core function to get unread topics, or the custom function for default behavior
		if ($this->unread_only && $this->user->data['user_id'] != ANONYMOUS)
		{
			// Get unread topics
			$sql_extra = ' AND ' . $this->db->sql_in_set('t.topic_id', $excluded_topics, true);
			$sql_extra .= ' AND ' . $this->content_visibility->get_forums_visibility_sql('topic', $this->forum_ids, $table_alias = 't.');
			$unread_topics = get_unread_topics(false, $sql_extra, '', $total_topics_limit);
			$start = min(count($unread_topics) - 1 , $start);

			foreach ($unread_topics as $topic_id => $mark_time)
			{
				$topics_count++;
				if (($topics_count > $start) && ($topics_count <= ($start + $topics_per_page)))
				{
					$this->topic_list[] = $topic_id;
				}
			}
		}
		else
		{
			// Get the allowed topics
			$sql_array = array(
				'SELECT'    => 't.forum_id, t.topic_id, t.topic_type, t.icon_id, tt.mark_time, ft.mark_time as f_mark_time, t.' . $sort_topics . ' as sortcr ',
				'FROM'      => array(TOPICS_TABLE => 't'),
				'LEFT_JOIN' => array(
					array(
						'FROM' => array(TOPICS_TRACK_TABLE => 'tt'),
						'ON'   => 'tt.topic_id = t.topic_id AND tt.user_id = ' . (int) $this->user->data['user_id'],
					),
					array(
						'FROM' => array(FORUMS_TRACK_TABLE => 'ft'),
						'ON'   => 'ft.forum_id = t.forum_id AND ft.user_id = ' . (int) $this->user->data['user_id'],
					),
				),
				'WHERE'     => $this->db->sql_in_set('t.topic_id', $excluded_topics, true) . '
					AND t.topic_status <> ' . ITEM_MOVED . '
					AND ' . $this->content_visibility->get_forums_visibility_sql('topic', $this->forum_ids, $table_alias = 't.'),
				'ORDER_BY'  => 't.' . $sort_topics . ' DESC',
			);

			// Check if we want all topics, or only stickies/announcements/globals
			if ($min_topic_level > 0)
			{
				$sql_array['WHERE'] .= ' AND t.topic_type >= ' . (int) $min_topic_level;
			}

			/**
			 * Event to modify the SQL query before the allowed topics list data is retrieved
			 *
			 * @event paybas.recenttopics.sql_pull_topics_list
			 * @var   array    sql_array        The SQL array
			 * @since 2.0.4
			 */
			$vars = array('sql_array');
			extract($this->dispatcher->trigger_event('paybas.recenttopics.sql_pull_topics_list', compact($vars)));

			$sql = $this->db->sql_build_query('SELECT', $sql_array);
			$result = $this->db->sql_query_limit($sql, $total_topics_limit);
			$start = min($result->num_rows - 1 , $start);

			while ($row = $this->db->sql_fetchrow($result))
			{
				$topics_count++;
				if (($topics_count > $start) && ($topics_count <= ($start + $topics_per_page)))
				{
					$this->topic_list[] = $row['topic_id'];

					$rowset[$row['topic_id']] = $row;
					if (!isset($this->forums[$row['forum_id']]) && $this->user->data['is_registered'] && $this->config['load_db_lastread'])
					{
						$this->forums[$row['forum_id']]['mark_time'] = $row['f_mark_time'];
					}
					$this->forums[$row['forum_id']]['topic_list'][] = $row['topic_id'];
					$this->forums[$row['forum_id']]['rowset'][$row['topic_id']] = & $rowset[$row['topic_id']];

					if ($row['icon_id'])
					{
						$this->obtain_icons = true;
					}

				}
			}
			$this->db->sql_freeresult($result);
		}

		return $topics_count;

	}

	/**
	 * @param $row
	 * @return array
	 */
	private function getusernamestrings($row)
	{
		$topic_author       = get_username_string('username', $row['topic_poster'], $row['topic_first_poster_name'], $row['topic_first_poster_colour']);
		$topic_author_color = get_username_string('colour', $row['topic_poster'], $row['topic_first_poster_name'], $row['topic_first_poster_colour']);
		$topic_author_full  = get_username_string('full', $row['topic_poster'], $row['topic_first_poster_name'], $row['topic_first_poster_colour']);
		$u_topic_author     = get_username_string('profile', $row['topic_poster'], $row['topic_first_poster_name'], $row['topic_first_poster_colour']);
		$last_post_author        = get_username_string('username', $row['topic_last_poster_id'], $row['topic_last_poster_name'], $row['topic_last_poster_colour']);
		$last_post_author_colour = get_username_string('colour', $row['topic_last_poster_id'], $row['topic_last_poster_name'], $row['topic_last_poster_colour']);
		$last_post_author_full   = get_username_string('full', $row['topic_last_poster_id'], $row['topic_last_poster_name'], $row['topic_last_poster_colour']);
		$u_last_post_author      = get_username_string('profile', $row['topic_last_poster_id'], $row['topic_last_poster_name'], $row['topic_last_poster_colour']);
		return array($topic_author, $topic_author_color, $topic_author_full, $u_topic_author, $last_post_author, $last_post_author_colour, $last_post_author_full, $u_last_post_author);
	}


	/**
	 * this helper function checks if anyone is listening to events
	 * @param string $class
	 * @param string $event
	 * @return bool
	 */
	public function is_listening($class, $event)
	{
		$listeners = $this->dispatcher->getListeners($event);

		foreach ($listeners as $listener)
		{
			if (is_a($listener[0], $class))
			{
				return true;
			}
		}

		return false;
	}

}
