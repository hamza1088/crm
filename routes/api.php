<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Use App\Models\Company;
Use App\Models\Employee;
 
Route::get('active_companies', function() {
    $data = Company::where('status', 1)
                   ->get();
    if(!count($data)){
        return response()->json("no record found", 200);      
    }
    else {
        return response()->json($data, 200);
    }
});

Route::get('get_today_employees/{company_id}', function($company_id) {
    DB::enableQueryLog(); // Enable query log
// Your Eloquent query executed by using get()

        
    $dateToday = Carbon\Carbon::now('Asia/Karachi')->toDateString();
    $timeOne = $dateToday. " 00:00:01";
    $timeTwo = $dateToday. " 11:59:59";
    $data = Employee::where('company_id', $company_id)
                    ->whereBetween('created_at',  [$timeOne, $timeTwo])
                   ->get();
    if(!count($data)){
        return response()->json("no record found", 200);      
    }
    else {
        return response()->json($data, 200);
    }
});


Route::get('get_weekly_employees/{company_id}', function($company_id) {
        
    $dateToday = Carbon\Carbon::now('Asia/Karachi');
    $weekStartDate = $dateToday->startOfWeek()->format('Y-m-d h:i:s');
    $weekEndDate = $dateToday->endOfWeek()->format('Y-m-d h:i:s');
    $data = Employee::where('company_id', $company_id)
                    ->whereBetween('created_at',  [$weekStartDate, $weekEndDate])
                   ->get();
    if(!count($data)){
        return response()->json("no record found", 200);      
    }
    else {
        return response()->json($data, 200);
    }
});

Route::get('get_employee_details/{company_id}', function($company_id) {
        
    $data = Employee::where('company_id', $company_id)
                    ->join('companies', 'companies.id', '=', 'employees.company_id')
                    ->get(['first_name','last_name', 'logo', 'employees.email']);
    if(!count($data)){
        return response()->json("no record found", 200);      
    }
    else {
        return response()->json($data, 200);
    }
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
