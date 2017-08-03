<?php
namespace App;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class MyRouter extends Router {

    public function resolveRouteFromUrl($url) {
        return $this->findRoute($url);
    }
}