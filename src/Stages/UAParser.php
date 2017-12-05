<?php

namespace hisorange\BrowserDetect\Stages;

use hisorange\BrowserDetect\Contracts\ResultInterface;
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
     * @param  ResultInterface $payload
     * @return ResultInterface
     */
    public function __invoke($payload)
    {
        $parser    = Parser::create();
        $result    = $parser->parse($payload->getUserAgent());
        $extension = [];

        if ($result->ua->family !== 'Other') {
            $extension['browserFamily']       = (string) $result->ua->family;
            $extension['browserVersionMajor'] = (int) $result->ua->major ?: 0;
            $extension['browserVersionMinor'] = (int) $result->ua->minor ?: 0;
            $extension['browserVersionPatch'] = (int) $result->ua->patch ?: 0;
        }

        if ($result->os->family !== 'Other') {
            $extension['osFamily']       = (string) $result->os->family;
            $extension['osVersionMajor'] = (int) $result->os->major ?: 0;
            $extension['osVersionMinor'] = (int) $result->os->minor ?: 0;
            $extension['osVersionPatch'] = (int) $result->os->patch ?: 0;
        }

        if ($result->device->family !== 'Other') {
            $extension['deviceFamily'] = (string) $result->device->family;
            $extension['deviceModel']  = (string) $result->device->model;
        }

        if ( ! empty($extension)) {
            $payload->extend($extension);
        }

        return $payload;
    }
}