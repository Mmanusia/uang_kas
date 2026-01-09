<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MonthlyIncome;
use App\Models\Budget;
use App\Models\Transaction;
use App\Models\Groups;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(DefaultGroupCategorySeeder::class);

        // Seed sample data for previous months
        $this->seedSampleData($user);
    }

    private function seedSampleData($user)
    {

        $now = Carbon::now();
        $months = 6; // Last 6 months

        for ($i = 1; $i <= $months; $i++) {
            $date = $now->copy()->subMonths($i);
            $year = $date->year;
            $month = $date->month;

            // Monthly Income
            MonthlyIncome::create([
                'user_id' => $user->id,
                'year' => $year,
                'month' => $month,
                'amount' => rand(5000000, 10000000), // Random income between 5M-10M
            ]);

            // Budgets for each group
            $groups = Groups::all();
            foreach ($groups as $group) {
                Budget::create([
                    'user_id' => $user->id,
                    'group_id' => $group->id,
                    'year' => $year,
                    'month' => $month,
                    'limit_amount' => rand(1000000, 3000000), // Random limit 1M-3M
                    'limit_percentage' => rand(20, 40), // Random percentage
                ]);
            }

            // Sample Transactions (expenses)
            $expenseCategories = Category::where('type', 'expense')->get();
            $numTransactions = rand(5, 15);
            for ($j = 0; $j < $numTransactions; $j++) {
                $category = $expenseCategories->random();
                Transaction::create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'amount' => rand(50000, 500000), // Random expense 50K-500K
                    'date' => $date->copy()->addDays(rand(0, 27)), // Random day in the month
                    'description' => 'Sample expense',
                ]);
            }
        }
    }
}
