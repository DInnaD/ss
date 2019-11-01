<?php

use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new Category();
        $category->name = 'Спорт';
        $category->save();

        $category = new Category();
        $category->name = 'Для дома';
        $category->save();

        $category = new Category();
        $category->name = 'Техника';
        $category->save();


    }
}
