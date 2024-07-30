<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use Illuminate\Support\Facades\Validator;

class ProduitController extends Controller
{
    public function index()
    {
        try {
            $produits = Produit::all();
            return response()->json($produits, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération des produits', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'CodeProduit' => 'required|string|max:255',
            'DesignationProduit' => 'required|string|max:255',
            'PU' => 'nullable|numeric',
            'QteMin' => 'nullable|integer',
            'QteCri' => 'nullable|integer',
            'CodeCategorie' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        try {
            $produit = new Produit;
            $produit->CodeProduit = $request->CodeProduit;
            $produit->DesignationProduit = $request->DesignationProduit;
            $produit->PU = $request->PU;
            $produit->QteMin = $request->QteMin;
            $produit->QteCri = $request->QteCri;
            $produit->CodeCategorie = $request->CodeCategorie;
            
            // Sauvegarde du produit en excluant les horodatages
            $produit->timestamps = false;
            $produit->save();
    
            return response()->json($produit, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la création du produit', 'error' => $e->getMessage()], 500);
        }
    }
    

    public function show($id)
    {
        try {
            $produit = Produit::findOrFail($id);
            return response()->json($produit, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Produit non trouvé', 'error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'CodeProduit' => 'required|string|max:255',
            'DesignationProduit' => 'required|string|max:255',
            'PU' => 'nullable|numeric',
            'QteMin' => 'nullable|integer',
            'QteCri' => 'nullable|integer',
            'CodeCategorie' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $produit = Produit::findOrFail($id);
            $produit->timestamps = false;
            $produit->update($request->all());
            return response()->json($produit, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la mise à jour du produit', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $produit = Produit::findOrFail($id);
            $produit->delete();
            return response()->json(['message' => 'Produit supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la suppression du produit', 'error' => $e->getMessage()], 500);
        }
    }
}
