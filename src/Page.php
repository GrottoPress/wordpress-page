<?php
declare (strict_types = 1);

namespace GrottoPress\WordPress\Page;

class Page
{
    public function type(): array
    {
        if (!($pages = $this->types())) {
            return [];
        }

        $type = [];

        foreach ($pages as $page) {
            if ($this->is($page)) {
                $type[] = $page;
            }
        }

        return $type;
    }

    public function title(): string
    {
        if ($this->is('singular')) {
            return \single_post_title('', false);
        }

        if ($this->is('archive')) {
            return \get_the_archive_title();
        }

        if ($this->is('search')) {
            return \sprintf(
                \esc_html__('Search results: "%s"'),
                \get_search_query()
            );
        }

        if ($this->is('404')) {
            return \esc_html__('Not found');
        }

        return '';
    }

    public function description(): string
    {
        if ($this->is('singular')) {
            return \get_the_excerpt();
        }

        if ($this->is('archive')) {
            return \get_the_archive_description();
        }

        return '';
    }

    public function URL(string $type = ''): string
    {
        $parsed = \wp_parse_url(
            ($home_url = \home_url()).$_SERVER['REQUEST_URI']
        );

        $path = $parsed['path'] ?? '';
        $query = isset($parsed['query']) ? '?'.$parsed['query'] : '';

        $url = $home_url.$path;

        if ('full' === $type) {
            $url .= $query;
        }

        return \esc_url_raw($url);
    }

    public function number(): int
    {
        if (($number = \absint(\get_query_var('paged')))) {
            return $number;
        }

        return 1;
    }

    /**
     * Are we on a particular page type?
     */
    public function is(string $type, ...$args): bool
    {
        if (!\in_array($type, $this->types())) {
            return false;
        }

        global $pagenow;

        if ('login' === $type) {
            return ($pagenow === 'wp-login.php');
        }

        if ('register' === $type) {
            return ($pagenow === 'wp-signup.php');
        }

        $is_type = 'is_'.$type;

        if (!\is_callable($is_type)) {
            return false;
        }

        return $is_type(...$args);
    }

    private function types(): array
    {
        return [
            'home',
            'front_page',
            'single',
            'page',
            'page_template',
            'attachment',
            'singular',
            'author',
            'category',
            'day',
            'month',
            'year',
            'date',
            'post_type_archive',
            'tag',
            'tax',
            'archive',
            '404',
            'search',
            'paged',
            'embed',
            'customize_preview',
            'admin',
            'login',
            'register',
        ];
    }
}
