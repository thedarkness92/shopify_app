<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
class CartController extends Controller{
    public function index() {
        // Récupérer les IDs des produits depuis la session
        $cart = session()->get('cart_details', []);
    
        // Initialiser un tableau pour stocker les produits du panier
        $productsInCart = [];
    
        // Parcourir les IDs des produits et récupérer les informations de chaque produit depuis la base de données
        foreach ($cart as $productId) {
            // Récupérer le produit à partir de la base de données
            $product = Product::find($productId);
    
            // Vérifier si le produit a été trouvé
            if ($product) {
                // Ajouter le produit au tableau des produits du panier
                $productsInCart[] = $product;
            }
        }
    
        // Passer les données à la vue et afficher la page du panier
        return view('cart_details', ['productsInCart' => $productsInCart]);
    }
    

        public function addToCart(Request $request){

                $productId = $request->input('productId'); // Récupérer l'ID du produit depuis la requête

                // Logique pour ajouter le produit au panier
                // Vous pouvez utiliser la session ou une base de données pour stocker les produits du panier

                // Exemple basique : Ajouter l'ID du produit à la session du panier
                $cart = session()->get('cart_details', []); // Récupérer le panier depuis la session, initialisé comme un tableau vide si non existant
                $cart[] = $productId; // Ajouter l'ID du produit au tableau du panier
                session()->put('cart_details', $cart); // Mettre à jour le panier dans la session

                // Vous pouvez également effectuer d'autres opérations telles que la gestion des quantités, la validation des produits, etc.

                return redirect()->route('cart_details'); // Rediriger l'utilisateur avec un message de succès
            }

            public function show()
            {
                // Récupérer les IDs des produits depuis la session
                $cart = session()->get('cart_details', []);
            
                // Initialiser un tableau pour stocker les produits du panier
                $productsInCart = [];
            
                // Parcourir les IDs des produits et récupérer les informations de chaque produit depuis la base de données
                foreach ($cart as $productId) {
                    // Récupérer le produit à partir de la base de données
                    $product = Product::find($productId);
            
                    // Ajouter le produit au tableau des produits du panier
                    $productsInCart[] = $product;
                }
            
                // Passer les données à la vue et afficher la page du panier
                return view('cart_details.show', ['productsInCart' => $productsInCart]);
            }

            public function getCartTotal(){
                    // Récupérer les IDs des produits depuis la session
                    $cart = session()->get('cart_details', []);

                    // Initialiser le total du panier
                    $cartTotal = 0;

                    // Parcourir les IDs des produits et calculer le total du panier
                    foreach ($cart as $productId) {
                        // Récupérer le produit à partir de la base de données
                        $product = Product::find($productId);

                        // Ajouter le prix du produit au total du panier
                        $cartTotal += $product->price;
                    }

                    // Retourner le total du panier au format JSON
                    return response()->json(['total' => $cartTotal]);
                }

            
}

