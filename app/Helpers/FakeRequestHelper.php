<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class FakeRequestHelper
{
    public static function fake(string $url, array $params): Request
    {
        $fullUrl = config('app.url') ?? 'https://develop.459x.com';

        URL::forceRootUrl($fullUrl);
        if (str_starts_with($fullUrl, 'https://')) {
            URL::forceScheme('https');
        } else {
            URL::forceScheme('http');
        }

        $request = Request::create($url, 'GET', $params);
        app()->instance('request', $request);
        URL::setRequest($request);

        return $request;
    }
}
