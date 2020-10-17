<?php

namespace App\Logic\Handlers;

use App\Logic\Controller\PageController;
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
        if ($request->getUrl()->contains('/api')) {
            response()->json([
                'error' => $error->getMessage(),
                'code' => $error->getCode(),
            ]);
        }

        /* The router will throw the NotFoundHttpException on 404 */
        if ($error instanceof NotFoundHttpException) {
            // Render custom 404-page
            $request->setRewriteUrl(url(NOT_FOUND));
            return;
        } elseif($error->getCode() == 500) {
            // Render custom 500-page
            $request->setRewriteUrl(url(SERVER_ERROR));
            return;
        }

        throw $error;
    }
}