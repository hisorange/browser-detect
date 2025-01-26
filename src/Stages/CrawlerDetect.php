<?php

namespace hisorange\BrowserDetect\Stages;

use Closure;
use hisorange\BrowserDetect\Contracts\StageInterface;
use hisorange\BrowserDetect\Contracts\PayloadInterface;

/**
 * Checks if the user agent belongs to bot or crawler.
 *
 * @package hisorange\BrowserDetect\Stages
 */
class CrawlerDetect implements StageInterface
{
    public function __invoke(PayloadInterface $payload, Closure $next)
    {
        $crawler = new \Jaybizzle\CrawlerDetect\CrawlerDetect(
            ['HTTP_FAKE_HEADER' => 'Crawler\Detect'],
            $payload->getAgent()
        );
        $payload->setValue('isBot', $crawler->isCrawler());

        return $next($payload);
    }
}
