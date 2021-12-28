<?php

namespace App\Entity;


class EmailSenderService
{
    public function sendEmail(string $email, string $subject, string $message): void
    {
        // Send email
        throw new \Exception('Il ne vous reste que 2 items à ajouter');
    }
}
