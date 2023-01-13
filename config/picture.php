<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Image upload path
	|--------------------------------------------------------------------------
	|
	| Path used for uploading images.
	|
	*/
	
    'user_path' => 'assets/pictures/user',
    'user_thumbs' => array(
        'small' => array( 'w' => 75, 'h' => 75 ),
        'medium' => array( 'w' => 150, 'h' => 150 )
	),
	
    'brand_path' => 'assets/pictures/brand',
    'brand_thumbs' => array(
        'small' => array( 'w' => 75, 'h' => 75 ),
        'medium' => array( 'w' => 150, 'h' => 150 )
    ),
    
    'client_path' => 'assets/pictures/client',
    'client_thumbs' => array(
        'small' => array( 'w' => 75, 'h' => 75 ),
        'medium' => array( 'w' => 150, 'h' => 150 )
    ),

    'product_path' => 'assets/pictures/product',
    'product_thumbs' => array(
        'small' => array( 'w' => 150, 'h' => 150 ),
        'medium' => array( 'w' => 300, 'h' => 300 ),
        'big' => array( 'w' => 600, 'h' => 600 )
    ),
	
    'category_path' => 'assets/pictures/category',
    'category_thumbs' => array(
        'small' => array( 'w' => 300, 'h' => 438 ),
        'medium' => array( 'w' => 600, 'h' => 877 ),
        'big' => array( 'w' => 1200, 'h' => 175 )
    ),
	
    'gallery_path' => 'assets/photos/gallery',
	'gallery_variations' => array(
		'original' => array( 'w' => null, 'h' => null ),
        'small' => array( 'w' => 150, 'h' => 150 ),
        'medium' => array( 'w' => 300, 'h' => 300 ),
        'big' => array( 'w' => 600, 'h' => 600 )
	),
    
    'action_path' => 'assets/pictures/action',
    'action_thumbs' => array(
        'small' => array( 'w' => 150, 'h' => 150 ),
        'medium' => array( 'w' => 300, 'h' => 300 ),
        'big' => array( 'w' => 1200, 'h' => 675 )
    ),
);
