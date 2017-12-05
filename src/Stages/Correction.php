<?php

namespace hisorange\BrowserDetect\Stages;

use hisorange\BrowserDetect\ResultInterface;
use League\Pipeline\StageInterface;

/**
 * Correction stage to fix mix ups caused by different results.
 *
 * @package hisorange\BrowserDetect\Stages
 */
class Correction implements StageInterface
{
    /**
     * @param  ResultInterface $payload
     * @return ResultInterface
     */
    public function __invoke($payload)
    {
        if ( ! $payload['isMobile'] && ! $payload['isTablet']) {
            $payload['isTablet'] = $payload['isMobile'] = ! ($payload['isDesktop'] = true);
        } elseif ($payload['isTablet']) {
            $payload['isMobile'] = $payload['isDesktop'] = ! ($payload['isTablet'] = true);
        } else {
            $payload['isTablet'] = $payload['isDesktop'] = ! ($payload['isMobile'] = true);
        }

        return $payload;
    }
}