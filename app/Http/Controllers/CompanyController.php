<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::paginate(10);
        return response()->json(['data' =>$company], 200);
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
            'name' => 'required',
            'email' => 'required'
        ]);
        $company = new Company();
        $company->name = $request->input('name');
        $company->email = $request->input('email');
        $company->website = $request->input('website');
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');

            $filename = time().'-'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $company->logo = $filename;
        }
        $company->save();
        return response()->json(['data' => $company, 'message' => 'created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Companies::findOrFail($id);
        return response()->json(['data' => $company], 200);
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
        $company = Company::findOrFail($id);
        $rules = [
            'name' => 'required',
            'email' => 'required'
        ];
        if ($company->logo =="" || ($company->logo != "" && !\File::exists('uploads/' . $companies->logo))) {
            $rules['image'] = 'required';
        }
        $this->validate($request, $rules);
        $company->name = $request->input('name');
        $company->email = $request->input('email');
        $company->website = $request->input('website');
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');

            $filename = time().'-'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $company->logo = $filename;
        }
        $company->save();
        return response()->json(['data' => $company, 'message' => 'Updated successfully'], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Companies::findOrFail($id);
        // remove image
        $this->removeImage($company);
        $post->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
