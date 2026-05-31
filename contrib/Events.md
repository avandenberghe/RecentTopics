# Recent Topics Extension — Events & Integration Points

## What are phpBB events?

phpBB is built around an *event system*. At hundreds of specific moments during a page request — when a topic list is about to be rendered, when the board index is loading, when an admin saves a forum setting — phpBB fires a named event and passes a bag of data along with it. Extensions register *listeners* that subscribe to these events by name. When the event fires, phpBB calls each listener in turn, letting it read and modify the data bag before the next step runs.

This means extensions never need to modify phpBB core files. An extension that wants to add a column to the topic list simply listens to the right event, adds its column data, and phpBB's template engine picks it up automatically.

There are two kinds of events:

**PHP events** fire inside phpBB's PHP code. Your extension subscribes by writing a listener class that implements `EventSubscriberInterface` and declaring which event names map to which methods. When the event fires, your method receives a `\phpbb\event\data` object — an array-like container of variables you can read and write back.

**Template events** fire inside phpBB's Twig templates. Your extension hooks in simply by placing an HTML file at `styles/prosilver/template/event/<event_name>.html`. phpBB automatically includes that file at the matching point in the page, with no PHP code needed. Template events are the right tool when you only want to inject a button, a column header, or a block of markup.

---

## What is the DI container?

The *dependency injection (DI) container* is phpBB's system for wiring services together. A *service* is any PHP object that does a specific job — for example, querying like counts from the database, or checking whether a category should be collapsed. Services are registered by name in `config/services.yml` files and phpBB automatically creates them and passes them to other services that need them.

When an extension wants to use a service from *another* extension, it can declare the dependency as **nullable** using the `@?` prefix in services.yml:

```yaml
my_service:
    arguments:
        - '@?avathar.postlove.topic_likes'
```

If Post Love is installed, phpBB injects the real `topic_likes` service. If it is not installed, phpBB injects `null`. The consuming service checks for `null` before using it and simply skips the feature. This is called *soft coupling* — Recent Topics works perfectly without Post Love; it just does not show a Likes column.

---

## 1. Own Events Emitted (Public API)

This section is the **public API contract** for the Recent Topics extension. These are the events that Recent Topics deliberately fires so that *other* extensions can hook in and customise the query, the topic data, or the template output — without patching any Recent Topics files.

If you are building an extension that wants to add a column, filter out certain topics, or inject extra per-row data into the recent topics block, this is where to look.

**Changing anything listed here is a breaking change and requires a major version bump.** Other extensions depend on these event names and argument names staying stable.

---

### 1.1 `avathar.recenttopics.sql_pull_topics_list`

**What this event is for:** Recent Topics first runs a query that determines which topic IDs are eligible — applying filters like "only topics the user has read access to" and "only topics from included forums". This event fires just before that query executes, letting your extension add extra WHERE conditions or change the ORDER BY.

**Example use case:** An extension that tags topics could exclude topics with a "hidden" tag by adding an extra condition here.

- **Placement:** `core\recenttopics::gettopiclist()`
- **Since:** 3.0.0
- **Arguments:**
  - `sql_array` (array) — The complete SQL query array, ready to pass to `$db->sql_build_query()`. Modify it and write it back to the event.
- **Known listeners:** none

---

### 1.2 `avathar.recenttopics.sql_pull_topics_data`

**What this event is for:** After the eligible topic IDs are known, Recent Topics runs a second query that fetches all the display data for those topics — titles, last post info, author usernames, unread status, and so on. This event fires before that query runs, letting your extension add LEFT JOINs or extra SELECT columns.

**Example use case:** An extension that shows topic preview text on hover (like `vse/topicpreview`) joins the post text table here and adds the `post_text` column to the SELECT list. It then reads that column in a later event.

- **Placement:** `core\recenttopics::get_topics_sql()`
- **Since:** 3.0.0
- **Arguments:**
  - `sql_array` (array) — The complete SQL query array for the detailed fetch query.
- **Known listeners:** none (legacy listeners `vse/topicpreview` and `bb3mobi/lastpostavatar` still use the deprecated alias — see section 1 deprecated aliases below)

---

### 1.3 `avathar.recenttopics.modify_topics_list`

**What this event is for:** After both queries have run, Recent Topics has a list of topic IDs and a full rowset of topic data. This event fires before the display loop starts, giving your extension access to the complete dataset at once — useful for bulk operations that need all rows at the same time.

**Example use case:** `rxu/thanks_for_posts` loads reputation scores for all topic authors in one query here, then uses the per-row event below to inject the score into each row. Loading them all at once (rather than one query per row) avoids the N+1 performance problem.

- **Placement:** `core\recenttopics::fill_template()`
- **Since:** 3.0.0
- **Arguments:**
  - `topic_list` (array) — An ordered array of topic IDs that will be displayed.
  - `rowset` (array) — A key-value array of `[topic_id => row_data]` for all topics.
- **Known listeners:** none (legacy: `vse/topicpreview`, `rxu/thanks_for_posts`, `PayBas/PBWoW3ext`)

---

### 1.4 `avathar.recenttopics.topictitle_remove_re`

**What this event is for:** phpBB stores the *last post subject* in the topic row rather than the topic title. When someone replies, the last post subject is "Re: Original Title". This event fires once per row so that a listener can strip the "Re: " prefix and display a clean title. Recent Topics includes a built-in listener for exactly this purpose — it is the only case where the extension listens to its own event.

- **Placement:** `core\recenttopics::fill_template()`, once per topic row
- **Since:** 3.0.0
- **Arguments:**
  - `row` (array) — The raw topic row data. The built-in listener reads and writes `row['topic_last_post_subject']`.
- **Known listeners:** internal — `event\listener::topictitle_remove_re()`

---

### 1.5 `avathar.recenttopics.modify_topictitle`

**What this event is for:** Just before the topic title is assembled for display, this event provides a chance to prepend a label or badge. The `prefix` argument starts as an empty string; your listener can set it to any HTML fragment and it will appear before the topic title in the output.

**Example use case:** An extension that adds a topic type label ("Announcement", "Sticky") could set `prefix` to a small badge here.

- **Placement:** `core\recenttopics::fill_template()`, once per topic row
- **Since:** 3.0.0
- **Arguments:**
  - `row` (array) — The raw topic row data (read only).
  - `prefix` (string) — The prefix string to prepend to the title. Set this and write it back to add content.
- **Known listeners:** none

---

### 1.6 `avathar.recenttopics.modify_tpl_ary`

**What this event is for:** This is the most commonly used hook. It fires once per topic row, just before the template variable array for that row is assigned to the `recent_topics` block. By this point Recent Topics has already built a complete `tpl_ary` with all its own variables (topic URL, title, author, last post time, unread flag, etc.). Your listener can add extra keys to `tpl_ary` and they will be available in the template.

**Example use case:** An extension that shows national flags next to usernames adds a `FLAG_IMG` key here, then uses a `topiclist_row_append` template event to output the flag icon.

- **Placement:** `core\recenttopics::fill_template()`, once per topic row
- **Since:** 3.0.0
- **Arguments:**
  - `row` (array) — The raw topic row data from the database (read only — changes are not persisted).
  - `tpl_ary` (array) — The template variable array about to be passed to `assign_block_vars()`. Add your custom keys here and write it back.
- **Known listeners:** none (legacy: `vse/topicpreview`, `rxu/thanks_for_posts`, `rmcgirr83/nationalflags`, `Dark1z/memberavatarstatus`, `tas2580/seourls`, `toxyy/anonymousposts`, `MuhClaren/timeago`, `bb3mobi/lastpostavatar`)

---

### 1.7 `avathar.recenttopics.modify_ads_code`

**What this event is for:** Fires just before the advertisement block HTML is assigned to the `ADS_INDEX_CODE` template variable. Recent Topics pre-populates `ads_index_code` from its own ACP setting (`rt_ads_enable` / `rt_ads_code`). A listener can read and replace this value to inject ad content from another source — for example, a style extension that manages its own ad configuration.

**Example use case:** The `paybas/pbwowext` style extension stores its own ad HTML in its ACP. It listens here and writes its content into `ads_index_code`, becoming the ad content provider for pbWoW3 users without needing to assign `ADS_INDEX_CODE` directly from a different event.

- **Placement:** `core\recenttopics::display_recent_topics()`, after the RT ad config is read, before `assign_vars()`
- **Since:** 3.0.6
- **Arguments:**
  - `ads_index_code` (string|false) — The ad HTML to render, pre-set from RT's own config or `false` if RT ads are disabled. Override this to provide content from another source.
- **Known listeners:** `paybas/pbwowext`

---

### Deprecated event aliases (removed in 3.1)

When this extension was forked from `paybas/recenttopics`, the event names changed from `paybas.recenttopics.*` to `avathar.recenttopics.*`. To avoid breaking every extension in the ecosystem overnight, the old names were kept firing alongside the new ones. This means an extension that listens to `paybas.recenttopics.modify_tpl_ary` still works — it just receives the event under the old name.

**These aliases will be removed in version 3.1.** If your extension uses any of the names below, migrate to the corresponding `avathar.recenttopics.*` name listed above. See [GitHub issue #169](https://github.com/avatharbe/RecentTopics/issues/169) for the full ecosystem analysis.

#### `paybas.recenttopics.sql_pull_topics_data`

- **Replaced by:** `avathar.recenttopics.sql_pull_topics_data`
- **Placement:** `core\recenttopics::get_topics_sql()`
- **Active since:** 2.0.0 — **Removed in:** 3.1
- **Arguments:** `sql_array` (array)
- **Known legacy listeners:** `vse/topicpreview`, `bb3mobi/lastpostavatar`

#### `paybas.recenttopics.modify_topics_list`

- **Replaced by:** `avathar.recenttopics.modify_topics_list`
- **Placement:** `core\recenttopics::fill_template()`
- **Active since:** 2.0.1 — **Removed in:** 3.1
- **Arguments:** `topic_list` (array), `rowset` (array)
- **Known legacy listeners:** `vse/topicpreview`, `rxu/thanks_for_posts`, `PayBas/PBWoW3ext`

#### `paybas.recenttopics.modify_tpl_ary`

- **Replaced by:** `avathar.recenttopics.modify_tpl_ary`
- **Placement:** `core\recenttopics::fill_template()`
- **Active since:** 2.0.0 — **Removed in:** 3.1
- **Arguments:** `row` (array), `tpl_ary` (array)
- **Known legacy listeners:** `vse/topicpreview`, `rxu/thanks_for_posts`, `rmcgirr83/nationalflags`, `Dark1z/memberavatarstatus`, `tas2580/seourls`, `toxyy/anonymousposts`, `MuhClaren/timeago`, `bb3mobi/lastpostavatar`

---

## 2. Events & Services Consumed from Other Extensions

This section documents every place where Recent Topics *reaches out* to another extension — calling its service or relying on its template events. All of these integrations are **optional and soft-coupled**: Recent Topics works normally when the other extension is absent. It checks for `null` before calling any optional service, and uses `ignore missing` in Twig includes so that absent template files produce no error.

---

### 2.1 `avathar/postlove` — Like counts per topic

Post Love tracks which users liked which posts. Recent Topics can show a "Likes" column alongside each topic, displaying the total number of likes that all posts in that topic have received.

- **How it works:** Recent Topics calls `get_topic_like_counts(array $topic_ids)` once for the whole batch of topics being displayed. The method returns a `[topic_id => count]` array. Recent Topics then assigns `TOPIC_LIKES` and the boolean flag `S_POSTLOVE` into each topic's template data. The templates use `{% if S_POSTLOVE %}` to show or hide the column.
- **DI reference:** `@?avathar.postlove.topic_likes` — declared as nullable so phpBB injects `null` when Post Love is not installed
- **Coupling:** Soft — the column simply does not appear without Post Love

---

### 2.2 `phpbb/collapsiblecategories` — Collapse/expand the RT block

The `phpbb/collapsiblecategories` extension adds a toggle button to category headers on the board index, allowing users to collapse sections they are not interested in. Recent Topics integrates with this so the entire RT block can be collapsed and remembered between visits.

- **How it works:** Recent Topics calls `$operator->get_hidden_categories()` (from the collapsible categories service) and sets `S_EXT_COLCAT_HIDDEN` and `U_EXT_COLCAT_COLLAPSE_URL` template vars. The template then includes the collapsible categories button partial.
- **DI reference:** `@?phpbb.collapsiblecategories.operator` — nullable
- **Template include:** `{% include '@phpbb_collapsiblecategories/collapsible_categories_button.html' ignore missing %}`
- **Coupling:** Soft — the collapse button simply does not appear without the extension

---

### 2.3 `paybas/pbwowext` — Advertisement block content for pbWoW3

The pbWoW Extension manages an ACP-configurable advertisement block for pbWoW3 style users. Rather than assigning `ADS_INDEX_CODE` directly from a separate event, it acts as a content provider via the `avathar.recenttopics.modify_ads_code` event.

- **How it works:** pbwowext listens to `avathar.recenttopics.modify_ads_code` and, if its own ad is enabled, writes its configured HTML into `ads_index_code`. Recent Topics then assigns that value to `ADS_INDEX_CODE` and renders the block. When pbwowext is not installed, Recent Topics falls back to its own `rt_ads_code` config.
- **Coupling:** Event-only — no DI dependency in either direction

---

### 2.4 `vse/topicpreview` — Topic hover previews

This extension shows a preview tooltip when the user hovers over a topic title. It integrates via the **deprecated event aliases** described in section 1, not via DI services.

- **PHP side:** `topicpreview` listens to `paybas.recenttopics.sql_pull_topics_data` (to JOIN the post text table), `paybas.recenttopics.modify_topics_list` (to load attachments), and `paybas.recenttopics.modify_tpl_ary` (to inject the preview HTML into `tpl_ary`).
- **Template side:** The `topiclist_row_append` template event fires inside the recent topics row template; `topicpreview` hooks in there to output the preview tooltip container.
- **Coupling:** Template + event — no direct DI dependency. Works as long as the deprecated event aliases remain active.

---

### 2.4 `dmzx/mchat` — Side-by-side display with mChat

mChat can be displayed alongside the recent topics block on the board index. This integration is template-only — no PHP is involved.

- **How it works:** The `recenttopics_mchat_side` template event fires inside `index_body_markforums_after.html`. mChat hooks into this event to inject its chat window next to the recent topics block.
- **Coupling:** Template-only — no PHP dependency

---

## 3. phpBB Core Events Used Internally

This section lists every phpBB event that Recent Topics subscribes to in order to deliver its own functionality. These are not part of the public API — they are internal implementation details.

### 3.1 PHP Events — Main listener (`event/listener.php`)

| phpBB Core Event | Handler method | What it does |
|---|---|---|
| `core.index_modify_page_title` | `display_rt()` | The main hook — renders the entire recent topics block on the board index page |
| `core.viewonline_overwrite_location` | `viewonline_overwrite_location()` | Shows "Viewing Recent Topics" on the Who Is Online page for users browsing `/rt` |
| `core.acp_manage_forums_request_data` | `acp_manage_forums_request_data()` | Saves the per-forum "include in recent topics" checkbox when an admin edits a forum |
| `core.acp_manage_forums_initialise_data` | `acp_manage_forums_initialise_data()` | Sets the default value ("included") for newly created forums |
| `core.acp_manage_forums_display_form` | `acp_manage_forums_display_form()` | Passes the stored per-forum setting to the ACP template so the checkbox shows the right state |
| `core.permissions` | `add_permission()` | Registers the `u_rt_view`, `u_rt_enable`, and `u_rt_location` permissions in phpBB's ACL system |
| `avathar.recenttopics.topictitle_remove_re` | `topictitle_remove_re()` | A self-listener: strips the "Re: " prefix from last-post subject lines |

### 3.2 PHP Events — UCP listener (`event/ucp_listener.php`)

These events handle per-user preferences — each registered user can configure their own recent topics display settings (sort order, number of topics, etc.) in their User Control Panel.

| phpBB Core Event | Handler method | What it does |
|---|---|---|
| `core.ucp_prefs_view_data` | `ucp_prefs_get_data()` | Loads the user's stored RT preferences and passes them to the UCP template |
| `core.ucp_prefs_view_update_data` | `ucp_prefs_set_data()` | Saves the submitted UCP form values back to the user's database record |
| `core.ucp_register_data_after` | `ucp_register_set_data()` | Sets default RT preferences for a brand-new user account at registration time |

### 3.3 Template Events Used

phpBB fires template events at fixed points inside its Twig templates. Recent Topics uses these in its own layout templates to give third-party extensions injection points in the topic rows.

| Template Event | Where it fires | Purpose |
|---|---|---|
| `topiclist_row_prepend` | All 4 layout templates | Before the topic title — allows an extension to prepend a badge, icon, or label |
| `topiclist_row_append` | All 4 layout templates | After the topic row — used by `vse/topicpreview` to inject a preview tooltip |
| `viewforum_body_topic_author_username_prepend` | topbottom, simple, page | Before the topic author's username |
| `viewforum_body_topic_author_username_append` | topbottom, simple, page | After the topic author's username |
| `viewforum_body_last_post_author_username_prepend` | All 4 layout templates | Before the last-post author's username |
| `viewforum_body_last_post_author_username_append` | All 4 layout templates | After the last-post author's username |
| `recenttopics_mchat_side` | `index_body_markforums_after.html` | Injection point for the mChat side-by-side layout |

**Layout templates:** `recent_topics_body_topbottom.html`, `recent_topics_body_side.html`, `recent_topics_page.html`, `recent_topics_simple.html` (plus style-specific overrides for pbwow3 and we_clearblue).
