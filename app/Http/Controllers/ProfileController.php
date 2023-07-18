<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Image ;
class ProfileController extends Controller
{
    public function upload(Request $request)
    {
        // Valider le formulaire d'upload (optionnel)
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Vérifier si une photo de profil a été téléchargée
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');

            // Compresser l'image avant de la sauvegarder
            //$compressedImage = Image::make($photo)->encode('jpg', 75);



            // Générer un nom unique pour la photo de profil basé sur le nom d'utilisateur et le timestamp
            $photoName = Auth::user()->name . '_' . time() . '.' . $photo->getClientOriginalExtension();



            // Déplacer la photo de profil téléchargée vers le dossier de destination
            $photo->move(public_path('profile_photos'), $photoName);

            // Déplacer la photo de profil compressée vers le dossier de destination
           // $compressedImage->save(public_path('profile_photos') . '/' . $photoName);

            // Mettre à jour le chemin de la photo de profil dans la base de données pour l'utilisateur connecté
            $user = Auth::user();
            $user->photo_de_profil = 'profile_photos/' . $photoName;
            $user->save();

            return "La photo de profil a été téléchargée avec succès.";
        }

        // Si aucune photo de profil n'a été téléchargée
        return "Aucune photo de profil n'a été sélectionnée.";
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
