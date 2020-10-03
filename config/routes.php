<?php

use App\Action\Auth\TokenCreateAction;
use App\Action\Item\ItemCreateAction;
use App\Action\Item\ItemDeleteAction;
use App\Action\Item\ItemEditAction;
use App\Action\Item\ItemSelectAction;
use App\Action\Listing\ListingApprovingAction;
use App\Action\Listing\ListingCreateAction;
use App\Action\Listing\ListingDeleteAction;
use App\Action\Listing\ListingFinishingAction;
use App\Action\Listing\ListingSelectAction;
use App\Action\Listing\ListingSelectItemsAction;
use App\Action\PreflightAction;
use App\Action\TestAction;
use App\Middleware\JwtAuthMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

/**
 * Register routes
 * @param App $app
 */
return static function (App $app) {
    $app->get('/test', TestAction::class);
    $app->post('/login', TokenCreateAction::class);
    $app->options('/login', PreflightAction::class);
    $app->group('/listings', function (RouteCollectorProxy $group) {
        $group->group('', function (RouteCollectorProxy $group) {
            $group->post('', ListingCreateAction::class);
            $group->delete('/{id}', ListingDeleteAction::class);
            $group->get('[/{id:[0-9]+}]', ListingSelectAction::class);
            $group->get('/{id}/items', ListingSelectItemsAction::class);
            $group->put('/{id}/approve', ListingApprovingAction::class);
            $group->put('/{id}/finish', ListingFinishingAction::class);
        })->add(JwtAuthMiddleware::class);
        $group->options('[/{id:[0-9]+}]',  PreflightAction::class);
        $group->options('/{id}/items',  PreflightAction::class);
        $group->options('/{id}/approve',  PreflightAction::class);
        $group->options('/{id}/finish',  PreflightAction::class);
    });
    $app->group('/items', function (RouteCollectorProxy $group) {
        $group->group('', function (RouteCollectorProxy $group) {
            $group->post('', ItemCreateAction::class);
            $group->delete('/{id}', ItemDeleteAction::class);
            $group->get('[/{id:[0-9]+}]', ItemSelectAction::class);
            $group->put('/{id:[0-9]+}', ItemEditAction::class);
        })->add(JwtAuthMiddleware::class);
        $group->options('[/{id:[0-9]+}]', PreflightAction::class);
        $group->options('/edit/{id:[0-9]+}', PreflightAction::class);
    });
};
