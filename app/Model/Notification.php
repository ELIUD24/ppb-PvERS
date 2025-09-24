<?php
App::uses('AppModel', 'Model');
/**
 * Notification Model
 *
 * @property User $User
 */
class Notification extends AppModel
{

	public $actsAs = array('Containable', 'Utils.SoftDelete', 'Search.Searchable');

	public $filterArgs = array(
		'reference_no' => array('type' => 'like', 'encode' => true),
		'protocol_no' => array('type' => 'like', 'encode' => true, 'field' => 'Notification.system_message'),
		// 'range' => array('type' => 'expression', 'method' => 'makeRangeCondition', 'field' => 'Notification.created BETWEEN ? AND ?'),

		'range' => array('type' => 'expression', 'method' => 'makeRangeCondition', 'field' => 'CAST(Notification.created as DATE) BETWEEN ? AND ?'),
		'model' => array('type' => 'like', 'encode' => true),
		'notification_source'=> array('type' => 'query', 'method' => 'dummy'),
		'start_date' => array('type' => 'query', 'method' => 'dummy'),
		'end_date' => array('type' => 'query', 'method' => 'dummy'),
	);
	public function dummy($data = array())
	{
		return array('1' => '1');
	}
	public function makeRangeCondition($data = array())
	{
		if (!empty($data['start_date'])) $start_date = date('Y-m-d', strtotime($data['start_date']));
		else $start_date = date('Y-m-d', strtotime('2012-05-01'));

		if (!empty($data['end_date'])) $end_date = date('Y-m-d', strtotime($data['end_date']));
		else $end_date = date('Y-m-d');

		return array($start_date, $end_date);
	}
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'foreign_key' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'url' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


	// UTILITY METHODS
	public function isOwnedBy($notification, $user)
	{
		return $user === $this->field('user_id', array('id' => $notification));
	}
}
