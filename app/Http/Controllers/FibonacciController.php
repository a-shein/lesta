<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetFibonacciRequest;
use App\Services\FibonacciService;
use Illuminate\Contracts\View\View;

class FibonacciController extends Controller
{
    public function getFibonacci(GetFibonacciRequest $request, FibonacciService $fibonacciService): View
    {
        $request->validated();

        $fibonacciList = null;
        $from = (int) $request->input('from') ?? null;
        $to = (int) $request->input('to') ?? null;

        if ($to) {
            $fibonacciList = $fibonacciService->getFibonacciList($from, $to);
        }

        $data = [
            'result' => $fibonacciList ? implode(', ', $fibonacciList) : null,
            'from' => $from,
            'to' => $to,
        ];

        return view('fibonacci', $data);
    }
}
