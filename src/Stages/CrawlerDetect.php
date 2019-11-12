<?php

namespace hisorange\BrowserDetect\Stages;

use hisorange\BrowserDetect\Contracts\PayloadInterface;
use League\Pipeline\StageInterface;

/**
 * Checks if the user agent belongs to bot or crawler.
 *
 * @package hisorange\BrowserDetect\Stages
 */
class CrawlerDetect implements StageInterface
{
    /**
     * @param  PayloadInterface $payload
     * @return PayloadInterface
     */
    public function __invoke($payload)
    {
        $crawler          = new \Jaybizzle\CrawlerDetect\CrawlerDetect(['HTTP_FAKE_HEADER' => 'Crawler\Detect'], $payload->getAgent());
        $payload->setValue('isBot', $crawler->isCrawler());

        return $payload;
    }
}
