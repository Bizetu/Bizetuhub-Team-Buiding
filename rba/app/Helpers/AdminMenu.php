<?php

if (!function_exists('adminMenu')) {
    function adminMenu()
    {
        return [
            'content' => [
                'title' => 'Content',
                'route' => 'twill.pages.index',
                'primary_navigation' => [
                    'pages' => [
                        'title' => 'Pages',
                        'module' => true,
                    ],
                    'menus' => [
                        'title' => 'Menus',
                        'route' => 'twill.menus.index',
                        'secondary_navigation' => [
                            'menus' => [
                                'title' => 'Menus',
                                'module' => true,
                            ],
                            'menuTypes' => [
                                'title' => 'Menu Types',
                                'module' => true,
                            ],
                        ],
                    ],
                    'faqs' => [
                        'title' => 'Faqs',
                        'route' => 'twill.faqs.index',
                        'secondary_navigation' => [
                            'faqs' => [
                                'title' => 'Faqs',
                                'module' => true,
                            ],
                            'faqCategories' => [
                                'title' => 'Faq Categories',
                                'module' => true,
                            ],
                        ],
                    ],
                    'platformSettings' => [
                        'title' => 'Platform Settings',
                        'module' => true,
                    ],
                    'partners' => [
                        'title' => 'Partners',
                        'module' => true,
                    ],
                ],
            ],
            'courses' => [
                'title' => 'Courses',
                'route' => 'twill.courses.index',
                'primary_navigation' => [
                    'courses' => [
                        'title' => 'Courses',
                        'module' => true,
                    ],
                    'courseCategories' => [
                        'title' => 'Course Categories',
                        'module' => true,
                    ],
                    'courseTypes' => [
                        'title' => 'Course Types',
                        'module' => true,
                    ],
                ],
            ],
            'reports' => [
                'title' => 'Reports',
                'route' => 'twill.graph.dashboard',
                'primary_navigation' => [
                    'dashboard' => [
                        'title' => 'Dashboard',
                        'route' => 'twill.graph.dashboard',
                    ],
                    'completionReports' => [
                        'title' => 'Course Completions',
                        'module' => true,
                    ],
                    'userLicenses' => [
                        'title' => 'Course Enrollments',
                        'module' => true,
                    ],
                    'company' => [
                        'title' => 'Company Completion',
                        'route' => 'twill.subsidiaryCompletion',
                    ],
                    'courseAverages' => [
                        'title' => 'Average Score per Module',
                        'route' => 'twill.courseAverage',
                    ],
                    'jobRoleCompletions' => [
                        'title' => 'Uptake per Job Role',
                        'route' => 'twill.jobRoleCompletions',
                    ],
                ],
            ],
            'dueDates' => [
                'title' => 'Training Calendar',
                'module' => true,
            ],
            'companies' => [
                'title' => 'Institutions',
                'route' => 'twill.companies.index',
                'primary_navigation' => [
                    'companies' => [
                        'title' => 'Companies',
                        'module' => true,
                    ],
                    'domains' => [
                        'title' => 'Domains',
                        'module' => true,
                    ],
                    'branches' => [
                        'title' => 'Branches',
                        'module' => true,
                    ],
                    'departments' => [
                        'title' => 'Departments',
                        'module' => true,
                    ],
                    'jobRoles' => [
                        'title' => 'Job Roles',
                        'module' => true,
                    ],
                ],
            ],
            'messages' => [
                'title' => 'Messages',
                'module' => true,
            ],
            'serialNumbers' => [
                'title' => 'Certified Learners',
                'module' => true,
            ],
            'evaluations' => [
                'title' => 'Evaluations',
                'route' => 'twill.userEvaluations.index',
                'primary_navigation' => [
                    'userEvaluations' => [
                        'title' => 'User Evaluations',
                        'module' => true,
                    ],
                    'evaluations' => [
                        'title' => 'Evaluations',
                        'module' => true,
                    ],
                    'evaluationOptions' => [
                        'title' => 'Evaluation Options',
                        'module' => true,
                    ],
                ],
            ],
            'surveys' => [
                'title' => 'Surveys',
                'route' => 'twill.userSurveys.index',
                'primary_navigation' => [
                    'userSurveys' => [
                        'title' => 'Survey Responses',
                        'module' => true,
                    ],
                    'surveys' => [
                        'title' => 'Create Surveys',
                        'module' => true,
                    ],
                ]
            ],
        ];
    }
}

if (!function_exists('companyHRMenu')) {
    function companyHRMenu()
    {
        return [
            'reports' => [
                'title' => 'Reports',
                'route' => 'twill.graph.dashboard',
                'primary_navigation' => [
                    'dashboard' => [
                        'title' => 'Dashboard',
                        'route' => 'twill.graph.dashboard',
                    ],
                    'completionReports' => [
                        'title' => 'Course Completions',
                        'module' => true,
                    ],
                    'userLicenses' => [
                        'title' => 'Course Enrollments',
                        'module' => true,
                    ],
                    'courseAverages' => [
                        'title' => 'Average Score per Module',
                        'route' => 'twill.courseAverage',
                    ],
                    'jobRoleCompletions' => [
                        'title' => 'Uptake per Job Role',
                        'route' => 'twill.jobRoleCompletions',
                    ],
                ],
            ],
            'dueDates' => [
                'title' => 'Training Calendar',
                'module' => true,
            ],
            'serialNumbers' => [
                'title' => 'Certified Learners',
                'module' => true,
            ],
            'evaluations' => [
                'title' => 'Evaluations',
                'route' => 'twill.userEvaluations.index',
                'primary_navigation' => [
                    'userEvaluations' => [
                        'title' => 'User Evaluations',
                        'module' => true,
                    ],
                    'evaluations' => [
                        'title' => 'Evaluations',
                        'module' => true,
                    ],
                ],
            ],
            'surveys' => [
                'title' => 'Surveys',
                'route' => 'twill.userSurveys.index',
                'primary_navigation' => [
                    'userSurveys' => [
                        'title' => 'Survey Responses',
                        'module' => true,
                    ],
                    'surveys' => [
                        'title' => 'Create Company Surveys',
                        'module' => true,
                    ],
                ]
            ],
        ];
    }
}

if (!function_exists('adminMenuHelper')) {
    function adminMenuHelper()
    {
        if (auth()->guard('twill_users')->check()) {
            $user = auth()->guard('twill_users')->user();

            if ($user->role_value === "SUPERADMIN" || $user->is_superadmin === 1 || $user->role_id === 1) {
                return adminMenu();
            }
            if ($user->role_value === "Company HR" || $user->is_company_admin === 1 || $user->role_id === 3) {
                return companyHRMenu();
            }
            return [];
        }

        return [];
    }
}
