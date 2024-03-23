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
            'place_name' => $request->place_name,
            'class_number' => $request->class_number,
            'hiragana' => $request->hiragana,
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
            $vehicle->place_name = $request->place_name;
            $vehicle->class_number = $request->class_number;
            $vehicle->hiragana = $request->hiragana;
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
                'company_id' => $row['company_id']
            ]);
        }

        return redirect()->route('vehicle.');
    }
}
