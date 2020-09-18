<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CalculatorRequest;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class CalculatorController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CalculatorRequest $request)
    {
        // var_dump($request->request->get('expression'));

        $expression = $request->request->get('expression');

        if (strlen($expression) > 0) {
            if (strpos($expression, ')(')) {
                $cnt = 0;
                $detect_multiplication_of_many_brackets = explode(')(', $expression);

                foreach ($detect_multiplication_of_many_brackets as $item) {
                    $item = str_replace("(", "", $item);
                    $item = str_replace(")", "", $item);

                    if ($cnt > 0) {
                        $expression =  $expression * math_eval($item);
                    } else {
                        $expression = math_eval($item);
                    }

                    $cnt++;
                }
            }

            $opening_brackets_count = substr_count($expression, '(');

            if ($opening_brackets_count > 0) {
                for ($x = 0; $x < $opening_brackets_count; $x++) {
                    $opening_bracket_start = strpos($expression, '(');
                    $closing_bracket_start = strpos($expression, ')');

                    $original_bracket_expression = substr($expression, $opening_bracket_start, $closing_bracket_start -1);
                    $brackets_expression_string = substr($expression, $opening_bracket_start + 1, $closing_bracket_start - 3);

                    $brackets_expression_results = $this->doCalculate($brackets_expression_string);

                    $expression = str_replace($original_bracket_expression, $brackets_expression_results, $expression);

                    // echo math_eval($expression);

                    return response([
                        'results' => math_eval($expression)
                    ], Response::HTTP_OK);
                }
            } else {
                // echo math_eval($expression);

                return response([
                    'results' => math_eval($expression)
                ], Response::HTTP_OK);
            }
        }
    }

    public function doCalculate($brackets_expression) {
        $division_results = 0;
        $multiplication_results = 0;
        $addition_results = 0;
        $substraction_results = 0;

        if (substr_count($brackets_expression, '/')) {
            $division_operants = explode('/', $brackets_expression);
            $division_results = $division_operants[0] / $division_operants[1];
        }

        if (substr_count($brackets_expression, '*')) {
            $multiplication_operants = explode('*', $brackets_expression);
            $multiplication_results = $multiplication_operants[0] * $multiplication_operants[1];
        }

        if (substr_count($brackets_expression, '+')) {
            $addition_operants = explode('+', $brackets_expression);
            $addition_results = $addition_operants[0] + $addition_operants[1];
        }

        if (substr_count($brackets_expression, '-')) {
            $substraction_operants = explode('-', $brackets_expression);
            $substraction_results = $substraction_operants[0] - $substraction_operants[1];
        }

        $results = $division_results + $multiplication_results + $addition_results + $substraction_results;

        return $results;
    }
}
