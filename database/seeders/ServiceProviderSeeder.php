<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contractor;
use Illuminate\Support\Facades\Hash;

class ServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Real EV charging contractor data
        $contractors = [
            [
                'first_name' => 'Michael',
                'last_name' => 'Johnson',
                'email' => 'michael@evpowerinstall.com',
                'phone' => '206-555-0101',
                'password' => Hash::make('password'),
                'company_name' => 'EV Power Installations',
                'company_address' => '123 Electric Ave, Seattle, WA 98101',
                'service_title' => 'Level 2 EV Charging Installation Specialist',
                'profile_overview' => 'Specializing in residential and commercial Level 2 charging stations with over 10 years of electrical experience.',
                'service_information' => json_encode(['Level 2 Charging', 'Electrical Panel Upgrades', 'Residential Installation', 'Commercial Installation']),
                'service_area_coverage' => 'Seattle, Bellevue, Tacoma, Everett',
                'years_of_experience' => 10,
                'starting_price' => 899.99,
                'status' => 'verified'
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Chen',
                'email' => 'sarah@greenpowerev.com',
                'phone' => '503-555-0202',
                'password' => Hash::make('password'),
                'company_name' => 'Green Power EV Solutions',
                'company_address' => '456 Renewable Way, Portland, OR 97201',
                'service_title' => 'DC Fast Charging Infrastructure Expert',
                'profile_overview' => 'Specializing in high-power DC fast charging stations for commercial and fleet applications.',
                'service_information' => json_encode(['DC Fast Charging', 'Fleet Charging Solutions', 'Commercial Installation', 'Load Management Systems']),
                'service_area_coverage' => 'Portland, Vancouver, Salem, Eugene',
                'years_of_experience' => 8,
                'starting_price' => 3500.00,
                'status' => 'verified'
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Williams',
                'email' => 'david@completeelectric.com',
                'phone' => '415-555-0303',
                'password' => Hash::make('password'),
                'company_name' => 'Complete Electric Solutions',
                'company_address' => '789 Power Street, San Francisco, CA 94103',
                'service_title' => 'Residential EV Charging Expert',
                'profile_overview' => 'Residential electrical contractor specializing in home EV charging installations and electrical upgrades.',
                'service_information' => json_encode(['Level 1 Charging', 'Level 2 Charging', 'Home Electrical Upgrades', 'Smart Charger Installation']),
                'service_area_coverage' => 'San Francisco, Oakland, San Jose, Palo Alto',
                'years_of_experience' => 5,
                'starting_price' => 599.99,
                'status' => 'verified'
            ],
            [
                'first_name' => 'Jennifer',
                'last_name' => 'Garcia',
                'email' => 'jennifer@solarevx.com',
                'phone' => '323-555-0404',
                'password' => Hash::make('password'),
                'company_name' => 'SolarEVX Integration',
                'company_address' => '101 Sunny Drive, Los Angeles, CA 90001',
                'service_title' => 'Solar + EV Charging Integration Specialist',
                'profile_overview' => 'Specializing in integrated solar power and EV charging solutions for maximum sustainability and savings.',
                'service_information' => json_encode(['Solar Installation', 'Level 2 Charging', 'Solar+EV Integration', 'Battery Backup Systems']),
                'service_area_coverage' => 'Los Angeles, San Diego, Orange County, Riverside',
                'years_of_experience' => 7,
                'starting_price' => 4999.99,
                'status' => 'verified'
            ],
            [
                'first_name' => 'Robert',
                'last_name' => 'Kim',
                'email' => 'robert@multifamilyev.com',
                'phone' => '312-555-0505',
                'password' => Hash::make('password'),
                'company_name' => 'Multifamily EV Solutions',
                'company_address' => '222 Condo Court, Chicago, IL 60601',
                'service_title' => 'Apartment & Condo Charging Specialist',
                'profile_overview' => 'Specializing in multi-unit dwelling EV charging installations with load sharing technology.',
                'service_information' => json_encode(['Multi-Unit Charging', 'Load Sharing Systems', 'Payment Solutions', 'Property Management Integration']),
                'service_area_coverage' => 'Chicago, Evanston, Oak Park, Naperville',
                'years_of_experience' => 6,
                'starting_price' => 1499.99,
                'status' => 'verified'
            ],
            [
                'first_name' => 'Amanda',
                'last_name' => 'Patel',
                'email' => 'amanda@fleetcharging.com',
                'phone' => '212-555-0606',
                'password' => Hash::make('password'),
                'company_name' => 'Fleet Charging Network',
                'company_address' => '333 Vehicle Lane, New York, NY 10001',
                'service_title' => 'Commercial Fleet Charging Expert',
                'profile_overview' => 'Specializing in fleet electrification and charging infrastructure for businesses with multiple vehicles.',
                'service_information' => json_encode(['Fleet Charging', 'Depot Infrastructure', 'Load Management', 'Commercial Installation']),
                'service_area_coverage' => 'New York, Newark, Jersey City, White Plains',
                'years_of_experience' => 9,
                'starting_price' => 7500.00,
                'status' => 'verified'
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Wilson',
                'email' => 'james@autoshopev.com',
                'phone' => '214-555-0707',
                'password' => Hash::make('password'),
                'company_name' => 'Auto Shop EV Specialists',
                'company_address' => '444 Dealership Road, Dallas, TX 75201',
                'service_title' => 'Automotive & Dealership Charging Expert',
                'profile_overview' => 'Specializing in EV charging solutions for auto dealerships, repair shops, and service centers.',
                'service_information' => json_encode(['Dealership Charging', 'Service Center Installations', 'Customer Charging Stations', 'High-Power Systems']),
                'service_area_coverage' => 'Dallas, Fort Worth, Plano, Arlington',
                'years_of_experience' => 4,
                'starting_price' => 2999.99,
                'status' => 'verified'
            ],
            [
                'first_name' => 'Lisa',
                'last_name' => 'Thompson',
                'email' => 'lisa@hospitalityev.com',
                'phone' => '305-555-0808',
                'password' => Hash::make('password'),
                'company_name' => 'Hospitality EV Charging',
                'company_address' => '555 Hotel Boulevard, Miami, FL 33101',
                'service_title' => 'Hotel & Restaurant Charging Specialist',
                'profile_overview' => 'Specializing in guest and customer charging solutions for the hospitality industry.',
                'service_information' => json_encode(['Hotel Charging', 'Restaurant Installations', 'Guest Payment Systems', 'Valet Charging Service']),
                'service_area_coverage' => 'Miami, Fort Lauderdale, Palm Beach, Naples',
                'years_of_experience' => 5,
                'starting_price' => 1899.99,
                'status' => 'verified'
            ],
            [
                'first_name' => 'Thomas',
                'last_name' => 'Martinez',
                'email' => 'thomas@evmaintain.com',
                'phone' => '303-555-0909',
                'password' => Hash::make('password'),
                'company_name' => 'EV Maintenance Pro',
                'company_address' => '666 Repair Street, Denver, CO 80201',
                'service_title' => 'Charging Station Maintenance Specialist',
                'profile_overview' => 'Specializing in maintenance, repair, and upgrade services for existing EV charging infrastructure.',
                'service_information' => json_encode(['Maintenance Services', 'Charger Repair', 'Software Updates', 'Parts Replacement']),
                'service_area_coverage' => 'Denver, Boulder, Aurora, Lakewood',
                'years_of_experience' => 6,
                'starting_price' => 199.99,
                'status' => 'verified'
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Jackson',
                'email' => 'emily@smartev.com',
                'phone' => '512-555-1010',
                'password' => Hash::make('password'),
                'company_name' => 'Smart EV Systems',
                'company_address' => '777 Automation Avenue, Austin, TX 78701',
                'service_title' => 'Smart Charging & Home Automation Expert',
                'profile_overview' => 'Specializing in smart home integration with EV charging systems for optimal energy management.',
                'service_information' => json_encode(['Smart Home Integration', 'Energy Management', 'Automated Charging', 'Home Battery Integration']),
                'service_area_coverage' => 'Austin, San Antonio, Houston, Dallas',
                'years_of_experience' => 5,
                'starting_price' => 1299.99,
                'status' => 'pending'
            ]
        ];

        foreach ($contractors as $contractorData) {
            // Create contractor directly with all data including credentials
            Contractor::create($contractorData);
        }
    }
}
