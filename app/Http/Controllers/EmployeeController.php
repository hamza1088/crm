<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Employee;
use DataTables;
use Carbon\Carbon;
class EmployeeController extends Controller
{

    public function __construct(){
            

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::latest()->paginate(5);
        return view('employees.index',compact('employees'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        //dd($companies[0]->name);
        return view('employees.create', compact('companies'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        $current_date_time = Carbon::now('Asia/Karachi')->toDateTimeString();

        Employee::insert ([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email'=>  $request->email,
            'phone'=>  $request->phone,
            'company_id'=> $request->company,
            'created_at'=> $current_date_time,
            'updated_at'=> $current_date_time,
             'status' => 1
             ]);
 
        return redirect()->route('employees')
                        ->with('success','Emloyee created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        \DB::connection()->enableQueryLog();
        $employees = Employee::join('companies', 'employees.company_id', '=', 'companies.id')
               ->get(['employees.*', 'companies.name']);
        $queries = \DB::getQueryLog();
        //dd($employees);
        
    
        $result = array();

        $counter = 0;
        foreach($employees as $row){
            $rows[$counter]["sno"] = $counter + 1;
            $rows[$counter]["first_name"] =$row['first_name'];
            $rows[$counter]["last_name"] =$row['last_name'];
            $rows[$counter]["email"] = $row['email'];
            $rows[$counter]["phone"] = $row['phone'];
            $rows[$counter]["company"] = $row['name'];
            $rows[$counter]["id"] = $row['id'];
            $counter+=1;
        }
        // $result["recordsFiltered"] = count($rows);
        $result["data"] = $rows; 
        return json_encode($result);
       
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $companies = Company::all();
        $employees = Employee::where('id', $id)->first();
        return view('employees.edit',compact(['companies','employees']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        // dd($request->id);
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        $current_date_time = Carbon::now('Asia/Karachi')->toDateTimeString();


        $emp = Employee::where('id',$request->id)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email'=>  $request->email,
            'phone'=>  $request->phone,
            'company_id'=> $request->company,
            'created_at'=> $current_date_time,
            'updated_at'=> $current_date_time,
             'status' => 1
             ]);
  
        //$company->update($request->all());
  
        return redirect()->route('employees')
                        ->with('success','Employee updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Employee::find($id)->delete();  
        return redirect()->route('employees');
    }
}
