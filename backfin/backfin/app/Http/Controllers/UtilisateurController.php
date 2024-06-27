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


    public function addAccount(Request $request)
    {
    // Validation des données de la requête
    $validatedData = $request->validate([
        'nom' => 'required|string',
        'prenom' => 'required|string',
        'email' => 'required|email|unique:utilisateurs',
        'password' => 'required|string',
        'profil' => 'required|in:ingénieur,directeur,manager',
        'ImageUserPath' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validation pour l'image
    ]);

    // Vérification si une image a été téléchargée
    if ($request->hasFile('ImageUserPath')) {
        // Récupérer le fichier image téléchargé
        $image = $request->file('ImageUserPath');

        // Nom du fichier avec un timestamp pour éviter les doublons
        $fileName = time() . '_' . $image->getClientOriginalName();

        // Stocker l'image dans le dossier "uploads" du stockage Laravel
        $image->storeAs('uploads', $fileName);

        // Création d'un nouvel utilisateur avec l'image
        $utilisateur = Utilisateur::create([
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'profil' => $validatedData['profil'],
            'ImageUserPath' => $fileName, // Stocker le nom du fichier dans la base de données
        ]);
    } else {
        // Création d'un nouvel utilisateur sans image
        $utilisateur = Utilisateur::create([
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'profil' => $validatedData['profil'],
        ]);
    }

    // Réponse JSON pour confirmer la création de l'utilisateur
    return response()->json(['message' => 'Utilisateur ajouté avec succès'], 201);
    }


public function getImage($id)
{
    try {
        // Trouver l'utilisateur par son identifiant
        $utilisateur = Utilisateur::findOrFail($id);

        // Vérifier si l'utilisateur a une image
        if ($utilisateur->ImageUserPath) {
            // Retourner l'URL de l'image
            return response()->json(['image_url' => Storage::url($utilisateur->ImageUserPath)], 200);
        } else {
            // Si l'utilisateur n'a pas d'image, retourner un message approprié
            return response()->json(['message' => 'L\'utilisateur n\'a pas d\'image associée'], 404);
        }
    } catch (\Exception $e) {
        // Gestion des erreurs
        return response()->json(['message' => 'Erreur lors de la récupération de l\'image de l\'utilisateur', 'error' => $e->getMessage()], 500);
    }
}


public function edition(Request $request, $id)
{
    try {
        // Trouver l'utilisateur par son identifiant
        $utilisateur = Utilisateur::findOrFail($id);

        // Validation des données de la requête
        $validatedData = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:utilisateurs,email,' . $id,
            'password' => 'sometimes|required|string|min:8', // Ajout de la longueur minimale du mot de passe
            'profil' => 'required|in:ingénieur,directeur,manager',
            'ImageUserPath' => 'sometimes|string', // Modification du type de validation pour l'URL de l'image
        ]);

        // Mise à jour des champs de l'utilisateur
        $utilisateur->nom = $validatedData['nom'];
        $utilisateur->prenom = $validatedData['prenom'];
        $utilisateur->email = $validatedData['email'];
        $utilisateur->profil = $validatedData['profil'];

        // Si un nouveau mot de passe est fourni, le hasher
        if (isset($validatedData['password'])) {
            $utilisateur->password = bcrypt($validatedData['password']);
        }

        // Si une nouvelle image est fournie, récupérer son URL
        if (isset($validatedData['ImageUserPath'])) {
            // Utiliser l'API pour récupérer l'URL de l'image en fonction de l'ID de l'utilisateur
            $imageResponse = Http::get(url('/api/utilisateurs/' . $id . '/image'));
            $imageData = $imageResponse->json();
            
            // Vérifier si la requête a réussi et l'URL de l'image est disponible
            if ($imageResponse->successful() && isset($imageData['image_url'])) {
                $utilisateur->ImageUserPath = $imageData['image_url'];
            } else {
                // Si la récupération de l'image a échoué, retourner un message d'erreur
                return response()->json(['message' => 'Erreur lors de la récupération de l\'URL de l\'image de l\'utilisateur'], 500);
            }
        }

        // Enregistrer les modifications de l'utilisateur
        $utilisateur->save();

        // Réponse JSON pour confirmer la mise à jour de l'utilisateur
        return response()->json(['message' => 'Utilisateur mis à jour avec succès'], 200);
    } catch (\Exception $e) {
        // Gestion des erreurs
        return response()->json(['message' => 'Erreur lors de la mise à jour de l\'utilisateur', 'error' => $e->getMessage()], 500);
    }
}





















}
