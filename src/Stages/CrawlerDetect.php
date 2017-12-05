<?php

namespace hisorange\BrowserDetect\Stages;

use hisorange\BrowserDetect\Contracts\ResultInterface;
use League\Pipeline\StageInterface;

/**
 * Checks if the user agent belongs to bot or crawler.
 *
 * @package hisorange\BrowserDetect\Stages
 */
class CrawlerDetect implements StageInterface
{
    /**
     * @param  ResultInterface $payload
     * @return ResultInterface
     */
    public function __invoke($payload)
    {
        $crawler          = new \Jaybizzle\CrawlerDetect\CrawlerDetect(['HTTP_FAKE_HEADER' => 'Crawler\Detect'], $payload->getUserAgent());
        $payload['isBot'] = $crawler->isCrawler();

        return $payload;
    }
}