<?php


namespace App\Services\Mail;


use Illuminate\Support\Facades\Mail;

class MailService implements MailServiceInterface
{

    public function sendConfirmationCode($code, $email, $name)
    {
        try {
            Mail::send(
                'emails.code',
                array('code' => $code),
                function ($message) use ($email, $name) {
                    $message->to($email, $name)
                        ->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'))
                        ->subject('Capyba Confirmation Code');
                }
            );
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
