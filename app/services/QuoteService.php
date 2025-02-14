<?php
    namespace App\Services;

    use App\Models\Quote;
    use App\Models\QuoteProduct;
    use Illuminate\Support\Facades\DB;
    use Exception;

    class QuoteService
    {
        public function createQuote(array $data, array $quoteProducts)
        {
    
            try {
                return DB::transaction(function () use ($data, $quoteProducts) {
                    // Créer le devis
                    $quote = Quote::create($data);
                    
                    // Ajouter les produits au devis
                    foreach ($quoteProducts as $quoteProduct) {
                        
                        QuoteProduct::create([
                            'quote_id' => $quote->id,
                            'product_id' => $quoteProduct['product_id'],
                            'quantity' => $quoteProduct['quantity'],
                            'unit_price' => $quoteProduct['unit_price'],
                            // 'line_price' => $quoteProduct['quantity'] * $quoteProduct['unit_price'],
                        ]);
                    }

                    return $quote;
                });
            } catch (Exception $e) {
                // Log l'erreur pour le débogage
                \Log::error('Error creating quote: ' . $e->getMessage(), ['data' => $data, 'quoteProducts' => $quoteProducts]);
                
                // Lever une exception personnalisée ou retourner un message d'erreur
                throw new \Exception('Failed to create quote. Please try again.'. $e->getMessage());
            }
        }

        public function updateQuote(int $quoteId, array $data, array $products = null)
        {
            try {
                return DB::transaction(function () use ($quoteId, $data, $products) {
                    $quote = Quote::findOrFail($quoteId);
                    $quote->update($data);

                    if ($products) {
                        // Supprimer les anciens produits liés au devis
                        $quote->quoteProducts()->delete();

                        // Ajouter les nouveaux produits
                        foreach ($products as $product) {
                            QuoteProduct::create([
                                'quote_id' => $quote->id,
                                'product_id' => $product['product_id'],
                                'quantity' => $product['quantity'],
                                'unit_price' => $product['unit_price'],
                                'line_price' => $product['quantity'] * $product['unit_price'],
                            ]);
                        }
                    }

                    return $quote;
                });
            } catch (Exception $e) {
                // Log l'erreur pour le débogage
                \Log::error('Error updating quote: ' . $e->getMessage(), ['quoteId' => $quoteId, 'data' => $data, 'products' => $products]);

                // Lever une exception personnalisée ou retourner un message d'erreur
                throw new \Exception('Failed to update quote. Please try again.');
            }
        }

        public function deleteQuote(int $quoteId)
        {
            try {
                return DB::transaction(function () use ($quoteId) {
                    $quote = Quote::findOrFail($quoteId);

                    // Supprimer les produits liés avant de supprimer le devis
                    $quote->quoteProducts()->delete();

                    // Supprimer le devis
                    $quote->delete();

                    return true;
                });
            } catch (Exception $e) {
                // Log l'erreur pour le débogage
                \Log::error('Error deleting quote: ' . $e->getMessage(), ['quoteId' => $quoteId]);

                // Lever une exception personnalisée ou retourner un message d'erreur
                throw new \Exception('Failed to delete quote. Please try again.');
            }
        }

        public function getQuoteWithProducts(int $quoteId)
        {
            try {
                return Quote::with('quoteProducts.product')->findOrFail($quoteId);
            } catch (Exception $e) {
                // Log l'erreur pour le débogage
                \Log::error('Error fetching quote: ' . $e->getMessage(), ['quoteId' => $quoteId]);

                // Lever une exception personnalisée ou retourner un message d'erreur
                throw new \Exception('Failed to fetch quote details. Please try again.');
            }
        }
    }
