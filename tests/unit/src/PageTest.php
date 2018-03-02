<?php
declare (strict_types = 1);

namespace GrottoPress\WordPress\Page;

use Codeception\Util\Stub;
use tad\FunctionMocker\FunctionMocker;

class PageTest extends AbstractTestCase
{
    /**
     * @var Page
     */
    private $page;

    public function _before()
    {
        $this->page = new Page();
    }

    /**
     * @dataProvider titleProvider
     */
    public function testTitle(string $pageType, string $expected)
    {
        FunctionMocker::replace('single_post_title', 'single title');
        FunctionMocker::replace('get_the_archive_title', 'archive title');
        FunctionMocker::replace('get_search_query', 'search query');

        FunctionMocker::replace('esc_html__', function (string $html): string {
            return $html;
        });

        $page = Stub::make(Page::class, [
            'is' => function (string $type) use ($pageType): bool {
                return ($type === $pageType);
            }
        ]);

        $this->assertSame($expected, $page->title());
    }

    /**
     * @dataProvider descriptionProvider
     */
    public function testDescription(string $pageType, string $expected)
    {
        FunctionMocker::replace('get_the_excerpt', 'excerpt');
        FunctionMocker::replace('get_the_archive_description', 'description');

        $page = Stub::make(Page::class, [
            'is' => function (string $type) use ($pageType): bool {
                return ($type === $pageType);
            }
        ]);

        $this->assertSame($expected, $page->description());
    }

    /**
     * @dataProvider URLProvider
     */
    public function testURL(string $type, string $expected)
    {
        $_SERVER = ['REQUEST_URI' => '/some/path/'];

        FunctionMocker::replace('home_url', 'http://my.site');
        FunctionMocker::replace(
            'wp_parse_url',
            ['path' => '/page/hi/', 'query' => 'name=kofi']
        );

        FunctionMocker::replace('esc_url_raw', function (string $url): string {
            return $url;
        });

        $this->assertSame($expected, $this->page->URL($type));
    }

    /**
     * @dataProvider numberProvider
     */
    public function testNumber(int $pageVar, int $expected)
    {
        FunctionMocker::replace('absint', function ($arg) {
            return $arg;
        });

        FunctionMocker::replace(
            'get_query_var',
            function (string $var) use ($pageVar): int {
                if ('paged' === $var) {
                    return $pageVar;
                }

                return 0;
            }
        );

        $this->assertSame($expected, $this->page->number());
    }

    /**
     * @dataProvider isProvider
     */
    public function testIs(
        string $type,
        string $subType,
        string $pagenow,
        bool $expected
    ) {
        FunctionMocker::setGlobal('pagenow', $pagenow);
        FunctionMocker::replace('is_home', false);
        FunctionMocker::replace(
            'is_singular',
            function (string $subType = ''): bool {
                if ($subType) {
                    return ('post' === $subType);
                }

                return true;
            }
        );

        $this->assertSame($expected, $this->page->is($type, $subType));
    }

    public function titleProvider()
    {
        return [
            'page is singular' => ['singular', 'single title'],
            'page is archive' => ['archive', 'archive title'],
            'page is search' => ['search', 'Search results: "search query"'],
            'page is 404' => ['404', 'Not found'],
            'page is not singular, archive, search or 404' => ['home', ''],
        ];
    }

    public function descriptionProvider()
    {
        return [
            'page is singular' => ['singular', 'excerpt'],
            'page is archive' => ['archive', 'description'],
            'page is neither singular nor archive' => ['home', ''],
        ];
    }

    public function URLProvider()
    {
        return [
            'type is full' => ['full', 'http://my.site/page/hi/?name=kofi'],
            'type is not full' => ['', 'http://my.site/page/hi/'],
        ];
    }

    public function numberProvider()
    {
        return [
            'page query var is 4' => [4, 4],
            'page query var is 0' => [0, 1]
        ];
    }

    public function isProvider()
    {
        return [
            'type is invalid' => ['unavailable', '', '', false],
            'type is login, $pagenow is wp-login.php' => [
                'login',
                '',
                'wp-login.php',
                true
            ],
            'type is login, $pagenow is not wp-login.php' => [
                'login',
                '',
                'wp-signup.php',
                false,
            ],
            'type is register, $pagenow is wp-signup.php' => [
                'register',
                '',
                'wp-signup.php',
                true,
            ],
            'type is register, $pagenow is not wp-signup.php' => [
                'register',
                '',
                'wp-login.php',
                false,
            ],
            'type is singular' => ['singular', '', '', true],
            'type is singular post' => ['singular', 'post', '', true],
            'type is singular tutorial' => ['singular', 'tutorial', '', false],
            'type is home' => ['home', '', '', false],
        ];
    }
}
