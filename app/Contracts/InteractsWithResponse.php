<?php

namespace App\Contracts;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

interface InteractsWithResponse
{
    public function handleResponse(Response|RedirectResponse $response): Response|RedirectResponse;
}
