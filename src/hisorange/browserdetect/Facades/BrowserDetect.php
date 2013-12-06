<?php 
namespace hisorange\browserdetect\Facades;

use Illuminate\Support\Facades\Facade;

class BrowserDetect extends Facade {
    /**
     * Get the registered component.
     *
     * @return object
     */
    protected static function getFacadeAccessor() { return 'browserdetect'; }
}