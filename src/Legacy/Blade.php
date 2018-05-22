<?php
foreach (['desktop', 'tablet', 'mobile'] as $key) {
    Blade::if($key, function () use ($key) {
        $fn = 'is' . $key;
        return app()->make('browser-detect')->detect()->$fn();
    });
}

Blade::if('browser', function ($fn) {
    return app()->make('browser-detect')->detect()->$fn();
});
