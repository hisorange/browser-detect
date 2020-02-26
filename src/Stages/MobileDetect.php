<?php

namespace hisorange\BrowserDetect\Stages;

use Mobile_Detect;
use hisorange\BrowserDetect\Contracts\StageInterface;
use hisorange\BrowserDetect\Contracts\PayloadInterface;

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
    public function __invoke(PayloadInterface $payload): PayloadInterface
    {
        $result = new Mobile_Detect();
        $result->setHttpHeaders(['HTTP_FAKE_HEADER' => 'Mobile\Detect\Header']);
        $result->setUserAgent($payload->getAgent());

        // Need to test for tablet first, because most of the tablet are mobile too.
        if ($result->isTablet()) {
            $payload->setValue('isTablet', true);
            $payload->setValue('mobileGrade', (string) $result->mobileGrade());
            $payload->setValue('deviceModel', (string) $this->filter($result, Mobile_Detect::getTabletDevices()));
        } elseif ($result->isMobile()) {
            $payload->setValue('isMobile', true);
            $payload->setValue('mobileGrade', (string) $result->mobileGrade());
            $payload->setValue('deviceModel', (string) $this->filter($result, Mobile_Detect::getPhoneDevices()));
        }

        $payload->setValue('platformFamily', $this->filter($result, Mobile_Detect::getOperatingSystems()));
        $payload->setValue('browserFamily', $this->filter($result, Mobile_Detect::getBrowsers()));

        return $payload;
    }

    /**
     * Filter through the choices to find the matching one.
     *
     * @param Mobile_Detect $result
     * @param array         $choices
     *
     * @return string|null
     */
    protected function filter(Mobile_Detect $result, array $choices): ?string
    {
        foreach ($choices as $key => $regex) {
            if ($result->is($key) and stripos($key, 'generic') === false) {
                return $key;
            }
        }

        return null;
    }
}
