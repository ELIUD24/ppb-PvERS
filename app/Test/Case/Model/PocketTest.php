<?php
App::uses('Pocket', 'Model');

/**
 * Pocket Test Case
 */
class PocketTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.pocket'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Pocket = ClassRegistry::init('Pocket');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Pocket);

		parent::tearDown();
	}

}
