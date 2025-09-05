<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Contracts\WithdrawEmailContract;
use App\Domain\ValueObjects\WithdrawEmail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use function Hyperf\Config\config;

class WithdrawEmailRepository implements WithdrawEmailContract
{
    public function send(WithdrawEmail $message): void
    {
        $mail = new PHPMailer(true);
        try {
            $fromConfig = config('mailer.from') ?? [];
            $mailerConfig = config('mailer.mailers.smtp.options') ?? [];
            $name  = $fromConfig['name'] ?? 'Tecnofit';
            $address = $fromConfig['address'] ?? 'no-reply@tecnofit.local';
            $mail->isSMTP();
            $mail->Host = $mailerConfig['host'] ?? 'smtp.exemplo.com';
            $mail->SMTPAuth = $mailerConfig['auth'] ?? false;
            $mail->SMTPSecure = $mailerConfig['encryption'] ?? null;
            $mail->Username = $mailerConfig['username'] ?? 'usuario@smtp.com';
            $mail->Password = $mailerConfig['password'] ?? 'senha';
            $mail->Port = $mailerConfig['port'] ?? 587;
            $mail->setFrom($address, $name);
            $mail->addAddress($message->to);
            $mail->Subject = $message->subject;
            $mail->Body    = $message->body;
            $mail->send();
        } catch (Exception $e) {
            throw new \RuntimeException("Erro ao enviar e-mail: {$mail->ErrorInfo}");
        }
    }
}
