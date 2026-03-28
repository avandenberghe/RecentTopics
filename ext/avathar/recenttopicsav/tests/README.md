# RecentTopics Test Suite

## Overview

This test suite validates the RecentTopics extension using the [phpBB extension test framework](https://github.com/phpbb-extensions/test-framework). Tests run automatically via GitHub Actions on push/PR to main and develop branches.

## Test structure

```
tests/
├── event/
│   ├── fixtures/users.xml
│   ├── listener_test.php          Main event listener
│   └── ucp_listener_test.php      UCP preferences listener
├── controller/
│   ├── fixtures/users.xml
│   └── page_controller_test.php   Page controller (/rt, /rt/simple)
└── functional/
    └── recenttopics_test.php      End-to-end browser tests
```

## What is tested and why

### Event listeners

The two event listeners (`listener.php` and `ucp_listener.php`) are the core integration points with phpBB. They hook into the board at 10 different events. Getting any of these wrong means the extension silently fails or breaks phpBB functionality.

#### listener.php

| Test | Why |
|------|-----|
| `getSubscribedEvents` match | Catches accidentally removed or renamed event subscriptions — a common regression when refactoring |
| `display_rt` enabled/disabled | The `rt_index` config flag gates the entire feature on the index page. Both paths must work: showing topics when enabled, doing nothing when disabled |
| `viewonline_overwrite_location` (4 cases) | The "Who Is Online" page shows where users are browsing. This handler must distinguish `/rt`, `/rt/simple`, other app pages, and non-app pages. A wrong match here shows incorrect location text to all users |
| `acp_manage_forums_request_data` | Captures the per-forum "include in recent topics" checkbox on save. If this breaks, forum admins lose the ability to exclude forums |
| `acp_manage_forums_initialise_data` | Sets the default for new forums to "included". Only fires on `action == 'add'`, not on edit — the conditional matters |
| `acp_manage_forums_display_form` | Passes the stored value to the ACP template. Without this, the checkbox always appears unchecked |
| `add_permission` | Registers 6 user permissions (`u_rt_view`, `u_rt_enable`, `u_rt_location`, `u_rt_sort_start_time`, `u_rt_unread_only`, `u_rt_number`). Missing any of these silently removes user control over that setting |
| `topictitle_remove_re` (3 cases) | Strips the "Re: " prefix from last-post subjects. Tests the regex works, leaves non-prefixed titles alone, and handles rows without the subject key |

#### ucp_listener.php

| Test | Why |
|------|-----|
| `getSubscribedEvents` match | Same rationale as above — guards against subscription regressions |
| `ucp_prefs_set_data` | Maps form data to the `user_*` SQL columns. Any mismatch means user preferences silently fail to save |
| `ucp_prefs_get_data` (no submit) | On page load, must merge user defaults into the data array, load the language file, and render template variables. Permission checks gate which options appear — tested with selective `acl_get` returns |
| `ucp_prefs_get_data` (submit) | On form submit, must still merge data but must NOT touch the template. Verifying this separation prevents double-rendering bugs |
| `ucp_register_set_data` | On new user registration, writes config defaults to the user row via direct SQL. This is verified by reading the DB after the call — it is the only listener that writes to the database, so it gets an integration-level assertion |

### Controller

The page controller serves two routes: `/rt` (full page) and `/rt/simple` (headerless, for iframe embedding).

| Test | Why |
|------|-----|
| `display` enabled | Verifies the full render chain: language loading, `display_recent_topics()` call, and `helper->render()` with the correct template |
| `display` disabled | When `rt_page_enable` is off, the page still renders but must NOT call `display_recent_topics()`. This is a subtle distinction from a 404 — the page exists but is empty |
| `display_simple` with pbwow3 | The simple page force-switches to the pbwow3 style if available. Tested with the style present in fixtures to verify the DB lookup works |
| `display_simple` without pbwow3 | When pbwow3 is not installed, the controller must fall back gracefully to the default style instead of erroring |

### Functional tests

These run against a real phpBB installation with the extension enabled. They catch problems that unit tests cannot: routing issues, template errors, migration failures, service wiring bugs.

| Test | Why |
|------|-----|
| RT page | Verifies `/rt` is routable and returns valid HTML |
| RT simple page | Verifies `/rt/simple` is routable and returns valid HTML |
| RT page disabled | Verifies the page still loads (no 500) when the feature is turned off |
| Index with recent topics | Verifies the index page renders with `rt_index` enabled and at least one topic in the database — this is the primary use case of the extension |
| UCP preferences | Verifies the UCP preferences page loads without errors when the extension is active |

## What is NOT tested (and why)

- **`core/recenttopics.php`**: The ~900-line query builder is tightly coupled to phpBB's content visibility, pagination, and template engine. Meaningful unit tests would require mocking nearly every dependency. The functional tests cover its output indirectly. Dedicated tests for this class would be a good follow-up once the query logic is refactored into smaller, testable methods.
- **Migrations**: The test framework runs migrations as part of functional test setup. If a migration is broken, the functional tests fail to start — so they are tested implicitly.
- **ACP module**: The ACP module (`acp/recenttopics_module.php`) delegates to phpBB's module system. Testing it requires a full admin session, which is better covered by manual testing or a dedicated ACP functional test in a future iteration.
- **Language files**: These are static key-value arrays. Typos are caught by visual inspection, not unit tests.

## Running locally

From the phpBB root directory:

```bash
# Unit tests only
phpunit --configuration ext/avathar/recenttopicsav/phpunit.xml.dist --testsuite "Extension Test Suite"

# Functional tests (requires a configured test database)
phpunit --configuration ext/avathar/recenttopicsav/phpunit.xml.dist --testsuite "Extension Functional Tests"
```
