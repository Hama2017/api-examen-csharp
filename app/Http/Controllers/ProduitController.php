<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Produit;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API Documentation",
 *     description="API Documentation for Produits"
 * )
 */
class ProduitController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/produits",
     *     tags={"Produits"},
     *     summary="Get list of produits",
     *     description="Returns list of produits",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Produit"))
     *     ),
     *     @OA\Response(response=500, description="Erreur lors de la récupération des produits")
     * )
     */
    public function index()
    {
        try {
            $produits = Produit::all();
            return response()->json($produits, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération des produits', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/produits",
     *     tags={"Produits"},
     *     summary="Create a new produit",
     *     description="Creates a new produit",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="CodeProduit", type="string"),
     *             @OA\Property(property="DesignationProduit", type="string"),
     *             @OA\Property(property="PU", type="number", format="float"),
     *             @OA\Property(property="QteMin", type="integer"),
     *             @OA\Property(property="QteCri", type="integer"),
     *             @OA\Property(property="CodeCategorie", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Produit")
     *     ),
     *     @OA\Response(response=400, description="Invalid input"),
     *     @OA\Response(response=500, description="Erreur lors de la création du produit")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/produits/{id}",
     *     tags={"Produits"},
     *     summary="Get a produit by ID",
     *     description="Returns a single produit",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Produit")
     *     ),
     *     @OA\Response(response=404, description="Produit non trouvé")
     * )
     */
    public function show($id)
    {
        try {
            $produit = Produit::findOrFail($id);
            return response()->json($produit, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Produit non trouvé', 'error' => $e->getMessage()], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/produits/{id}",
     *     tags={"Produits"},
     *     summary="Update an existing produit",
     *     description="Updates a produit",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="CodeProduit", type="string"),
     *             @OA\Property(property="DesignationProduit", type="string"),
     *             @OA\Property(property="PU", type="number", format="float"),
     *             @OA\Property(property="QteMin", type="integer"),
     *             @OA\Property(property="QteCri", type="integer"),
     *             @OA\Property(property="CodeCategorie", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Produit")
     *     ),
     *     @OA\Response(response=400, description="Invalid input"),
     *     @OA\Response(response=404, description="Produit non trouvé"),
     *     @OA\Response(response=500, description="Erreur lors de la mise à jour du produit")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/produits/{id}",
     *     tags={"Produits"},
     *     summary="Delete a produit",
     *     description="Deletes a produit",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Produit supprimé avec succès"),
     *     @OA\Response(response=404, description="Produit non trouvé"),
     *     @OA\Response(response=500, description="Erreur lors de la suppression du produit")
     * )
     */
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
