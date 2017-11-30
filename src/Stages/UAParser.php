<?php

namespace hisorange\BrowserDetect\Stages;

use hisorange\BrowserDetect\ResultInterface;
use League\Pipeline\StageInterface;

class UAParser implements StageInterface
{
    /**
     * @param  ResultInterface $payload
     * @return ResultInterface
     */
    public function __invoke($payload)
    {
        $parser   = new \UAParser\UAParser();
        $result   = $parser->parse($payload['agent']);
        $filtered = [];

        $browser = $result->getBrowser();

        if ($browser->getFamily() !== 'Other') {
            $filtered['browserFamily']       = $browser->getFamily();
            $filtered['browserVersionMajor'] = $browser->getMajor() ?: 0;
            $filtered['browserVersionMinor'] = $browser->getMinor() ?: 0;
            $filtered['browserVersionPatch'] = $browser->getPatch() ?: 0;
        }

        $os = $result->getOperatingSystem();

        if ($os->getFamily() !== 'Other') {
            $filtered['osFamily']       = $os->getFamily();
            $filtered['osVersionMajor'] = $os->getMajor() ?: 0;
            $filtered['osVersionMinor'] = $os->getMinor() ?: 0;
            $filtered['osVersionPatch'] = $os->getPatch() ?: 0;
        }

        $device = $result->getDevice();

        if ($device->getConstructor() !== 'Other') {
            $filtered['isMobile']     = $device->is('mobile');
            $filtered['isTablet']     = $device->is('tablet');
            $filtered['deviceFamily'] = $device->getConstructor();
            $filtered['deviceModel']  = $device->getModel() ?: null;
        }

        $payload->extend($filtered);

        return $payload;
    }
}