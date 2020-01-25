<?php

namespace App\Console\Commands;

use App\EmailUser;
use App\HistoryShip;
use App\Jobs\SendEmailPertamina;
use App\Jobs\SendEmailToUserWhoHaveShip;
use App\Setting;
use App\Ship;
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
        $historyShip = (new \App\Helpers\CronData)->getReturnMessages();
        foreach ($historyShip as $key => $data) {
            $data = json_decode($data);
            if ($data->ErrorID === 0) {
                foreach ($data->Messages as $message) {
                    $countShip = HistoryShip::where(['history_ids' => $message->ID, 'message_utc' => $message->MessageUTC, 'receive_utc' => $message->ReceiveUTC])->count();
                    $ship = Ship::where('ship_ids', $message->MobileID)->first();
                    $payload = [];
                    foreach ($message->Payload->Fields as $field) {
                        $field->Name = strtolower($field->Name);
                        if ($field->Name === 'latitude' || $field->Name === 'longitude') {
                            $field->Value = ($field->Value / 6) * 0.0001;
                        }

                        if ($field->Name === 'speed') {
                            $field->Value = ($field->Value) * 0.1;
                        }

                        if ($field->Name === 'heading') {
                            $field->Value = ($field->Value) * 0.1;
                        }

                        $payload[] = [
                            'Name' => $field->Name,
                            'Value' => $field->Value,
                        ];
                    }
                    $message->Payload->Fields = $payload;
                    if ($countShip === 0 && !empty($ship)) {
                        $historyShip = new HistoryShip();
                        $historyShip->history_ids = $message->ID;
                        $historyShip->sin = $message->SIN;
                        $historyShip->min = $message->MIN ?? '';
                        $historyShip->region_name = $message->RegionName;
                        $historyShip->receive_utc = $message->ReceiveUTC;
                        $historyShip->message_utc = $message->MessageUTC;
                        $historyShip->payload = json_encode($message->Payload);
                        $historyShip->ota_message_size = $message->OTAMessageSize;
                        $historyShip->ship_id = $ship->id;
                        $historyShip->save();

                        if ($ship->call_sign && $ship->call_sign !== null && count($ship->shipTerminals) > 0) {
                            foreach ($ship->shipTerminals as $terminals) {
                                if(count($terminals->email) > 0) {
                                    foreach ($terminals->email as $emailTerminal) {
                                        dispatch(new SendEmailPertamina($historyShip, $ship, $emailTerminal));
                                    }
                                }
                            }
                        }
                        $findHistoryShip = HistoryShip::where('ship_id', $ship->id)->count();
                        $setSendEmail = Setting::find(1);
                        if(isset($setSendEmail) && $findHistoryShip % $setSendEmail->simple_report === 0) {
                            $email = EmailUser::join('users', 'email_user.user_id' , '=' , 'users.id')
                                ->join('terminal_user', 'users.id' , '=' , 'terminal_user.user_id' )
                                ->join('terminals', 'terminal_user.terminal_id', '=','terminals.id')
                                ->join('ship_terminal', 'terminals.id', '=','ship_terminal.terminal_id' )
                                ->join('ships', 'ship_terminal.ship_id', '=','ships.id')
                                ->where('ships.id' , $ship->id)->get();

                            foreach ($email as $datas ) {
                                dispatch(new SendEmailToUserWhoHaveShip($historyShip, $ship, $datas->email , $datas->username));
                            }
                        }

                        echo 'Insert History Ship Id ' . $message->ID . "\n";
                    }
                }
            }
        }
    }

}
