<?php

namespace hisorange\BrowserDetect\Stages;

use hisorange\BrowserDetect\Contracts\PayloadInterface;
use hisorange\BrowserDetect\Contracts\ResultInterface;
use hisorange\BrowserDetect\Result;
use League\Pipeline\StageInterface;

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
    public function __invoke($payload)
    {
        // Fix issue when the device is detected at tablet and mobile in the same time.
        if ( ! $payload->getValue('isMobile') && ! $payload->getValue('isTablet')) {
            $payload->getValue('isMobile', false);
            $payload->getValue('isTablet', false);
            $payload->getValue('isDesktop', true);
        } elseif ($payload->getValue('isTablet')) {
            $payload->getValue('isMobile', false);
            $payload->getValue('isTablet', true);
            $payload->getValue('isDesktop', false);
        } else {
            $payload->getValue('isMobile', true);
            $payload->getValue('isTablet', false);
            $payload->getValue('isDesktop', false);
        }

        // Popular browser vendors.
        if (false !== stripos($payload->getValue('browserFamily'), 'chrom')) {
            $payload->setValue('isChrome', true);
        } elseif (false !== stripos($payload->getValue('browserFamily'), 'firefox')) {
            $payload->setValue('isFirefox', true);
        } elseif (false !== stripos($payload->getValue('browserFamily'), 'opera')) {
            $payload->setValue('isOpera', true);
        } elseif (false !== stripos($payload->getValue('browserFamily'), 'safari')) {
            $payload->setValue('isSafari', true);
        } elseif (
            false !== stripos($payload->getValue('browserFamily'), 'explorer') ||
            false !== stripos($payload->getValue('browserFamily'), 'ie') ||
            false !== stripos($payload->getValue('browserFamily'), 'trident')
        ) {
            $payload->setValue('isIE', true);
        }

        // Popular browser engines.
        if (false !== stripos($payload->getAgent(), 'webkit')) {
            $payload->setValue('isWebkit', true);
        } elseif (false !== stripos($payload->getAgent(), 'mozilla')) {
            $payload->setValue('isMozilla', true);
        }

        // Human readable browser version.
        $payload->setValue('browserVersion', $this->trimVersion(
            implode('.', [
                $payload->getValue('browserVersionMajor'),
                $payload->getValue('browserVersionMinor'),
                $payload->getValue('browserVersionPatch'),
            ])
        ));

        $payload->setValue('browserName', trim($payload->getValue('browserFamily') . ' ' . $payload->getValue('browserVersion')));

        // Human readable platform version.
        $payload->setValue('platformVersion', $this->trimVersion(
            implode('.', [
                $payload->getValue('platformVersionMajor'),
                $payload->getValue('platformVersionMinor'),
                $payload->getValue('platformVersionPatch'),
            ])
        ));

        $payload->setValue('platformName', trim($payload->getValue('platformFamily') . ' ' . $payload->getValue('platformVersion')));

        return new Result($payload->toArray());
    }

    /**
     * Trim the trailing .0 versions from a semantic version string.
     * It makes it more readable for an end user.
     *
     * @param  string $version
     * @return string
     */
    protected function trimVersion($version)
    {
        return preg_replace('%(^0.0.0$|\.0\.0$|\.0$)%', '', $version);
    }
}