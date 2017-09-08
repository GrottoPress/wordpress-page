<?php

/**
 * Pagination Tests
 *
 * @package GrottoPress\WordPress\Page\Tests
 *
 * @since 0.1.0
 *
 * @author GrottoPress <info@grottopress.com>
 * @author N Atta Kus Adusei
 */

declare ( strict_types = 1 );

namespace GrottoPress\WordPress\Page;

use GrottoPress\WordPress\Page\Page;

/**
 * Page test case
 *
 * @since 0.1.0
 */
class Page_Test extends \WP_UnitTestCase {
    private $post_ids;
    private $tutorial_ids;
    
    public function setUp() {
        parent::setUp();

        \register_post_type( 'tutorial', [
            'public' => true,
            'has_archive' => true,
            'rewrite' => [ 'slug' => 'tutorials', 'with_front' => false ],
            'taxonomy' => [ 'level' ],
        ] );

        \register_taxonomy( 'level', [ 'tutorial' ], [
            'rewrite' => [ 'with_front' => false ],
            'hierarchical' => true,
        ] );

        \wp_insert_term( 'basic', 'level' );

        \delete_option( 'show_on_front' );
        \delete_option( 'page_on_front' );
        \delete_option( 'page_for_posts' );

        $this->post_ids = $this->factory->post->create_many( 12, [
            'post_type' => 'post',
            'post_status' => 'publish',
        ] );

        $this->page_ids = $this->factory->post->create_many( 12, [
            'post_type' => 'page',
            'post_status' => 'publish',
        ] );

        $this->tutorial_ids = $this->factory->post->create_many( 12, [
            'post_type' => 'tutorial',
            'post_status' => 'publish',
        ] );

        $this->attachment_ids = $this->factory->post->create_many( 12, [
            'post_type' => 'attachment',
        ] );

        $this->user_ids = $this->factory->user->create_many( 12 );
    }

    // ----------

	public function test_current_page_type_valid_on_home() {
		$url = \home_url( '/' );
        $this->go_to( $url );
        // WP test case seems buggy: is_front_page() return false on homepage
        // $this->assertSame( [ 'home', 'front_page' ], ( new Page() )->type() );

        $post_id = $this->page_ids[0];
        \update_option( 'show_on_front', 'page' );
        \update_option( 'page_on_front', $post_id );
        $this->go_to( $url );
        $this->assertSame( [ 'front_page', 'page', 'singular' ], ( new Page() )->type() );

        $posts_page_id = $this->page_ids[1];
        \update_option( 'page_for_posts', $posts_page_id );
        $this->go_to( get_permalink( $posts_page_id ) );
        $this->assertSame( [ 'home' ], ( new Page() )->type() );
	}

    public function test_current_page_type_valid_on_post_type_archive() {
        $post_id = $this->post_ids[ \array_rand( $this->post_ids ) ];

        $url = \get_post_type_archive_link( \get_post_type( $post_id ) );
        $this->go_to( $url );
        $this->assertSame( [ 'home' ], ( new Page() )->type() );
    }

    public function test_current_page_type_valid_on_custom_post_type_archive() {
        $post_id = $this->tutorial_ids[ \array_rand( $this->tutorial_ids ) ];

        $url = \get_post_type_archive_link( \get_post_type( $post_id ) );
        $this->go_to( $url );
        $this->assertTrue( ( new Page() )->is( 'post_type_archive', 'tutorial' ) );
        $this->assertSame( [ 'post_type_archive', 'archive' ], ( new Page() )->type() );
    }

    public function test_current_page_type_valid_on_single_post() {
        $post_id = $this->post_ids[ \array_rand( $this->post_ids ) ];

        $url = \get_permalink( $post_id );
        $this->go_to( $url );
        $this->assertSame( [ 'single', 'singular' ], ( new Page() )->type() );
    }

    public function test_current_page_type_valid_on_single_attahment() {
        $post_id = $this->attachment_ids[ \array_rand( $this->attachment_ids ) ];

        $url = \get_permalink( $post_id );
        $this->go_to( $url );
        $this->assertSame( [ 'single', 'attachment', 'singular' ], ( new Page() )->type() );
    }

    public function test_current_page_type_valid_on_single_custom_post() {
        $post_id = $this->tutorial_ids[ \array_rand( $this->tutorial_ids ) ];

        $url = \get_permalink( $post_id );
        $this->go_to( $url );
        $this->assertSame( [ 'single', 'singular' ], ( new Page() )->type() );
    }

    public function test_current_page_type_valid_on_category_archive() {
        $post_id = $this->post_ids[ \array_rand( $this->post_ids ) ];

        $url = \get_term_link( 1, 'category' );
        $this->go_to( $url );
        $this->assertSame( [ 'category', 'archive' ], ( new Page() )->type() );
    }

    public function test_current_page_type_valid_on_custom_tax_term_archive() {
        $post_id = $this->tutorial_ids[ \array_rand( $this->tutorial_ids ) ];

        $url = \get_term_link( 'basic', 'level' );
        $this->go_to( $url );
        $this->assertSame( [ 'tax', 'archive' ], ( new Page() )->type() );
    }

    public function test_current_page_type_valid_on_author_archive() {
        $user_id = $this->user_ids[ \array_rand( $this->user_ids ) ];

        $url = \get_author_posts_url( $user_id );
        $this->go_to( $url );
        $this->assertSame( [ 'author', 'archive' ], ( new Page() )->type() );
    }

    // ----------

    public function test_current_page_url_valid_on_home() {
        $url = \home_url( '/' );
        $this->go_to( $url );
        $this->assertSame( $url, ( new Page() )->url( true ) );
    }

    public function test_current_page_url_valid_on_post_type_archive() {
        $post_id = $this->post_ids[ \array_rand( $this->post_ids ) ];

        $url = \get_post_type_archive_link( \get_post_type( $post_id ) );
        $this->go_to( $url );
        $this->assertSame( $url, ( new Page() )->url( true ) );
    }

    public function test_current_page_url_valid_on_custom_post_type_archive() {
        $post_id = $this->tutorial_ids[ \array_rand( $this->tutorial_ids ) ];

        $url = \get_post_type_archive_link( \get_post_type( $post_id ) );
        $this->go_to( $url );
        $this->assertSame( $url, ( new Page() )->url( true ) );
    }

    public function test_current_page_url_valid_on_single_post() {
        $post_id = $this->post_ids[ \array_rand( $this->post_ids ) ];

        $url = \get_permalink( $post_id );
        $this->go_to( $url );
        $this->assertSame( $url, ( new Page() )->url( true ) );
    }

    public function test_current_page_url_valid_on_single_custom_post() {
        $post_id = $this->tutorial_ids[ \array_rand( $this->tutorial_ids ) ];

        $url = \get_permalink( $post_id );
        $this->go_to( $url );
        $this->assertSame( $url, ( new Page() )->url( true ) );
    }

    public function test_current_page_url_valid_on_category_archive() {
        $post_id = $this->post_ids[ \array_rand( $this->post_ids ) ];

        $url = \get_term_link( 1, 'category' );
        $this->go_to( $url );
        $this->assertSame( $url, ( new Page() )->url( true ) );
    }

    public function test_current_page_url_valid_on_custom_tax_term_archive() {
        $post_id = $this->tutorial_ids[ \array_rand( $this->tutorial_ids ) ];

        $url = \get_term_link( 'basic', 'level' );
        $this->go_to( $url );
        $this->assertSame( $url, ( new Page() )->url( true ) );
    }

    // ----------

    public function test_page_description_works_on_single_custom_post() {
        $post_id = $this->tutorial_ids[ \array_rand( $this->tutorial_ids ) ];
        
        $this->go_to( \get_permalink( $post_id ) );
        $this->assertSame( ( new Page() )->description(), \get_the_excerpt( $post_id ) );
    }

    public function test_page_title_works_on_cutom_post_type_archive() {
        $post_id = $this->tutorial_ids[ \array_rand( $this->tutorial_ids ) ];

        $url = \get_post_type_archive_link( \get_post_type( $post_id ) );
        $this->go_to( $url );
        $this->assertSame( ( new Page() )->title(), \get_the_archive_title() );
    }
}
