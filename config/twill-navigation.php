<?php

return [
    'pages' => [
        'title' => 'Website',
        'primary_navigation' => [
            'pages' => ['title' => 'Pages', 'module' => true],
            'menuItems' => ['title' => 'Menu', 'module' => true],
            'menuTypes' => ['title' => 'Menu type', 'module' => true],
        ],
    ],
    'homeSlides' => ['title' => 'Homepage sliders'],
    'services' => [
        'title' => 'Services',
        'primary_navigation' => [
            'services' => [
                'title' => 'Services',
                'module' => true,
            ],
            'serviceCategories' => [
                'title' => 'Service Categories',
                'module' => true,
            ],
        ],
    ],
    'events' => ['title' => 'Events'],
    'galleryItems' => ['title' => 'Gallery'],
    'bookings' => ['title' => 'Booking requests'],
    'enquiries' => [
        'title' => 'Contact',
        'primary_navigation' => [
            'enquiries' => ['title' => 'Contact messages', 'module' => true],
            'contactInterests' => ['title' => 'Interests', 'module' => true],
        ],
    ],
    'testimonials' => ['title' => 'Testimonials'],
    'insights' => ['title' => 'Insights'],
];
