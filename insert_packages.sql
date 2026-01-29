-- Clear existing packages
DELETE FROM re_account_packages;
DELETE FROM re_packages;

-- Insert new packages
-- Note: Replace @currency_id with your actual currency ID (usually 1)
SET @currency_id = 1;

-- Builder Starter
INSERT INTO re_packages (name, description, price, currency_id, percent_save, number_of_listings, number_of_projects, account_limit, `order`, package_type, is_recurring, duration_days, features, is_default, status, created_at, updated_at) VALUES
('Builder Starter', 'Perfect for small developers with 1-2 projects', 2999, @currency_id, 0, 25, 2, NULL, 1, 'builder', 1, NULL, '[{"key":"text","value":"Basic project showcase"},{"key":"text","value":"Company profile page"},{"key":"text","value":"Email notifications for leads"},{"key":"text","value":"Standard listing visibility"},{"key":"text","value":"Basic analytics (views, inquiries)"},{"key":"text","value":"Verified badge"},{"key":"text","value":"5 featured listings/month"}]', 0, 'published', NOW(), NOW()),

-- Builder Professional
('Builder Professional', 'For medium-sized developers with multiple ongoing projects', 7999, @currency_id, 0, 100, 10, NULL, 2, 'builder', 1, NULL, '[{"key":"text","value":"Enhanced project showcase with gallery"},{"key":"text","value":"Premium company profile with branding"},{"key":"text","value":"Priority email + WhatsApp lead notifications"},{"key":"text","value":"20 featured listings/month"},{"key":"text","value":"Advanced analytics & lead tracking"},{"key":"text","value":"Verified + Premium badge"},{"key":"text","value":"Social media integration"},{"key":"text","value":"Virtual tour support"},{"key":"text","value":"Dedicated account manager (email support)"},{"key":"text","value":"Export leads to CSV"}]', 0, 'published', NOW(), NOW()),

-- Builder Enterprise
('Builder Enterprise', 'For large developers and real estate companies', 19999, @currency_id, 0, 999999, 999999, NULL, 3, 'builder', 1, NULL, '[{"key":"text","value":"Everything in Professional"},{"key":"text","value":"Priority placement in search results"},{"key":"text","value":"Homepage banner placement (1/month)"},{"key":"text","value":"CRM integration support"},{"key":"text","value":"API access for bulk uploads"},{"key":"text","value":"Custom branding options"},{"key":"text","value":"Multiple user accounts (up to 5 team members)"},{"key":"text","value":"Dedicated account manager (phone + email)"},{"key":"text","value":"Priority customer support"},{"key":"text","value":"Advanced lead scoring"},{"key":"text","value":"WhatsApp Business API integration"},{"key":"text","value":"Monthly performance reports"}]', 0, 'published', NOW(), NOW()),

-- Agent Basic
('Agent Basic', 'For individual agents starting out', 999, @currency_id, 0, 10, NULL, NULL, 4, 'agent', 1, NULL, '[{"key":"text","value":"Agent profile page"},{"key":"text","value":"Email lead notifications"},{"key":"text","value":"Standard listing visibility"},{"key":"text","value":"Basic analytics"},{"key":"text","value":"Verified badge"},{"key":"text","value":"2 featured listings/month"},{"key":"text","value":"Mobile app access"}]', 1, 'published', NOW(), NOW()),

-- Agent Professional
('Agent Professional', 'For established agents with steady business', 2999, @currency_id, 0, 50, NULL, NULL, 5, 'agent', 1, NULL, '[{"key":"text","value":"Enhanced agent profile with testimonials"},{"key":"text","value":"Email + WhatsApp lead notifications"},{"key":"text","value":"10 featured listings/month"},{"key":"text","value":"Advanced analytics"},{"key":"text","value":"Verified + Professional badge"},{"key":"text","value":"Lead management dashboard"},{"key":"text","value":"Priority listing in agent directory"},{"key":"text","value":"Social media sharing tools"},{"key":"text","value":"Save searches & get alerts"}]', 0, 'published', NOW(), NOW()),

-- Agent Premium
('Agent Premium', 'For top-performing agents and small brokerages', 5999, @currency_id, 0, 150, NULL, NULL, 6, 'agent', 1, NULL, '[{"key":"text","value":"Everything in Professional"},{"key":"text","value":"Premium profile with video introduction"},{"key":"text","value":"Priority placement in search"},{"key":"text","value":"25 featured listings/month"},{"key":"text","value":"CRM tools (lead tracking, follow-ups)"},{"key":"text","value":"Multiple user access (3 team members)"},{"key":"text","value":"Export leads"},{"key":"text","value":"Dedicated support"},{"key":"text","value":"Performance analytics"},{"key":"text","value":"Custom domain for profile (optional)"}]', 0, 'published', NOW(), NOW()),

-- Owner Free
('Owner Free', 'For individual owners testing the platform', 0, @currency_id, 0, 1, NULL, NULL, 7, 'owner', 0, 30, '[{"key":"text","value":"Basic listing with photos (up to 5)"},{"key":"text","value":"Standard visibility"},{"key":"text","value":"Email notifications"},{"key":"text","value":"Basic property details"},{"key":"text","value":"Watermarked photos"}]', 0, 'published', NOW(), NOW()),

-- Owner Basic
('Owner Basic', 'For individual property owners', 299, @currency_id, 0, 1, NULL, NULL, 8, 'owner', 0, 90, '[{"key":"text","value":"Enhanced listing with photos (up to 15)"},{"key":"text","value":"Standard visibility"},{"key":"text","value":"Email + SMS notifications"},{"key":"text","value":"Detailed property information"},{"key":"text","value":"No watermarks"},{"key":"text","value":"Listing refresh (once)"}]', 0, 'published', NOW(), NOW()),

-- Owner Premium
('Owner Premium', 'For owners wanting maximum visibility', 999, @currency_id, 0, 1, NULL, NULL, 9, 'owner', 0, 180, '[{"key":"text","value":"Premium listing with photos (up to 30)"},{"key":"text","value":"Featured placement (30 days)"},{"key":"text","value":"Priority in search results"},{"key":"text","value":"Email + SMS + WhatsApp notifications"},{"key":"text","value":"Virtual tour support"},{"key":"text","value":"Video upload"},{"key":"text","value":"Unlimited listing refreshes"},{"key":"text","value":"Highlighted in similar properties"},{"key":"text","value":"Social media promotion"}]', 0, 'published', NOW(), NOW()),

-- Featured Listing Boost
('Featured Listing Boost', 'Highlight your listing at the top of search results for one week', 199, @currency_id, 0, 1, NULL, NULL, 10, 'addon', 0, 7, '[{"key":"text","value":"Top placement in search results"},{"key":"text","value":"Highlighted badge on listing"},{"key":"text","value":"7 days duration"}]', 0, 'published', NOW(), NOW()),

-- Homepage Banner
('Homepage Banner', 'Get your project/property featured on the homepage banner', 4999, @currency_id, 0, 1, NULL, NULL, 11, 'addon', 0, 7, '[{"key":"text","value":"Homepage banner placement"},{"key":"text","value":"Maximum visibility"},{"key":"text","value":"7 days duration"}]', 0, 'published', NOW(), NOW()),

-- Extra Listings (Agent)
('Extra Listings (Agent)', 'Add extra listings beyond your package limit', 99, @currency_id, 0, 1, NULL, NULL, 12, 'addon', 0, 30, '[{"key":"text","value":"1 additional listing"},{"key":"text","value":"For agents"},{"key":"text","value":"30 days duration"}]', 0, 'published', NOW(), NOW()),

-- Extra Listings (Builder)
('Extra Listings (Builder)', 'Add extra listings beyond your package limit', 49, @currency_id, 0, 1, NULL, NULL, 13, 'addon', 0, 30, '[{"key":"text","value":"1 additional listing"},{"key":"text","value":"For builders"},{"key":"text","value":"30 days duration"}]', 0, 'published', NOW(), NOW()),

-- Virtual Tour Creation
('Virtual Tour Creation', 'Professional virtual tour creation service', 1999, @currency_id, 0, 1, NULL, NULL, 14, 'addon', 0, NULL, '[{"key":"text","value":"Professional 360° virtual tour"},{"key":"text","value":"Embedded in your listing"},{"key":"text","value":"One-time service"}]', 0, 'published', NOW(), NOW()),

-- Professional Photography
('Professional Photography', 'Professional photo shoot service for your property', 2999, @currency_id, 0, 1, NULL, NULL, 15, 'addon', 0, NULL, '[{"key":"text","value":"Professional photographer visit"},{"key":"text","value":"High-quality edited photos"},{"key":"text","value":"Up to 30 photos delivered"},{"key":"text","value":"One-time service"}]', 0, 'published', NOW(), NOW());

-- Verify the packages were created
SELECT COUNT(*) as total_packages FROM re_packages;
SELECT name, package_type, price, number_of_listings FROM re_packages ORDER BY `order`;
