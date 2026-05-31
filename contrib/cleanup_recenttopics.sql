-- Cleanup script for recenttopics (both paybas and avathar)
-- Modules
DELETE FROM phpbb_modules WHERE module_basename LIKE '%recenttopics%' OR module_langname IN ('RECENT_TOPICS', 'RT_CONFIG');

-- Migrations
DELETE FROM phpbb_migrations WHERE migration_name LIKE '%avathar%recenttopics%' OR migration_name LIKE '%paybas%recenttopics%';

-- Extension state
DELETE FROM phpbb_ext WHERE ext_name IN ('avathar/recenttopics', 'avathar/recenttopicsav', 'paybas/recenttopics');

-- Permissions
DELETE FROM phpbb_acl_roles_data WHERE auth_option_id IN (SELECT auth_option_id FROM phpbb_acl_options WHERE auth_option LIKE 'u_rt_%');
DELETE FROM phpbb_acl_groups WHERE auth_option_id IN (SELECT auth_option_id FROM phpbb_acl_options WHERE auth_option LIKE 'u_rt_%');
DELETE FROM phpbb_acl_users WHERE auth_option_id IN (SELECT auth_option_id FROM phpbb_acl_options WHERE auth_option LIKE 'u_rt_%');
DELETE FROM phpbb_acl_options WHERE auth_option LIKE 'u_rt_%';

-- Config
DELETE FROM phpbb_config WHERE config_name LIKE 'rt_%';

-- Schema columns
ALTER TABLE phpbb_forums DROP COLUMN IF EXISTS forum_recent_topics;
ALTER TABLE phpbb_users DROP COLUMN IF EXISTS user_rt_enable;
ALTER TABLE phpbb_users DROP COLUMN IF EXISTS user_rt_sort_start_time;
ALTER TABLE phpbb_users DROP COLUMN IF EXISTS user_rt_unread_only;
ALTER TABLE phpbb_users DROP COLUMN IF EXISTS user_rt_location;
ALTER TABLE phpbb_users DROP COLUMN IF EXISTS user_rt_number;
ALTER TABLE phpbb_users DROP COLUMN IF EXISTS user_rt_viewforum_location;

-- Verify
SELECT 'modules' AS item, COUNT(*) AS cnt FROM phpbb_modules WHERE module_basename LIKE '%recenttopics%' OR module_langname IN ('RECENT_TOPICS', 'RT_CONFIG')
UNION ALL SELECT 'migrations', COUNT(*) FROM phpbb_migrations WHERE migration_name LIKE '%recenttopics%'
UNION ALL SELECT 'ext', COUNT(*) FROM phpbb_ext WHERE ext_name LIKE '%recenttopics%'
UNION ALL SELECT 'acl_options', COUNT(*) FROM phpbb_acl_options WHERE auth_option LIKE 'u_rt_%'
UNION ALL SELECT 'config', COUNT(*) FROM phpbb_config WHERE config_name LIKE 'rt_%';
