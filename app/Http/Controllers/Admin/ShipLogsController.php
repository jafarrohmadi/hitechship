<?php

namespace App\Http\Controllers\Admin;

use App\EmailSendPertamina;
use App\Http\Controllers\Controller;

use App\Jobs\SendEmailPertaminaManual;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;

class ShipLogsController extends
    Controller
{
    public function index($id)
    {
        abort_if(Gate::denies('ship_logs'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ships = EmailSendPertamina::where('ship_id', $id)->get();

        return view('admin.ships.logs', compact('ships'));
    }

    public function sendManual(Request $request)
    {
        $email = EmailSendPertamina::find($request->id);

        $emailTerminal = explode(';', $request->last_seen_destination);

        dispatch(new SendEmailPertaminaManual($email, array_filter($emailTerminal), $request->subject, $request->filename_chr,
            $request->content));
        Artisan::command('queue:work --stop-when-empty', function ($user) {
            $this->info("Sending email to: {$user}!");
        });
        sleep(20);
<<<<<<< Updated upstream
        return $email;
=======
        return redirect()->back();
>>>>>>> Stashed changes

    }


}
