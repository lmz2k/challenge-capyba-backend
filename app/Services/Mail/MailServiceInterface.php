<?php


namespace App\Services\Mail;


interface MailServiceInterface
{
    public function sendConfirmationCode($code, $email, $name);
}
