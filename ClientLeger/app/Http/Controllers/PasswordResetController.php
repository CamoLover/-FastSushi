<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PasswordResetController extends Controller
{
    public function sendResetLink(Request $request)
    {
        // Validation de l'email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $email = $request->email;
        $token = Str::random(60);

        // Supprimer les anciens tokens
        PasswordReset::where('email', $email)->delete();

        // Enregistrer le nouveau token
        PasswordReset::create([
            'email' => $email,
            'token' => $token
        ]);

        // Construire le lien de réinitialisation
        $resetLink = url("/reset-password?token={$token}");

        // Envoyer l'email avec PHPMailer
        if ($this->sendEmail($email, $resetLink)) {
            return response()->json(['message' => 'Email de réinitialisation envoyé !']);
        } else {
            return response()->json(['error' => 'Erreur lors de l\'envoi de l\'email'], 500);
        }
    }

    private function sendEmail($email, $resetLink)
    {
        $mail = new PHPMailer(true);

        try {
            // Configurer PHPMailer
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST', 'smtp.example.com');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME', 'your-email@example.com');
            $mail->Password = env('MAIL_PASSWORD', 'PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = env('MAIL_PORT', 587);

            // Destinataire
            $mail->setFrom(env('MAIL_FROM_ADDRESS', 'no-reply@example.com'), 'FastSushi Support');
            $mail->addAddress($email);

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = 'Réinitialisation de votre mot de passe';
            $mail->Body = "<p>Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p>
                           <p><a href='{$resetLink}'>Réinitialiser mon mot de passe</a></p>";

            // Envoyer l'email
            return $mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}
