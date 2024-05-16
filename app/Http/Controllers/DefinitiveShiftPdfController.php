<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DefinitiveShiftPdfController extends Controller
{
    public function index()
    {
        // 保存されているディレクトリを取得
        $directories = Storage::disk('public')->directories('shift-calendar');

        // 年を抽出
        $years = array_map(function ($dir) {
            return basename($dir);
        }, $directories);

        // 年を降順に並び替え
        rsort($years);

        return view('definitive-shift-pdf.index', compact('years'));
    }

    public function listMonth($year)
    {
        // 年に対する保存されている月のディレクトリを取得
        $directories = Storage::disk('public')->directories("shift-calendar/{$year}");

        // 月を抽出
        $months = array_map(function ($dir) {
            return basename($dir);
        }, $directories);

        return view('definitive-shift-pdf.list-month', compact('year', 'months'));
    }

    public function listPdf($year, $month)
    {
        // 月に対するpdfのディレクトリを取得
        $files = Storage::disk('public')->files("shift-calendar/{$year}/{$month}");

        // PDFファイル名を抽出
        $pdfs = array_map(function ($file) {
            $basename = basename($file);
            // 年月部分を削除
            $displayName = preg_replace('/^\d{4}年\d{1,2}月/', '', $basename);
            return [
                'full_name' => $basename,
                'display_name' => $displayName,
            ];
        }, $files);

        return view('definitive-shift-pdf.list-pdf', compact('year', 'month', 'pdfs'));
    }

    public function pdfDownload($year, $month, $fileName)
    {
        $path = "shift-calendar/{$year}/{$month}/{$fileName}";

        return response()->download(storage_path("app/public/{$path}"));
    }
}
