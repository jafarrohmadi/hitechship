<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    // Ships
    Route::apiResource('ships', 'ShipApiController');

    // Terminal Ships
    Route::apiResource('terminal-ships', 'TerminalShipApiController');
});
