<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
// use Illuminate\Mail\Mailables\Content;
// use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Mitra;

class MitraVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $mitra;
    public $password;

    public function __construct($mitra, $password)
    {
        $this->mitra = $mitra;
        $this->password = $password;
    }

    /** 
     * Build the Message.
     * 
     * @return $this
     */

    public function build() {
        return $this->from('dbandengkrobokan@gmail.com', 'DBandeng')
                    ->subject('Verifikasi Email & Generate Password')
                    ->view('mails.name');
    }

    // /**
    //  * Get the message envelope.
    //  *
    //  * @return \Illuminate\Mail\Mailables\Envelope
    //  */
    // public function envelope()
    // {
    //     return new Envelope(
    //         from: 'tannicholas54@gmail.com',
    //         subject: 'Mitra Verification',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  *
    //  * @return \Illuminate\Mail\Mailables\Content
    //  */
    // public function content()
    // {
    //     return new Content(
    //         view: 'mails.name',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array
    //  */
    // public function attachments()
    // {
    //     return [];
    // }
}
