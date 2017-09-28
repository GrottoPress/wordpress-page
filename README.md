# WordPress Page

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
    $page = new Page();

    // Get page title
    echo $page->title();

    // Get page description
    echo $page->description();

    // Get page URL
    echo $page->URL('full');

    // Get page number
    echo $page->number();

    // Get page type
    print_r($page->type());

    // Check if page is single post
    if ($page->is('single')) {
        echo 'Single!';
    } else {
        echo 'Not single :(';
    }

    // Check if page is 'tutorial' custom post archive
    if ($page->is('post_type_archive', 'tutorial')) {
        echo 'Yay!!! Tutorials.';
    } else {
        echo 'Nope :(';
    }
