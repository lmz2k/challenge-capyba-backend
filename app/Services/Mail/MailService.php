<?php


namespace App\Services\Mail;


use Illuminate\Support\Facades\Mail;

class MailService implements MailServiceInterface
{
    /**
     * @param $code
     * @param $email
     * @param $name
     */
    public function sendConfirmationCode($code, $email, $name)
    {
        Mail::send(
            'emails.code',
            array('code' => $code),
            function ($message) use ($email, $name) {
                $message->to($email, $name)
                    ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
                    ->subject('Capyba Confirmation Code');
            }
        );
    }
}
