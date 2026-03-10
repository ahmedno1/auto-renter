<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $demoOwner1 = User::firstOrCreate(
            ['email' => 'owner1@example.com'],
            [
                'name' => 'Demo Owner 1',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'phone' => '+1 (555) 010-1001',
                'email_verified_at' => now(),
            ],
        );

        $demoOwner2 = User::firstOrCreate(
            ['email' => 'owner2@example.com'],
            [
                'name' => 'Demo Owner 2',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'phone' => '+1 (555) 010-1002',
                'email_verified_at' => now(),
            ],
        );

        $demoCustomer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '+1 (555) 010-2001',
                'email_verified_at' => now(),
            ],
        );

        $demoCustomer2 = User::firstOrCreate(
            ['email' => 'customer2@example.com'],
            [
                'name' => 'Demo Customer 2',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '+1 (555) 010-2002',
                'email_verified_at' => now(),
            ],
        );

        $demoCustomer3 = User::firstOrCreate(
            ['email' => 'customer3@example.com'],
            [
                'name' => 'Demo Customer 3',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '+1 (555) 010-2003',
                'email_verified_at' => now(),
            ],
        );

        $allCustomers = collect([$demoCustomer, $demoCustomer2, $demoCustomer3]);

        $imagePath = $this->ensureDemoImage();

        $owners = collect([$demoOwner1, $demoOwner2]);

        $owners->each(function (User $owner, int $index) use ($imagePath) {
            if ($owner->cars()->count() >= 6) {
                return;
            }

            $owner->cars()->createMany([
                [
                    'image' => $imagePath,
                    'brand' => $index === 0 ? 'Toyota' : 'Honda',
                    'model' => $index === 0 ? 'Corolla' : 'Civic',
                    'year' => 2022,
                    'daily_rent' => 45.00,
                    'description' => 'Clean, reliable, and great for city driving.',
                    'status' => 'available',
                ],
                [
                    'image' => $imagePath,
                    'brand' => $index === 0 ? 'BMW' : 'Kia',
                    'model' => $index === 0 ? 'X5' : 'Sportage',
                    'year' => 2021,
                    'daily_rent' => 90.00,
                    'description' => 'Comfortable ride with plenty of space.',
                    'status' => 'available',
                ],
                [
                    'image' => $imagePath,
                    'brand' => $index === 0 ? 'Tesla' : 'Hyundai',
                    'model' => $index === 0 ? 'Model 3' : 'Elantra',
                    'year' => 2023,
                    'daily_rent' => 110.00,
                    'description' => 'Modern features and smooth driving experience.',
                    'status' => 'available',
                ],
            ]);

            // Add a few extra random cars for variety.
            Car::factory()
                ->count(4)
                ->state([
                    'owner_id' => $owner->id,
                    'image' => $imagePath,
                ])
                ->create();
        });

        // Create reservations for the next 45 days (mix of pending/approved/rejected).
        $cars = Car::query()->whereNotNull('owner_id')->with('owner')->get();

        foreach ($cars as $car) {
            if (Reservation::where('car_id', $car->id)->exists()) {
                continue;
            }

            // Keep some cars free of reservations.
            if (random_int(1, 5) === 1) {
                continue;
            }

            $ranges = $this->buildNonOverlappingRanges(2, Carbon::today()->addDays(1), Carbon::today()->addDays(45));

            foreach ($ranges as [$start, $end]) {
                $customer = $allCustomers->random();

                if ($customer->id === $car->owner_id) {
                    $customer = $demoCustomer;
                }

                $days = $start->diffInDays($end) + 1;
                $total = $days * (float) $car->daily_rent;

                Reservation::create([
                    'car_id' => $car->id,
                    'customer_id' => $customer->id,
                    'start_date' => $start->toDateString(),
                    'end_date' => $end->toDateString(),
                    'total_price' => $total,
                    'status' => collect(['pending', 'approved', 'rejected'])->random(),
                ]);
            }
        }

        // Ensure at least one "pending" for incoming tab testing.
        $anyCar = Car::query()->where('owner_id', $demoOwner1->id)->first();
        if ($anyCar) {
            $start = Carbon::today()->addDays(3);
            $end = Carbon::today()->addDays(5);
            Reservation::firstOrCreate(
                [
                    'car_id' => $anyCar->id,
                    'customer_id' => $demoCustomer->id,
                    'start_date' => $start->toDateString(),
                    'end_date' => $end->toDateString(),
                ],
                [
                    'total_price' => ((float) $anyCar->daily_rent) * ($start->diffInDays($end) + 1),
                    'status' => 'pending',
                ],
            );
        }
    }

    private function ensureDemoImage(): string
    {
        $target = 'cars/demo-car.png';

        if (Storage::disk('public')->exists($target)) {
            return $target;
        }

        $source = public_path('image/car.png');

        if (is_file($source)) {
            Storage::disk('public')->put($target, file_get_contents($source));
            return $target;
        }

        // Fallback to an arbitrary path; UI will still show the placeholder via getImageUrl().
        return $target;
    }

    /**
     * @return array<int, array{0: \Carbon\Carbon, 1: \Carbon\Carbon}>
     */
    private function buildNonOverlappingRanges(int $count, Carbon $min, Carbon $max): array
    {
        $ranges = [];
        $attempts = 0;

        while (count($ranges) < $count && $attempts < 50) {
            $attempts += 1;

            $start = $min->copy()->addDays(random_int(0, max(0, $min->diffInDays($max) - 7)));
            $end = $start->copy()->addDays(random_int(1, 4));

            if ($end->gt($max)) {
                continue;
            }

            $overlaps = false;

            foreach ($ranges as [$existingStart, $existingEnd]) {
                if ($start->lte($existingEnd) && $end->gte($existingStart)) {
                    $overlaps = true;
                    break;
                }
            }

            if ($overlaps) {
                continue;
            }

            $ranges[] = [$start, $end];
        }

        return $ranges;
    }
}
