<?php

namespace Osi\AuthApi\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

trait ResponseBaseTrait
{
    public function data($data)
    {
        return $this->success([JsonResource::$wrap => $data]);
    }
    /**
     * 201
     *
     * @author osindex<yaoiluo@gmail.com>
     * @param string $content
     * @return Response
     */
    protected function created($content = '')
    {
        return new Response($content, Response::HTTP_CREATED);
    }

    /**
     * 202
     *
     * @author osindex<yaoiluo@gmail.com>
     * @return Response
     */
    protected function accepted()
    {
        return new Response('', Response::HTTP_ACCEPTED);
    }

    /**
     * 204
     *
     * @author osindex<yaoiluo@gmail.com>
     * @return Response
     */
    protected function noContent()
    {
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * 400
     *
     * @author osindex<yaoiluo@gmail.com>
     * @param $message
     * @param array $headers
     * @param int $options
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest($message, array $headers = [], $options = 0)
    {
        return response()->json([
            'message' => $message,
        ], Response::HTTP_BAD_REQUEST, $headers, $options);
    }

    /**
     * 401
     *
     * @author osindex<yaoiluo@gmail.com>
     * @param string $message
     * @param array $headers
     * @param int $options
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unauthorized($message = '', array $headers = [], $options = 0)
    {
        return response()->json([
            'message' => $message ? $message : 'Token Signature could not be verified.',
        ], Response::HTTP_UNAUTHORIZED, $headers, $options);
    }

    /**
     * 403
     *
     * @author osindex<yaoiluo@gmail.com>
     * @param string $message
     * @param array $headers
     * @param int $options
     * @return \Illuminate\Http\JsonResponse
     */
    protected function forbidden($message = '', array $headers = [], $options = 0)
    {
        return response()->json([
            'message' => $message ? $message : 'Insufficient permissions.',
        ], Response::HTTP_FORBIDDEN, $headers, $options);
    }

    /**
     * 422
     *
     * @author osindex<yaoiluo@gmail.com>
     * @param array $errors
     * @param array $headers
     * @param string $message
     * @param int $options
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unprocesableEtity(array $errors = [], array $headers = [], $message = '', $options = 0)
    {
        return response()->json([
            'message' => $message ? $message : '422 Unprocessable Entity',
            'errors' => $errors,
        ], Response::HTTP_UNPROCESSABLE_ENTITY, $headers, $options);
    }

    /**
     * 200
     *
     * @author osindex<yaoiluo@gmail.com>
     * @param array $data
     * @param array $headers
     * @param int $options
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success(array $data, array $headers = [], $options = 0)
    {
        return response()->json($data, Response::HTTP_OK, $headers, $options);
    }
}
