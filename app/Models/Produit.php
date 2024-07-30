<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Produit",
 *     type="object",
 *     title="Produit",
 *     required={"CodeProduit", "DesignationProduit", "CodeCategorie"},
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="CodeProduit", type="string", example="P001"),
 *     @OA\Property(property="DesignationProduit", type="string", example="Produit 1"),
 *     @OA\Property(property="PU", type="number", format="float", example=99.99),
 *     @OA\Property(property="QteMin", type="integer", example=10),
 *     @OA\Property(property="QteCri", type="integer", example=5),
 *     @OA\Property(property="CodeCategorie", type="string", example="C001")
 * )
 */
class Produit extends Model
{
    use HasFactory;
    protected $guarded = [];
}
