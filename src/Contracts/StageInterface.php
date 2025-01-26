<?php

namespace hisorange\BrowserDetect\Contracts;

use Closure;

interface StageInterface
{
    /**
     * Process the payload.
     *
     * @param  PayloadInterface $payload
     * @return mixed
     */
    public function __invoke(PayloadInterface $payload, Closure $next);
}
