# Upgrade Guide: Adding a New Laravel Major Version

Checklist for extending `hisorange/browser-detect` support to a new Laravel major
(e.g. 9.x → 10.x). Every step below is derived from how previous majors were
shipped (4.4.0 → Laravel 8.x, 4.5.0 → Laravel 9.x, 5.0.0 → Laravel 10.x).

## 1. Dependency floor (composer.json)

- `require-dev` `orchestra/testbench` must cover the new Laravel major:
  testbench 7.x ↔ Laravel 9.x, 8.x ↔ Laravel 10.x, 9.x ↔ Laravel 11.x. Widen the
  OR expression,
  e.g. `"orchestra/testbench": "~7.0 || ~8.0"`.
- Confirm the `php` floor (`^8.1`) matches the new Laravel's PHP requirement.
- Detection engines (`ua-parser`, `mobiledetect`, `crawler-detect`,
  `device-detector`, `league/pipeline`) must still resolve against the new
  framework; run `composer update` and diff `composer.lock`.
- The `extra.laravel` block (provider + `Browser` alias) must stay valid for
  the new version's service/alias API.

## 2. Runtime API checks (src/)

- `src/ServiceProvider.php`: DI keys `cache`, `request`, `config` must resolve
  in the new version; `mergeConfigFrom` + `publishes` semantics may change.
- Octane/lazy-container injection was fixed once (#204) — re-verify the
  `app()->make('browser-detect')` binding under the new framework.
- `src/Parser.php` uses `Illuminate\Cache\CacheManager::remember()` and
  `Illuminate\Http\Request::userAgent()`; confirm both signatures survive.
- Blade directives (`Blade::if`) in `registerDirectives()` must compile the
  same `@mobile/@tablet/@desktop/@browser` blocks.

## 3. Static analysis (phpstan.neon)

- The `ignoreErrors` entry for `Illuminate\...\CacheManager::remember()` is
  version-specific; re-run
  `vendor/bin/phpstan analyse -c phpstan.neon ./src/` and adjust ignores if
  Illuminate's typed signatures changed.

## 4. Test suite (tests/)

- `tests/TestCase.php` extends `Orchestra\Testbench\TestCase`; the installed
  testbench major must match the new Laravel major.
- Run locally: `composer run-script test-dev` (fast) and
  `composer run-script test` (clover to `tests/logs/clover.xml`).
- All `*Test.php` files (incl. `tests/Stages/`) must stay green; `BladeTest`
  covers directive compilation, `ServiceProviderTest` covers config merging.

## 5. CI matrices (.github/workflows/)

- `latest.yml`: bump the pinned `laravel/framework` version
  (`composer require laravel/framework:10.5.0 --no-update`) and the
  `php-version` in `shivammathur/setup-php`.
- `test-php8.2.yml`: extend the `laravel-version` matrix array (e.g.
  `[9.52, 10.0, 10.1, 10.2, 10.3, 10.4]`) with the new major's point releases
  that support the target PHP version.

## 6. Documentation

- README "Version support" matrix: add a column for the new library major and
  tick the newly supported Laravel/PHP rows (also the `Standalone` row when
  applicable).
- CHANGELOG.md: prepend a `### Changes in X.Y.Z` section, e.g.
  "Support for Laravel 10.x", "Test on PHP 8.2 too".
- Commit messages follow `type: summary (#issue)` (conventional prefix +
  upstream issue number).

## 7. Release flow

- Merge the release branch into `stable` (observed: develop → stable), tag the
  release commit ("Release 5.0 (#202)"), and push; packagist picks up the new
  version from `composer.json` automatically.
- > TODO: repo has no CODEOWNERS or branch protection; maintainer review per
  CHANGELOG flow is assumed, not enforced.

## Compatibility matrix (current)

| Library major | Laravel range        | PHP range |
| :------------ | :------------------- | :-------- |
| 1.x           | 4.x                  | 5.6+      |
| 2.x           | 4.x–5.x              | 5.6+      |
| 3.x           | 5.x–6.x              | 5.6+      |
| 4.x (4.4+)    | 6.x–9.x              | 8.0+      |
| 5.x           | 9.x–11.x, standalone | 8.1–8.2   |
