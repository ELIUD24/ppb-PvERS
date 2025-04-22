<?php
App::uses('AggregateListOfSignal', 'Model');

/**
 * AggregateListOfSignal Test Case
 */
class AggregateListOfSignalTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.aggregate_list_of_signal',
		'app.aggregate',
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
		'app.ce2b',
		'app.facility_code',
		'app.ce2b_list_of_drug',
		'app.ce2b_followup',
		'app.ce2b_reaction',
		'app.aggregate_followup'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->AggregateListOfSignal = ClassRegistry::init('AggregateListOfSignal');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->AggregateListOfSignal);

		parent::tearDown();
	}

}
