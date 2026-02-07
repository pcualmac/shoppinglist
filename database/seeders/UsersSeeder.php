<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $groceryNames = [
            'Apples',
            'Bananas',
            'Bread',
            'Milk',
            'Eggs',
            'Cheese',
            'Tomatoes',
            'Potatoes',
            'Rice',
            'Pasta',
            'Chicken',
            'Yogurt',
            'Carrots',
            'Coffee',
            'Tea',
            'Olive Oil',
            'Cereal',
            'Spinach',
            'Butter',
            'Orange Juice',
        ];

        for ($i = 1; $i <= 10; $i++) {
            $user = User::updateOrCreate(
                ['email' => "emai{$i}@test.com"],
                [
                    'name' => "user{$i}",
                    'password' => Hash::make("pass{$i}"),
                ]
            );

            $listId = DB::table('shopping_lists')->insertGetId([
                'user_id' => $user->id,
                'name' => "user{$i}'s list",
                'spending_limit' => random_int(25, 120),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $itemCount = random_int(3, 10);
            $selectedNames = collect($groceryNames)->shuffle()->take($itemCount)->values();

            foreach ($selectedNames as $index => $name) {
                DB::table('shopping_items')->insert([
                    'shopping_list_id' => $listId,
                    'name' => $name,
                    'quantity' => random_int(1, 5),
                    'price' => random_int(50, 1500) / 100,
                    'is_purchased' => (bool) random_int(0, 1),
                    'sort_order' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $extraUser = User::updateOrCreate(
            ['email' => 'pumpignano@gmail.com'],
            [
                'name' => 'user17',
                'password' => Hash::make('pass17'),
            ]
        );

        $extraListId = DB::table('shopping_lists')->insertGetId([
            'user_id' => $extraUser->id,
            'name' => "user17's list",
            'spending_limit' => random_int(25, 120),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $extraItemCount = random_int(3, 10);
        $extraSelectedNames = collect($groceryNames)->shuffle()->take($extraItemCount)->values();

        foreach ($extraSelectedNames as $index => $name) {
            DB::table('shopping_items')->insert([
                'shopping_list_id' => $extraListId,
                'name' => $name,
                'quantity' => random_int(1, 5),
                'price' => random_int(50, 1500) / 100,
                'is_purchased' => (bool) random_int(0, 1),
                'sort_order' => $index,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
