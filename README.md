# Setter

## Description

*WordPress Page* is a simple library to get WordPress page elements.

Note that page as used here refers to page as in 'web page', NOT the page post type in WordPress.

## Usage

Install via composer:

`composer require grottopress/wordpress-page`

Instantiate a new WordPress page and use thus:

    <?php

    use GrottoPress\WordPress\Page\Page;

    // Instantiate
    $wp_page = new Page();

    // Get page title
    echo $wp_page->title();

    // Get page description
    echo $wp_page->description();

    // Get page type
    print_r( $wp_page->type() );

    // Check if page is single post
    if ( $wp_page->is( 'single' ) ) {
        echo 'Single!';
    } else {
        echo 'Not single :-)';
    }

    // Check if page is 'tutorial' custom post archive
    if ( $wp_page->is( 'post_type_archive', 'tutorial' ) ) {
        echo 'Yay!!! Tutorials.';
    } else {
        echo 'Nope :-)';
    }
