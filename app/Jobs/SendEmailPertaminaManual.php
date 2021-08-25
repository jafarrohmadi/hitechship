<?php

namespace App\Jobs;

use App\EmailSendPertamina;
use App\HistoryShip;
use App\Mail\PertaminaShippedManual;
use App\Ship;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailPertaminaManual implements
    ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailSendPertamina;
    protected $emailTerminal, $subject,
        $filename,
        $content;

    /**
     * SendEmailPertaminaManual constructor.
     * @param  EmailSendPertamina  $emailSendPertamina
     * @param  array  $emailTerminal
     * @param $subject
     * @param $filename
     * @param $content
     */
    public function __construct(
        EmailSendPertamina $emailSendPertamina,
        array $emailTerminal,
        $subject,
        $filename,
        $content
    ) {
        $this->emailSendPertamina = $emailSendPertamina;
        $this->emailTerminal      = $emailTerminal;
        $this->subject            = $subject;
        $this->filename           = $filename;
        $this->content            = $content;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->emailTerminal as $email) {
            if($email != '') {
                Mail::to($email)->send(new PertaminaShippedManual($this->subject, $this->content, $this->filename));
            }
        }

        $data                        = new EmailSendPertamina();
        $data->ship_id               = $this->emailSendPertamina->ship_id;
        $data->history_ship_id       = $this->emailSendPertamina->history_ship_id;
        $data->last_seen_time        = date('Y-m-d H:i:s');
        $data->last_sent_destination = json_encode($this->emailTerminal);
        $data->last_sent_status      = 'Delivered';
        $data->subject               = $this->subject;
        $data->filename_chr          = $this->filename;
        $data->content               = $this->content;

        // check for failures
        if (Mail::failures()) {
            $data->last_sent_status = 'Failed';
        }
        $data->save();
    }
}
