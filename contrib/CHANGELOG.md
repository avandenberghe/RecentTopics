### Changelog

- 3.0.0 (31/05/2026)
  - Squashed all prior 3.0.x migrations into a single release migration; canonical version is now `ext::RT_VERSION` (class constant), no `rt_version` row in `phpbb_config`
  - Forum visibility in Recent Topics includes forums where the user holds `f_list_topics` ("Can see topics") permission without `f_read` — topic titles are shown to entice login while content remains gated by phpBB's normal read check
  - For the full feature history of the 3.0.x line see the git log
