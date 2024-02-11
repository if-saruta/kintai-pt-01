<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Company;
use League\Csv\Reader;
use League\Csv\Statement;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::all();
        $companies = Company::all();

        return view('vehicle.index', compact('vehicles', 'companies'));
    }

    public function create()
    {
        $companies = Company::all();

        return view('vehicle.create', compact('companies'));
    }

    public function store(Request $request)
    {

        Vehicle::create([
            'number' => $request->number,
            'company_id' => $request->company,
        ]);

        return redirect()->route('vehicle.');
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $vehicle = Vehicle::find($id);

        if($request->input('action') == 'save'){
            $vehicle->number = $request->number;
            $vehicle->company_id = $request->company;
            $vehicle->save();
        }elseif($request->input('action') == 'delete'){
            $vehicle->delete();
        }

        return redirect()->route('vehicle.');
    }

    public function delete($id)
    {
        try{
            $vehicle = Vehicle::find($id);

            $vehicle->delete();

            return redirect()->route('vehicle.');
        }catch (\Exception $e){
            return redirect()->route('vehicle.')->with('alert', 'シフトに登録されている車両は現在は削除できない仕様にしてあります。ご了承ください。');
        }
    }

    public function csvImport(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $row){
            Vehicle::create([
                'number' => $row['number'],
            ]);
        }

        return redirect()->route('vehicle.');
    }
}
