<?php

namespace hisorange\BrowserDetect\Stages;

use hisorange\BrowserDetect\Contracts\PayloadInterface;
use League\Pipeline\StageInterface;

/**
 * Most reliable mobile and tablet testing stage.
 *
 * @package hisorange\BrowserDetect\Stages
 */
class MobileDetect implements StageInterface
{
    /**
     * @param  PayloadInterface $payload
     * @return PayloadInterface
     */
    public function __invoke($payload)
    {
        if (class_exists('Mobile_Detect')) {
            $class = 'Mobile_Detect';
        } else {
            $class = 'MobileDetect';
        }

        /** @var \Mobile_Detect|\MobileDetect $result */
        $result = new $class;
        $result->setHttpHeaders(['HTTP_FAKE_HEADER' => 'Mobile\Detect\Header']);
        $result->setUserAgent($payload->getAgent());

        // Need to test for tablet first, because most of the tablet are mobile too.
        if ($result->isTablet()) {
            $payload->setValue('isTablet', true);
            $payload->setValue('mobileGrade', (string) $result->mobileGrade());
            $payload->setValue('deviceModel', (string) $this->filter($result, $class::getTabletDevices()));
        } elseif ($result->isMobile()) {
            $payload->setValue('isMobile', true);
            $payload->setValue('mobileGrade', (string) $result->mobileGrade());
            $payload->setValue('deviceModel', (string) $this->filter($result, $class::getPhoneDevices()));
        }

        $payload->setValue('platformFamily', $this->filter($result, $class::getOperatingSystems()));
        $payload->setValue('browserFamily', $this->filter($result, $class::getBrowsers()));

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
            if ($result->is($key) and stripos($key, 'generic') === false) {
                return $key;
            }
        }

        return null;
    }
}