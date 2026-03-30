# Recent Topics Extension — Events & Integration Points

## 1. Outgoing PHP Events (dispatched by this extension)

Events fired by `core/recenttopics.php` that other extensions can listen to.

### 1.1 sql_pull_topics_list

Modify the SQL query that determines which topic IDs are eligible for display in the recent topics listing. Use this to add extra filtering conditions (e.g. exclude topics by tag, restrict to certain topic types).

- **Current event:** `avathar.recenttopicsav.sql_pull_topics_list`
- **Placement:** `core\recenttopics::gettopiclist()`
- **Since:** 2.0.4
- **Arguments:**
  - `sql_array` (array) — The SQL array
- **Known listeners:** none

### 1.2 sql_pull_topics_data

Modify the SQL query that fetches detailed topic data for display in the recent topics listing. Use this to add LEFT JOINs or SELECT columns (e.g. vse/topicpreview joins first/last post text and avatar data).

- **Current event:** `avathar.recenttopicsav.sql_pull_topics_data`
- **Deprecated alias:** `paybas.recenttopics.sql_pull_topics_data` (since 3.0.5, removed in 3.1)
- **Placement:** `core\recenttopics::get_topics_sql()`
- **Since:** 2.0.0
- **Arguments:**
  - `sql_array` (array) — The SQL array
- **Known listeners for deprecated alias:** vse/topicpreview, bb3mobi/lastpostavatar

### 1.3 modify_topics_list

Modify the fetched topic list and rowset before the recent topics display loop starts. Use this to reorder, filter, or enrich topic data in bulk (e.g. rxu/thanks_for_posts loads reputation data, vse/topicpreview loads attachments).

- **Current event:** `avathar.recenttopicsav.modify_topics_list`
- **Deprecated alias:** `paybas.recenttopics.modify_topics_list` (since 3.0.5, removed in 3.1)
- **Placement:** `core\recenttopics::fill_template()`
- **Since:** 2.0.1
- **Arguments:**
  - `topic_list` (array) — Array of all topic IDs
  - `rowset` (array) — The full topics list array
- **Known listeners for deprecated alias:** vse/topicpreview, rxu/thanks_for_posts, PayBas/PBWoW3ext

### 1.4 topictitle_remove_re

Clean up the topic row before title rendering in the recent topics listing. The built-in listener uses this to strip the "Re: " prefix from last post subjects.

- **Current event:** `avathar.recenttopicsav.topictitle_remove_re`
- **Placement:** `core\recenttopics::fill_template()`
- **Since:** 2.2.11
- **Arguments:**
  - `row` (array) — The topic row data
- **Known listeners:** internal — `event\listener::topictitle_remove_re()`

### 1.5 modify_topictitle

Add or modify the prefix prepended to topic titles in the recent topics listing (e.g. topic type labels, category tags, custom badges).

- **Current event:** `avathar.recenttopicsav.modify_topictitle`
- **Placement:** `core\recenttopics::fill_template()`
- **Since:** 2.1.3
- **Arguments:**
  - `row` (array) — The topic row data
  - `prefix` (string) — The topic title prefix

### 1.6 modify_tpl_ary

Modify or add template variables for a topic row in the recent topics listing just before it is assigned to the template. Use this to inject extra display data per row (e.g. country flags, SEO URLs, preview text, anonymized author info, relative timestamps).

- **Current event:** `avathar.recenttopicsav.modify_tpl_ary`
- **Deprecated alias:** `paybas.recenttopics.modify_tpl_ary` (since 3.0.5, removed in 3.1)
- **Placement:** `core\recenttopics::fill_template()`
- **Since:** 2.0.0
- **Arguments:**
  - `row` (array) — Array with topic data
  - `tpl_ary` (array) — Template block array with topic data
- **Known listeners for deprecated alias:** vse/topicpreview, rxu/thanks_for_posts, rmcgirr83/nationalflags, Dark1z/memberavatarstatus, tas2580/seourls, toxyy/anonymousposts, MuhClaren/timeago, bb3mobi/lastpostavatar

### Deprecated alias summary

Three `paybas.recenttopics.*` events are fired as backward-compat aliases immediately after their `avathar.recenttopicsav.*` equivalents. Extensions should migrate to the `avathar.recenttopicsav.*` event names. The aliases were introduced in 3.0.5 and will be removed in 3.1.

See [GitHub issue #169](https://github.com/avatharbe/RecentTopics/issues/169) for the full ecosystem analysis.

## 2. Incoming PHP Events (subscribed to by this extension)

### 2.1 Main listener (`event/listener.php`)

| phpBB Core Event | Handler | Purpose |
|---|---|---|
| `core.index_modify_page_title` | `display_rt()` | Render recent topics block on the board index |
| `core.viewonline_overwrite_location` | `viewonline_overwrite_location()` | Show "Viewing Recent Topics" on Who Is Online page |
| `core.acp_manage_forums_request_data` | `acp_manage_forums_request_data()` | Save per-forum RT settings in ACP |
| `core.acp_manage_forums_initialise_data` | `acp_manage_forums_initialise_data()` | Set default RT settings for new forums |
| `core.acp_manage_forums_display_form` | `acp_manage_forums_display_form()` | Display RT settings in ACP forum editor |
| `core.permissions` | `add_permission()` | Register u_rt_view, u_rt_enable, u_rt_location permissions |
| `avathar.recenttopicsav.topictitle_remove_re` | `topictitle_remove_re()` | Self-listener: handle "Re: " removal |

### 2.2 UCP listener (`event/ucp_listener.php`)

| phpBB Core Event | Handler | Purpose |
|---|---|---|
| `core.ucp_prefs_view_data` | `ucp_prefs_get_data()` | Retrieve user RT preferences |
| `core.ucp_prefs_view_update_data` | `ucp_prefs_set_data()` | Save user RT preferences |
| `core.ucp_register_data_after` | `ucp_register_set_data()` | Set default RT preferences on new registration |

## 3. Template Events (fired in template files)

phpBB core template events included in the recent topics templates, allowing other extensions to inject content into topic rows.

| Template Event | Fired In | Purpose |
|---|---|---|
| `topiclist_row_prepend` | All 4 layout templates | Before topic title — used by extensions to prepend content |
| `topiclist_row_append` | All 4 layout templates | After topic row — used by `vse/topicpreview` to inject preview tooltip |
| `viewforum_body_topic_author_username_prepend` | topbottom, simple, page | Before topic author username |
| `viewforum_body_topic_author_username_append` | topbottom, simple, page | After topic author username |
| `viewforum_body_last_post_author_username_prepend` | All 4 layout templates | Before last post author username |
| `viewforum_body_last_post_author_username_append` | All 4 layout templates | After last post author username |
| `recenttopics_mchat_side` | index_body_markforums_after.html | Injection point for dmzx/mchat in side mode |

Layout templates: `recent_topics_body_topbottom.html`, `recent_topics_body_side.html`, `recent_topics_page.html`, `recent_topics_simple.html` (plus style-specific overrides for pbwow3 and we_clearblue).

## 4. Optional Extension Dependencies

### 4.1 avathar/postlove — Like counts per topic

- **Detection:** Optional DI in `services.yml` (`@?avathar.postlove.topic_likes`)
- **Integration:** Calls `get_topic_like_counts()` service to get aggregated like counts per topic, assigns `TOPIC_LIKES` template var and `S_POSTLOVE` flag
- **Template:** Conditional `{% if S_POSTLOVE %}` blocks in topbottom/side layouts show a "Likes" column
- **Coupling:** Soft — works without postlove installed, same pattern as collapsiblecategories

### 4.2 phpbb/collapsiblecategories — Collapse/expand RT block

- **Detection:** Nullable DI in `services.yml` (`@?phpbb.collapsiblecategories.operator`)
- **Integration:** Sets `S_EXT_COLCAT_HIDDEN` and `U_EXT_COLCAT_COLLAPSE_URL` template vars
- **Template:** `{% include '@phpbb_collapsiblecategories/collapsible_categories_button.html' ignore missing %}`
- **Coupling:** Soft — gracefully handles null operator

### 4.3 vse/topicpreview — Topic hover previews

- **Status:** Working via deprecated `paybas.recenttopics.*` event aliases (since 3.1.0)
- **Template side:** `topiclist_row_append` is fired, and topicpreview's template checks `recent_topics.TOPIC_PREVIEW_FIRST_POST`
- **PHP side:** topicpreview listens to `paybas.recenttopics.sql_pull_topics_data`, `paybas.recenttopics.modify_topics_list`, and `paybas.recenttopics.modify_tpl_ary`

### 4.4 dmzx/mchat — Side-by-side display

- **Integration:** Template event `recenttopics_mchat_side` provides an injection point
- **Coupling:** Template-only — no PHP dependency
