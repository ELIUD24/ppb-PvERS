<?php
/**
 * AggregateListOfSignal Fixture
 */
class AggregateListOfSignalFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'aggregate_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'aggregate_followup_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'signal_term' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'source_trigger' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'status' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'outcome' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'date_detected' => array('type' => 'date', 'null' => true, 'default' => null),
		'date_closed' => array('type' => 'date', 'null' => true, 'default' => null),
		'reason_summary' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'evaluation_method' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'aggregate_id' => 1,
			'aggregate_followup_id' => 1,
			'signal_term' => 'Lorem ipsum dolor sit amet',
			'source_trigger' => 'Lorem ipsum dolor sit amet',
			'status' => 'Lorem ipsum dolor sit amet',
			'outcome' => 'Lorem ipsum dolor sit amet',
			'date_detected' => '2024-06-07',
			'date_closed' => '2024-06-07',
			'reason_summary' => 'Lorem ipsum dolor sit amet',
			'evaluation_method' => 'Lorem ipsum dolor sit amet',
			'created' => '2024-06-07 17:09:17',
			'modified' => '2024-06-07 17:09:17'
		),
	);

}
