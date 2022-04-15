<?php
require_once '../vendor/autoload.php';

use hisorange\BrowserDetect\Parser;

$detector = new Parser(null, null);
$agentString = $_GET['agent'] ?? $_SERVER['HTTP_USER_AGENT'] ?? 'Missing';

$result = $detector->parse($agentString);

$deviceIcon = 'desktop_windows';

if ($result->isMobile()) {
  $deviceIcon =  'phone_iphonet';
} elseif ($result->isTablet()) {
  $deviceIcon =  'tablet';
} elseif ($result->isBot()) {
  $deviceIcon =  'precision_manufacturing';
}


$details = [
  [
    'title' => 'Device Type',
    'elements' => [
      // ['User Agent', 'userAgent', 'string'],
      ['Is Mobile', 'isMobile', 'boolean'],
      ['Is Tablet', 'isTablet', 'boolean'],
      ['Is Desktop', 'isDesktop', 'boolean'],
      ['Is Bot', 'isBot', 'boolean'],
      ['Device Type', 'deviceType', 'string'],
    ]
  ],
  [
    'title' => 'Browser',
    'elements' => [
      ['Browser Name', 'browserName', 'string'],
      ['Browser Family', 'browserFamily', 'string'],
      ['Browser Version', 'browserVersion', 'string'],
      ['Browser Version Major', 'browserVersionMajor', 'integer'],
      ['Browser Version Minor', 'browserVersionMinor', 'integer'],
      ['Browser Engine', 'browserEngine', 'string'],
    ]
  ],
  [
    'title' => 'Platform',
    'elements' => [
      ['Platform Name', 'platformName', 'string'],
      ['Platform Family', 'platformFamily', 'string'],
      ['Platform Version', 'platformVersion', 'string'],
      ['Platform Version Major', 'platformVersionMajor', 'integer'],
      ['Platform Version Minor', 'platformVersionMinor', 'integer'],
    ]
  ],
  [
    'title' => 'Operation System',
    'elements' => [
      ['Is Windows', 'isWindows', 'boolean'],
      ['Is Linux', 'isLinux', 'boolean'],
      ['Is Mac', 'isMac', 'boolean'],
      ['Is Android', 'isAndroid', 'boolean'],
    ]
  ],
  [
    'title' => 'Device Categorization',
    'elements' => [
      ['Device Family', 'deviceFamily', 'string'],
      ['Device Model', 'deviceModel', 'string'],
      ['Mobile Grade', 'mobileGrade', 'string'],
    ]
  ],
  [
    'title' => 'Browser Vendor',
    'elements' => [
      ['Is Chrome', 'isChrome', 'boolean'],
      ['Is Firefox', 'isFirefox', 'boolean'],
      ['Is Opera', 'isOpera', 'boolean'],
      ['Is Safari', 'isSafari', 'boolean'],
      ['Is Internet Explorer', 'isIE', 'boolean'],
      ['Is Edge', 'isEdge', 'boolean'],
    ]
  ],
  [
    'title' => 'Mobile Environment',
    'elements' => [
      ['Is an Embeded Browser', 'isInApp', 'boolean'],
    ]
  ],
];

$commons = [
  'Mozilla/5.0 (Linux; Android 4.4; Nexus 5 Build/_BuildID_) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Mobile Safari/537.36',
  'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582',
  'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.11 Safari/535.19',
  'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36',
  'Mozilla/5.0 (Linux; U; Android 2.2) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
  'Mozilla/5.0 (X11; Linux x86_64; rv:17.0) Gecko/20121202 Firefox/17.0 Iceweasel/17.0.1',
  'Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)',
  'Surf/0.4.1 (X11; U; Unix; en-US) AppleWebKit/531.2+ Compatible (Safari; MSIE 9.0)',
  'Mozilla/5.0 (X11; Linux i686; rv:8.0) Gecko/20100101 Firefox/8.0 Iceweasel/8.0',
  'Opera/9.80 (X11; Linux i686; Ubuntu/14.10) Presto/2.12.388 Version/12.16.2',
  'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
  'curl/7.15.4 (i686-pc-linux-gnu) libcurl/7.15.4 OpenSSL/0.9.7e zlib/1.2.3',
  'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
  'Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)',
  'Googlebot/2.1 (+http://www.googlebot.com/bot.html)',
  'Opera/9.61 (X11; Linux i686; U; de) Presto/2.1.1',
  'Mozilla/5.0 (Windows NT 5.1; U) Opera 7.11 [en]',
  'Opera/5.02 (Macintosh; U; id)',
  'Python-urllib/3.1',
  'Wget/1.9.1',
]
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Browser Detect - Best Browser Recognition written in PHP</title>

  <meta name="keywords" content="laravel, user-agent, browser, mobile, detect, tablet, user agent, mobile, tablet, analyse, php, open source, hisorange" />
  <meta name="robots" content="index,follow" />
  <meta name="description" content="Easy to use package to identify the visitor's browser details and device type." />
  <meta name="theme-color" content="#374151" />
  <meta name="copyright" content="All rights reserved by Varga Zsolt - 2013/2022" />
  <link rel="preload" as="font">

  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="manifest" href="/site.webmanifest">

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons&display=swap" rel="stylesheet">
</head>

<body class="pb-96 bg-white">

  <nav class="container mx-auto flex py-6 border-b border-slate-500 items-center justify-between">
    <h1 class="text-5xl font-thin"><a href="/">Browser Detect</a></h1>

    <a href="https://github.com/hisorange/browser-detect" class="inline-block">
      <img src="/assets/GitHub-Mark-64px.png" class="w-16 h-16" alt="Github Repository Logo">
    </a>
  </nav>

  <main class="flex container mx-auto py-8 text-gray-800 border-b border-slate-500">
    <div class="shrink pr-8 hidden lg:block">
      <span class="material-icons material-icons-outlined text-9xl text-teal-400">
        <?php echo $deviceIcon; ?>
      </span>
    </div>


    <div class="grow py-2 text-lg">
      <div>
        <table class="border-separate w-full">
          <tbody>
            <tr>
              <td class="text-right min-w-64 w-1/6"><strong>User Agent:</strong></td>
              <td class="px-8"><?php echo htmlentities($result->userAgent()) ?></td>
            </tr>

            <tr>
              <td class="text-right"><strong>Browser Name:</strong></td>
              <td class="indent-8"><?php echo $result->browserName() ?></td>
            </tr>

            <tr>
              <td class="text-right"><strong>Platform Name:</strong></td>
              <td class="indent-8"><?php echo $result->platformName() ? $result->platformName() : 'Unknown' ?></td>
            </tr>

            <tr>
              <td class="text-right"><strong>Device Type:</strong></td>
              <td class="indent-8"><?php echo $result->deviceType() ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>


  <div class="container mx-auto">
    <h2 class="text-4xl font-thin py-8">Your Device's Details</h2>

    <?php foreach ($details as $segment) { ?>
      <table class="border-collapse border border-gray-400 w-full mb-4">
        <thead>
          <tr>
            <th class="border border-gray-300 text-xl font-thin py-2 bg-gray-700 text-white text-left indent-4" colspan="3">
              <h2><?php echo $segment['title'] ?></h2>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($segment['elements'] as $e) { ?>
            <tr>
              <td class="border border-gray-300 px-4 w-1/3 lg:w-1/4 font-bold">
                <h3><?php echo $e[0] ?>:</h3>
              </td>
              <td class="border border-gray-300 px-4"><?php
                                                      switch ($e[2]) {
                                                        case 'string':
                                                          echo htmlentities($result->{$e[1]}() ? $result->{$e[1]}() : '---');
                                                          break;
                                                        case 'integer':
                                                          echo number_format($result->{$e[1]}(), 0, '.', ' ');
                                                          break;
                                                        case 'boolean':
                                                          echo $result->{$e[1]}() ? 'Yes' : 'No';
                                                          break;
                                                      }
                                                      ?></td>
              <td class="border border-gray-300 text-right w-1/4 py-1 hidden lg:table-cell"><code class="text-gray-600 bg-gray-200 rounded rounded-md py-1 px-2 font-mono m-1 text-sm">Browser::<?php echo $e[1] ?>()</code></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } ?>
  </div>


  <div class="container mx-auto">
    <h2 class="text-4xl font-thin py-8 mb-4 border-b border-slate-500">Popular Browsers</h2>

    <?php foreach ($commons as $ua) : ?>
      <div class="clear-both indent-2 leading-10 md:leading-8">
        <span class="material-icons material-icons-outlined text-lg float-left">
          chevron_right
        </span>

        <a href="/?agent=<?php echo urlencode($ua) ?>" class="text-teal-600 hover:text-teal-300"><?php echo $ua ?></a>
      </div>
    <?php endforeach; ?>
  </div>


  <div class="container mx-auto leading-7 indent-2">
    <h2 class="text-4xl font-thin py-8 mb-8 border-b border-slate-500">Use Cases</h2>

    <p>A) Identify your visitor and serve them different webpage based on their devices.</p>

    <p>B) Store and show your users what kind of devices they are logged in with, it helps them to recognize their mobile, tablet, or other devices.</p>

    <p>C) Analyze your service's usage even if your service does not have user interface analytics.</p>

    <p>D) Secure your services by monitoring device access for your users and quarantine suspicious devices.</p>

    <p>E) Speed up your webpage by recognizing and blocking bad bot requests.</p>
  </div>

  <div class="container mx-auto my-8 pt-16 border-t text-2xl border-slate-500">
    <p class="text-center">The result above is generated by the <b>hisorange/browser-detect</b> PHP library, <a title="GitHub repository" href="https://github.com/hisorange/browser-detect" class="text-teal-400 hover:underline">click here</a> to learn more.</p>
  </div>
</body>

</html>