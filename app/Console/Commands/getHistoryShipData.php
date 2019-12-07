<?php
namespace App\Console\Commands;

use App\HistoryShip;
use Illuminate\Console\Command;

class getHistoryShipData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getHistoryShip:data';

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
    public function __construct ()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle ()
    {
        $historyShip = (new \App\Helpers\CronData)->getReturnMessages();
        foreach ($historyShip as $key => $data) {
            $data = json_decode($data);
            if ($data->ErrorID === 0) {
                foreach ($data->Messages as $message) {
                    $countShip = HistoryShip::where(['history_ids' => $message->ID, 'message_utc' => $message->MessageUTC, 'receive_utc' => $message->ReceiveUTC])->count();
                    if($countShip === 0) {
                        $historyShip = new HistoryShip();
                        $historyShip->history_ids = $message->ID;
                        $historyShip->sin = $message->SIN;
                        $historyShip->region_name = $message->RegionName;
                        $historyShip->receive_utc = $message->ReceiveUTC;
                        $historyShip->message_utc = $message->MessageUTC;
                        $historyShip->payload = json_encode($message->Payload);
                        $historyShip->ota_message_size =$message->OTAMessageSize;
                        $historyShip->ship_id =$message->MobileID;
                        $historyShip->save();

                        echo 'Insert History Ship Id' . $message->ID ."\n";
                    }

                }
            }
        }
    }

}
