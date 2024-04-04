<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')
    <!-- Ajout de Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
</head>
<body>  
    @include('layouts.header')

    <div class="container">
        <div class="row">
            @foreach ($products as $product)
            @php 
            $shope_id = $product->store_id;
            $product_id = $product->id;
            $variants = json_decode($product->variants, true);
    
            // Récupérer les valeurs price, sku et compare_at_price
            $price = $variants[0]['price'];
            $sku = $variants[0]['sku'];
            $compare_at_price = $variants[0]['compare_at_price'];
            @endphp 
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{$product->image}}" class="card-img-top" alt="Product Image">

                    <div class="card-body">
                        <h4 class="card-title">{{$product->title}}</h4>
                        <h6 class="card-title">Vendor: {{$product->vendor}}</h6>
                        <h6 class="card-title">Type: {{$product->product_type}}</h6>
                        <h6 class="card-title">Tag: {{$product->tags}}</h6>
                        <p class="card-text">{{$product->body_html}}</p>
                        <p class="card-text">Price: {{$price}}, SKU: {{$sku}}, Compare at Price: {{$compare_at_price}}</p>
                        <button class="btn btn-primary add-to-cart" data-product-id="{{$product->id}}">Add to Cart</button>
                        <a href="{{ route('product.details', ['id' =>$shope_id, 'product_id' => $product_id]) }}" class="btn btn-primary">Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @include('layouts.footer')

    <div id="cart-icon" class="position-fixed bottom-0 end-0 p-3">
        <a href="/api/cart_details" class="text-blue text-decoration-none">
            <i class="fas fa-shopping-cart fa-2x"></i>
            <!-- Vous pouvez également ajouter un badge pour afficher le nombre total d'articles dans le panier -->
            <span class="badge bg-primary"></span>
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Sélectionne tous les boutons "Add to Cart"
            const addToCartButtons = document.querySelectorAll('.add-to-cart');

            // Boucle à travers chaque bouton
            addToCartButtons.forEach(button => {
                // Ajoute un écouteur d'événements au clic
                button.addEventListener('click', function() {
                    // Récupère l'ID du produit à partir de l'attribut data-product-id
                    const productId = this.getAttribute('data-product-id');

                    // Envoie une requête AJAX pour ajouter le produit au panier
                    addToCart(productId);
                });
            });

    // Fonction pour ajouter un produit au panier
            function addToCart(productId) {
                // Vous devez implémenter cette fonction pour envoyer une requête AJAX
                // au serveur pour ajouter le produit au panier
                // Vous pouvez utiliser Axios, Fetch API ou jQuery pour envoyer la requête AJAX
                // Exemple d'utilisation de Fetch API
                fetch('/add-to-cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Ajoutez le jeton CSRF si vous l'utilisez
                    },
                    body: JSON.stringify({ productId: productId })
                })
                .then(response => {
                    // Gérer la réponse de la requête
                    if (response.ok) {
                        // Mettre à jour l'interface utilisateur pour refléter l'ajout du produit au panier
                        updateCartIcon();
                        // Vous pouvez également afficher un message de succès à l'utilisateur
                    } else {
                        // Gérer les erreurs éventuelles
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }

    // Fonction pour mettre à jour l'icône du panier
            function updateCartIcon() {
                // Récupérer l'élément HTML de l'icône du panier
                const cartIcon = document.getElementById('cart-icon');

                // Vous devez implémenter la logique pour récupérer le nombre total de produits dans le panier depuis le serveur
                // Par exemple, vous pouvez envoyer une requête AJAX au serveur pour récupérer les informations du panier
                fetch('/get-cart-total')
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            throw new Error('Failed to fetch cart total');
                        }
                    })
                    .then(data => {
                        // Mettre à jour l'icône du panier avec le nombre total de produits
                        cartIcon.innerText = data;
                    });
            }
        });
    </script>
</body>
</html>
