### Change in 4.0.0

---

- PHP 5.6 is no longer supported.
- Raised the minimum Laravel version to 6.0.
- Support for Laravel 6.0, 6.1, 6.2, 6.3, 6.4, 6.5.
- Unify the coding standards.
- Remove legacy PHP workarounds.
- Release the isEdge result variable.
- Invalidate cache with 3.x versions.
- Update the tests to test for every laravel framework version.

### Changes in 3.1.4

---

- Fix blade directives, add test coverage.

### Changes in 3.1.3

---

- Allow PHPUnit 7.0 as dependency.

### Changes in 3.1.2

---

- Bump version testing to laravel 5.6.

### Changes in 3.1.1

---

- Fix: MobileDetect still used the osName instead of platformName.
- Fix: isIEVersion comparison called the parameters in wrong order.
- Addition: Version parser now forces the semantic version pieces to be integer.
- Fixed: MobileDetect test only ran on one sample.
- Addition: More test coverage, getting closer to the maximum.

### Changes in 3.1.0

---

- Added the DeviceDetector stage to the pipeline.
- Fixed a minor issue with versions and trailing dots.
- Added the Browser::browserEngine() function.
- Much better detection rates with the new stage.

### Changes in 3.0.1

---

- Fixed the result objects bad property calls.
- Added more unit test for the fixed case.

### Changes in 3.0.0

---

- The package has been rewrote from ground zero.
- Added PHPUnit, and covering the main features.
- Added the travis ci to the release cycle.
- Moved to the Develop -> Staging -> Stable branch model.
- Interfaced everything, seriously!
- Custom exceptions for easier package managing.
- Blade directives.
- Result is now a well annotated object, any IDE can work with it.
- End of the plugin era, pipelines ha arrived.
- Added the crawler detect package.
- Replaced the UAParser to a more supported one.
- Support for MobileDetect 2.0 to 2.8, 3.0 will never come :D
- Parser class is much more simple to use.
- PSR-2 code style.
- Browsecap plugin has been removed.
- UserAgentStringApi plugin has been removed. (Too slow to call)
- Everything is easier now, but also less flexibility in the package.
- Better version support for PHP and Laravel.
- Easy fast setup.
- Namespaces are redesigned to be more descriptive.

### Changes in 2.0 version

---

- Laravel 5 is now supported, first draft.

### Changes in 1.0.0pre

---

The code has been almost totaly rewrited except like 30 line of code from v0.9.\*, this breaks the compability with older versions so the major version has been increased to v1.0.0pre.

The version 1.0.0 is promised when the Mobile Detect 3 comes out but since they passed the due date for the release the support for their new detector will be intruduced in a plugin so the package dev can move on.

- Most prior change is the PHP requirement increased to 5.4~ this allows the usage of traits.
- Class loading now uses PSR-4 instead of PSR-0 structure. This will be handled by the composer automaticaly.
- Package now requires the hisorange/traits package to share resources between packages.
- PHP namespace are moved from **hisorange\browserdetect** to **hisorange\BrowserDetect** to avoid collusions.
- Package now uses the 'browser-detect.parser', 'browser-detect.result' component names in the L4 Di.
- Service provider is more extendable with splitted parser and result component keys.
- Manager class has been renamed to Parser.
- Instead of useing the basic Cache and Config class from the Laravel app now useing the app's Di to forge the needed component.
- Most of the Manager class' functions has been renamed and reoriented in the Parser.
- Before hardcoded generic values now stored in the config file.
- Default cache prefix has been changed to 'hbd1'.
- Cacheing now requires less memory the results are stored in a compact string format instead of an array.
- Parser now determine the browser's javascript support.
- Parsing are now plugin oriented instead of hardcodeing.
- Plugins are costumizeable from the config/plugins.php file.
- Package ships with 4 built in plugin.
- UserAgentStringApi plugin is default turned off, because it requires greater amount of time to process.

### v0.9.2

---

- Fix the case where importing datas and query the current agent in the same request.
- Perform self analization before importing data.

### v0.9.1

---

- New import and export function on the info object.

### Initial release v0.9.0
