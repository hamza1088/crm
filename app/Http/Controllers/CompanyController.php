<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use DataTables;
use Carbon\Carbon;
class CompanyController extends Controller
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
        $companies = Company::latest()->paginate(5);
        return view('companies.index',compact('companies'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('companies.create');

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
            'name' => 'required',
        ]);
        if($request->hasFile('image_name')){
            $image = $request->file('image_name');
            $image_name = $image->getClientOriginalName();
            $image->move(public_path('/images'),$image_name);
        
            $image_path =  $image_name;
        }

        else {
            $image_path = "";
        }
        $current_date_time = Carbon::now('Asia/Karachi')->toDateTimeString();

        Company::insert ([
            'name' => $request->name,
            'email' => $request->email,
            'website'=>  $request->website,
            'logo'=> $image_path ,
            'created_at'=> $current_date_time,
            'updated_at'=> $current_date_time,
             'status' => 1
             ]);
 
        return redirect()->route('companies')
                        ->with('success','Company created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $companies = Company::orderBy('updated_at', 'DESC')->get();
        $result = array();

        $counter = 0;
        foreach($companies as $row){
            $rows[$counter]["sno"] = $counter + 1;
            $rows[$counter]["name"] =$row['name'];
            $rows[$counter]["email"] = $row['email'];
            $rows[$counter]["website"] = $row['website'];
            $rows[$counter]["website"] = $row['website'];
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
        $company = Company::where('id', $id)->first();
        return view('companies.edit',compact('company'));

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
            'name' => 'required',
        ]);
        if($request->hasFile('image_name')){
            $image = $request->file('image_name');
            $image_name = $image->getClientOriginalName();
            $image->move(public_path('/images'),$image_name);
        
            $image_path = $image_name;
        }

        else {
            //"public/images/"


            $image_path = Company::where('id', $request->id )->pluck('logo')->first();

        }
        $current_date_time = Carbon::now('Asia/Karachi')->toDateTimeString();


        $product = Company::where('id',$request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'website'=>  $request->website,
            'logo'=> $image_path,
            'updated_at'=>  $current_date_time,
             'status' => 1
             ]);
  
        //$company->update($request->all());
  
        return redirect()->route('companies')
                        ->with('success','Company updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Company::find($id)->delete();  
        return redirect()->route('companies');
    }
}
