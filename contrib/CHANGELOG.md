### Changelog

- 3.0.0 (31/05/2026) — relaunch under avathar/recenttopics namespace, consolidating everything since the avathar fork of paybas/recenttopics

  New features
  - [NEW] Display Recent Topics on viewforum (top/bottom) in addition to the index page (#178)
  - [NEW] Standalone pages: `/app.php/rt` (full) and `/app.php/rt/simple` (iframe-embeddable), with a dedicated ACP toggle independent of index display (#155)
  - [NEW] Optional advertisement / HTML block on the index page, with ACP toggle and textarea (#160)
  - [NEW] Postlove integration — like counts in the topic list when `avathar/postlove` is installed (#162); ACP toggle to enable/disable (#164)
  - [NEW] ACP toggle to show/hide date in side view (#163)
  - [NEW] `modify_ads_code` event so third-party extensions can inject advertisement content (#175)
  - [NEW] Backward-compat `paybas.recenttopics.*` event aliases — downstream extensions written against the original fork keep working (#169)
  - [NEW] Forum visibility widened: forums where the user has `f_list_topics` ("Can see topics") but not `f_read` now also appear — exposes titles to entice login while content stays gated (#182)
  - [NEW] Slovak (sk) and Swedish (sv) translations; grammar / spelling sweep across all locales
  - [NEW] `contrib/cleanup_recenttopics.sql` script for removing legacy installs

  Changes
  - [CHG] Renamed namespace from the legacy `recenttopicsav` to `recenttopics` — the "av" suffix from the paybas-era fork is no longer needed since the vendor namespace is already `avathar`
  - [CHG] Migration history squashed to a single release migration; canonical version is now `ext::RT_VERSION` (class constant), no `rt_version` row in `phpbb_config`
  - [CHG] Donate link moved from PayPal to Patreon
  - [CHG] PHP 8.1 and phpBB 3.3.0 minimum enforced via `is_enableable()` with clear error messages (#171)
  - [CHG] Removed abandoned integrations: paybas/topicprefixes (#166), imkingdavid/prefixed (#167), nickvergessen/newspage

  Fixes
  - [FIX] PHP 8.0–8.4 compatibility across the codebase (#142, #148)
  - [FIX] French language files no longer 500 on unescaped apostrophes
  - [FIX] Duplicate ad block render in pbWoW3 — use `DEFINE` for the `ADSIDE` flag so it's detectable across template scopes
  - [FIX] Standalone page loads `functions_display.php` and resolves the template path (#138, #150)
  - [FIX] User registration applies ACP default preferences (#124)
  - [FIX] "Posted in" star icon (S_USER_POSTED) now shows in the topic listing (#133)
  - [FIX] Undefined array key warning in the viewonline listener (#176)
  - [FIX] pbWoW3 top/bottom block: collapse support and column alignment (#139)
  - [FIX] Duplicate collapse buttons in pbWoW3 style
  - [FIX] Empty "Latest version:" line when the version check service is unreachable
  - [FIX] Unclosed `<span>` in UCP preferences template
  - [FIX] pbTech side-block heading matches the category-bar style

- 2.2.15 (05/04/2021) — last release under paybas/recenttopics before the avathar fork
  - [FIX] PHP 8.0 compatibility (#142)
