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

declare (strict_types = 1);

namespace GrottoPress\WordPress\Page;

use GrottoPress\WordPress\Page\Page;

/**
 * Page test case
 *
 * @since 0.1.0
 */
class PageTest extends \WP_UnitTestCase
{
    private $page;
    private $post_ids;
    private $tutorial_ids;
    
    public function setUp()
    {
        parent::setUp();

        $this->page = new Page();

        \register_post_type('tutorial', [
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'tutorials', 'with_front' => false],
            'taxonomy' => ['level'],
        ]);

        \register_taxonomy('level', ['tutorial'], [
            'rewrite' => ['with_front' => false],
            'hierarchical' => true,
        ]);

        \wp_insert_term('basic', 'level');

        \delete_option('show_on_front');
        \delete_option('page_on_front');
        \delete_option('page_for_posts');

        $this->post_ids = $this->factory->post->create_many(12, [
            'post_type' => 'post',
            'post_status' => 'publish',
        ]);

        $this->page_ids = $this->factory->post->create_many(12, [
            'post_type' => 'page',
            'post_status' => 'publish',
        ]);

        $this->tutorial_ids = $this->factory->post->create_many(12, [
            'post_type' => 'tutorial',
            'post_status' => 'publish',
        ]);

        $this->attachment_ids = $this->factory->post->create_many(12, [
            'post_type' => 'attachment',
        ]);

        $this->user_ids = $this->factory->user->create_many(12);
    }

    // ----------

    public function testCurrentPageTypeValidOnHome()
    {
        $url = \home_url('/');
        $this->go_to($url);
        // WP test case seems buggy: is_front_page() return false on homepage
        // $this->assertSame(['home', 'front_page'], $this->page->type());

        $post_id = $this->page_ids[0];
        \update_option('show_on_front', 'page');
        \update_option('page_on_front', $post_id);
        $this->go_to($url);
        $this->assertSame(
            ['front_page', 'page', 'singular'],
            $this->page->type()
        );

        $posts_page_id = $this->page_ids[1];
        \update_option('page_for_posts', $posts_page_id);
        $this->go_to(get_permalink($posts_page_id));
        $this->assertSame(['home'], $this->page->type());
    }

    public function testCurrentPageTypeValidOnPostTypeArchive()
    {
        $post_id = $this->post_ids[\array_rand($this->post_ids)];

        $url = \get_post_type_archive_link(\get_post_type($post_id));
        $this->go_to($url);
        $this->assertSame(['home'], $this->page->type());
    }

    public function testCurrentPageTypeValidOnCustomPostTypeArchive()
    {
        $post_id = $this->tutorial_ids[\array_rand($this->tutorial_ids)];

        $url = \get_post_type_archive_link(\get_post_type($post_id));
        $this->go_to($url);
        $this->assertTrue($this->page->is('post_type_archive', 'tutorial'));
        $this->assertSame(['post_type_archive', 'archive'], $this->page->type());
    }

    public function testCurrentPageTypeValidOnSinglePost()
    {
        $post_id = $this->post_ids[\array_rand($this->post_ids)];

        $url = \get_permalink($post_id);
        $this->go_to($url);
        $this->assertSame(['single', 'singular'], $this->page->type());
    }

    public function testCurrentPageTypeValidOnSingleAttahment()
    {
        $post_id = $this->attachment_ids[\array_rand($this->attachment_ids)];

        $url = \get_permalink($post_id);
        $this->go_to($url);
        $this->assertSame(
            ['single', 'attachment', 'singular'],
            $this->page->type()
        );
    }

    public function testCurrentPageTypeValidOnSingleCustomPost()
    {
        $post_id = $this->tutorial_ids[\array_rand($this->tutorial_ids)];

        $url = \get_permalink($post_id);
        $this->go_to($url);
        $this->assertSame(['single', 'singular'], $this->page->type());
    }

    public function testCurrentPageTypeValidOnCategoryArchive()
    {
        $post_id = $this->post_ids[\array_rand($this->post_ids)];

        $url = \get_term_link(1, 'category');
        $this->go_to($url);
        $this->assertSame(['category', 'archive'], $this->page->type());
    }

    public function testCurrentPageTypeValidOnCustomTaxTermArchive()
    {
        $post_id = $this->tutorial_ids[\array_rand($this->tutorial_ids)];

        $url = \get_term_link('basic', 'level');
        $this->go_to($url);
        $this->assertSame(['tax', 'archive'], $this->page->type());
    }

    public function testCurrentPageTypeValidOnAuthorArchive()
    {
        $user_id = $this->user_ids[\array_rand($this->user_ids)];

        $url = \get_author_posts_url($user_id);
        $this->go_to($url);
        $this->assertSame(['author', 'archive'], $this->page->type());
    }

    // ----------

    public function testCurrentPageUrlValidOnHome()
    {
        $url = \home_url('/');
        $this->go_to($url);
        $this->assertSame($url, $this->page->URL('full'));
    }

    public function testCurrentPageUrlValidOnPostTypeArchive()
    {
        $post_id = $this->post_ids[\array_rand($this->post_ids)];

        $url = \get_post_type_archive_link(\get_post_type($post_id));
        $this->go_to($url);
        $this->assertSame($url, $this->page->URL('full'));
    }

    public function testCurrentPageUrlValidOnCustomPostTypeArchive()
    {
        $post_id = $this->tutorial_ids[\array_rand($this->tutorial_ids)];

        $url = \get_post_type_archive_link(\get_post_type($post_id));
        $this->go_to($url);
        $this->assertSame($url, $this->page->URL('full'));
    }

    public function testCurrentPageUrlValidOnSinglePost()
    {
        $post_id = $this->post_ids[\array_rand($this->post_ids)];

        $url = \get_permalink($post_id);
        $this->go_to($url);
        $this->assertSame($url, $this->page->URL('full'));
    }

    public function testCurrentPageUrlValidOnSingleCustomPost()
    {
        $post_id = $this->tutorial_ids[\array_rand($this->tutorial_ids)];

        $url = \get_permalink($post_id);
        $this->go_to($url);
        $this->assertSame($url, $this->page->URL('full'));
    }

    public function testCurrentPageUrlValidOnCategoryArchive()
    {
        $post_id = $this->post_ids[\array_rand($this->post_ids)];

        $url = \get_term_link(1, 'category');
        $this->go_to($url);
        $this->assertSame($url, $this->page->URL('full'));
    }

    public function testCurrentPageUrlValidOnCustomTaxArchive()
    {
        $post_id = $this->tutorial_ids[\array_rand($this->tutorial_ids)];

        $url = \get_term_link('basic', 'level');
        $this->go_to($url);
        $this->assertSame($url, $this->page->URL('full'));
    }

    // ----------

    public function testPageDescriptionWorksOnSingleCustomPost()
    {
        $post_id = $this->tutorial_ids[\array_rand($this->tutorial_ids)];
        
        $this->go_to(\get_permalink($post_id));
        $this->assertSame($this->page->description(), \get_the_excerpt($post_id));
    }

    public function testPageTitleWorksOnCutomPostTypeArchive()
    {
        $post_id = $this->tutorial_ids[\array_rand($this->tutorial_ids)];

        $url = \get_post_type_archive_link(\get_post_type($post_id));
        $this->go_to($url);
        $this->assertSame($this->page->title(), \get_the_archive_title());
    }

    // ---

    public function testPageNumberWorksOnCustomPostTypeArchive()
    {
        $post_id = $this->tutorial_ids[\array_rand($this->tutorial_ids)];
        
        $url = \get_post_type_archive_link(\get_post_type($post_id));
        $this->go_to($url);
        $url = \get_pagenum_link(2, false);
        $this->go_to($url);
        
        $this->assertSame($this->page->number(), 2);
    }
}
