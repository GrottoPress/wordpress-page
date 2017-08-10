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

namespace GrottoPress\WordPress\Page;

if ( defined( 'WPINC' ) ) :

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
     * @return array Template tags applicable to this page.
     */
    public function type() {
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
    public function title() {
        if ( $this->is( 'singular' ) ) {
            return \single_post_title( '', false );
        }

        if ( $this->is( 'archive' ) ) {
            return \get_the_archive_title();
        }

        if ( $this->is( 'search' ) ) {
            return \sprintf( \esc_html__( 'Search results: "%s"', 'wordpress-template' ),
                \get_search_query() );
        }

        if ( $this->is( '404' ) ) {
            return \esc_html__( 'Not found', 'wordpress-template' );
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
    public function description() {
        if ( $this->is( 'singular' ) ) {
            return \get_the_excerpt();
        }

        if ( $this->is( 'archive' ) ) {
            return \get_the_archive_description();
        }

        return '';
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
    public function is( $type ) {
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
     * @return array Template types
     */
    protected function types() {
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
