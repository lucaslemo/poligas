<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\PaymentType;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sales')->delete();

        try {
            DB::beginTransaction();

            // Total de produtos no estoque
            $stockCount = Stock::where('status', 'available')->count();
            $stockCount -= ceil($stockCount / 3);

            for($j = 0; $j < $stockCount; $j++) {
                // Condicionais
                $shouldPayment = fake()->randomElement([true, false]);
                $shouldDeliveryman = fake()->randomElement([true, false]);

                // Datas
                $date = Carbon::now()->subMinutes(rand(1, 1440))->subDays(rand(1, 720));
                $paymentDate = $shouldPayment ? $date->addDays(rand(0, 10)) : null;

                // Vendedor, entregador e cliente
                $user = User::where('type', 'Gerente')->inRandomOrder()->first();
                $deliveryman = User::whereHas('managers', function($query) use($user){
                    $query->where('get_manager_user_id', $user->id);
                })->inRandomOrder()->first();
                $customer = Customer::inRandomOrder()->first();

                // Pagamento
                $paymentType = PaymentType::inRandomOrder()->first();

                // Cria venda
                $sale = new Sale();
                $sale->status = 'closed';
                $sale->total_value = 0;
                $sale->payment_date = $paymentDate;
                $sale->get_customer_id = $customer->id;
                $sale->get_user_id = $user->id;
                $sale->get_deliveryman_user_id = $shouldDeliveryman ? $deliveryman->id : null;
                $sale->get_payment_type_id = $shouldPayment ? $paymentType->id : null;
                $sale->created_at = $date;
                $sale->updated_at = $date;

                // Produtos
                $productsQty = fake()->randomDigitNotNull() * 3;

                for($i = 0; $i < $productsQty; $i++) {
                    $stock = Stock::with('product')->where('created_at', '>', $date)
                        ->where('status', 'available')
                        ->orderBy('created_at')
                        ->first();



                    if ($stock) {
                        // Atualiza preço
                        $value = $stock->product->prices()->latest()->first()->value;
                        $sale->total_value += $value;
                        $sale->save();

                        // Atualiza o estoque
                        $stock->status = 'sold';
                        $stock->save();

                        $sale->stocks()->attach($stock, ['sale_value' => $value]);
                    }
                }

                // Deleta venda se não houver produtos
                if ($sale->total_value == 0) {
                    $sale->delete();
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
