<?php
App::uses('Disproportionality', 'Model');

/**
 * Disproportionality Test Case
 */
class DisproportionalityTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.disproportionality'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Disproportionality = ClassRegistry::init('Disproportionality');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Disproportionality);

		parent::tearDown();
	}

}
