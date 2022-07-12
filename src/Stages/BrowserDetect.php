<?php

namespace hisorange\BrowserDetect\Stages;

use hisorange\BrowserDetect\Result;
use hisorange\BrowserDetect\Contracts\StageInterface;
use hisorange\BrowserDetect\Contracts\ResultInterface;
use hisorange\BrowserDetect\Contracts\PayloadInterface;

/**
 * BrowserDetect stage to fix mix ups caused by different results.
 *
 * @package hisorange\BrowserDetect\Stages
 */
class BrowserDetect implements StageInterface
{
    /**
     * @param  PayloadInterface $payload
     * @return ResultInterface
     */
    public function __invoke(PayloadInterface $payload): ResultInterface
    {
        // Fix issue when the device is detected at tablet and mobile in the same time.
        if (!$payload->getValue('isMobile') && !$payload->getValue('isTablet')) {
            $payload->setValue('isMobile', false);
            $payload->setValue('isTablet', false);
            $payload->setValue('isDesktop', true);
        } elseif ($payload->getValue('isTablet')) {
            $payload->setValue('isMobile', false);
            $payload->setValue('isTablet', true);
            $payload->setValue('isDesktop', false);
        } else {
            $payload->setValue('isMobile', true);
            $payload->setValue('isTablet', false);
            $payload->setValue('isDesktop', false);
        }

        // Prerender desktop bot checker
        if (strpos($payload->getAgent(), 'Prerender') !== false) {
            $payload->setValue('isBot', true);
            $payload->setValue('isMobile', false);
            $payload->setValue('isTable', false);
            $payload->setValue('isDesktop', true);
        }

        // Prerender mobile bot checker
        if (
            stripos($payload->getAgent(), 'Prerender') !== false &&
            stripos($payload->getAgent(), 'Android') !== false
        ) {
            $payload->setValue('isBot', true);
            $payload->setValue('isMobile', true);
            $payload->setValue('isTable', false);
            $payload->setValue('isDesktop', false);
        }

        // Popular browser vendors.
        if (false !== stripos($payload->getValue('browserFamily') ?? '', 'chrom')) {
            $payload->setValue('isChrome', true);
        } elseif (false !== stripos($payload->getValue('browserFamily') ?? '', 'firefox')) {
            $payload->setValue('isFirefox', true);
        } elseif (false !== stripos($payload->getValue('browserFamily') ?? '', 'opera')) {
            $payload->setValue('isOpera', true);
        } elseif (false !== stripos($payload->getValue('browserFamily') ?? '', 'safari')) {
            $payload->setValue('isSafari', true);
        } elseif (
            false !== stripos($payload->getValue('browserFamily') ?? '', 'explorer')
            || false !== stripos($payload->getValue('browserFamily') ?? '', 'ie')
            || false !== stripos($payload->getValue('browserFamily') ?? '', 'trident')
        ) {
            $payload->setValue('isIE', true);
        } elseif (false !== stripos($payload->getValue('browserFamily') ?? '', 'edge')) {
            $payload->setValue('isEdge', true);
        }

        // Human readable browser version.
        $payload->setValue(
            'browserVersion',
            $this->trimVersion(
                implode(
                    '.',
                    [
                        $payload->getValue('browserVersionMajor'),
                        $payload->getValue('browserVersionMinor'),
                        $payload->getValue('browserVersionPatch'),
                    ]
                )
            )
        );

        $payload->setValue('browserName', trim(
            $payload->getValue('browserFamily') .
                ' ' .
                $payload->getValue('browserVersion')
        ));

        // Human readable platform version.
        $payload->setValue(
            'platformVersion',
            $this->trimVersion(
                implode(
                    '.',
                    [
                        $payload->getValue('platformVersionMajor'),
                        $payload->getValue('platformVersionMinor'),
                        $payload->getValue('platformVersionPatch'),
                    ]
                )
            )
        );

        $payload->setValue('platformName', trim(
            $payload->getValue('platformFamily') .
                ' ' .
                $payload->getValue('platformVersion')
        ));

        // Popular os vendors.
        if (false !== stripos($payload->getValue('platformFamily') ?? '', 'windows')) {
            $payload->setValue('isWindows', true);
        } elseif (false !== stripos($payload->getValue('platformFamily') ?? '', 'android')) {
            $payload->setValue('isAndroid', true);
        } elseif (
            false !== stripos($payload->getValue('platformFamily') ?? '', 'mac')
            || false !== stripos($payload->getValue('platformFamily') ?? '', 'ios')
        ) {
            $payload->setValue('isMac', true);
        } elseif (false !== stripos($payload->getValue('platformFamily') ?? '', 'linux')) {
            $payload->setValue('isLinux', true);
        }

        # Request: https://github.com/hisorange/browser-detect/issues/156
        $payload->setValue('isInApp', $this->detectIsInApp($payload));

        return new Result($payload->toArray());
    }

    /**
     * Code snippet based on https://github.com/f2etw/detect-inapp/blob/master/src/inapp.js#L38-L47
     *
     * @param PayloadInterface $payload
     * @return bool
     */
    protected function detectIsInApp(PayloadInterface $payload): bool
    {
        // Simple WebView match
        if (stripos($payload->getAgent(), 'WebView') !== false) {
            return true;
        }

        // Twitter
        if (stripos($payload->getAgent(), 'Twitter') !== false) {
            return true;
        }

        // Twitter
        if (stripos($payload->getAgent(), 'MicroMessenger') !== false) {
            return true;
        }

        // Apple
        if (preg_match(
            '%(iPhone|iPod|iPad)(?!.*Safari\/)%i',
            $payload->getAgent()
        )) {
            return true;
        }

        // Android
        if (preg_match(
            '%Android.*wv%i',
            $payload->getAgent()
        )) {
            return true;
        }

        return false;
    }

    /**
     * Trim the trailing .0 versions from a semantic version string.
     * It makes it more readable for an end user.
     *
     * @param  string $version
     * @return string
     */
    protected function trimVersion(string $version): string
    {
        return trim((string) preg_replace('%(^0.0.0$|\.0\.0$|\.0$)%', '', $version), '.');
    }
}
