<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
        ]);

        $users = [
            [
                'name' => 'Jānis Bērziņš',
                'email' => 'janis@example.com',
                'department' => 'Grāmatvedība',
            ],
            [
                'name' => 'Anna Kalniņa',
                'email' => 'anna@example.com',
                'department' => 'Grāmatvedība',
            ],
            [
                'name' => 'Toms Ozols',
                'email' => 'toms@example.com',
                'department' => 'Grāmatvedība',
            ],
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            $createdUsers[] = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'department' => $userData['department'],
                ]
            );
        }

        $admin = User::where('email', 'admin@gmail.com')->first();

        $sampleTickets = [
            [
                'title' => 'Nestrādā printeris',
                'description' => 'Printeris rāda kļūdu un neizdrukā dokumentus.',
                'category' => 'hardware',
                'priority' => 'medium',
                'class_department' => 'Grāmatvedība',
                'first_name' => 'Jānis',
                'last_name' => 'Bērziņš',
            ],
            [
                'title' => 'Nav piekļuves e-pastam',
                'description' => 'Pieslēgšanās pie e-pasta konta neizdodas.',
                'category' => 'software',
                'priority' => 'high',
                'class_department' => 'Grāmatvedība',
                'first_name' => 'Anna',
                'last_name' => 'Kalniņa',
            ],
            [
                'title' => 'Wi-Fi signāls ļoti vājš',
                'description' => 'Klasē 301 praktiski nav interneta pieslēguma.',
                'category' => 'network',
                'priority' => 'urgent',
                'class_department' => 'Grāmatvedība',
                'first_name' => 'Toms',
                'last_name' => 'Ozols',
            ],
        ];

        foreach ($sampleTickets as $index => $ticketData) {
            $user = $createdUsers[$index] ?? $createdUsers[0];

            Ticket::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'title' => $ticketData['title'],
                ],
                [
                    'first_name' => $ticketData['first_name'],
                    'last_name' => $ticketData['last_name'],
                    'class_department' => $ticketData['class_department'],
                    'category' => $ticketData['category'],
                    'description' => $ticketData['description'],
                    'priority' => $ticketData['priority'],
                    'status' => 'open',
                    'assigned_to' => $admin?->id,
                ]
            );
        }
    }
}
