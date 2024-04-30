<?php

namespace App\Http\Controllers;

use App\Models\InfoManagement;

use Illuminate\Http\Request;

class InfoManagementController extends Controller
{
    public function index(){

        $info = InfoManagement::first();

        return view('info-management.index', compact('info'));
    }

    public function edit()
    {
        $info = InfoManagement::first();

        return view('info-management.edit', compact('info'));
    }

    public function updateOrCreate(Request $request)
    {
        // リクエストデータからカンマを除去する
        $cleanedData = $this->removeCommas($request->all());

        $info = InfoManagement::first();

        if ($info === null) {
            // データが存在しない場合は新規作成
            InfoManagement::create($cleanedData);
        } else {
            // データが存在する場合は更新
            $info->update($cleanedData);
        }

        return redirect()->route('info-management.');
    }

    private function removeCommas(array $data)
    {
        return array_map(function ($value) {
            return is_string($value) ? str_replace(',', '', $value) : $value;
        }, $data);
    }
}
