<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Traite le formulaire de contact.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request)
    {
        // Validation des données du formulaire
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Logique d'envoi d'email (à implémenter selon votre configuration)
        // Vous pouvez utiliser Laravel Mail ou PHPMailer comme dans PasswordResetController
        
        // Pour l'instant, on simule juste un succès
        // Dans un environnement de production, vous devriez envoyer un vrai e-mail ici
        
        // Enregistrement du message dans la base de données (optionnel)
        // Contact::create($request->all());
        
        // Redirection avec message de succès
        return redirect()->back()
            ->with('success', 'Votre message a été envoyé avec succès. Notre équipe vous répondra dans les plus brefs délais.');
    }
} 