<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee = Employee::paginate(10);
        return response()->json(['data' => $employee], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        $employee = new Employee();
        $employee->first_name = $request->input('first_name');
        $employee->last_name = $request->input('last_name');
        $employee->email = $request->input('email');
        $employee->companies_id = $request->input('companies_id');
        $employee->phone = $request->input('phone');
        $employee->save();
        //store companies
        if ($request->has('companies')) {
            $employee->companies()->sync($request->input('companies'));
        }
        $employee = Employee::with('companies')->find($employee->id);

        return response()->json(['data' => $employee, 'message' => 'Created successfully'] , 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::with('companies')->findOrFail($id);
        $employee->prev_employee = Employee::where('id', '<' , $id)->orderBy('id', 'desc')->first();
        $employee->next_employee = Employee::where('id','>', $id)->first();
        return response()->json(['data' =>$employee], 200);
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
            $this->validate($request,[
                'first_name' => 'required',
                'last_name' => 'required',
            ]);
            
            $employee->first_name = $request->input('first_name');
            $employee->last_name = $request->input('last_name');
            $employee->email = $request->input('email');
            $employee->companies_id = $request->input('companies_id');
            $employee->phone = $request->input('phone');
            $employee->save();
            //store companies
            if ($request->has('companies')) {
                $employee->companies()->sync($request->input('companies'));
            }
            $employee = Employee::with('companies')->find($employee->id);

            return response()->json(['data' => $employee, 'message' => 'Updated successfully'] , 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return response()->json(['message' =>'Deleted successfully'], 200);
        // if (!auth("api")->user()->is_admin) {
        //     return response()->json(['message' => 'Unauthorised'], 500);
        // }
        
    }
}
