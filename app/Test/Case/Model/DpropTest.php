<?php
App::uses('Dprop', 'Model');

/**
 * Dprop Test Case
 */
class DpropTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.dprop'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Dprop = ClassRegistry::init('Dprop');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Dprop);

		parent::tearDown();
	}

}
