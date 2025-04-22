<?php
App::uses('Ce2bReaction', 'Model');

/**
 * Ce2bReaction Test Case
 */
class Ce2bReactionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.ce2b_reaction',
		'app.ce2b',
		'app.user',
		'app.group',
		'app.designation',
		'app.pqmp',
		'app.county',
		'app.sadr',
		'app.sub_county',
		'app.aefi',
		'app.aefi_description',
		'app.aefi_reaction',
		'app.attachment',
		'app.aefi_list_of_vaccine',
		'app.vaccine',
		'app.reminder',
		'app.comment',
		'app.padr',
		'app.padr_list_of_medicine',
		'app.sadr_list_of_drug',
		'app.sadr_followup',
		'app.dose',
		'app.route',
		'app.frequency',
		'app.sadr_description',
		'app.sadr_list_of_medicine',
		'app.sadr_reaction',
		'app.country',
		'app.notification',
		'app.feedback',
		'app.saefi',
		'app.review',
		'app.device',
		'app.list_of_device',
		'app.medication',
		'app.medication_product',
		'app.transfusion',
		'app.pint',
		'app.sae',
		'app.concomittant_drug',
		'app.suspected_drug',
		'app.facility_code'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Ce2bReaction = ClassRegistry::init('Ce2bReaction');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Ce2bReaction);

		parent::tearDown();
	}

}
