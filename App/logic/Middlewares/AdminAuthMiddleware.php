<?php

namespace App\Logic\Middlewares;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;

class AdminAuthMiddleware implements IMiddleware
{
    public function handle(Request $request): void
    {
        // Do authentication
        $request->authenticated = false;
    }
}