<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Customer;
use App\Models\DriverCustomer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Price;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Customer::factory(30)->create();
        for ($i = 1; $i < 31; $i++) {
            $product      = sprintf('Item %d', $i);
            $product_data = [
                'name'  => $product,
                'price' => fake()->randomFloat(2, 0, 3000),
            ];
            Product::create($product_data);
        }

        $customers = Customer::all();
        $products  = Product::all();
        foreach ($customers as $customer) {
            foreach ($products as $product) {
                $original_price = $product->price;
                $price_data     = [
                    'product_id'  => $product->id,
                    'customer_id' => $customer->id,
                    'price'       => mt_rand(0, $original_price * 100) / 100.0,
                    'max_stock'   => rand(1, 10),
                ];
                Price::create($price_data);
            }
        }

        $drivers = User::where('id', '>', 1)->get();
        foreach ($drivers as $driver) {
            for ($i = ($driver->id - 2) * 5 + 1; $i <= ($driver->id - 1) * 5; $i++) {
                $customer             = Customer::find($i);
                $driver_customer_data = [
                    'driver_id'   => $driver->id,
                    'customer_id' => $customer->id,
                ];
                DriverCustomer::create($driver_customer_data);

                $status = fake()->randomElement(['pending', 'completed', 'cancelled']);
                $order  = Order::create([
                    'driver_id'   => $driver->id,
                    'customer_id' => $customer->id,
                    'status'      => $status,
                ]);

                $selected_products = [];
                for ($details = 0; $details < rand(1, 5); $details++) {
                    do {
                        $product = Product::inRandomOrder()->first();
                    } while (in_array($product, $selected_products));
                    $selected_products[] = $product;

                    $price             = Price::where('product_id', $product->id)->where('customer_id', $customer->id)->first();
                    $purchase_quantity = rand(1, 15);
                    if ($purchase_quantity < $price->max_stock) {
                        $order_details = [
                            'order_id'   => $order->id,
                            'product_id' => $product->id,
                            'price'      => $price->price,
                            'quantity'   => $purchase_quantity,
                        ];
                    } else {
                        $order_details = [
                            'order_id'   => $order->id,
                            'product_id' => $product->id,
                            'price'      => $price->price,
                            'quantity'   => $price->max_stock,
                        ];
                        OrderDetails::create($order_details);

                        $order_details = [
                            'order_id'   => $order->id,
                            'product_id' => $product->id,
                            'price'      => $product->price,
                            'quantity'   => ($purchase_quantity - $price->max_stock),
                        ];
                    }
                    OrderDetails::create($order_details);
                }
            }
        }
    }
}
