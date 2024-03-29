<?php
declare (strict_types = 1);

namespace GrottoPress\WordPress;

class Page
{
    /**
     * @return string[]
     */
    public function type(): array
    {
        return \array_filter($this->types(), function (string $page): bool {
            return $this->is($page);
        });
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
                \esc_html__('Search results: "%s"', 'grotto-wp-page'),
                \get_search_query()
            );
        }

        if ($this->is('404')) {
            return \esc_html__('Not found', 'grotto-wp-page');
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

        $is_type = "is_{$type}";

        if (!\is_callable($is_type)) {
            return false;
        }

        return $is_type(...$args);
    }

    /**
     * @return string[]
     */
    protected function types(): array
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
            'privacy_policy',
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
            'feed',
        ];
    }
}
