# Project Overview

Browser Detect (`hisorange/browser-detect`) is a PHP library that identifies a visitor's browser, operating system, and device type (mobile / tablet / desktop / bot) by piping the HTTP user-agent string through five well-known detection engines instead of relying on custom heuristics. It targets Laravel 9.x–10.x apps (with a standalone, Laravel-free mode for any PHP 8.1+ project) and exposes a single `Browser` facade plus Blade directives (`@mobile`, `@tablet`, `@desktop`, `@browser`), caching every parse so repeated lookups cost under 0.02 ms.

## Repository Structure

- `src/` — all library code, PSR-4 namespace `hisorange\BrowserDetect\`.
  - `src/Facade.php` — Laravel facade exposing the `Browser` alias API.
  - `src/ServiceProvider.php` — binds the `browser-detect` service and registers Blade directives.
  - `src/Parser.php` — orchestrator: reads the agent, enforces security limits, caches, and runs the pipeline.
  - `src/Payload.php` — mutable state carrier passed down the pipeline.
  - `src/Result.php` — immutable, typed result object with the public getters.
  - `src/Contracts/` — interfaces (`ParserInterface`, `PayloadInterface`, `ResultInterface`, `StageInterface`) defining the pipeline contract.
  - `src/Exceptions/` — namespaced exception hierarchy rooted at `Exception`.
  - `src/Stages/` — one class per detection engine: `UAParser`, `MobileDetect`, `CrawlerDetect`, `DeviceDetector`, `BrowserDetect` (final reconciliation).
- `config/browser-detect.php` — default configuration (cache interval/prefix, max header length), publishable via `php artisan vendor:publish`.
- `tests/` — PHPUnit + Orchestra/Testbench suite, one `*Test.php` per class; `tests/Stages/` mirrors stage tests; `tests/logs/` holds generated coverage output.
- `vendor/` — Composer dependencies (ua-parser, mobiledetect, crawler-detect, device-detector, league/pipeline, testbench); never hand-edit.
- `.github/workflows/` — CI: `latest.yml` (push/PR) and `test-php8.2.yml` (manual version matrix).
- Root files — `composer.json`, `phpunit.xml`, `phpstan.neon`, `.coveralls.yml`, `README.md`, `CHANGELOG.md`, `LICENSE`.

## Build & Development Commands

Install (consumer side):

```sh
composer require hisorange/browser-detect
```

In a Laravel app, publish the config file:

```sh
php artisan vendor:publish
```

Run the test suite (commands preserved verbatim from `composer.json` scripts):

```sh
composer run-script test-dev   # phpunit, no coverage
composer run-script test       # phpunit --coverage-clover ./tests/logs/clover.xml
```

Type-check (phpstan level max, config in `phpstan.neon`; command as used in CI):

```sh
vendor/bin/phpstan analyse -c phpstan.neon ./src/
```

Lint (PSR-12; currently commented out in CI, no root phpcs.xml):

```sh
php vendor/bin/phpcs --standard=PSR12 ./src/
```

## Code Style & Conventions

- PHP files follow PSR-12 (enforced via the CI phpcs line); 4-space indentation, one class per file.
- Namespaces mirror directories: `hisorange\BrowserDetect\Stages\UAParser` lives at `src/Stages/UAParser.php` (PSR-4 mapping in `composer.json`).
- Classes are PascalCase nouns; methods are camelCase; boolean getters use the `is*` prefix (`isMobile`, `isIEVersion`).
- Every public method carries a docblock with `@param`/`@return` (or `@inheritdoc`); return types are declared — recent commits added missing ones.
- Interfaces live in `Contracts/` and are implemented, never duck-typed; exceptions are the namespaced ones in `Exceptions/`, never built-ins directly.
- Commit template (observed in history): `type: summary (#issue)` — e.g. `chore: adding some missed return types (#209)`; plain `Fix ... (#204)` also occurs. Prefer conventional prefixes (`docs:`, `chore:`, `fix:`) with the upstream issue number.

## Architecture Notes

```
user-agent string
      |  (truncated to config.security.max-header-length = 2048 bytes)
      v
Parser.detect() --> Parser.parse(agent)
      |  key = "bd4_" + md5(agent)
      +-- runtime[] (in-memory, per page load)
      +-- Laravel CacheManager.remember(key, interval=10080s)
      v
League\Pipeline  (src/Parser.php:204-211)
  UAParser -> MobileDetect -> CrawlerDetect -> DeviceDetector -> BrowserDetect
      |  each Stage.__invoke(Payload) mutates Payload key/value store
      v
new Result(payload.toArray())  --> Facade / Blade reflect calls onto Result
```

Prose: `ServiceProvider.register()` binds the DI key `browser-detect` to a `Parser` wired with the app's `cache`, `request`, and merged config. `Parser` is the only stateful component: it truncates the agent (DoS guard), hashes it, and consults a two-tier cache (runtime array + Laravel cache). On a miss, `process()` pipes a fresh `Payload` through the five stages in fixed order — UAParser seeds browser/OS/device fields, MobileDetect and CrawlerDetect add mobile/bot flags, DeviceDetector refines platform and engine details (skipped for bots), and `BrowserDetect` reconciles conflicting flags (exactly one of mobile/tablet/desktop, Prerender-as-bot, vendor booleans, human-readable version strings, in-app detection) before sealing everything into an immutable `Result`. `Facade.__call` and `Parser.__call` reflect any API call onto the `ResultInterface`, so the public API surface is exactly the Result's getters.

## Testing Strategy

- Framework: PHPUnit 9/10 with `orchestra/testbench` (Laravel app harness); bootstrap is `./vendor/autoload.php` per `phpunit.xml`.
- Suite layout: one `*Test.php` per source class under `tests/` and `tests/Stages/`, all extending `tests/TestCase.php` which registers the provider and the `Browser` alias; `phpunit.xml` discovers files with suffix `Test.php` and excludes `./tests/_fixture`.
- Local: `composer run-script test-dev` (fast) or `composer run-script test` (writes clover to `tests/logs/clover.xml`).
- CI: `.github/workflows/latest.yml` runs on every push/PR with PHP 8.2 + Laravel 10.5.0, then uploads coverage via `php vendor/bin/php-coveralls -v` using `COVERALLS_REPO_TOKEN` (`.coveralls.yml` points at `tests/logs/clover.xml`).
- Version matrix: `test-php8.2.yml` (manual `workflow_dispatch`) tests Laravel 9.52–10.4; the README matrix documents which library majors support which Laravel/PHP combinations — keep changes compatible with the supported matrix.

## Security & Compliance

- DoS guard: agent strings are cut at `config.security.max-header-length` (2048 bytes) before any regex engine sees them — never remove this truncation.
- No secrets in the repo; CI uses `GITHUB_TOKEN` only. Never commit tokens; `.gitignore` already excludes caches and editor dirs.
- Dependency scanning: run `composer list --outdated` / audit via your vendor service; > TODO: repo has no dedicated dependency-scanning config.
- License: MIT (`LICENSE`, © Varga Zsolt); bundled deps carry their own licenses inside `vendor/` — do not relicense or edit them.
- Cache keys are `prefix + md5(agent)`; keep the `bd4_` prefix so old and new cache entries never collide.

## Agent Guardrails

- Never modify: `vendor/`, `composer.lock`, `tests/logs/`, `.phpunit.cache/`, generated coverage output.
- Do not touch `src/Contracts/` signatures or `config/browser-detect.php` defaults without an explicit request — they are public API and published config.
- Any change to `src/Stages/*` or `src/Parser.php` must keep all `*Test.php` suites green before being proposed.
- Preserve the pipeline stage order in `Parser.process()`; reordering changes precedence semantics (later stages overwrite earlier values).
- > TODO: repo has no CODEOWNERS or branch protection config; require maintainer review for releases per CHANGELOG flow.

## Extensibility Hooks

- Config override: pass a third ctor arg to `Parser` (`cache.interval`, `cache.prefix`, `security.max-header-length`); Laravel merges `config/browser-detect.php` via `mergeConfigFrom`.
- Custom stages: any class implementing `StageInterface` with `__invoke(Payload): Payload` can be inserted into the `league/pipeline` chain in `Parser.process()`.
- DI hooks: service key `browser-detect`, facade accessor `'browser-detect'`, Blade directives `@mobile/@tablet/@desktop/@browser(fn)`.
- Standalone mode: `hisorange\BrowserDetect\Parser` static singleton reads `$_SERVER['HTTP_USER_AGENT']` and works without a cache.
- > TODO: no env vars or feature flags exist in the codebase; configuration is file/array based only.

## Further Reading

- [README.md](README.md) — full API table, install, Blade usage, version matrix.
- [CHANGELOG.md](CHANGELOG.md) — release history and supported-version changes.
- [phpstan.neon](phpstan.neon) and [phpunit.xml](phpunit.xml) — analyzer and test configuration.
- [.github/workflows/latest.yml](.github/workflows/latest.yml) — canonical CI/test procedure.