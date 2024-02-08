<?php

namespace App\Http\Controllers;

use App\Contracts\FirstTimeRun;
use Illuminate\Http\Request;

class FirstTimeRunController extends Controller
{
    public function __construct(protected FirstTimeRun $firstTimeRun)
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if($this->firstTimeRun->initialized()) {
            $this->firstTimeRun->firstTimeRun();
        }
        return to_route('home');
    }
}
