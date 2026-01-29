<?php

namespace Database\Seeders;

use Botble\RealEstate\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NewPackagesSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing packages (delete instead of truncate to avoid foreign key issues)
        DB::table('re_account_packages')->delete();
        DB::table('re_packages')->delete();

        $currency_id = 1;
        try {
            $currency_id = DB::table('currencies')->where('is_default', 1)->value('id') ?? 1;
        } catch (\Exception $e) {
            try {
                $currency_id = DB::table('re_currencies')->where('is_default', 1)->value('id') ?? 1;
            } catch (\Exception $e) {
                // Default to 1 if neither exists
                $currency_id = 1;
            }
        }

        $packages = [
            // ============================================
            // BUILDER PACKAGES
            // ============================================
            [
                'name' => 'Builder Starter',
                'description' => 'Perfect for small developers with 1-2 projects',
                'price' => 2999,
                'currency_id' => $currency_id,
                'percent_save' => 0,
                'number_of_listings' => 25,
                'number_of_projects' => 2,
                'account_limit' => null,
                'order' => 1,
                'package_type' => 'builder',
                'is_recurring' => true,
                'duration_days' => null,
                'features' => json_encode([
                    ['key' => 'text', 'value' => 'Basic project showcase'],
                    ['key' => 'text', 'value' => 'Company profile page'],
                    ['key' => 'text', 'value' => 'Email notifications for leads'],
                    ['key' => 'text', 'value' => 'Standard listing visibility'],
                    ['key' => 'text', 'value' => 'Basic analytics (views, inquiries)'],
                    ['key' => 'text', 'value' => 'Verified badge'],
                    ['key' => 'text', 'value' => '5 featured listings/month'],
                ]),
                'is_default' => false,
                'status' => 'published',
            ],
            [
                'name' => 'Builder Professional',
                'description' => 'For medium-sized developers with multiple ongoing projects',
                'price' => 7999,
                'currency_id' => $currency_id,
                'percent_save' => 0,
                'number_of_listings' => 100,
                'number_of_projects' => 10,
                'account_limit' => null,
                'order' => 2,
                'package_type' => 'builder',
                'is_recurring' => true,
                'duration_days' => null,
                'features' => json_encode([
                    ['key' => 'text', 'value' => 'Enhanced project showcase with gallery'],
                    ['key' => 'text', 'value' => 'Premium company profile with branding'],
                    ['key' => 'text', 'value' => 'Priority email + WhatsApp lead notifications'],
                    ['key' => 'text', 'value' => '20 featured listings/month'],
                    ['key' => 'text', 'value' => 'Advanced analytics & lead tracking'],
                    ['key' => 'text', 'value' => 'Verified + Premium badge'],
                    ['key' => 'text', 'value' => 'Social media integration'],
                    ['key' => 'text', 'value' => 'Virtual tour support'],
                    ['key' => 'text', 'value' => 'Dedicated account manager (email support)'],
                    ['key' => 'text', 'value' => 'Export leads to CSV'],
                ]),
                'is_default' => false,
                'status' => 'published',
            ],
            [
                'name' => 'Builder Enterprise',
                'description' => 'For large developers and real estate companies',
                'price' => 19999,
                'currency_id' => $currency_id,
                'percent_save' => 0,
                'number_of_listings' => 999999, // Unlimited
                'number_of_projects' => 999999, // Unlimited
                'account_limit' => null,
                'order' => 3,
                'package_type' => 'builder',
                'is_recurring' => true,
                'duration_days' => null,
                'features' => json_encode([
                    ['key' => 'text', 'value' => 'Everything in Professional'],
                    ['key' => 'text', 'value' => 'Priority placement in search results'],
                    ['key' => 'text', 'value' => 'Homepage banner placement (1/month)'],
                    ['key' => 'text', 'value' => 'CRM integration support'],
                    ['key' => 'text', 'value' => 'API access for bulk uploads'],
                    ['key' => 'text', 'value' => 'Custom branding options'],
                    ['key' => 'text', 'value' => 'Multiple user accounts (up to 5 team members)'],
                    ['key' => 'text', 'value' => 'Dedicated account manager (phone + email)'],
                    ['key' => 'text', 'value' => 'Priority customer support'],
                    ['key' => 'text', 'value' => 'Advanced lead scoring'],
                    ['key' => 'text', 'value' => 'WhatsApp Business API integration'],
                    ['key' => 'text', 'value' => 'Monthly performance reports'],
                ]),
                'is_default' => false,
                'status' => 'published',
            ],

            // ============================================
            // AGENT PACKAGES
            // ============================================
            [
                'name' => 'Agent Basic',
                'description' => 'For individual agents starting out',
                'price' => 999,
                'currency_id' => $currency_id,
                'percent_save' => 0,
                'number_of_listings' => 10,
                'number_of_projects' => null,
                'account_limit' => null,
                'order' => 4,
                'package_type' => 'agent',
                'is_recurring' => true,
                'duration_days' => null,
                'features' => json_encode([
                    ['key' => 'text', 'value' => 'Agent profile page'],
                    ['key' => 'text', 'value' => 'Email lead notifications'],
                    ['key' => 'text', 'value' => 'Standard listing visibility'],
                    ['key' => 'text', 'value' => 'Basic analytics'],
                    ['key' => 'text', 'value' => 'Verified badge'],
                    ['key' => 'text', 'value' => '2 featured listings/month'],
                    ['key' => 'text', 'value' => 'Mobile app access'],
                ]),
                'is_default' => true,
                'status' => 'published',
            ],
            [
                'name' => 'Agent Professional',
                'description' => 'For established agents with steady business',
                'price' => 2999,
                'currency_id' => $currency_id,
                'percent_save' => 0,
                'number_of_listings' => 50,
                'number_of_projects' => null,
                'account_limit' => null,
                'order' => 5,
                'package_type' => 'agent',
                'is_recurring' => true,
                'duration_days' => null,
                'features' => json_encode([
                    ['key' => 'text', 'value' => 'Enhanced agent profile with testimonials'],
                    ['key' => 'text', 'value' => 'Email + WhatsApp lead notifications'],
                    ['key' => 'text', 'value' => '10 featured listings/month'],
                    ['key' => 'text', 'value' => 'Advanced analytics'],
                    ['key' => 'text', 'value' => 'Verified + Professional badge'],
                    ['key' => 'text', 'value' => 'Lead management dashboard'],
                    ['key' => 'text', 'value' => 'Priority listing in agent directory'],
                    ['key' => 'text', 'value' => 'Social media sharing tools'],
                    ['key' => 'text', 'value' => 'Save searches & get alerts'],
                ]),
                'is_default' => false,
                'status' => 'published',
            ],
            [
                'name' => 'Agent Premium',
                'description' => 'For top-performing agents and small brokerages',
                'price' => 5999,
                'currency_id' => $currency_id,
                'percent_save' => 0,
                'number_of_listings' => 150,
                'number_of_projects' => null,
                'account_limit' => null,
                'order' => 6,
                'package_type' => 'agent',
                'is_recurring' => true,
                'duration_days' => null,
                'features' => json_encode([
                    ['key' => 'text', 'value' => 'Everything in Professional'],
                    ['key' => 'text', 'value' => 'Premium profile with video introduction'],
                    ['key' => 'text', 'value' => 'Priority placement in search'],
                    ['key' => 'text', 'value' => '25 featured listings/month'],
                    ['key' => 'text', 'value' => 'CRM tools (lead tracking, follow-ups)'],
                    ['key' => 'text', 'value' => 'Multiple user access (3 team members)'],
                    ['key' => 'text', 'value' => 'Export leads'],
                    ['key' => 'text', 'value' => 'Dedicated support'],
                    ['key' => 'text', 'value' => 'Performance analytics'],
                    ['key' => 'text', 'value' => 'Custom domain for profile (optional)'],
                ]),
                'is_default' => false,
                'status' => 'published',
            ],

            // ============================================
            // OWNER PACKAGES (One-time)
            // ============================================
            [
                'name' => 'Owner Free',
                'description' => 'For individual owners testing the platform',
                'price' => 0,
                'currency_id' => $currency_id,
                'percent_save' => 0,
                'number_of_listings' => 1,
                'number_of_projects' => null,
                'account_limit' => null,
                'order' => 7,
                'package_type' => 'owner',
                'is_recurring' => false,
                'duration_days' => 30,
                'features' => json_encode([
                    ['key' => 'text', 'value' => 'Basic listing with photos (up to 5)'],
                    ['key' => 'text', 'value' => 'Standard visibility'],
                    ['key' => 'text', 'value' => 'Email notifications'],
                    ['key' => 'text', 'value' => 'Basic property details'],
                    ['key' => 'text', 'value' => 'Watermarked photos'],
                ]),
                'is_default' => false,
                'status' => 'published',
            ],
            [
                'name' => 'Owner Basic',
                'description' => 'For individual property owners',
                'price' => 299,
                'currency_id' => $currency_id,
                'percent_save' => 0,
                'number_of_listings' => 1,
                'number_of_projects' => null,
                'account_limit' => null,
                'order' => 8,
                'package_type' => 'owner',
                'is_recurring' => false,
                'duration_days' => 90,
                'features' => json_encode([
                    ['key' => 'text', 'value' => 'Enhanced listing with photos (up to 15)'],
                    ['key' => 'text', 'value' => 'Standard visibility'],
                    ['key' => 'text', 'value' => 'Email + SMS notifications'],
                    ['key' => 'text', 'value' => 'Detailed property information'],
                    ['key' => 'text', 'value' => 'No watermarks'],
                    ['key' => 'text', 'value' => 'Listing refresh (once)'],
                ]),
                'is_default' => false,
                'status' => 'published',
            ],
            [
                'name' => 'Owner Premium',
                'description' => 'For owners wanting maximum visibility',
                'price' => 999,
                'currency_id' => $currency_id,
                'percent_save' => 0,
                'number_of_listings' => 1,
                'number_of_projects' => null,
                'account_limit' => null,
                'order' => 9,
                'package_type' => 'owner',
                'is_recurring' => false,
                'duration_days' => 180,
                'features' => json_encode([
                    ['key' => 'text', 'value' => 'Premium listing with photos (up to 30)'],
                    ['key' => 'text', 'value' => 'Featured placement (30 days)'],
                    ['key' => 'text', 'value' => 'Priority in search results'],
                    ['key' => 'text', 'value' => 'Email + SMS + WhatsApp notifications'],
                    ['key' => 'text', 'value' => 'Virtual tour support'],
                    ['key' => 'text', 'value' => 'Video upload'],
                    ['key' => 'text', 'value' => 'Unlimited listing refreshes'],
                    ['key' => 'text', 'value' => 'Highlighted in similar properties'],
                    ['key' => 'text', 'value' => 'Social media promotion'],
                ]),
                'is_default' => false,
                'status' => 'published',
            ],

            // ============================================
            // ADD-ON SERVICES
            // ============================================
            [
                'name' => 'Featured Listing Boost',
                'description' => 'Highlight your listing at the top of search results for one week',
                'price' => 199,
                'currency_id' => $currency_id,
                'percent_save' => 0,
                'number_of_listings' => 1,
                'number_of_projects' => null,
                'account_limit' => null,
                'order' => 10,
                'package_type' => 'addon',
                'is_recurring' => false,
                'duration_days' => 7,
                'features' => json_encode([
                    ['key' => 'text', 'value' => 'Top placement in search results'],
                    ['key' => 'text', 'value' => 'Highlighted badge on listing'],
                    ['key' => 'text', 'value' => '7 days duration'],
                ]),
                'is_default' => false,
                'status' => 'published',
            ],
            [
                'name' => 'Homepage Banner',
                'description' => 'Get your project/property featured on the homepage banner',
                'price' => 4999,
                'currency_id' => $currency_id,
                'percent_save' => 0,
                'number_of_listings' => 1,
                'number_of_projects' => null,
                'account_limit' => null,
                'order' => 11,
                'package_type' => 'addon',
                'is_recurring' => false,
                'duration_days' => 7,
                'features' => json_encode([
                    ['key' => 'text', 'value' => 'Homepage banner placement'],
                    ['key' => 'text', 'value' => 'Maximum visibility'],
                    ['key' => 'text', 'value' => '7 days duration'],
                ]),
                'is_default' => false,
                'status' => 'published',
            ],
            [
                'name' => 'Extra Listings (Agent)',
                'description' => 'Add extra listings beyond your package limit',
                'price' => 99,
                'currency_id' => $currency_id,
                'percent_save' => 0,
                'number_of_listings' => 1,
                'number_of_projects' => null,
                'account_limit' => null,
                'order' => 12,
                'package_type' => 'addon',
                'is_recurring' => false,
                'duration_days' => 30,
                'features' => json_encode([
                    ['key' => 'text', 'value' => '1 additional listing'],
                    ['key' => 'text', 'value' => 'For agents'],
                    ['key' => 'text', 'value' => '30 days duration'],
                ]),
                'is_default' => false,
                'status' => 'published',
            ],
            [
                'name' => 'Extra Listings (Builder)',
                'description' => 'Add extra listings beyond your package limit',
                'price' => 49,
                'currency_id' => $currency_id,
                'percent_save' => 0,
                'number_of_listings' => 1,
                'number_of_projects' => null,
                'account_limit' => null,
                'order' => 13,
                'package_type' => 'addon',
                'is_recurring' => false,
                'duration_days' => 30,
                'features' => json_encode([
                    ['key' => 'text', 'value' => '1 additional listing'],
                    ['key' => 'text', 'value' => 'For builders'],
                    ['key' => 'text', 'value' => '30 days duration'],
                ]),
                'is_default' => false,
                'status' => 'published',
            ],
            [
                'name' => 'Virtual Tour Creation',
                'description' => 'Professional virtual tour creation service',
                'price' => 1999,
                'currency_id' => $currency_id,
                'percent_save' => 0,
                'number_of_listings' => 1,
                'number_of_projects' => null,
                'account_limit' => null,
                'order' => 14,
                'package_type' => 'addon',
                'is_recurring' => false,
                'duration_days' => null,
                'features' => json_encode([
                    ['key' => 'text', 'value' => 'Professional 360° virtual tour'],
                    ['key' => 'text', 'value' => 'Embedded in your listing'],
                    ['key' => 'text', 'value' => 'One-time service'],
                ]),
                'is_default' => false,
                'status' => 'published',
            ],
            [
                'name' => 'Professional Photography',
                'description' => 'Professional photo shoot service for your property',
                'price' => 2999,
                'currency_id' => $currency_id,
                'percent_save' => 0,
                'number_of_listings' => 1,
                'number_of_projects' => null,
                'account_limit' => null,
                'order' => 15,
                'package_type' => 'addon',
                'is_recurring' => false,
                'duration_days' => null,
                'features' => json_encode([
                    ['key' => 'text', 'value' => 'Professional photographer visit'],
                    ['key' => 'text', 'value' => 'High-quality edited photos'],
                    ['key' => 'text', 'value' => 'Up to 30 photos delivered'],
                    ['key' => 'text', 'value' => 'One-time service'],
                ]),
                'is_default' => false,
                'status' => 'published',
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }

        $this->command->info('Successfully created ' . count($packages) . ' packages!');
    }
}
