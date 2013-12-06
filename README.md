# Browser &amp; Mobile detection package for Laravel.

The packages collects and wrap together the best user-agent identifier packages.

[garetjax/phpbrowscap](https://github.com/GaretJax/phpbrowscap) Provides the most accurate informations about the browser by using a constantly updated browscap.ini (without the get_browser() function.)
[yzalis/ua-parser](https://github.com/yzalis/UAParser) This awesome package parses the user agent and provides accurate version numbers, os name, browser name, etc...
[mobiledetect/mobiledetectli](https://github.com/serbanghita/Mobile-Detect) The famous Mobile_Detect, identifies the device kind (almost) perfectly!

##### Laravel way <3, Examples:

The package created for the Laravel 4 application, and tries to follow the laravelKindOfPrograming.

```php
// You can always get the Info object from the facade if you wish to operate with it.
BrowserDetect::getInfo(); // return Info object.

// But the requests are mirrored to the Info object for easier use.
BrowserDetect::browserVersion(); // return '3.6' string.

// Supporting human readable formats.
BrowserDetect::browserName(); // return 'Firefox 3.6' string.

// Or can be objective.
BrowserDetect::browserFamily(); // return 'Firefox' string.
```

##### Operate with different headers.

If you do not set any agent for the BrowserDetect it will use the current $_SERVER['HTTP_USER_AGENT'], but you can change user agent on the fly.

```php
// Get my current browser name.
BrowserDetect::browserName(); // Chrome 23

// Analize a different header in the same run.
BrowserDetect::setAgent('Opera/9.63 (Windows NT 6.0; U; nb) Presto/2.1.1')->browserName(); // Opera Opera 9.63

// Many time as you want...
BrowserDetect::setAgent('Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)')->browserVersion(); // 10
```

##### Fetched informations.

The Info class always provides a fix information schema, which containing the following datas.

```php
print_r(BrowserDetect::getInfo());

// Will result.
hisorange\browserdetect\Info Object
(
    [data] => Array
        (
            [userAgentString] => Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_2 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8H7 Safari/6533.18.5
            [isMobile] => 1
            [isTablet] => 0
            [isDesktop] => 0
            [isBot] => 0
            [browserFamily] => Safari
            [browserVersionMajor] => 5
            [browserVersionMinor] => 0
            [browserVersionPatch] => 2
            [osFamily] => iOS
            [osVersionMajor] => 4
            [osVersionMinor] => 3
            [osVersionPatch] => 2
            [deviceFamily] => Apple
            [deviceModel] => iPhone
            [cssVersion] => 3
            [mobileGrade] => A
        )

)
```

##### Manage user agents.

Can analize other then the current visitor's user-agent on the fly.

```php
// Set a different user agent then the current. (will be processed only when an information getting requested)
BrowserDetect::setAgent('Opera/9.99 (Windows NT 5.1; U; pl) Presto/9.9.9');

// Get the currently processed user agent.
BrowserDetect::getAgent();

// Same as BrowserDetect::getAgent();
BrowserDetect::userAgentString();
```

##### Determine the device kind.

Can determine if the device is a mobile phone, tablet or desktop computer.

```php
// Is the device a mobile?
BrowserDetect::isMobile(); // return true : false;

// Is the device a tablet?
BrowserDetect::isTablet(); // return true : false;

// Is the device a desktop computer?
BrowserDetect::isDesktop(); // return true : false;
```

##### A bot visiting my page?

If you wish to ban bots from your webpage:

```php
if (BrowserDetect::isBot()) {
	exit('Sorry, humans only <3');
}
```

##### Browser software informations.

The package determine the browser family and its semantic version.

```php
// Get the browser family.
BrowserDetect::browserFamily(); // return 'Internet Explore', 'Firefox', 'Googlebot', 'Chrome'...

// Get the browser version.
BrowserDetect::browserVersionMajor(); // return '5' integer.
BrowserDetect::browserVersionMinor(); // return '2' integer.
BrowserDetect::browserVersionPatch(); // return '0' integer.

// Get the human friendly version.
BrowserDetect::browserVersion(); // return '5.2' string, cuts the unecessary .0 or .0.0 from the end of the version.

// Even more love for humans..
BrowserDetect::browserName(); // return 'Internet Explorer 10' string, merges the family and it's version.
``

##### Operating system informations.

The package determine the Operating system family and its version.

```php
// Get the os family.
BrowserDetect::osFamily(); // return 'Windows' string.

// Get the browser version.
BrowserDetect::osVersionMajor(); // return 'XP' string or integer if the os uses semantic versioning like Android OS.
BrowserDetect::osVersionMinor(); // return '0' integer.
BrowserDetect::osVersionPatch(); // return '0' integer.

// Get the human friendly version.
BrowserDetect::osVersion(); // return '2.3.6' string, for Android OS 2.3.6
BrowserDetect::osVersion(); // return '8' integer, for Windows 8
BrowserDetect::osVersion(); // return 'Vista' string, for Windows Vista
BrowserDetect::osVersion(); // return 'XP' string, for Windows XP

// Even more love for humans..
BrowserDetect::osName(); // return 'Windows 7' string, merges the family and it's version.
```

##### Device informations.

Can determine the device family e.g: 'Apple' and the device model e.g: 'iPhone'.
This function only works with tablets and model devices yet, since the request to the server do not containing informations about the desktop computers.

```php
// Get device family.
BrowserDetect::deviceFamily(); // return 'Apple' string. Nokia, Samsung, BlackBerry, etc...

// Get device model.
BrowserDetect::deviceModel(); // return 'iPad' string. iPhone, Nexus, etc..
```

##### CSS version support.

The Browscap package can determine many browser's css version support.

```php
// Get the supported css version.
BrowserDetect::cssVersion(); // return '3' integer. Possible values null = unknown, 1, 2, 3
```

##### Mobile grades.

The mobile grading function inherited from the Mobile_Detect and works as it defines it.
For desktop computers this value will be null.

```php
// Get a tablet's or phone's mobile grade in scale of A,B,C.
BrowserDetect::mobileGrade(); // returns 'A'. Values are always capitalized.
```

