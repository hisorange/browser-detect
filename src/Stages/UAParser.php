<?php

namespace hisorange\BrowserDetect\Stages;

use hisorange\BrowserDetect\ResultInterface;
use League\Pipeline\StageInterface;
use UAParser\Parser;

class UAParser implements StageInterface
{
    /**
     * @param  ResultInterface $payload
     * @return ResultInterface
     * @throws \UAParser\Exception\FileNotFoundException
     */
    public function __invoke($payload)
    {
        $parser = Parser::create();
        $result = $parser->parse($payload['agent']);

        $filtered = [];

        if ($result->ua->family !== 'Other') {
            $filtered['browserFamily']       = $result->ua->family;
            $filtered['browserVersionMajor'] = $result->ua->major ?: 0;
            $filtered['browserVersionMinor'] = $result->ua->minor ?: 0;
            $filtered['browserVersionPatch'] = $result->ua->patch ?: 0;
        }

        if ($result->os->family !== 'Other') {
            $filtered['osFamily']       = $result->os->family;
            $filtered['osVersionMajor'] = $result->os->major ?: 0;
            $filtered['osVersionMinor'] = $result->os->minor ?: 0;
            $filtered['osVersionPatch'] = $result->os->patch ?: 0;
        }

        $payload->extend($filtered);

        return $payload;
    }
}