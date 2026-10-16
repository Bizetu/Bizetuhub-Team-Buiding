<?php

return [
    'block_editor' => [
        'block_single_layout' => 'layouts.block',
        'repeaters' => [
            'domain' => [
                'title' => 'Domain',
                'trigger' => 'Add Domain',
                'component' => 'a17-block-domain'
            ],
            'evaluation_repeater' => [
                'title' => 'Evaluation',
                'trigger' => 'Add Evaluation',
                'component' => 'a17-block-evaluation_repeater',
                'max' => 10,
            ],
            'slideshow_repeater' => [
                'title' => 'Slider',
                'trigger' => 'Add Slider',
                'component' => 'a17-block-slideshow_repeater'
            ],
            'icalendar_repeater' => [
                'title' => 'Slider',
                'trigger' => 'Add Industry Calendar',
                'component' => 'a17-block-icalendar_repeater'
            ],
            'resource_events_repeater' => [
                'title' => 'Event',
                'icon' => 'b-membership',
                'component' => 'a17-block-resource_events_repeater',
            ],
            'resource_research_repeater' => [
                'title' => 'Reasearch & Publication',
                'icon' => 'download',
                'component' => 'a17-block-resource_research_repeater',
            ],
            'resource_external_links_repeater' => [
                'title' => 'External Link',
                'icon' => 'website',
                'component' => 'a17-block-resource_external_links_repeater',
            ],
            'resource_videos_repeater' => [
                'title' => 'Video',
                'icon' => 'video',
                'component' => 'a17-block-resource_videos_repeater',
            ],
            'resource_slideshow_repeater' => [
                'title' => 'Slideshow',
                'icon' => 'slideshow',
                'component' => 'a17-block-resource_slideshow_repeater',
            ],
            'pillars_repeater' => [
                'title' => 'Pillar',
                'icon' => 'video',
                'trigger' => 'Add a Pillar',
                'component' => 'a17-block-pillars_repeater',
            ],
            'esg_guidelines_repeater' => [
                'title' => 'ESG Guideline',
                'icon' => 'video',
                'trigger' => 'Add Guideline',
                'component' => 'a17-block-esg_guidelines_repeater',
            ],
            'about_repeater' => [
                'title' => 'ESG Guideline',
                'icon' => 'video',
                'trigger' => 'Add Guideline',
                'component' => 'a17-block-about_repeater',
            ],
            'panel' => [
                'title' => 'Panel',
                'trigger' => 'Add a Panel',
                'component' => 'a17-block-panel',
                'max' => 3,
            ],
            'course_grade_repeater' => [
                'title' => 'Course Assignment Type',
                'trigger' => 'Add Assignment Type',
                'component' => 'a17-block-course_grade_repeater',
                'max' => 5,
            ],
        ],

        'blocks' => [
            'slideshow' => [
                'title' => 'Slide Show Section',
                'icon' => 'slideshow',
                'component' => 'a17-block-slideshow',
            ],
            'icalendar' => [
                'title' => 'Industry Calendar Block',
                'icon' => 'text',
                'component' => 'a17-block-icalendar',
            ],
            'resource_events' => [
                'title' => 'Events',
                'icon' => 'b-membership',
                'component' => 'a17-block-resource_events',
            ],
            'resource_research' => [
                'title' => 'Reasearch & Publications',
                'icon' => 'download',
                'component' => 'a17-block-resource_research',
            ],
            'resource_external_links' => [
                'title' => 'External Links',
                'icon' => 'website',
                'component' => 'a17-block-resource_external_links',
            ],
            'resource_videos' => [
                'title' => 'Videos',
                'icon' => 'video',
                'component' => 'a17-block-resource_videos',
            ],
            'pillars' => [
                'title' => 'ESG Pillars',
                'icon' => 'text',
                'component' => 'a17-block-pillars',
            ],
            'esg_guidelines' => [
                'title' => 'ESG Guidelines',
                'icon' => 'text',
                'component' => 'a17-block-esg_guidelines',
            ],
            'about' => [
                'title' => 'ESG Guidelines',
                'icon' => 'text',
                'component' => 'a17-block-about',
            ],
            'resource_slideshow' => [
                'title' => 'Slide Show',
                'icon' => 'slideshow',
                'component' => 'a17-block-resource_slideshow',
            ],
            'image_next_to_text' => [
                'title' => 'Image next to text',
                'icon' => 'slideshow',
                'component' => 'a17-block-image_next_to_text',
            ],
            'rwandas_green_growth' => [
                'title' => 'Rwandas Green Growth',
                'icon' => 'slideshow',
                'component' => 'a17-block-rwandas_green_growth',
            ],
            'top_courses' => [
                'title' => 'Top Courses',
                'icon' => 'image',
                'component' => 'a17-block-top_courses',
            ],
            'courses' => [
                'title' => 'Courses',
                'icon' => 'image',
                'component' => 'a17-block-courses',
            ],
            'limited_courses' => [
                'title' => 'Limited Courses',
                'icon' => 'image',
                'component' => 'a17-block-limited_courses',
            ],
            'evaluation' => [
                'title' => 'Evaluation Block',
                'icon' => 'text',
                'component' => 'a17-block-evaluation',
            ],
            'branch_in_panel' => [
                'title' => 'Branches Our Progress',
                'icon' => 'image',
                'component' => 'a17-block-branch_in_panel',
            ],
            'our_progress_map' => [
                'title' => 'Our Progress Map',
                'icon' => 'image',
                'component' => 'a17-block-our_progress_map',
            ],
            'session_in_panel' => [
                'title' => 'Session Panels',
                'icon' => 'image',
                'component' => 'a17-block-session_in_panel',
            ],
            'course_grade' => [
                'title' => 'Course Grade Block',
                'icon' => 'text',
                'component' => 'a17-block-course_grade',
            ],
        ],

        'crops' => [
            'slide_image' => [
                'default' => [
                    [
                        'name' => 'default',
                        'ratio' => 16 / 9,
                    ],
                ],
                'mobile' => [
                    [
                        'name' => 'mobile',
                        'ratio' => 1,
                    ],
                ],
                'flexible' => [
                    [
                        'name' => 'free',
                        'ratio' => 0,
                    ],
                    [
                        'name' => 'landscape',
                        'ratio' => 16 / 9,
                    ],
                    [
                        'name' => 'portrait',
                        'ratio' => 3 / 5,
                    ],
                ],
            ],
            'event_cover_image' => [
                'default' => [
                    [
                        'name' => 'default',
                        'ratio' => 16 / 9,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
                'tablet' => [
                    [
                        'name' => 'tablet',
                        'ratio' => 4 / 3,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
                'mobile' => [
                    [
                        'name' => 'mobile',
                        'ratio' => 1,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
            ],
            'resource_slideshow_image' => [
                'default' => [
                    [
                        'name' => 'default',
                        'ratio' => 16 / 9,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
                'tablet' => [
                    [
                        'name' => 'tablet',
                        'ratio' => 4 / 3,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
                'mobile' => [
                    [
                        'name' => 'mobile',
                        'ratio' => 1,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
            ],
            'external_link_cover_image' => [
                'default' => [
                    [
                        'name' => 'default',
                        'ratio' => 7 / 2,
                    ],
                ],
                'tablet' => [
                    [
                        'name' => 'tablet',
                        'ratio' => 7 / 2,
                    ],
                ],
                'mobile' => [
                    [
                        'name' => 'mobile',
                        'ratio' => 7 / 2,
                    ],
                ],
            ],
            'publication_cover_image' => [
                'default' => [
                    [
                        'name' => 'default',
                        'ratio' => 16 / 9,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
                'tablet' => [
                    [
                        'name' => 'tablet',
                        'ratio' => 4 / 3,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
                'mobile' => [
                    [
                        'name' => 'mobile',
                        'ratio' => 1,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
                'potrait' => [
                    [
                        'name' => 'potrait',
                        'ratio' => 7 / 10,
                        'minValues' => [
                            'width' => 70,
                            'height' => 100,
                        ],
                    ],
                ],
            ],
            'resource_video_cover_image' => [
                'default' => [
                    [
                        'name' => 'default',
                        'ratio' => 16 / 9,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
                'tablet' => [
                    [
                        'name' => 'tablet',
                        'ratio' => 4 / 3,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
                'mobile' => [
                    [
                        'name' => 'mobile',
                        'ratio' => 1,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
            ],
            'image_beside' => [
                'default' => [
                    [
                        'name' => 'default',
                        'ratio' => 1,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
                'tablet' => [
                    [
                        'name' => 'tablet',
                        'ratio' => 1,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
                'mobile' => [
                    [
                        'name' => 'mobile',
                        'ratio' => 1,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
                'portrait' => [
                    [
                        'name' => 'portrait',
                        'ratio' => 1,
                        'minValues' => [
                            'width' => 70,
                            'height' => 100,
                        ],
                    ],
                ],
            ],
            'rwandas_green_growth_img' => [
                'default' => [
                    [
                        'name' => 'default',
                        'ratio' => 10/8,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
                'tablet' => [
                    [
                        'name' => 'tablet',
                        'ratio' => 10/8,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
                'mobile' => [
                    [
                        'name' => 'mobile',
                        'ratio' => 10/8,
                        'minValues' => [
                            'width' => 100,
                            'height' => 100,
                        ],
                    ],
                ],
                'portrait' => [
                    [
                        'name' => 'portrait',
                        'ratio' => 10/8,
                        'minValues' => [
                            'width' => 70,
                            'height' => 100,
                        ],
                    ],
                ],
            ],
            'pillar_image_icon' => [
                'default' => [
                    [
                        'name' => 'default',
                        'ratio' => 7 / 2,
                    ],
                ],
                'tablet' => [
                    [
                        'name' => 'tablet',
                        'ratio' => 7 / 2,
                    ],
                ],
                'mobile' => [
                    [
                        'name' => 'mobile',
                        'ratio' => 7 / 2,
                    ],
                ],
            ],

            'bestpractice_image_icon' => [
                'default' => [
                    [
                        'name' => 'default',
                        'ratio' => 7 / 2,
                    ],
                ],
                'tablet' => [
                    [
                        'name' => 'tablet',
                        'ratio' => 7 / 2,
                    ],
                ],
                'mobile' => [
                    [
                        'name' => 'mobile',
                        'ratio' => 7 / 2,
                    ],
                ],
            ],

            'about_our_mission_icon' => [
                'default' => [
                    [
                        'name' => 'default',
                        'ratio' => 7 / 2,
                    ],
                ],
                'tablet' => [
                    [
                        'name' => 'tablet',
                        'ratio' => 7 / 2,
                    ],
                ],
                'mobile' => [
                    [
                        'name' => 'mobile',
                        'ratio' => 7 / 2,
                    ],
                ],
            ],

            'about_our_vision_icon' => [
                'default' => [
                    [
                        'name' => 'default',
                        'ratio' => 7 / 2,
                    ],
                ],
                'tablet' => [
                    [
                        'name' => 'tablet',
                        'ratio' => 7 / 2,
                    ],
                ],
                'mobile' => [
                    [
                        'name' => 'mobile',
                        'ratio' => 7 / 2,
                    ],
                ],
            ],

            'about_our_values_icon' => [
                'default' => [
                    [
                        'name' => 'default',
                        'ratio' => 7 / 2,
                    ],
                ],
                'tablet' => [
                    [
                        'name' => 'tablet',
                        'ratio' => 7 / 2,
                    ],
                ],
                'mobile' => [
                    [
                        'name' => 'mobile',
                        'ratio' => 7 / 2,
                    ],
                ],
            ],

        ],
        'files' => [
            'publication_file',
            'resource_mp4_video',
        ]
    ],

    'file_library' => [
        'allowed_extensions' => ['pdf', 'doc', 'docx', 'mp4', 'jpg', 'jpeg', 'png'],
        'max_file_size' => 5000000000,
    ],

    'media_library' => [
        'allowed_extensions' => ['svg','jpg','jpeg','png', 'gif','webp','avif'],
    ],

    'enabled' => [
        'permissions-management' => true,
        'dashboard' => true,
    ],

    'permissions' => [
        'level' => \A17\Twill\Enums\PermissionLevel::LEVEL_ROLE,
        'modules' => [
            'menuTypes',
            'menus',
            'pages',
            'platformSettings',
            'companies',
            'branches',
            'domains',
            'departments',
            'jobRoles',
            'faqCategories',
            'faqs',
            'caseStudies',
            'evaluationOptions',
            'courseCategories',
            'courseTypes',
            'evaluations',
            'userEvaluations',
            'courses',
            'userLicenses',
            'completionReports',
            'partners',
            'dueDates',
            'serialNumbers',
            'surveys',
            'userSurveys',
        ],
    ],

    /* 'dashboard' => [
        'modules' => [
            'App\Models\Page' => [
                'name' => 'pages',
                'count' => true,
                'create' => false,
                'activity' => true,
                'draft' => true,
                'search' => true,
                'search_fields' => ['title', 'header_title']
            ],
            'App\Models\Department' => [
                'name' => 'departments',
                'count' => true,
                'create' => true,
                'search' => true,
            ],
            'App\Models\Company' => [
                'name' => 'companies',
                'count' => true,
                'create' => true,
                'search' => true,
            ],
            'App\Models\Branch' => [
                'name' => 'branches',
                'count' => true,
                'create' => true,
                'search' => true,
            ],
        ],
    ], */

    'users_table' => 'twill_users',
];
