<?php

namespace App\Console\Commands;

use App\HistoryShip;
use Illuminate\Console\Command;
use Carbon\Carbon;

class fixMoving extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "fix:moving";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $histories = HistoryShip::where('created_at', '>=', Carbon::now()->subDays(2)->toDateTimeString())->get();
//$histories = HistoryShip::where('history_ids', 5817968769)->get();
        foreach ($histories as $history) {
            $historySave = HistoryShip::find($history->id);
            $payload     = [];
            $newPayload  = [];
            foreach (json_decode($history->payload) as $key => $payloas) {
                if ($key == 'Fields') {
                    if($payloas != 'propertyValues'){
                    foreach ($payloas as $fields) {
                        if ($fields->Name == 'Latitude') {
                            if ($fields->Value < -100 || $fields->Value > 100) {
                                $fields->Value = ($fields->Value / 6) * 0.0001;
                            }
                        }

                        if ($fields->Name == 'Longitude') {
                            if ($fields->Value > 1000) {
                                $fields->Value = ($fields->Value / 6) * 0.0001;
                            }
                        }
                        if ($fields->Name === 'Speed') {
                            $fields->Value = ($fields->Value) * 0.1;
                        }

                        if ($fields->Name === 'Heading') {
                            $fields->Value = ($fields->Value) * 0.1;
                        }

                        $payload[] = [
                            'Name'  => $fields->Name,
                            'Value' => $fields->Value ?? '',
                        ];

                    }
                    echo "fix moving $history->id \n";
                    $newPayload[$key] = $payload;
                    }else{
                        $newPayload[$key] = $payloas;
                    }
                } else {
                    $newPayload[$key] = $payloas;
                }
            }
            $historySave->payload = $newPayload;
            $historySave->save();
        }
    }
}
