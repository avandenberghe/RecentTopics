-- ---------------------------------------------------------------------------
-- Manual cleanup script for Recent Topics (both paybas and avathar installs)
--
-- This script is NOT run by the extension. An administrator executes it by
-- hand to remove leftovers from a broken or partially removed install, after
-- the normal ACP "Disable" and "Delete data" steps have failed.
--
-- BEFORE YOU RUN THIS
--
--   1. Take a full database backup. Every statement below is destructive and
--      none of it can be undone.
--
--   2. Replace {TABLE_PREFIX} with your board's actual table prefix. It is the
--      value of $table_prefix in your phpBB config.php, and is 'phpbb_' on a
--      default install. The script will not run until you do this.
--
--   3. ALTER TABLE ... DROP COLUMN IF EXISTS requires MySQL 8.0+, MariaDB 10.0+
--      or PostgreSQL. On MySQL 5.7 the IF EXISTS clause is a syntax error, so
--      drop the columns individually and ignore the errors for ones that are
--      already gone.
-- ---------------------------------------------------------------------------

-- Modules
DELETE FROM {TABLE_PREFIX}modules WHERE module_basename LIKE '%recenttopics%' OR module_langname IN ('RECENT_TOPICS', 'RT_CONFIG');

-- Migrations
DELETE FROM {TABLE_PREFIX}migrations WHERE migration_name LIKE '%avathar%recenttopics%' OR migration_name LIKE '%paybas%recenttopics%';

-- Extension state
DELETE FROM {TABLE_PREFIX}ext WHERE ext_name IN ('avathar/recenttopics', 'avathar/recenttopicsav', 'paybas/recenttopics');

-- Permissions
DELETE FROM {TABLE_PREFIX}acl_roles_data WHERE auth_option_id IN (SELECT auth_option_id FROM {TABLE_PREFIX}acl_options WHERE auth_option LIKE 'u_rt_%');
DELETE FROM {TABLE_PREFIX}acl_groups WHERE auth_option_id IN (SELECT auth_option_id FROM {TABLE_PREFIX}acl_options WHERE auth_option LIKE 'u_rt_%');
DELETE FROM {TABLE_PREFIX}acl_users WHERE auth_option_id IN (SELECT auth_option_id FROM {TABLE_PREFIX}acl_options WHERE auth_option LIKE 'u_rt_%');
DELETE FROM {TABLE_PREFIX}acl_options WHERE auth_option LIKE 'u_rt_%';

-- Config
DELETE FROM {TABLE_PREFIX}config WHERE config_name LIKE 'rt_%';

-- Schema columns
ALTER TABLE {TABLE_PREFIX}forums DROP COLUMN IF EXISTS forum_recent_topics;
ALTER TABLE {TABLE_PREFIX}users DROP COLUMN IF EXISTS user_rt_enable;
ALTER TABLE {TABLE_PREFIX}users DROP COLUMN IF EXISTS user_rt_sort_start_time;
ALTER TABLE {TABLE_PREFIX}users DROP COLUMN IF EXISTS user_rt_unread_only;
ALTER TABLE {TABLE_PREFIX}users DROP COLUMN IF EXISTS user_rt_location;
ALTER TABLE {TABLE_PREFIX}users DROP COLUMN IF EXISTS user_rt_number;
ALTER TABLE {TABLE_PREFIX}users DROP COLUMN IF EXISTS user_rt_viewforum_location;

-- Verify: every row returned below must show a count of 0
SELECT 'modules' AS item, COUNT(*) AS cnt FROM {TABLE_PREFIX}modules WHERE module_basename LIKE '%recenttopics%' OR module_langname IN ('RECENT_TOPICS', 'RT_CONFIG')
UNION ALL SELECT 'migrations', COUNT(*) FROM {TABLE_PREFIX}migrations WHERE migration_name LIKE '%recenttopics%'
UNION ALL SELECT 'ext', COUNT(*) FROM {TABLE_PREFIX}ext WHERE ext_name LIKE '%recenttopics%'
UNION ALL SELECT 'acl_options', COUNT(*) FROM {TABLE_PREFIX}acl_options WHERE auth_option LIKE 'u_rt_%'
UNION ALL SELECT 'config', COUNT(*) FROM {TABLE_PREFIX}config WHERE config_name LIKE 'rt_%';
