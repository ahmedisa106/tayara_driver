<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

trait response
{
    /**
     * @param bool $success
     * @param string $message
     * @param mixed|null $data
     * @param int $code
     * @param null $pagination
     * @return JsonResponse
     */
    function final_response(bool $success = true, string $message = "", mixed $data = null, int $code = 200, $pagination = null): JsonResponse
    {
        $returned_data = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];

        if ($pagination !== null) {
            $returned_data['pagination'] = $pagination;
        }

        return response()->json($returned_data, $code);
    }

    function success(LengthAwarePaginator $paginator, string $resource = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => "success",
            'data' => $resource ? $resource::collection($paginator->items()) : $paginator->items(),
            'pagination' => getPaginationInfo($paginator)
        ]);
    }
}
