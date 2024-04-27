<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importez la classe Auth

class UtilisateurController extends Controller
{
    public function inscription(Request $request)
    {
        // Validation des données de la requête
        $validatedData = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:utilisateurs',
            'password' => 'required|string',
            'profil' => 'required|in:ingénieur,directeur,manager',
        ]);

        // Création d'un nouvel utilisateur
        $utilisateur = Utilisateur::create([
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'profil' => $validatedData['profil'],
        ]);

        // Réponse JSON pour confirmer la création de l'utilisateur
        return response()->json(['message' => 'Utilisateur ajouté avec succès'], 201);
    }
    public function connexion(Request $request)
    {
        // Récupérer les données de la requête
        $credentials = $request->only('email', 'password');

        // Vérifier si l'utilisateur existe avec les identifiants fournis
        $utilisateur = Utilisateur::where('email', $credentials['email'])->first();

        // Vérifier si l'utilisateur existe et si le mot de passe correspond
        if ($utilisateur && password_verify($credentials['password'], $utilisateur->password)) {
            // Si les identifiants sont corrects, retournez une réponse JSON avec un message de succès
            return response()->json(['message' => 'Connexion réussie'], 200);
        } else {
            // Si les identifiants sont incorrects, retournez une réponse JSON avec un message d'erreur
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }
    }
}
