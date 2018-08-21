# WordPress Page

Get current page attributes in WordPress.

## Usage

Install via composer:

```bash
composer require grottopress/wordpress-page
```

Use thus:

```php
<?php
declare (strict_types = 1);

use GrottoPress\WordPress\Page;

// Instantiate
$page = new Page();

// `$page` now represents the current page (where it was instantiated)

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
```
