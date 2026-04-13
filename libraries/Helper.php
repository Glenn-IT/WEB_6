<?php 

function dd($data){
    echo "<pre>";
        var_dump($data);
    echo "</pre>";
}

function baseUrl($path = '') {
    // Get the base path from the script name
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $baseDir = str_replace('\\', '/', dirname($scriptName));
    
    // Remove trailing slash if exists
    $baseDir = rtrim($baseDir, '/');
    
    // Add leading slash to path if not empty and doesn't have one
    if (!empty($path) && $path[0] !== '/') {
        $path = '/' . $path;
    }
    
    return $baseDir . $path;
}

function assetUrl($path = '') {
    // For asset URLs, we can use the same logic as baseUrl
    // This ensures CSS, JS, images, etc. load correctly
    return baseUrl($path);
}

function getSegment(){
    $uri = $_SERVER['REQUEST_URI'];

    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }

    $uri = rawurldecode($uri);
    
    // Remove the base directory from URI if present
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $baseDir = str_replace('\\', '/', dirname($scriptName));
    if ($baseDir !== '/' && strpos($uri, $baseDir) === 0) {
        $uri = substr($uri, strlen($baseDir));
    }

    $uri = explode('/',$uri);

    return $uri;

}

function getRequestAll(){
    // Check request method
    $method = $_SERVER['REQUEST_METHOD'];
    $data = [];
    switch ($method) {
        case 'GET':
            $data = $_GET; // Handle GET requests
            break;
            
        case 'POST':
            $data = $_POST; // Handle POST requests
            $data = array_merge($data, $_FILES);
            break;

        case 'PUT':
            $input_data = file_get_contents('php://input'); // Handle PUT requests
            $data = json_decode($input_data, true); // Assuming JSON payload
            break;
            
        case 'DELETE':
            $input_data = file_get_contents('php://input'); // Handle DELETE requests
            $data = json_decode($input_data, true); // Assuming JSON payload
            break;
    }
   
    return $data;

}


function generateToken($length = 32) {
    // Generate random bytes
    $token = bin2hex(random_bytes($length / 2)); // Convert to hexadecimal format
    return $token;
}

function formatControllerName($string) {
    // Replace hyphens with spaces to create separate words
    $string = str_replace('-', ' ', $string);

    // Capitalize the first letter of each word
    $string = ucwords($string);

    // Remove spaces to merge the words back together
    $string = str_replace(' ', '', $string);

    return $string;
}

function sideBarDetails() {


    if($_SESSION["user_type"] == 2) {
        $componentPages = [
            'System Module' => [
                "dashboard" =>    [
                    "Title" => 'Dashboard',
                    "Description" => "Welcome to Dashboard",
                    "icon" => 'home',
                    'child' => []
                ],
                "messages" =>    [
                        "Title" => 'Messages',
                        "Description" => "Messages",
                        "icon" => 'user',
                        'child' => []
                ],
                "transaction-bidding" =>    [
                    "Title" => 'Bookings',
                    "Description" => "Bookings",
                    "icon" => 'calendar',
                    'child' => []
                   
                ],


                // "inventory" =>    [
                //     "Title" => 'Inventories',
                //     "Description" => "",
                //     "icon" => 'settings',
                //     'child' => [
                //         "inventory-report" =>    [
                //             "Title" => 'Inventory Report',
                //             "Description" => "Inventory Report",
                //             "icon" => 'list',
                //             'child' => []
                //         ],

                //         "inventory-stockin" =>    [
                //             "Title" => 'STOCK',
                //             "Description" => "STOCK",
                //             "icon" => 'list',
                //             'child' => []
                //         ],


                //     ]
                // ],


                

                // "item-master" =>    [
                //     "Title" => 'List of Items',
                //     "Description" => "List of Items",
                //     "icon" => 'list',
                //     'child' => []
                // ],

                

                // "brand-type" =>    [
                //     "Title" => 'Item Categories',
                //     "Description" => "",
                //     "icon" => 'settings',
                //     'child' => []
                // ],
                

  
                // "user-archive" =>    [
                //     "Title" => 'User Archive',
                //     "Description" => "Archive",
                //     "icon" => 'archive',
                //     'child' => []
                // ],

                // "customer-management" =>    [
                //     "Title" => 'Customer Management',
                //     "Description" => "Welcome to Customer Management",
                //     "icon" => 'user',
                //     'child' => []
                // ],
                
            ]
        
        ];

    } else {


        $componentPages = [
            'System Module' => [
                "dashboard" =>    [
                    "Title" => 'Dashboard',
                    "Description" => "Welcome to Dashboard",
                    "icon" => 'home',
                    'child' => []
                ],
                "messages" =>    [
                        "Title" => 'Messages',
                        "Description" => "Messages",
                        "icon" => 'user',
                        'child' => []
                ],
                "transaction" =>    [
                    "Title" => 'Transactions',
                    "Description" => "",
                    "icon" => 'file',
                    'child' => [
                        // "transaction-order" =>    [
                        //     "Title" => 'Orders',
                        //     "Description" => "Orders",
                        //     "icon" => 'list',
                        //     'child' => []
                        // ],

                        "transaction-bidding" =>    [
                            "Title" => 'Bookings',
                            "Description" => "Bidding",
                            "icon" => 'list',
                            'child' => []
                        ],


                    ]
                ],

                
                 "inventory-report" =>    [
                    "Title" => 'Reports',
                    "Description" => "Reports",
                    "icon" => 'list',
                    'child' => []
                ],

                
                "brand-type" =>    [
                    "Title" => 'Services',
                    "Description" => "",
                    "icon" => 'settings',
                    'child' => []
                ],
                

                "item-master" =>    [
                    "Title" => 'List of Sub-services',
                    "Description" => "List of Sub-services",
                    "icon" => 'list',
                    'child' => []
                ],


                "therapist" =>    [
                    "Title" => 'Therapist`s',
                    "Description" => "List of Therapist",
                    "icon" => 'heart',
                    'child' => []
                ],

                "banner" =>    [
                    "Title" => 'Banner',
                    "Description" => "Manage Homepage Banners",
                    "icon" => 'image',
                    'child' => []
                ],

                "promo" =>    [
                    "Title" => 'Promos',
                    "Description" => "Manage Promotions",
                    "icon" => 'tag',
                    'child' => []
                ],

                "about" =>    [
                    "Title" => 'About Page',
                    "Description" => "Manage About Us Content",
                    "icon" => 'info-alt',
                    'child' => []
                ],

            
                "user-management" =>    [
                    "Title" => 'User Management',
                    "Description" => "Welcome to User Management",
                    "icon" => 'user',
                    'child' => []
                ],

                // "user-archive" =>    [
                //     "Title" => 'User Archive',
                //     "Description" => "Archive",
                //     "icon" => 'archive',
                //     'child' => []
                // ],

                "customer-management" =>    [
                    "Title" => 'Customer Management',
                    "Description" => "Welcome to Customer Management",
                    "icon" => 'user',
                    'child' => []
                ],
                
            ]
        
        ];

    }

    return $componentPages;
}


function getDetailsSideBarActive() {
    $const_display_component_SEGMENT = isset(getSegment()[2]) ? getSegment()[2] : 'dashboard';

    $componentPages = sideBarDetails();

    // Access 'dashboard' details
    $dashboardDetails = isset($componentPages['System Module'][$const_display_component_SEGMENT])? $componentPages['System Module'][$const_display_component_SEGMENT] : [];

    return $dashboardDetails;


}



