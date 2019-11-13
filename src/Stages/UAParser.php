<?php

namespace hisorange\BrowserDetect\Stages;

use hisorange\BrowserDetect\Contracts\PayloadInterface;
use League\Pipeline\StageInterface;
use UAParser\Parser;

/**
 * Main parser to get the most of the info about the browser and it's operating system.
 *
 * @package hisorange\BrowserDetect\Stages
 */
class UAParser implements StageInterface
{
    /**
     * @throws \UAParser\Exception\FileNotFoundException
     *
     * @param  PayloadInterface $payload
     * @return PayloadInterface
     */
    public function __invoke($payload)
    {
        $parser = Parser::create();
        $result = $parser->parse($payload->getAgent());

        if ($result->ua->family !== 'Other') {
            $payload->setValue('browserFamily', (string) $result->ua->family);
            $payload->setValue('browserVersionMajor', (int) ($result->ua->major ?: 0));
            $payload->setValue('browserVersionMinor', (int) ($result->ua->minor ?: 0));
            $payload->setValue('browserVersionPatch', (int) ($result->ua->patch ?: 0));
        }

        if ($result->os->family !== 'Other') {
            $payload->setValue('platformFamily', (string) $result->os->family);
            $payload->setValue('platformVersionMajor', (int) ($result->os->major ?: 0));
            $payload->setValue('platformVersionMinor', (int) ($result->os->minor ?: 0));
            $payload->setValue('platformVersionPatch', (int) ($result->os->patch ?: 0));
        }

        if ($result->device->family !== 'Other') {
            $payload->setValue('deviceFamily', (string) $result->device->family);
            $payload->setValue('deviceModel', (string) $result->device->model);
        }

        return $payload;
    }
}
