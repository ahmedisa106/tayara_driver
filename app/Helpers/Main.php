<?php

if (!function_exists('getFile')) {
    function getFile($path = null)
    {
        return app()->isLocal() ? config('tayara.local', 'tayara.production') . '/storage/uploads/' . $path : "";
    }
}

if (!function_exists('getPaginationInfo')) {
    function getPaginationInfo($model)
    {
        if (!is_object($model)) {
            return false;
        }
        return [
            'total' => $model->total(),
            'per_page' => $model->perPage(),
            'current_page' => $model->currentPage(),
            'last_page_url' => $model->lastPage(),
            'next_page_url' => $model->nextPageUrl(),
            'prev_page_url' => $model->previousPageUrl(),
            'path' => $model->path(),
            'from' => $model->firstItem(),
            'to' => $model->lastItem(),
        ];
    }
}
