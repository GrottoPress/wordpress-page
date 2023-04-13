# WordPress Page

Get current page attributes in WordPress.

## Installation

Install via composer:

```bash
composer require grottopress/wordpress-page
```

## Usage

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

## Development

Run tests with `composer run test`.

## Contributing

1. [Fork it](https://github.com/GrottoPress/wordpress-page/fork)
1. Switch to the `master` branch: `git checkout master`
1. Create your feature branch: `git checkout -b my-new-feature`
1. Make your changes, updating changelog and documentation as appropriate.
1. Commit your changes: `git commit`
1. Push to the branch: `git push origin my-new-feature`
1. Submit a new *Pull Request* against the `GrottoPress:master` branch.
