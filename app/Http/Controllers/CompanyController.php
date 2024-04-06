<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

use League\Csv\Reader;
use League\Csv\Statement;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();


        return view('company.index', compact('companies'));
    }

    public function store(Request $request)
    {
        Company::create([
            'register_number' => $request->register_number,
            'name' => $request->name,
            'post_code' => $request->post_code,
            'address' => $request->address,
            'building_name' => $request->building_name,
            'phone' => $request->phone,
            'fax' => $request->fax,
            'bank_name' => $request->bank_name,
            'account_holder_name' => $request->account_holder_name,
        ]);

        return redirect()->route('company.');
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $company = Company::find($id);

        if($request->input('action') == 'save'){
            $company->register_number = $request->register_number;
            $company->name = $request->name;
            $company->post_code = $request->post_code;
            $company->address = $request->address;
            $company->building_name = $request->building_name;
            $company->phone = $request->phone;
            $company->fax = $request->fax;
            $company->bank_name = $request->bank_name;
            $company->account_holder_name = $request->account_holder_name;
            $company->save();
        } elseif($request->input('action') == 'delete'){
            $company->delete();
        }

        return redirect()->route('company.');
    }

    public function delete($id)
    {
        $company = Company::find($id);

        $company->delete();

        return redirect()->route('company.');
    }

    public function csvImport(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $row){
            $employee = Company::create([
                'register_number' => $row['register_number'],
                'name' => $row['name'],
                'post_code' => $row['post_code'],
                'address' => $row['address'],
                'phone' => $row['phone'],
                'fax' => $row['fax'],
                'bank_name' => $row['bank_name'],
                'account_holder_name' => $row['account_holder_name'],
            ]);
        }

        return redirect()->route('company.');
    }
}
