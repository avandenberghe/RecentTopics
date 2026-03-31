# Recent Topics Extension — Events & Integration Points

## 1. Own Events Emitted (API contract)

This section is the public API contract. Recent Topics deliberately fires these events at specific points in its processing loop so that *other* extensions can hook in and modify the SQL queries, topic data, or template output — without patching any Recent Topics files. If you are building an extension and want to add a column, filter topics, or inject extra per-row data into the recent topics block, this is where to look. Changing anything listed here is a breaking change and requires a major version bump.

Events fired by Recent Topics that other extensions can listen to.

### 1.1 `avathar.recenttopicsav.sql_pull_topics_list`

Modify the SQL query that determines which topic IDs are eligible for display in the recent topics listing. Use this to add extra filtering conditions (e.g. exclude topics by tag, restrict to certain topic types).

- **Placement:** `core\recenttopics::gettopiclist()`
- **Since:** 3.0.0
- **Arguments:**
  - `sql_array` (array) — The SQL array
- **Known listeners:** none

### 1.2 `avathar.recenttopicsav.sql_pull_topics_data`

Modify the SQL query that fetches detailed topic data for display in the recent topics listing. Use this to add LEFT JOINs or SELECT columns (e.g. vse/topicpreview joins first/last post text and avatar data).

- **Placement:** `core\recenttopics::get_topics_sql()`
- **Since:** 3.0.0
- **Arguments:**
  - `sql_array` (array) — The SQL array
- **Known listeners:** none (legacy listeners vse/topicpreview, bb3mobi/lastpostavatar still use `paybas.recenttopics.sql_pull_topics_data`)

### 1.3 `avathar.recenttopicsav.modify_topics_list`

Modify the fetched topic list and rowset before the recent topics display loop starts. Use this to reorder, filter, or enrich topic data in bulk (e.g. rxu/thanks_for_posts loads reputation data, vse/topicpreview loads attachments).

- **Placement:** `core\recenttopics::fill_template()`
- **Since:** 3.0.0
- **Arguments:**
  - `topic_list` (array) — Array of all topic IDs
  - `rowset` (array) — The full topics list array
- **Known listeners:** none (legacy listeners vse/topicpreview, rxu/thanks_for_posts, PayBas/PBWoW3ext still use `paybas.recenttopics.modify_topics_list`)

### 1.4 `avathar.recenttopicsav.topictitle_remove_re`

Clean up the topic row before title rendering. The built-in listener uses this to strip the "Re: " prefix from last post subjects.

- **Placement:** `core\recenttopics::fill_template()`
- **Since:** 3.0.0
- **Arguments:**
  - `row` (array) — The topic row data
- **Known listeners:** internal — `event\listener::topictitle_remove_re()`

### 1.5 `avathar.recenttopicsav.modify_topictitle`

Add or modify the prefix prepended to topic titles in the recent topics listing (e.g. topic type labels, category tags, custom badges).

- **Placement:** `core\recenttopics::fill_template()`
- **Since:** 3.0.0
- **Arguments:**
  - `row` (array) — The topic row data
  - `prefix` (string) — The topic title prefix
- **Known listeners:** none

### 1.6 `avathar.recenttopicsav.modify_tpl_ary`

Modify or add template variables for a topic row just before it is assigned to the template. Use this to inject extra display data per row (e.g. country flags, SEO URLs, preview text, anonymized author info, relative timestamps).

- **Placement:** `core\recenttopics::fill_template()`
- **Since:** 3.0.0
- **Arguments:**
  - `row` (array) — Array with topic data
  - `tpl_ary` (array) — Template block array with topic data
- **Known listeners:** none (legacy listeners vse/topicpreview, rxu/thanks_for_posts, rmcgirr83/nationalflags, Dark1z/memberavatarstatus, tas2580/seourls, toxyy/anonymousposts, MuhClaren/timeago, bb3mobi/lastpostavatar still use `paybas.recenttopics.modify_tpl_ary`)

### Deprecated aliases (removed in 3.1)

These are the original event names from the paybas era. They have been fired since 2.0.x and are still fired alongside their `avathar.recenttopicsav.*` replacements for backward compatibility. They will be removed in 3.1. If your extension listens to any of these, migrate to the corresponding `avathar.recenttopicsav.*` event listed above. See [GitHub issue #169](https://github.com/avatharbe/RecentTopics/issues/169) for the full ecosystem analysis.

#### `paybas.recenttopics.sql_pull_topics_data`

- **Replaced by:** `avathar.recenttopicsav.sql_pull_topics_data`
- **Placement:** `core\recenttopics::get_topics_sql()`
- **Since:** 2.0.0 — **Removed in:** 3.1
- **Arguments:**
  - `sql_array` (array) — The SQL array
- **Known listeners:** vse/topicpreview, bb3mobi/lastpostavatar

#### `paybas.recenttopics.modify_topics_list`

- **Replaced by:** `avathar.recenttopicsav.modify_topics_list`
- **Placement:** `core\recenttopics::fill_template()`
- **Since:** 2.0.1 — **Removed in:** 3.1
- **Arguments:**
  - `topic_list` (array) — Array of all topic IDs
  - `rowset` (array) — The full topics list array
- **Known listeners:** vse/topicpreview, rxu/thanks_for_posts, PayBas/PBWoW3ext

#### `paybas.recenttopics.modify_tpl_ary`

- **Replaced by:** `avathar.recenttopicsav.modify_tpl_ary`
- **Placement:** `core\recenttopics::fill_template()`
- **Since:** 2.0.0 — **Removed in:** 3.1
- **Arguments:**
  - `row` (array) — Array with topic data
  - `tpl_ary` (array) — Template block array with topic data
- **Known listeners:** vse/topicpreview, rxu/thanks_for_posts, rmcgirr83/nationalflags, Dark1z/memberavatarstatus, tas2580/seourls, toxyy/anonymousposts, MuhClaren/timeago, bb3mobi/lastpostavatar

---

## 2. Events & Services Subscribed from Other Extensions

Extensions can listen to each other's events or consume each other's services. This section documents every place where Recent Topics reaches *out* to another extension — for example, calling a service provided by postlove to display like counts, or an operator provided by collapsiblecategories to make the block collapsible. These integrations are always optional (soft-coupled via nullable DI): Recent Topics works normally when the other extension is absent.

Recent Topics integrates with two optional extensions via nullable DI. Neither triggers phpBB events — the integration is service-based.

### 2.1 `avathar/postlove` — Like counts per topic

- **DI reference:** `@?avathar.postlove.topic_likes` (nullable)
- **Integration:** Calls `get_topic_like_counts()` to get aggregated like counts per topic, assigns `TOPIC_LIKES` template var and `S_POSTLOVE` flag
- **Template:** Conditional `{% if S_POSTLOVE %}` blocks in topbottom/side layouts show a Likes column
- **Coupling:** Soft — works without postlove installed

### 2.2 `phpbb/collapsiblecategories` — Collapse/expand RT block

- **DI reference:** `@?phpbb.collapsiblecategories.operator` (nullable)
- **Integration:** Sets `S_EXT_COLCAT_HIDDEN` and `U_EXT_COLCAT_COLLAPSE_URL` template vars
- **Template:** `{% include '@phpbb_collapsiblecategories/collapsible_categories_button.html' ignore missing %}`
- **Coupling:** Soft — gracefully handles null operator

### 2.3 `vse/topicpreview` — Topic hover previews

- **Status:** Working via the deprecated `paybas.recenttopics.*` event aliases (section 1)
- **PHP side:** topicpreview listens to `paybas.recenttopics.sql_pull_topics_data`, `paybas.recenttopics.modify_topics_list`, and `paybas.recenttopics.modify_tpl_ary`
- **Template side:** `topiclist_row_append` is fired; topicpreview injects a preview tooltip there
- **Coupling:** Template + event — no direct DI dependency

### 2.4 `dmzx/mchat` — Side-by-side display

- **Integration:** Template event `recenttopics_mchat_side` in `index_body_markforums_after.html` provides an injection point
- **Coupling:** Template-only — no PHP dependency

---

## 3. phpBB Core Events & Template Events

phpBB itself fires hundreds of named events at key moments — when a page loads, when the ACP saves forum settings, when a user registers, and so on. Extensions hook into these events without modifying any phpBB core files.

**PHP events** are fired from within phpBB's PHP code. Your extension subscribes to them by registering a listener class (implementing `EventSubscriberInterface`). When the event fires, phpBB passes a data object containing variables you can read and write — for example, adding a permission to the ACL system, or saving extra form fields when a forum is edited. PHP events are the right tool when you need to run logic, query the database, or compute something.

**Template events** are fired from within phpBB's HTML templates. Your extension hooks into them simply by creating an HTML file whose name matches the event, placed at `styles/.../template/event/<event_name>.html`. phpBB automatically includes that file at the event location when rendering the page. Template events are the right tool when you only need to inject markup — a column header, a block of rows, a button — at a fixed point in the page layout, without any PHP logic.

This section lists every phpBB hook that Recent Topics uses internally to deliver its own functionality.

### 3.1 PHP Events — Main listener (`event/listener.php`)

| phpBB Core Event | Handler | Purpose |
|---|---|---|
| `core.index_modify_page_title` | `display_rt()` | Render recent topics block on the board index |
| `core.viewonline_overwrite_location` | `viewonline_overwrite_location()` | Show "Viewing Recent Topics" on Who Is Online page |
| `core.acp_manage_forums_request_data` | `acp_manage_forums_request_data()` | Save per-forum RT settings in ACP |
| `core.acp_manage_forums_initialise_data` | `acp_manage_forums_initialise_data()` | Set default RT settings for new forums |
| `core.acp_manage_forums_display_form` | `acp_manage_forums_display_form()` | Display RT settings in ACP forum editor |
| `core.permissions` | `add_permission()` | Register `u_rt_view`, `u_rt_enable`, `u_rt_location` permissions |
| `avathar.recenttopicsav.topictitle_remove_re` | `topictitle_remove_re()` | Self-listener: strip "Re: " prefix from last post subjects |

### 3.2 PHP Events — UCP listener (`event/ucp_listener.php`)

| phpBB Core Event | Handler | Purpose |
|---|---|---|
| `core.ucp_prefs_view_data` | `ucp_prefs_get_data()` | Retrieve user RT preferences |
| `core.ucp_prefs_view_update_data` | `ucp_prefs_set_data()` | Save user RT preferences |
| `core.ucp_register_data_after` | `ucp_register_set_data()` | Set default RT preferences on new registration |

### 3.3 Template Events

phpBB core template events included in the recent topics templates, allowing other extensions to inject content into topic rows.

| Template Event | Fired in | Purpose |
|---|---|---|
| `topiclist_row_prepend` | All 4 layout templates | Before topic title — used by extensions to prepend content |
| `topiclist_row_append` | All 4 layout templates | After topic row — used by `vse/topicpreview` to inject preview tooltip |
| `viewforum_body_topic_author_username_prepend` | topbottom, simple, page | Before topic author username |
| `viewforum_body_topic_author_username_append` | topbottom, simple, page | After topic author username |
| `viewforum_body_last_post_author_username_prepend` | All 4 layout templates | Before last post author username |
| `viewforum_body_last_post_author_username_append` | All 4 layout templates | After last post author username |
| `recenttopics_mchat_side` | `index_body_markforums_after.html` | Injection point for dmzx/mchat in side mode |

Layout templates: `recent_topics_body_topbottom.html`, `recent_topics_body_side.html`, `recent_topics_page.html`, `recent_topics_simple.html` (plus style-specific overrides for pbwow3 and we_clearblue).
