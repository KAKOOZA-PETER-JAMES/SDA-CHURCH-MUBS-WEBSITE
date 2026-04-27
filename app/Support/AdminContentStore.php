<?php

namespace App\Support;

class AdminContentStore
{
    public static function path(): string
    {
        return storage_path('app/admin_content.json');
    }

    public static function defaults(): array
    {
        return [
            'pastor' => [
                'name' => 'Pastor John',
                'photo' => 'PRIME.jpg',
                'phone' => '+255700000000',
                'whatsapp' => '+255700000000',
                'email' => 'pastor@mubssdachurch.org',
            ],
            'pastors_since_inception' => [
                [
                    'name' => 'Gibson Nkosi',
                    'years' => '1995-2000',
                    'photo' => 'https://unitedafricansda.org/wp-content/uploads/brizy/imgs/Gibson-Nkosi-177x237x0x0x177x156x1683844487.jpg',
                ],
                [
                    'name' => 'Gilbert Jacob',
                    'years' => '2000-2010',
                    'photo' => 'https://unitedafricansda.org/wp-content/uploads/brizy/imgs/Gilbert-Jacob-287x191x50x11x180x142x1683844276.jpg',
                ],
                [
                    'name' => 'Juneroy Nugent',
                    'years' => '2010-2018',
                    'photo' => 'https://unitedafricansda.org/wp-content/uploads/brizy/imgs/Juneroy-Nugent-226x366x0x21x178x156x1683844129.jpg',
                ],
                [
                    'name' => 'Emmanuel Abar',
                    'years' => '2019-2026',
                    'photo' => 'https://unitedafricansda.org/wp-content/uploads/brizy/imgs/Portrait-Photo-Pr-192x249x19x26x173x171x1684212410.jpg',
                ],
                [
                    'name' => 'Noel Lazarus',
                    'years' => '2026-Present',
                    'photo' => '',
                ],
            ],
            'association_previous_executives' => [
                [
                    'years' => '1995-2000',
                    'name' => 'KAKOOZA PETER',
                    'photo' => 'S1.jpg',
                    'executives' => [
                        ['role' => 'President', 'name' => 'KAKOOZA PETER', 'photo' => 'S1.jpg'],
                        ['role' => 'Vice President', 'name' => 'RUTH NAMATA', 'photo' => 'S2.jpg'],
                        ['role' => 'Secretary', 'name' => 'PAULINE KIPKOECH', 'photo' => 'adventist world radio.png'],
                        ['role' => 'Treasurer', 'name' => 'JOSEPH MUSHI', 'photo' => 'sda.png'],
                        ['role' => 'Chaplain', 'name' => 'SAMUEL KATO', 'photo' => '4.jpg'],
                    ],
                ],
                [
                    'years' => '2010-2018',
                    'name' => 'JUNEROY NUGENT',
                    'photo' => 'https://unitedafricansda.org/wp-content/uploads/brizy/imgs/Juneroy-Nugent-226x366x0x21x178x156x1683844129.jpg',
                    'executives' => [
                        ['role' => 'President', 'name' => 'JUNEROY NUGENT', 'photo' => 'https://unitedafricansda.org/wp-content/uploads/brizy/imgs/Juneroy-Nugent-226x366x0x21x178x156x1683844129.jpg'],
                        ['role' => 'Vice President', 'name' => 'EMMANUEL ABAR', 'photo' => 'S2.jpg'],
                        ['role' => 'Secretary', 'name' => 'ESTHER MASSAWE', 'photo' => 'adventist world radio.png'],
                        ['role' => 'Treasurer', 'name' => 'DANIEL MAGESA', 'photo' => 'sda.png'],
                        ['role' => 'Worship Coordinator', 'name' => 'MIRIAM RAPHAEL', 'photo' => '6.png'],
                    ],
                ],
                [
                    'years' => '2026-PRESENT',
                    'name' => 'KUSASIRA CLINTON SEBASTIAN',
                    'photo' => 'join.jpg',
                    'executives' => [
                        ['role' => 'President', 'name' => 'KUSASIRA CLINTON SEBASTIAN', 'photo' => 'join.jpg'],
                        ['role' => 'Vice President', 'name' => 'GRACE NSUBUGA', 'photo' => 'S2.jpg'],
                        ['role' => 'Secretary', 'name' => 'ENOCK TUSUBIRA', 'photo' => 'adventist world radio.png'],
                        ['role' => 'Treasurer', 'name' => 'LYDIA MBISE', 'photo' => 'sda.png'],
                        ['role' => 'Programs Director', 'name' => 'REHEMA PAUL', 'photo' => '4.jpg'],
                    ],
                ],
            ],
            'events' => [
                [
                    'month' => 'January',
                    'title' => 'Week of Prayer Launch',
                    'date_range' => 'January 12 - January 18',
                    'department' => 'Spiritual Nurture',
                    'details' => 'Special prayer meetings and devotion emphasis to begin the year in spiritual revival.',
                ],
                [
                    'month' => 'March',
                    'title' => 'Community Service Sabbath',
                    'date_range' => 'March 22',
                    'department' => 'Community Outreach',
                    'details' => 'Members engage in neighborhood support ministry and practical service projects.',
                ],
            ],
            'event_media' => [
                [
                    'category' => 'evangelism-campaign',
                    'section' => 'story',
                    'title' => 'Campaign Opening Story',
                    'description' => 'A testimony from the opening day of evangelism campaign.',
                    'media_url' => '',
                    'thumbnail' => 'S1.jpg',
                ],
                [
                    'category' => 'community-outreach',
                    'section' => 'gallery',
                    'title' => 'Outreach Highlights',
                    'description' => 'Community visits and practical ministry moments.',
                    'media_url' => '',
                    'thumbnail' => 'S2.jpg',
                ],
                [
                    'category' => 'social-events',
                    'section' => 'videos',
                    'title' => 'Fellowship Video',
                    'description' => 'A short video clip from church fellowship event.',
                    'media_url' => 'https://www.youtube.com/@MUSDABS',
                    'thumbnail' => '4.jpg',
                ],
            ],
            'hero_slides' => [
                [
                    'title' => 'Welcome to SDA CHURCH MUBS',
                    'subtitle' => 'Experience faith, fellowship, and growth together every Sabbath and beyond.',
                    'image' => 'join.jpg',
                    'link' => '',
                    'text_color' => '#ffffff',
                ],
                [
                    'title' => 'Serve & Connect',
                    'subtitle' => 'Join a ministry or department and make a difference in your community.',
                    'image' => 'uploads/departments/dept-20260310221915-f4e3ba91.jpg',
                    'link' => '',
                    'text_color' => '#ffffff',
                ],
                [
                    'title' => 'Grow in Christ',
                    'subtitle' => 'Explore Bible study, prayer, and discipleship resources for all ages.',
                    'image' => 'uploads/departments/dept-20260310221915-b60866df.jpg',
                    'link' => '',
                    'text_color' => '#ffffff',
                ],
                [
                    'title' => 'All Are Welcome',
                    'subtitle' => 'Find hope, purpose, and belonging—no matter your background or journey.',
                    'image' => '13.JPG',
                    'link' => '',
                    'text_color' => '#ffffff',
                ],
            ],
            'updates' => [
                [
                    'month' => 'January',
                    'title' => "Let's Get Healthier Together",
                    'date_range' => 'January 1 - March 31',
                    'department' => 'Health Ministry',
                    'details' => 'A church-wide wellness initiative focused on nutrition, exercise, and spiritual health habits.',
                ],
                [
                    'month' => 'March',
                    'title' => 'Youth Bible Weekend',
                    'date_range' => 'March 20 - March 22',
                    'department' => 'Youth Department',
                    'details' => 'Weekend fellowship with Bible study, worship, and mentorship activities.',
                ],
            ],
            'upcoming_sabbaths' => [
                [
                    'text' => 'January 18: Religious Liberty Day',
                    'media_url' => '',
                ],
                [
                    'text' => 'February 1: Reach the World: Personal Outreach',
                    'media_url' => '',
                ],
                [
                    'text' => 'February 8-15: Christian Home and Marriage Week | Resources',
                    'media_url' => '',
                ],
                [
                    'text' => 'March 1: Women’s Day of Prayer | Resources',
                    'media_url' => '',
                ],
            ],
            'daily_communication' => [
                [
                    'text' => 'Morning devotion and prayer call starts at 5:30 AM. Fellowship reminders are shared on ministry channels.',
                    'media_url' => '',
                ],
                [
                    'text' => 'For urgent announcements, contact your department leader or church clerk before 7:00 PM.',
                    'media_url' => '',
                ],
            ],
            'family_calendar_activities' => [
                [
                    'date' => '',
                    'day' => 'Friday',
                    'area' => 'Jordan',
                    'activity' => 'Serving in the kitchen and related activities. Please keep time and work together.',
                    'time' => '',
                ],
                [
                    'date' => '',
                    'day' => 'Friday',
                    'area' => 'Jericho',
                    'activity' => 'Serving in the kitchen and related activities. Please keep time and work together.',
                    'time' => '',
                ],
                [
                    'date' => '',
                    'day' => 'Friday',
                    'area' => 'Jerusalem',
                    'activity' => 'Serving in the kitchen and related activities. Please keep time and work together.',
                    'time' => '',
                ],
                [
                    'date' => '',
                    'day' => 'Friday',
                    'area' => 'Hebron',
                    'activity' => 'Serving in the kitchen and related activities. Please keep time and work together.',
                    'time' => '',
                ],
            ],
            'departments' => [
                'church_board' => [
                    [
                        'name' => 'Children’s Ministries',
                        'image' => '4.jpg',
                        'intro' => 'Children ministry nurtures faith and discipleship for young members.',
                        'department_introduction' => 'Children ministry nurtures faith and discipleship for young members.',
                        'department_head_name' => 'Pr. Samuel Kato',
                        'department_head_photo' => '4.jpg',
                        'secretary_name' => 'Jemima Odhiambo',
                        'secretary_photo' => '4.jpg',
                        'explore_url' => 'https://children.adventist.org',
                        'details' => 'Sabbath programs and child discipleship support.',
                        'pastor_name' => 'Pr. Samuel Kato',
                        'pastor_phone' => '+256700000111',
                        'pastor_email' => 'children@mubssdachurch.org',
                        'pastor_info' => 'Coordinates child discipleship and family support initiatives.',
                    ],
                    [
                        'name' => 'Communication',
                        'image' => 'adventist world radio.png',
                        'intro' => 'Communication ministry supports church messaging and media witness.',
                        'department_introduction' => 'Communication ministry supports church messaging and media witness.',
                        'department_head_name' => 'Pr. Garry Nsubuga',
                        'department_head_photo' => 'adventist world radio.png',
                        'secretary_name' => 'Office Secretary',
                        'secretary_photo' => 'adventist world radio.png',
                        'explore_url' => 'https://communication.adventist.org',
                        'details' => 'Church messaging, media publishing, and announcements.',
                        'pastor_name' => 'Pr. Grace Nansubuga',
                        'pastor_phone' => '+256700000222',
                        'pastor_email' => 'communication@mubssdachurch.org',
                        'pastor_info' => 'Leads church communication strategy and digital outreach.',
                    ],
                ],
                'association' => [
                    [
                        'name' => 'Family Ministries',
                        'image' => 'S1.jpg',
                        'intro' => 'Family ministries strengthens homes through practical and biblical guidance.',
                        'department_introduction' => 'Family ministries strengthens homes through practical and biblical guidance.',
                        'department_head_name' => 'Pr. Daniel Ssenfuka',
                        'department_head_photo' => 'S1.jpg',
                        'secretary_name' => 'Pauline Kipkoech',
                        'secretary_photo' => 'S1.jpg',
                        'explore_url' => 'https://family.adventist.org',
                        'details' => 'Marriage, parenting, and home discipleship enrichment.',
                        'pastor_name' => 'Pr. Daniel Ssenfuka',
                        'pastor_phone' => '+256700000333',
                        'pastor_email' => 'family@mubssdachurch.org',
                        'pastor_info' => 'Supports couples, parents, and family faith formation.',
                    ],
                    [
                        'name' => 'Health Ministries',
                        'image' => 'S2.jpg',
                        'intro' => 'Health ministries promotes whole-person wellness and prevention.',
                        'department_introduction' => 'Health ministries promotes whole-person wellness and prevention.',
                        'department_head_name' => 'Pr. Ruth Namata',
                        'department_head_photo' => 'S2.jpg',
                        'secretary_name' => 'Office Secretary',
                        'secretary_photo' => 'S2.jpg',
                        'explore_url' => 'https://health.adventist.org',
                        'details' => 'Lifestyle education and community wellness outreach.',
                        'pastor_name' => 'Pr. Ruth Namata',
                        'pastor_phone' => '+256700000444',
                        'pastor_email' => 'health@mubssdachurch.org',
                        'pastor_info' => 'Coordinates preventive health and wellness ministries.',
                    ],
                ],
                'church_families' => [
                    [
                        'name' => 'Jericho',
                        'image' => '6.png',
                        'intro' => 'Family visitation, care groups, and prayer support.',
                        'secretary_name' => 'Sis. Rehema Paul',
                        'explore_url' => '',
                        'family_head_name' => 'Bro. Daniel Magesa',
                        'family_secretary_name' => 'Sis. Rehema Paul',
                        'family_spiritual_leader' => 'Bro. Samuel Kato',
                        'family_financial_mobiliser' => 'Sis. Lydia Mbise',
                        'family_social_wellbeing_leader' => 'Sis. Naomi Peter',
                        'details' => 'Family visitation, care groups, and prayer support.',
                        'pastor_name' => 'Pr. Peter Kakooza',
                        'pastor_phone' => '0709061019',
                        'pastor_email' => 'peterkakooza968@gmail.com',
                        'pastor_info' => 'Coordinates family care, mentorship, and home fellowship support.',
                    ],
                    [
                        'name' => 'Jordan',
                        'image' => 'sda.png',
                        'intro' => 'Community nurture and member fellowship support.',
                        'secretary_name' => 'Sis. Esther Massawe',
                        'explore_url' => '',
                        'family_head_name' => 'Bro. Joseph Mushi',
                        'family_secretary_name' => 'Sis. Esther Massawe',
                        'family_spiritual_leader' => 'Bro. Jackson Mrema',
                        'family_financial_mobiliser' => 'Sis. Grace Mkude',
                        'family_social_wellbeing_leader' => 'Sis. Miriam Raphael',
                        'details' => 'Community nurture and member fellowship support.',
                        'pastor_name' => 'Pr. John Sserugga',
                        'pastor_phone' => '+256700000555',
                        'pastor_email' => 'jordan@mubssdachurch.org',
                        'pastor_info' => 'Leads nurture ministries and family visitation routines.',
                    ],
                ],
                'church_board_leaders' => [
                    [
                        'role' => 'Pastor',
                        'name' => 'Pr. Samuel Kato',
                        'message' => 'Serving with prayer, teaching, and pastoral care for every family.',
                        'image' => '4.jpg',
                    ],
                    [
                        'role' => 'Chief Elder',
                        'name' => 'Bro. Daniel Magesa',
                        'message' => 'Let us stand together in mission and faithfulness.',
                        'image' => 'S1.jpg',
                    ],
                    [
                        'role' => 'Church Clerk',
                        'name' => 'Sis. Rehema Paul',
                        'message' => 'Order, accountability, and service for the growth of the church.',
                        'image' => 'S2.jpg',
                    ],
                ],
                'association_leaders' => [
                    [
                        'role' => 'President',
                        'name' => 'Pr. Daniel Ssenfuka',
                        'message' => 'Guiding the association in mission and discipleship.',
                        'image' => 'S1.jpg',
                    ],
                    [
                        'role' => 'Vice President',
                        'name' => 'Pr. Ruth Namata',
                        'message' => 'Supporting every district for stronger ministry impact.',
                        'image' => 'S2.jpg',
                    ],
                    [
                        'role' => 'Secretary',
                        'name' => 'Pauline Kipkoech',
                        'message' => 'Coordinating leadership communication and service delivery.',
                        'image' => 'adventist world radio.png',
                    ],
                ],
            ],
        ];
    }

    public static function get(): array
    {
        $path = self::path();

        if (!file_exists($path)) {
            return self::defaults();
        }

        $decoded = json_decode((string) file_get_contents($path), true);

        if (!is_array($decoded)) {
            return self::defaults();
        }

        return array_replace_recursive(self::defaults(), $decoded);
    }

    public static function save(array $data): void
    {
        $path = self::path();
        $dir = dirname($path);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

