<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Category::class, 5)->create()->each(function (Category $category){
            $category->subCategories()->saveMany(factory(Category::class, 3)->create([
                'parent_id'=> $category->id,
            ]));
            $category->save();
        });
    }
}
