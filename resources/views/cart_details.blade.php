<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')
    <!-- Ajout de Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Ajoutez d'autres balises meta, title, etc. si nécessaire -->
</head>
<body>  
    @include('layouts.header')

    <section class="h-100 h-custom" style="background-color: #eee;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-lg-7">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div>
                                            <p class="mb-1">Shopping cart</p>
                                            <!-- Ajoutez des informations sur le panier ici -->
                                        </div>
                                        <div>
                                            <p class="mb-0"><span class="text-muted">Sort by:</span> <a href="#!"
                                                class="text-body">price <i class="fas fa-angle-down mt-1"></i></a></p>
                                        </div>
                                    </div>
                                    @foreach ($productsInCart as $product)
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex flex-row align-items-center">
                                                        <div>
                                                            <img src="{{$product->image}}"
                                                                class="img-fluid rounded-3" alt="Shopping item"
                                                                style="width: 65px;">
                                                        </div>
                                                        <div class="ms-3">
                                                            <h5>{{$product->title}}</h5>
                                                            <p class="small mb-0">{{$product->tag}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-row align-items-center">
                                                        <div style="width: 60px;">
                                                            <!-- Champ d'entrée pour la quantité avec des boutons pour incrémenter/décrémenter -->
                                                            <input type="number" class="form-control quantity-input" value="1" min="1">
                                                        </div>
                                                        <div style="width: 80px;">
                                                            <h5 class="mb-0">${{$product->variants}}</h5>
                                                        </div>
                                                        <a href="#!" style="color: #cecece;"><i
                                                                class="fas fa-trash-alt"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                </div>
                                <div class="col-lg-5">
                                        <form action="/checkout" method="POST">
                                            @csrf
                                            <div class="card bg-primary text-white rounded-3">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <h5 class="mb-0">Card details</h5>
                                                        <img src="card-logo.png" class="img-fluid rounded-3" style="width: 45px;" alt="Card Logo">
                                                    </div>

                                                    <p class="small mb-2">Card type</p>
                                                    <a href="#!" type="submit" class="text-white"><i class="fab fa-cc-mastercard fa-2x me-2"></i></a>
                                                    <a href="#!" type="submit" class="text-white"><i class="fab fa-cc-visa fa-2x me-2"></i></a>
                                                    <!-- Ajoutez d'autres types de cartes si nécessaire -->

                                                    <div class="mt-4">
                                                        <label for="typeName" class="form-label">Cardholder's Name</label>
                                                        <input type="text" id="typeName" class="form-control form-control-lg" name="cardholder_name" required>

                                                        <label for="typeText" class="form-label">Card Number</label>
                                                        <input type="text" id="typeText" class="form-control form-control-lg" name="card_number" required minlength="16" maxlength="16">

                                                        <div class="row mt-4">
                                                            <div class="col-md-6">
                                                                <label for="typeExp" class="form-label">Expiration</label>
                                                                <input type="text" id="typeExp" class="form-control form-control-lg" name="expiration_date" required placeholder="MM/YYYY" minlength="7" maxlength="7">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="typeCvv" class="form-label">CVV</label>
                                                                <input type="password" id="typeCvv" class="form-control form-control-lg" name="cvv" required minlength="3" maxlength="3">
                                                            </div>
                                                        </div>

                                                        <hr class="my-4">

                                                        <div class="d-flex justify-content-between">
                                                            <p class="mb-2">Total Amount</p>
                                                            <!-- Affichez le montant total de la commande ici -->
                                                        </div>

                                                        <button type="submit" class="btn btn-info btn-block btn-lg mt-3">Checkout <i class="fas fa-long-arrow-alt-right ms-2"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')

    <!-- JavaScript -->
    <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Sélectionner tous les champs d'entrée de quantité dans le panier
                const quantityInputs = document.querySelectorAll('.quantity-input');

                // Ajouter un écouteur d'événements à chaque champ d'entrée
                quantityInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        // S'assurer que la quantité est un nombre entier positif
                        const quantity = parseInt(this.value);
                        if (isNaN(quantity) || quantity <= 0) {
                            this.value = 1; // Défaut à 1 si la quantité est invalide
                        } else {
                            // Mettre à jour le panier lorsque la quantité change
                            updateCart();
                        }
                    });
                });

                // Sélectionner tous les boutons de suppression d'article du panier
                const removeButtons = document.querySelectorAll('.remove-from-cart');

                // Ajouter un écouteur d'événements à chaque bouton de suppression
                removeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Récupérer l'ID du produit à supprimer du panier
                        const productId = this.getAttribute('data-product-id');
                        
                        // Supprimer le produit du panier
                        removeFromCart(productId);
                    });
                });

                // Fonction pour supprimer un produit du panier
                function removeFromCart(productId) {
                    // Envoyer une requête AJAX au serveur pour supprimer le produit du panier
                    // Remplacer cet exemple par votre propre logique de suppression du panier
                    console.log('Removing product with ID ' + productId + ' from cart...');
                }

                // Fonction pour mettre à jour le panier
                function updateCart() {
                    // Envoyer une requête AJAX pour mettre à jour le panier
                    // Remplacer cet exemple par votre propre logique de mise à jour du panier
                    console.log('Updating cart...');
                }
            });
        </script>

</body>
</html>
