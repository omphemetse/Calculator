<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CalculatorRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalculatorController extends Controller
{
    public function store(CalculatorRequest $request)
    {
        var_dump($request->request->get('expression'));
    }
}
