<?php

namespace hisorange\BrowserDetect\Stages;

use hisorange\BrowserDetect\ResultInterface;
use League\Pipeline\StageInterface;

/**
 * Class Correction
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
        // Fix where different packages define devices differently.
        // Device is desktop if neither mobile or tablet.
        if ( ! $payload['isMobile'] && ! $payload['isTablet']) {
            $payload['isDesktop'] = true;
            $payload['isTablet']  = $payload['isMobile'] = false;
        }
        // Device is tablet if any of the plugin identified as a tablet.
        // Fix for mobile detect plugin where a tablet is assumed as both mobile and tablet.
        elseif ($payload['isTablet']) {
            $payload['isTablet']  = true;
            $payload['isDesktop'] = $payload['isMobile'] = false;
        } // Mobile if neither tablet or desktop.
        else {
            $payload['isMobile']  = true;
            $payload['isDesktop'] = $payload['isTablet'] = false;
        }
        // Fixing empty operating system with a generic value.
        $payload['osFamily'] = $payload['osFamily'] ?: 'UnknownOS';
        // Fixing empty browser family with generic value.
        $payload['browserFamily'] = $payload['browserFamily'] ?: 'UnknownBrowserFamily';
        // Common name for Internet Explorer.
        $payload['browserFamily'] = preg_match('%^(IE|MSIE)%', $payload['browserFamily']) ? 'Internet Explorer' : $payload['browserFamily'];

        return $payload;
    }
}