<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();


        return view('company.index', compact('companies'));
    }

    public function create()
    {
        return view('company.create');
    }

    public function store(Request $request)
    {
        Company::create([
            'name' => $request->name,
        ]);

        return redirect()->route('company.');
    }

    public function edit($id)
    {
        $company = Company::find($id);

        return view('company.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $company = Company::find($id);

        $company->name = $request->name;
        $company->save();

        return redirect()->route('company.');
    }

    public function delete($id)
    {
        $company = Company::find($id);

        $company->delete();

        return redirect()->route('company.');
    }
}
