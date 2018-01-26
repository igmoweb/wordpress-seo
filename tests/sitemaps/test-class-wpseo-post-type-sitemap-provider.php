<?php
/**
 * @package WPSEO\Tests\Sitemaps
 */

/**
 * Class WPSEO_Post_Type_Sitemap_Provider_Test
 */
class WPSEO_Post_Type_Sitemap_Provider_Test extends WPSEO_UnitTestCase {

	/**
	 * @var WPSEO_Post_Type_Sitemap_Provider
	 */
	private static $class_instance;

	/**
	 * Setting up.
	 */
	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();

		require_once WPSEO_TESTS_PATH . 'doubles/inc/sitemaps/class-post-type-sitemap-provider-double.php';
	}

	/**
	 * Set up our double class
	 */
	public function setUp() {
		parent::setUp();

		self::$class_instance = new WPSEO_Post_Type_Sitemap_Provider();
	}

	/**
	 * @covers WPSEO_Post_Type_Sitemap_Provider::get_index_links
	 */
	public function test_get_index_links() {

		$index_links = self::$class_instance->get_index_links( 1 );
		$this->assertEmpty( $index_links );

		$this->factory->post->create();
		$index_links = self::$class_instance->get_index_links( 1 );
		$this->assertNotEmpty( $index_links );
		$this->assertContains( 'http://example.org/post-sitemap.xml', $index_links[0] );

		$this->factory->post->create();
		$index_links = self::$class_instance->get_index_links( 1 );
		$this->assertContains( 'http://example.org/post-sitemap1.xml', $index_links[0] );
		$this->assertContains( 'http://example.org/post-sitemap2.xml', $index_links[1] );
	}

	/**
	 * @covers WPSEO_Post_Type_Sitemap_Provider::get_sitemap_links
	 */
	public function test_get_sitemap_links() {

		$sitemap_links = self::$class_instance->get_sitemap_links( 'post', 1, 1 );
		$this->assertContains( WPSEO_Utils::home_url(), $sitemap_links[0] );

		$post_id       = $this->factory->post->create();
		$sitemap_links = self::$class_instance->get_sitemap_links( 'post', 1, 1 );
		$this->assertContains( get_permalink( $post_id ), $sitemap_links[1] );
	}

	/**
	 * Tests the filtering of invalid ids.
	 *
	 * @covers WPSEO_Post_Type_Sitemap_Provider::filter_invalid_ids()
	 */
	public function test_filter_invalid_ids() {
		$sitemap_provider = new WPSEO_Post_Type_Sitemap_Provider_Double();

		$this->assertEquals(
			array( 1, 2 ),
			$sitemap_provider->filter_invalid_ids( array( '1', 'string', 2, false ) )
		);
	}

	/**
	 *
	 */
	public function test_get_excluded_posts() {
		$sitemap_provider = new WPSEO_Post_Type_Sitemap_Provider_Double();

		$this->assertEquals( array( '1', '2' ) , $sitemap_provider->get_excluded_posts( '1,2' ) );
	}
	/**
	 *
	 */
	public function test_get_excluded_posts_filter_out_invalid_values() {
		$sitemap_provider = new WPSEO_Post_Type_Sitemap_Provider_Double();

		$this->assertEquals( array() , $sitemap_provider->get_excluded_posts( '' ) );
		$this->assertEquals( array() , $sitemap_provider->get_excluded_posts( 'a' ) );
		$this->assertEquals( array() , $sitemap_provider->get_excluded_posts( ',' ) );
		$this->assertEquals( array() , $sitemap_provider->get_excluded_posts( 'a,b' ) );
	}

	public function test_get_excluded_posts_with_set_filter() {
		$sitemap_provider = new WPSEO_Post_Type_Sitemap_Provider_Double();

		add_filter( 'wpseo_posts_to_exclude', array( $this, 'filter_with_valid_output' ) );

		$this->assertEquals( array( 1, 2, 3, 4, 5, 600 ) , $sitemap_provider->get_excluded_posts( '1,2,3,4' ) );

		remove_filter( 'wpseo_posts_to_exclude', array( $this, 'filter_with_valid_output' ) );
	}

	public function test_get_excluded_posts_with_set_filter_that_has_invalid_return_value() {
		$sitemap_provider = new WPSEO_Post_Type_Sitemap_Provider_Double();

		add_filter( 'wpseo_posts_to_exclude', array( $this, 'filter_with_invalid_output' ) );

		$this->assertEquals( array() , $sitemap_provider->get_excluded_posts( '1,2,3,4' ) );

		remove_filter( 'wpseo_posts_to_exclude', array( $this, 'filter_with_invalid_output' ) );
	}

	/**
	 * Filter method for test.
	 *
	 * @param array $excluded_post_ids The excluded post ids.
	 *
	 * @return array The post ids.
	 */
	public function filter_with_valid_output( $excluded_post_ids ) {
		array_push( $excluded_post_ids, 5 );
		array_push( $excluded_post_ids, 600 );

		return $excluded_post_ids;
	}

	/**
	 * Filter method for test.
	 *
	 * @param array $excluded_post_ids The excluded post ids.
	 *
	 * @return array The post ids.
	 */
	public function filter_with_invalid_output( $excluded_post_ids ) {
		return '';
	}

}
