<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vehicle;
use App\Mail\InspectionNotice;
use Illuminate\Support\Facades\Mail;

class SendInspectionNotices extends Command
{
    // コマンドを設定しオプションを追加
    protected $signature = 'send:inspection-notices {--one-month : Send notices for inspections due in one month}
                                                      {--one-week : Send notices for inspections due in one week}';
    // 概要
    protected $description = 'Send an email notification for vehicle inspections due soon.';

    public function handle()
    {
        if ($this->option('one-month')) {
            $dateCutoff = now()->addMonth();
            $this->sendNotices($dateCutoff, '1ヶ月');
        }

        if ($this->option('one-week')) {
            $dateCutoff = now()->addWeek();
            $this->sendNotices($dateCutoff, '１週間');
        }
    }

    // メールを送信処理
    protected function sendNotices($dateCutoff, $timeFrame)
    {
        $vehicles = Vehicle::whereDate('inspection_expiration_date', $dateCutoff)->get();

        if($vehicles->isNotEmpty()){
            Mail::to('h.saruta@if-aiefu.com')->send(new InspectionNotice($vehicles, $timeFrame));
        }

        // Mail::to('example@example.com')->send(new InspectionNotice());

        // foreach ($vehicles as $vehicle) {
        //     // if ($vehicle->owner_email) {
        //     //     Mail::to($vehicle->owner_email)->send(new InspectionNotice($vehicle, $timeFrame));
        //     // }
        // }
        $this->info('Inspection notices sent for ' . $timeFrame . ' cutoff: ' . $dateCutoff);
    }
}

