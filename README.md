Recent Topics for phpBB 3.3
==========

[![Tests](https://github.com/avatharbe/RecentTopics/actions/workflows/tests.yml/badge.svg)](https://github.com/avatharbe/RecentTopics/actions/workflows/tests.yml)

Extension for phpBB to display recent topics on the index page.
Originally based on NV Recent Topics by Joas Schilling ([nickvergessen](https://github.com/nickvergessen)), later maintained by PayBas. Now maintained by [avathar](https://www.avathar.be).

**Version:** 3.0.4 (29/03/2026)

#### Requirements
- phpBB 3.3.0 or higher
- PHP 7.1.3 or higher

#### Features
- Recent (or unread) topics list on the index page
- Standalone pages at `/app.php/rt` (full) and `/app.php/rt/simple` (for iframe embedding)
- Three display locations: Top, Bottom or Side
- User-overridable preferences via UCP (location, count, sort order, unread only)
- Six granular user permissions
- New users inherit ACP defaults on registration
- Filters "Re:" from reply subjects
- Custom PHP events for extension developers

#### ACP Options
- Enable/disable on index page
- Per-forum include/exclude (in ACP Forum Management)
- Pagination: page limit, show all pages toggle
- Minimum topic type level (normal/sticky/announcement/global)
- Exclude topics by ID
- Display parent forum names
- Topic title link target (first post, last post, or first unread)
- Optional advertisement/HTML block in sidebar
- Show/hide date in side view
- Show/hide postlove like counts (hidden when postlove is not installed)
- Reset all user preferences to defaults
- Built-in version checker

#### Extension integrations
All integrations are optional soft dependencies — Recent Topics works without any of them.
- [Post Love](https://github.com/avatharbe/postlove) (avathar/postlove) like counts per topic (column in top/bottom view, inline hearts in side view)
- [Collapsible Categories](https://www.phpbb.com/customise/db/extension/collapsible_forum_categories/) (phpbb/collapsiblecategories) — collapse/expand the Recent Topics block
- [Topic Prefixes](https://www.phpbb.com/customise/db/extension/topicprefixes/) (phpbb/topicprefixes) — topic prefixes in the listing
- [mChat](https://www.phpbb.com/customise/db/extension/mchat_extension/) (dmzx/mchat) — side-by-side display via template event

#### Languages
English, German, French, Dutch, Spanish, Czech, Slovak, Russian, Portuguese, Arabic, Ukrainian, Swedish

#### Tested on
prosilver, pbTech, pbWow3

#### Installation
1. Disable, delete data and remove the extension paybas/recenttopics if previously installed.
2. [Download the latest release](https://www.avathar.be/forum/app.php/dlext/details?df_id=35) and unzip it.
3. Copy the contents to `/ext/avathar/recenttopicsav/` (so that `ext.php` is at `/ext/avathar/recenttopicsav/ext.php`).
4. Navigate in the ACP to `Customise -> Manage extensions`.
5. Find `Recent Topics` under "Disabled Extensions" and click `Enable`.

#### Uninstallation
1. Navigate in the ACP to `Customise -> Manage extensions`.
2. Click the `Disable` link for `Recent Topics`.
3. To permanently uninstall, click `Delete Data`, then delete the `recenttopicsav` folder from `/ext/avathar/`.

#### Support
- [Support forum](https://www.avathar.be/forum/viewforum.php?f=16)

#### License
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)

Originally by PayBas and nickvergessen. Maintained by Andreas Vandenberghe (avathar).
