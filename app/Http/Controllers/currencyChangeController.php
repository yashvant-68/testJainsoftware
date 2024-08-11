<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class currencyChangeController extends Controller
{
   

    public function getCustomCurrency(Request $request)
    {   //Only USD to INR currency conversion is allowed
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'currency_code' => 'required|string',
                'amount' => 'required|numeric',
                'converter_currency_code' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }
    
            $fromCurrency = strtoupper($request->currency_code);
            $toCurrency = strtoupper($request->converter_currency_code);
            $amount = $request->amount;
    
           
            if ($fromCurrency !== "USD" || $toCurrency !== "INR") {
                return response()->json(['error' => 'Only USD to INR currency conversion is allowed. Please use currency_code as USD and converter_currency_code as INR.'], 400);
            }
    
           
            $rate = 83.97;
            $fluctuation = mt_rand(-100, 100) / 10000;
            $rate += $fluctuation;
    
            $convertedAmount = $amount * $rate;
    
            
            $decimalPlaces = mt_rand(0, 1) ? 2 : 3;
    
            return response()->json([
                'original_amount' => $amount,
                'original_currency' => $fromCurrency,
                'converted_amount' => round($convertedAmount, $decimalPlaces),
                'converted_currency' => $toCurrency,
                'fluctuation_applied' => $fluctuation,
            ]);
        } catch (\Exception $e) {
            
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    


    public function convertCurrency(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'currency_code' => 'required|string',
                'amount' => 'required|numeric',
                'converter_currency_code' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }
    
            $fromCurrency = strtoupper($request->currency_code);
            $toCurrency = strtoupper($request->converter_currency_code);
            $amount = $request->amount;
    
            $response = Http::get("https://api.exchangerate-api.com/v4/latest/{$fromCurrency}");
    
            if ($response->successful()) {
                $rates = $response->json()['rates'];
    
                if (isset($rates[$toCurrency])) {
                    $rate = $rates[$toCurrency];
                    $fluctuation = mt_rand(-100, 100) / 10000;
                    $rate += $fluctuation;
    
                    $convertedAmount = $amount * $rate;
    
                
                    $decimalPlaces = mt_rand(0, 1) ? 2 : 3;
    
                    return response()->json([
                        'original_amount' => $amount,
                        'original_currency' => $fromCurrency,
                        'converted_amount' => round($convertedAmount, $decimalPlaces),
                        'converted_currency' => $toCurrency,
                        'fluctuation_applied' => $fluctuation,
                    ]);
                } else {
                    return response()->json(['error' => 'Conversion currency not supported'], 400);
                }
            } else {
                return response()->json(['error' => 'Failed to fetch exchange rates'], 500);
            }
        } catch (\Exception $e) {
          
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    
}
