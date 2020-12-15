<?php

namespace App\Logic\Handlers;

use Pecee\Http\Request;
use Pecee\SimpleRouter\Handlers\IExceptionHandler;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;

class CustomExceptionHandler implements IExceptionHandler
{
    /**
     * @param Request $request
     * @param \Exception $error
     * @throws \Exception
     */
    public function handleError(Request $request, \Exception $error): void
    {
        /* You can use the exception handler to format errors depending on the request and type. */
        if ($request->getUrl()->contains('/api') ||
            $request->getUrl()->contains('/ajax')) {
            $resourceHandler = new ResourceHandler();
            $resourceHandler->statusCode((int)$error->getCode())->errorMessage($error->getMessage());
            response()->httpCode((int)$error->getCode())->json($resourceHandler->getReturnData());
        }

        /* The router will throw the NotFoundHttpException on 404 */
        if ($error instanceof NotFoundHttpException) {
            // Render custom 404-page
            $request->setRewriteUrl(url(NOT_FOUND));
            return;
        } elseif ($error->getCode() == 500) {
            // Render custom 500-page
            $request->setRewriteUrl(url(SERVER_ERROR));
            return;
        }

        throw $error;
    }
}