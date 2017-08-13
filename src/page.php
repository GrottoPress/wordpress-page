<?php

/**
 * WordPress Page.
 *
 * Page as in 'web page', NOT the page post type in WordPress.
 *
 * @package GrottoPress\WordPress\Page
 * @since 0.1.0
 *
 * @author GrottoPress (https://www.grottopress.com)
 * @author N Atta Kus Adusei (https://twitter.com/akadusei)
 */

declare ( strict_types = 1 );

namespace GrottoPress\WordPress\Page;

if ( \defined( 'WPINC' ) ) :

/**
 * WordPress Page.
 *
 * @since 0.1.0
 */
class Page {
    /**
     * Get page type
     * 
     * @since 0.1.0
     * @access public
     * 
     * @return array Page type.
     */
    public function type(): array {
        $return = [];
        
        if ( ! ( $types = $this->types() ) ) {
            return $return;
        }
        
        foreach ( $types as $type ) {
            if ( $this->is( $type ) ) {
                $return[] = $type;
            }
        }
        
        return $return;
    }

    /**
     * Get page title
     * 
     * @since 0.1.0
     * @access public
     * 
     * @return string Page title
     */
    public function title(): string {
        if ( $this->is( 'singular' ) ) {
            return \single_post_title( '', false );
        }

        if ( $this->is( 'archive' ) ) {
            return \get_the_archive_title();
        }

        if ( $this->is( 'search' ) ) {
            return \sprintf( \esc_html__( 'Search results: "%s"', 'wordpress-page' ),
                \get_search_query() );
        }

        if ( $this->is( '404' ) ) {
            return \esc_html__( 'Not found', 'wordpress-page' );
        }

        return '';
    }

    /**
     * Get page description
     *
     * @since 0.1.0
     * @access public
     *
     * @return string Description.
     */
    public function description(): string {
        if ( $this->is( 'singular' ) ) {
            return \get_the_excerpt();
        }

        if ( $this->is( 'archive' ) ) {
            return \get_the_archive_description();
        }

        return '';
    }

    /**
     * Current page URL
     *
     * @var boolean $query_string Append query string?
     *
     * @since 0.1.0
     * @access public
     *
     * @return string URL of page we're currently on.
     */
    public function url( bool $query_string = false ): string {
        $home_url = \home_url();

        $parsed = \wp_parse_url( $home_url . $_SERVER['REQUEST_URI'] );

        $path = isset( $parsed['path'] ) ? $parsed['path'] : '';
        $query = isset( $parsed['query'] ) ? '?' . $parsed['query'] : '';
    
        $page_url = $home_url . $path;
    
        if ( $query_string ) {
            $page_url .= $query;
        }
    
        return \esc_url_raw( $page_url );
    }

    /**
     * Are we on a particular page type?
     * 
     * @var string $type Page name/slug
     * 
     * @since Jentil 0.1.0
     * @access public
     *
     * @return boolean Whether or not current page is of a given type.
     */
    public function is( string $type ): bool {
        if ( ! \in_array( $type, $this->types() ) ) {
            return false;
        }

        global $pagenow;

        if ( 'login' == $type ) {
            return ( $pagenow === 'wp-login.php' );
        }

        if ( 'register' == $type ) {
            return ( $pagenow === 'wp-signup.php' );
        }

        $is_type = 'is_' . $type;

        if ( ! \is_callable( $is_type ) ) {
            return false;
        }

        $args = \func_get_args();
        \array_shift( $args );
        
        return \call_user_func_array( $is_type, $args );
    }

    /**
     * All page types
     * 
     * @since 0.1.0
     * @access protected
     *
     * @return array page types
     */
    protected function types(): array {
        return [
            'home',
            'front_page',
            'single',
            'page',
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
            'embed',
            'customize_preview',
            'admin',
            'login',
            'register',
        ];
    }
}

endif;
