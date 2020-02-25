<?php
require_once '../vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Cache\CacheManager;
use hisorange\BrowserDetect\Parser;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;

// Create a service container
$container = new Container;

$container['config'] = [
    'cache.default' => 'file',
    'cache.stores.file' => [
        'driver' => 'file',
        'path' => __DIR__ . '/cache'
    ]
];

$container['files'] = new Filesystem;

// Create the CacheManager
$cacheManager = new CacheManager($container);

// Create a request from server variables, and bind it to the container; optional
$request = Request::capture();
$container->instance('Illuminate\Http\Request', $request);

$detector = new Parser($cacheManager, $request);

$agentString = $_GET['user-agent'] ?? $request->server('HTTP_USER_AGENT');

$result = $detector->parse($agentString);

$kind = 'desktop';

if ($result->isMobile()) {
    $kind =  'mobile-alt';
} elseif ($result->isTablet()) {
    $kind =  'tablet-alt';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Browser Detect for Laravel - by hisorange!</title>

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>

<body>
  <nav>
    <div class="nav-wrapper amber darken-4">
      <div class="container">
        <a href="#!" class="brand-logo">Browser Detect - Demo</a>
        <ul class="right hide-on-med-and-down">
          <li><a href="https://github.com/hisorange/browser-detect">Github</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main>
    <div class="container">
      <br>

      <!-- Browser informations -->
      <div class="row">
        <div class="col s4 left-align">
          <span class="fa-stack fa-5x">
            <i class="fas fa-square fa-stack-2x"></i>
            <i
              class="fas fa-fw fa-inverse fa-stack-1x fa-<?php echo $kind ?>"></i>
          </span>
        </div>
        <div class="col s8 center-align">
          <h3>Browser</h3>
        </div>
      </div>

      <div class="row">
        <div class="col s12">
          <table>
            <thead>
              <tr>
                <th style="width: 145px;">Interest</th>
                <th>Output</th>
                <th style="width: 255px;">Code</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>User&nbsp;agent:</td>
                <td class="truncate"><?php echo $result->userAgent(); ?>
                </td>
                <td><code>Browser::userAgent()</code></td>
              </tr>
              <tr>
                <td>Name:</td>
                <td><?php echo $result->browserName(); ?>
                </td>
                <td><code>Browser::browserName()</code></td>
              </tr>
              <tr>
                <td>Family:</td>
                <td><?php echo $result->browserFamily(); ?>
                </td>
                <td><code>Browser::browserFamily()</code></td>
              </tr>
              <tr>
                <td>Version:</td>
                <td><?php echo $result->browserVersion(); ?>
                </td>
                <td><code>Browser::browserVersion()</code></td>
              </tr>
              <tr>
                <td>Rendering&nbsp;engine:</td>
                <td><?php echo $result->browserEngine(); ?>
                </td>
                <td><code>Browser::browserEngine()</code></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- & Browser informations -->


      <!-- OS informations -->
      <div class="row">
        <div class="col s4 left-align">
          <span class="fa-stack fa-5x">
            <i class="fas fa-square fa-stack-2x"></i>
            <i class="fas fa-fw fa-inverse fa-stack-1x fa-cogs"></i>
          </span>
        </div>
        <div class="col s8 center-align">
          <h3>Operating System</h3>
        </div>
      </div>

      <div class="row">
        <div class="col s12">
          <table>
            <thead>
              <tr>
                <th style="width: 145px;">Interest</th>
                <th>Output</th>
                <th style="width: 255px;">Code</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Name:</td>
                <td><?php echo $result->platformName(); ?>
                </td>
                <td><code>Browser::platformName()</code></td>
              </tr>
              <tr>
                <td>Family:</td>
                <td><?php echo $result->platformFamily(); ?>
                </td>
                <td><code>Browser::platformFamily()</code></td>
              </tr>
              <tr>
                <td>Version:</td>
                <td><?php echo $result->platformVersion(); ?>
                </td>
                <td><code>Browser::platformVersion()</code></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- & OS informations -->

      <!-- Device informations -->
      <div class="row">
        <div class="col s4 left-align">
          <span class="fa-stack fa-5x">
            <i class="fas fa-square fa-stack-2x"></i>
            <i class="fas fa-fw fa-inverse fa-stack-1x fa-expand"></i>
          </span>
        </div>
        <div class="col s8 center-align">
          <h3>Device</h3>
        </div>
      </div>

      <div class="row">
        <div class="col s12">
          <table>
            <thead>
              <tr>
                <th style="width: 145px;">Interest</th>
                <th>Output</th>
                <th style="width: 255px;">Code</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Family:</td>
                <td><?php echo $result->deviceFamily(); ?>
                </td>
                <td><code>Browser::deviceFamily()</code></td>
              </tr>
              <tr>
                <td>Model:</td>
                <td><?php echo $result->deviceModel(); ?>
                </td>
                <td><code>Browser::deviceModel()</code></td>
              </tr>
              <tr>
                <td>Mobile&nbsp;grade:</td>
                <td><?php echo $result->mobileGrade(); ?>
                </td>
                <td><code>Browser::mobileGrade()</code></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- & Device informations -->

    </div>

  </main>
</body>

</html>