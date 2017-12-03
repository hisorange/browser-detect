<?php

namespace hisorange\BrowserDetect\Stages;

use hisorange\BrowserDetect\ResultInterface;
use League\Pipeline\StageInterface;
use Mobile_Detect;
use RuntimeException;

class MobileDetect implements StageInterface
{
    /**
     * @param  ResultInterface $payload
     * @return ResultInterface
     * @throws RuntimeException
     */
    public function __invoke($payload)
    {
        $headers = null;

        // If the parsed user agent is not the current visitor's user agent
        // then provide fake headers, because the MobileDetect uses
        // request headers too to identify the device kind.
        if (isset($_SERVER['HTTP_USER_AGENT']) and $payload['agent'] !== $_SERVER['HTTP_USER_AGENT']) {
            $headers = [
                'HTTP_FAKE_HEADER' => 'HiSoRange\Browser',
            ];
        }

        if (class_exists('Mobile_Detect')) {
            $result = new \Mobile_Detect;
        } elseif (class_exists('MobileDetect')) {
            $result = new \MobileDetect;
        } else {
            throw new RuntimeException('MobileDetect is not installed.');
        }

        $result->setHttpHeaders($headers);
        $result->setUserAgent($payload['agent']);

        /**
         * Filtering mechanism for MobileDetect 2.* versions.
         *
         * @param  array $items
         * @return int|null|string
         */
        $filter = function ($items) use ($result) {
            // Play down the $parsed->is('Android'); mechanism.
            foreach ($items as $key => $regex) {
                if ($result->is($key)) {
                    return $key;
                }
            }

            return null;
        };

        $filtered = [];

        // Operating system's family.
        $filtered['osFamily'] = $filter($result->getOperatingSystems());

        // Browser's family.
        $filtered['browserFamily'] = $filter(Mobile_Detect::getBrowsers());

        // Just 'mobile' kind devices.
        if ($result->isMobile() and ! $result->isTablet()) {
            $filtered['isMobile']    = true;
            $filtered['mobileGrade'] = $result->mobileGrade();
            $filtered['deviceModel'] = $filter(Mobile_Detect::getPhoneDevices());
        } elseif ($result->isTablet()) { // Just 'tablet' kind devices.
            $filtered['isTablet']    = true;
            $filtered['mobileGrade'] = $result->mobileGrade();
            $filtered['deviceModel'] = $filter(Mobile_Detect::getTabletDevices());
        }

        // Clear the generic and null values.
        $filtered = array_filter(array_filter($filtered, function ($value) {
            return ! preg_match('%^generic%i', $value);
        }));

        $payload->extend($filtered);

        return $payload;
    }
}