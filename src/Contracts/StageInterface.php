<?php

namespace hisorange\BrowserDetect\Contracts;

interface StageInterface
{
    /**
     * Process the payload.
     *
     * @param  PayloadInterface $payload
     * @return mixed
     */
    public function __invoke(PayloadInterface $payload);
}
