<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contractor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ContractorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $serviceTitles = [
            'EV Installation Expert',
            'Technical Consultant',
            'Service Technician',
            'Solar Panel Specialist',
            'Electrical Engineer',
            'HVAC Specialist',
            'Construction Manager',
            'Project Coordinator',
            'Maintenance Supervisor',
            'Installation Expert'
        ];

        // First, ensure the storage link is created
        if (!file_exists(public_path('storage'))) {
            \Artisan::call('storage:link');
        }

        // Ensure directories exist
        Storage::makeDirectory('public/contractors/images');
        Storage::makeDirectory('public/contractors/documents');

        foreach (range(1, 10) as $index) {
            // Generate a random profile image
            $imagePath = 'contractors/images/' . Str::random(10) . '.jpg';

            // Create a blank image
            $width = 200;
            $height = 200;
            $image = imagecreatetruecolor($width, $height);

            // Add some random colors and shapes to make it look like a profile image
            $bgColor = imagecolorallocate($image, 
                rand(200, 255), // R
                rand(200, 255), // G
                rand(200, 255)  // B
            );
            $fgColor = imagecolorallocate($image, 
                rand(0, 100),   // R
                rand(0, 100),   // G
                rand(0, 100)    // B
            );

            // Fill background
            imagefill($image, 0, 0, $bgColor);

            // Draw a circle for avatar-like appearance
            imagefilledellipse($image, $width/2, $height/2, 150, 150, $fgColor);

            // Add initials
            $initials = strtoupper(substr($faker->firstName, 0, 1) . substr($faker->lastName, 0, 1));
            $font_size = 5;
            $text_box = imagettfbbox($font_size, 0, 5, $initials);
            $text_width = abs($text_box[4] - $text_box[0]);
            $text_height = abs($text_box[5] - $text_box[1]);
            $x = ($width - $text_width) / 2;
            $y = ($height + $text_height) / 2;

            // Save the image
            ob_start();
            imagejpeg($image);
            $imageData = ob_get_clean();
            Storage::put('public/' . $imagePath, $imageData);
            imagedestroy($image);

            // Generate a random document (PDF)
            $documentPath = 'contractors/documents/' . Str::random(10) . '.pdf';
            $pdfContent = "%PDF-1.4\n%Fake PDF Content\n";
            Storage::put('public/' . $documentPath, $pdfContent);

            DB::table('contractors')->insert([
                'company_name' => $faker->company,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'company_address' => $faker->address,
                'service_title' => $faker->randomElement($serviceTitles),
                'password' => Hash::make('password123'),
                'starting_price' => $faker->randomFloat(2, 99, 999),
                'service_information' => json_encode($faker->randomElements([
                    'Electrical Work', 'Solar Installation', 'Construction', 
                    'HVAC Repair', 'Project Management'
                ], rand(1, 3))),
                'service_area_coverage' => $faker->city,
                'license_number' => $faker->randomElement(['C10', 'A', 'B', 'C7', 'C46']),
                'insurance_information' => $faker->randomElement(['Insured', 'Not Insured']),
                'profile_image' => $imagePath,
                'uploaded_document' => $documentPath,
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'status' => $faker->randomElement(['pending', 'verified', 'rejected']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
