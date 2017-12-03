## Browser Detect by _[hisorange](https://hisorange.me)_

[![Latest Stable Version](https://poser.pugx.org/hisorange/browser-detect/v/stable)](https://packagist.org/packages/hisorange/browser-detect)
[![Build Status](https://travis-ci.org/hisorange/browser-detect.svg?branch=stable)](https://travis-ci.org/hisorange/browser-detect)
[![Coverage Status](https://coveralls.io/repos/github/hisorange/browser-detect/badge.svg)](https://coveralls.io/github/hisorange/browser-detect)
[![Total Downloads](https://poser.pugx.org/hisorange/browser-detect/downloads)](https://packagist.org/packages/hisorange/browser-detect)
[![License](https://poser.pugx.org/hisorange/browser-detect/license)](https://packagist.org/packages/hisorange/browser-detect) 

This package is able to identify the visitor's browser by using multiple well tested packages and services together to give you the best results possible.
**Laravel 5.0 &raquo; 5.5** is tested on **PHP 5.6 &raquo; 7.2** versions, with a 100% code coverage!

### [Install](#install)

```sh
composer require hisorange/browser-detect
```

Yep, that's it! At least for lavarel 5.5 and above, for 5.4 and below please read [the extended installation](#extended-installation).

### [Use](#use)

In your classes and controllers:

```php
// Easy check on the browser :)
Browser::isMobile();
Browser::isTablet();
Browser::isDesktop();

// Wonder if it is a bot?
if (Browser::isBot()) {
    echo 'No need to wonder anymore!';
}
```

Even in your blade templates:

```blade
// Easy to use directives are built in!
@mobile
    <p>This is the MOBILE template!</p>
@endmobile

@tablet
    <p>This is the TABLET template!</p>
@endtablet

@desktop
    <p>This is the DESKTOP template!</p>
@enddesktop

// Every key is supported.
@browser('isBot')
    <p>Bots are identified too :)</p>
@endbrowser
```

### [Version support](#versions)

The following matrix is being continuously tested by the great and awesome [Travis CI](https://travis-ci.org/hisorange)!

| ----- | PHP 5.6 | PHP 7.0 | PHP 7.1 | PHP 7.2 |
| :---: | :-----: | :-----: | :-----: | :-----: |
| Laravel 5.0 | &#10003; | - | - | - | 
| Laravel 5.1 | &#10003; | - | - | - | 
| Laravel 5.2 | &#10003; | - | - | - | 
| Laravel 5.3 | &#10003; | - | - | - | 
| Laravel 5.4 | &#10003; | &#10003; | &#10003; | &#10003; | 
| Laravel 5.5 | - | &#10003; | &#10003; | &#10003; | 

\* _Cannot auto test the laravel 5.4 on PHP 7.1 because of version incompatibility between the PHPUnit, Laravel and the package testing library, but the versions are tested manually._

### [Extended Installation]




### [Changes](#changes)

See the detailed changes in the [CHANGELOG](CHANGELOG.md)

