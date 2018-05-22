<?php
Blade::if('desktop', function () {
    return app()->make('browser-detect')->detect()->isDesktop();
});

Blade::if('tablet', function () {
    return app()->make('browser-detect')->detect()->isTablet();
});

Blade::if('mobile', function () {
    return app()->make('browser-detect')->detect()->isMobile();
});

Blade::if('browser', function ($fn) {
    return app()->make('browser-detect')->detect()->$fn();
});
