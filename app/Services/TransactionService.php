<?php
    namespace App\Services;

    use App\Models\Transaction;
    use App\Models\Product;
    use App\Models\Stock;
    use Illuminate\Support\Facades\DB;

    class TransactionService {
        
        public function createTransaction(array $data) {
            return DB::transaction(function () use ($data) {
                // 1️⃣ Create the transaction
                $transaction = Transaction::create($data);
                // 2️⃣ Update stock if it's a selling or purchase transaction
                if ($transaction->transactionType === 'purchase') {
                    
                    $this->increaseStock($transaction);
                    $this->updateProductPrice($transaction);
                } elseif ($transaction->transactionType === 'selling') {
                    $this->decreaseStock($transaction);
                }

                return $transaction;
            });
        }

        public function updateTransaction(Transaction $transaction, array $data) {
            return DB::transaction(function () use ($transaction, $data) {
                // Calculate stock changes before updating
                $oldQuantity = $transaction->quantity;
                $newQuantity = $data['quantity'] ?? $oldQuantity;

                // Update the transaction
                $transaction->update($data);

                // Handle stock adjustments based on transaction type
                if ($transaction->transaction_type === 'purchase') {
                    $this->adjustStock($transaction, $oldQuantity, $newQuantity, 'increase');
                    $this->updateProductPrice($transaction);
                } elseif ($transaction->transaction_type === 'selling') {
                    $this->adjustStock($transaction, $oldQuantity, $newQuantity, 'decrease');
                }

                return $transaction;
            });
        }

        private function increaseStock(Transaction $transaction) {
            // Check if stock exists for the given product_id
           
            $stock = Stock::where('product_id', $transaction->productId)->first();
        
            if ($stock) {
                // If stock exists, update the quantity
                $stock->quantity += $transaction->quantity;
                $stock->save();
            } else {
                // If stock does not exist, create a new stock record
                Stock::create([
                    'product_id' => $transaction->productId,
                    'location' => $transaction->location,
                    'quantity' => $transaction->quantity
                ]);
            }
        }
        

        private function decreaseStock(Transaction $transaction) {
            $stock = Stock::where('product_id', $transaction->product_id)->firstOrFail();
            
            // Prevent negative stock
            if ($stock->quantity < $transaction->quantity) {
                throw new \Exception('Not enough stock available.');
            }

            $stock->quantity -= $transaction->quantity;
            $stock->save();
        }

        private function adjustStock(Transaction $transaction, $oldQuantity, $newQuantity, $operation) {
            $stock = Stock::where('product_id', $transaction->product_id)->firstOrFail();
            $quantityDifference = $newQuantity - $oldQuantity;

            if ($operation === 'increase') {
                $stock->quantity += $quantityDifference;
            } else {
                if ($stock->quantity < $quantityDifference) {
                    throw new \Exception('Not enough stock available.');
                }
                $stock->quantity -= $quantityDifference;
            }

            $stock->save();
        }

        private function updateProductPrice(Transaction $transaction) {
            $product = Product::findOrFail($transaction->product_id);
            $product->purchase_price = $transaction->purchase_price;
            $product->selling_price = $transaction->selling_price ?? ($transaction->purchase_price * 1.2);
            $product->save();
        }
    }
