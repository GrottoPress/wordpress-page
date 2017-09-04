<?php

/**
 * WordPress Page.
 *
 * Page as used here refers to 'web page',
 * NOT the page post type in WordPress.
 *
 * @package GrottoPress\WordPress\Page
 * @since 0.1.0
 *
 * @author GrottoPress <info@grottopress.com>
 * @author N Atta Kus Adusei
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
     * Type
     *
     * @since 0.1.0
     * @access protected
     * 
     * @var array $type Page type.
     */
    protected $type = null;

    /**
     * Title
     *
     * @since 0.1.0
     * @access protected
     * 
     * @var string $title Page title.
     */
    protected $title = null;

    /**
     * Description
     *
     * @since 0.1.0
     * @access protected
     * 
     * @var string $description Page description.
     */
    protected $description = null;

    /**
     * URL
     *
     * @since 0.1.0
     * @access protected
     * 
     * @var string $url Page URL.
     */
    protected $url = null;

    /**
     * Number
     *
     * @since 0.1.0
     * @access protected
     * 
     * @var int $number Current page number.
     */
    protected $number = null;

    /**
     * Get page type
     * 
     * @since 0.1.0
     * @access public
     * 
     * @return array Page type.
     */
    public function type(): array {
        if ( null === $this->type ) {
            if ( ! ( $types = $this->types() ) ) {
                return [];
            }

            foreach ( $types as $type ) {
                if ( $this->is( $type ) ) {
                    $this->type[] = $type;
                }
            }
        }

        return $this->type;
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
        if ( null === $this->title ) {
            $this->title = '';

            if ( $this->is( 'singular' ) ) {
                $this->title = \single_post_title( '', false );
            } elseif ( $this->is( 'archive' ) ) {
                $this->title = \get_the_archive_title();
            } elseif ( $this->is( 'search' ) ) {
                $this->title = \sprintf( \esc_html__( 'Search results: "%s"', 'wordpress-page' ), \get_search_query() );
            } elseif ( $this->is( '404' ) ) {
                $this->title = \esc_html__( 'Not found', 'wordpress-page' );
            }
        }

        return $this->title;
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
        if ( null === $this->description ) {
            $this->description = '';

            if ( $this->is( 'singular' ) ) {
                $this->description = \get_the_excerpt();
            } elseif ( $this->is( 'archive' ) ) {
                $this->description = \get_the_archive_description();
            }
        }

        return $this->description;
    }

    /**
     * Current page URL
     *
     * @param boolean $query_string Append query string?
     *
     * @since 0.1.0
     * @access public
     *
     * @return string URL of page we're currently on.
     */
    public function url( bool $query_string = false ): string {
        if ( null === $this->url ) {
            $parsed = \wp_parse_url( ( $home_url = \home_url() ) . $_SERVER['REQUEST_URI'] );

            $path = $parsed['path'] ?? '';
            $query = isset( $parsed['query'] ) ? '?' . $parsed['query'] : '';
        
            $this->url = $home_url . $path;
        
            if ( $query_string ) {
                $this->url .= $query;
            }

            $this->url = \esc_url_raw( $this->url );
        }
    
        return $this->url;
    }

    /**
     * Current page number
     *
     * @since 0.1.0
     * @access public
     *
     * @return int Current page number.
     */
    public function number(): int {
        if ( null === $this->number ) {
            if ( ( $number = absint( \get_query_var( 'paged' ) ) ) ) {
                $this->number = $number;
            } else {
                $this->number = 1;
            }
        }
    
        return $this->number;
    }

    /**
     * Are we on a particular page type?
     * 
     * @param string $type Page name/slug
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
            'paged',
            'embed',
            'customize_preview',
            'admin',
            'login',
            'register',
        ];
    }
}

endif;
