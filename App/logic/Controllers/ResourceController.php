<?php

namespace App\Logic\Controllers;

use App\Logic\Handlers\ResourceHandler;
use Pecee\Controllers\IResourceController;
use Sim\Abstracts\Mvc\Controller\AbstractController;

class ResourceController extends AbstractController implements IResourceController
{
    /**
     * @return string|null
     */
    public function index(): ?string
    {
        $data = new ResourceHandler();

        // fill data

        \response()->json($data->getReturnData());
        return null;
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function show($id): ?string
    {
        $data = new ResourceHandler();

        // fill data

        \response()->json($data->getReturnData());
        return null;
    }

    /**
     * @return string|null
     */
    public function store(): ?string
    {
        $data = new ResourceHandler();

        // fill data

        \response()->json($data->getReturnData());
        return null;
    }

    /**
     * @return string|null
     */
    public function create(): ?string
    {
        $data = new ResourceHandler();

        // fill data

        \response()->json($data->getReturnData());
        return null;
    }

    /**
     * View
     * @param mixed $id
     * @return string|null
     */
    public function edit($id): ?string
    {
        $data = new ResourceHandler();

        // fill data

        \response()->json($data->getReturnData());
        return null;
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function update($id): ?string
    {
        $data = new ResourceHandler();

        // fill data

        \response()->json($data->getReturnData());
        return null;
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function destroy($id): ?string
    {
        $data = new ResourceHandler();

        // fill data

        \response()->json($data->getReturnData());
        return null;
    }
}