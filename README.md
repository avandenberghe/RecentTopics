Recent Topics for phpBB 3.3
==========

Extension for phpBB to display recent topics on the index page.
Originally based on NV Recent Topics by Joas Schilling ([nickvergessen](https://github.com/nickvergessen)), later maintained by PayBas. Now maintained by [avathar](https://www.avathar.be).

#### Version
v3.0.0 (22/02/2026)

#### Support
- [Support forum](https://www.avathar.be/forum/viewforum.php?f=65)

#### Requirements
- phpBB 3.3.0 or higher
- PHP 7.1.3 or higher

#### Features
- Adds a list of recent (or unread) topics or last reply to topics to the index page
- UCP permissions and settings so users can choose their own preferences to override ACP
- View all recent topics on a dedicated page at `/app.php/rt` (full page) or `/app.php/rt/simple` (minimal, for iframe embedding)
- ACP / UCP Options:
  - Screen location: Top, Bottom or Side
  - Number of topics to show per page
  - Sort by topic start time instead of last post time
  - Only show unread topics
- ACP Options:
  - Enable/disable Recent Topics on the index page
  - Per-forum toggle: enable/disable Recent Topics display per forum (in ACP Forum Management)
  - Show all recent topic pages
  - Maximum number of pages
  - Set minimum topic type level to display (normal/sticky/announcement/global)
  - Exclusion of topics by ID
  - Display parent forum name in the row
  - Reset all user preferences to defaults
  - Built-in version checker (checks avathar.be for updates)
- Permissions: six granular user permissions — view, enable/disable, location, sort order, unread only, number of topics
- New users automatically inherit ACP default preferences on registration
- Inherits all styling from regular "viewforum" templates
- Filters "Re:" from reply subjects
- Compatible with:
  - "Pre:fixed" Extension from imkingdavid
  - "Topic Prefix" Extension from Stathis
  - Official extension "phpbb/topicprefixes"
  - mChat
  - Collapsible Categories v2
- Custom PHP events for extension integration: `topictitle_remove_re`, `sql_pull_topics_list`, `sql_pull_topics_data`, `modify_topics_list`, `modify_topictitle`, `modify_tpl_ary`
- Tested on:
  - prosilver
  - pbTech
  - pbWow3  

#### Languages supported
- English, German, French, Dutch, Spanish, Czech, Russian, Portuguese, Arabic, Ukrainian, Swedish, Slovak

### Installation
1. Disable, delete data and remove the extension paybas/recenttopics.
2. [Download the latest release](https://www.avathar.be/forum/app.php/dlext/details?df_id=35) and unzip it.
3. Copy the entire contents from the unzipped folder to `/ext/avathar/recenttopicsav/`.
4. Navigate in the ACP to `Customise -> Manage extensions`.
5. Find `Recent Topics` under "Disabled Extensions" and click `Enable`.


#### Uninstallation
1. Navigate in the ACP to `Customise -> Manage extensions`.
2. Click the `Disable` link for `Recent Topics`.
3. To permanently uninstall, click `Delete Data`, then delete the `recenttopicsav` folder from `/ext/avathar/`.

### License
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)

Originally by PayBas and nickvergessen. Maintained by Andreas Vandenberghe (avathar).
