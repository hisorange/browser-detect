<?php

namespace hisorange\BrowserDetect\Stages;

use hisorange\BrowserDetect\Contracts\ResultInterface;
use hisorange\BrowserDetect\Exceptions\RuntimeException;
use League\Pipeline\StageInterface;
use Mobile_Detect;

/**
 * Most reliable mobile and tablet testing stage.
 *
 * @package hisorange\BrowserDetect\Stages
 */
class MobileDetect implements StageInterface
{
    /**
     * @throws RuntimeException
     *
     * @param  ResultInterface $payload
     * @return ResultInterface
     */
    public function __invoke($payload)
    {
        if (class_exists('Mobile_Detect')) {
            $class = 'Mobile_Detect';
        } elseif (class_exists('MobileDetect')) {
            $class = 'MobileDetect';
        } else {
            throw new RuntimeException('Mobile Detect package is not installed.');
        }

        /** @var Mobile_Detect $result */
        $result = new $class;
        $result->setHttpHeaders(['HTTP_FAKE_HEADER' => 'Mobile\Detect']);
        $result->setUserAgent($payload->getUserAgent());

        $extension = [];

        // Mobile but not tablet, some tablet gets the mobile match too.
        if ($result->isMobile() and ! $result->isTablet()) {
            $extension['isMobile']    = true;
            $extension['mobileGrade'] = (string) $result->mobileGrade();
            $extension['deviceModel'] = (string) $this->filter($result, $class::getPhoneDevices());
        } elseif ($result->isTablet()) {
            $extension['isTablet']    = true;
            $extension['mobileGrade'] = (string) $result->mobileGrade();
            $extension['deviceModel'] = (string) $this->filter($result, $class::getTabletDevices());
        }

        $extension['osFamily']      = $this->filter($result, $class::getOperatingSystems());
        $extension['browserFamily'] = $this->filter($result, $class::getBrowsers());

        if ( ! empty($extension)) {
            $payload->extend($extension);
        }

        return $payload;
    }

    /**
     * Filter through the choices to find the matching one.
     *
     * @param Mobile_Detect|MobileDetect $result
     * @param array                      $choices
     *
     * @return string|null
     */
    protected function filter($result, $choices)
    {
        foreach ($choices as $key => $regex) {
            if ($result->is($key) and stripos($key, 'generic')) {
                return $key;
            }
        }

        return null;
    }
}