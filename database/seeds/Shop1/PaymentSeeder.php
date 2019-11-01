<?php

use Illuminate\Database\Seeder;
use App\Models\Payment;
class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new Payment();
        $category->title = 'Наличными';
        $category->save();

        $category = new Payment();
        $category->title = 'Картой';
        $category->save();
    }
}
