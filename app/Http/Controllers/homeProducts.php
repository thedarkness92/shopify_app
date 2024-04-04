<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product as ProductModel;
use Illuminate\Support\Facades\View;



$products = ProductModel::all();         


class homeProducts extends Controller
{
    //
    public function index() {
        $products = ProductModel::all();
        $shopIDs = []; // Initialiser un tableau pour stocker les shop_ids
    
        foreach ($products as $product) {
            $shopIDs[] = $product->shop_id; // Ajouter chaque shop_id au tableau

        }

    
        View::share('products', $products); // Partager les produits avec toutes les vues
        return view('products_list', ['store_id' => $shopIDs]); // Passer le tableau de shop_ids à la vue
    }

    public function show($product_id)
    {
        // Récupérer les détails du produit à partir de l'ID
        $product = ProductModel::find($product_id);

    
        // Vérifier si le produit existe
        if (!$product) {
            abort(404); // Rediriger vers une erreur 404 si le produit n'est pas trouvé
        }
    
        // Passer les détails du produit à la vue
        return view('products_details', ['product' => $product]);
    }
}
