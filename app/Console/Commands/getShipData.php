<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Ship;

class getShipData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getShip:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To Get All Data Ship';

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
        $shipData = (new \App\Helpers\CronData)->getMobilesPaged();
        foreach ($shipData as $key => $data) {
            $data = json_decode($data);
            if ($data->ErrorID === 0) {
                foreach ($data->Mobiles as $mobile) {
                    $countShip = Ship::where(['ship_ids' => $mobile->ID, 'last_registration_utc' => $mobile->LastRegistrationUTC])->count();
                    if ($countShip === 0) {
                        if (Ship::where('ship_ids', $mobile->ID)->count() > 0) {
                            $ship = Ship::where('ship_ids', $mobile->ID)->first();
                        }
                        else {
                            $ship = new Ship();
                        }
                        $ship->ship_ids              = $mobile->ID ?? '';
                        $ship->region_name           = $mobile->RegionName ?? '';
                        $ship->last_registration_utc = $mobile->LastRegistrationUTC ;
                        if (isset($mobile->Description)) {
                            $explodeDescription = explode('-', $mobile->Description);
                            $ship->name         = trim($explodeDescription[0]);
                            if (isset($explodeDescription[1])) {
                                $ship->owner = trim($explodeDescription[1]);
                            }
                            else {
                                $ship->owner = '';
                            }
                        }
                        $ship->save();

                        echo 'Insert Ship Id ' . $mobile->ID ."\n";
                    }
                }
            }
        }
    }

}
