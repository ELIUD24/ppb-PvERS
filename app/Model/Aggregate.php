<?php
App::uses('AppModel', 'Model');
/**
 * Aggregate Model
 *
 * @property User $User
 * @property Ce2b $Ce2b
 * @property County $County
 * @property SubCounty $SubCounty
 * @property Designation $Designation
 */
class Aggregate extends AppModel
{


	public $actsAs = array('Containable', 'Search.Searchable');
	public $filterArgs = array(
		'reference_no' => array('type' => 'like', 'encode' => true),
		'brand_name' => array('type' => 'like', 'encode' => true),
		'inn_name' => array('type' => 'like', 'encode' => true),
		'range' => array('type' => 'expression', 'method' => 'makeRangeCondition', 'field' => 'CAST(Aggregate.submitted_date as DATE) BETWEEN ? AND ?'),
        'start_date' => array('type' => 'query', 'method' => 'dummy'),
		'archived' => array('type' => 'value'),
        'end_date' => array('type' => 'query', 'method' => 'dummy'),
		'submission_frequency' => array('type' => 'like', 'encode' => true),
	);
	public function makeRangeCondition($data = array())
    {
        if (!empty($data['start_date'])) $start_date = date('Y-m-d', strtotime($data['start_date']));
        else $start_date = date('Y-m-d', strtotime('2012-05-01'));

        if (!empty($data['end_date'])) $end_date = date('Y-m-d', strtotime($data['end_date']));
        else $end_date = date('Y-m-d');

        return array($start_date, $end_date);
    }
	public function dummy($data = array())
	{
		return array('1' => '1');
	}
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(

	 
		'brand_name' => array(
			'seriousYes' => array(
				'rule'     => 'seriousYes',
				'required' => false,
				'message' => 'Please provide a brand name',
			),
		),
		'inn_name' => array(
			'conditionalNotBlank' => array(
				'rule' =>'notBlank',
				// 'required' => true,
				'message'  => 'Please provide a INN Name'
			),
		),
		'mah' => array(
			'conditionalNotBlank' => array(
				'rule' =>'notBlank',
				// 'required' => true,
				'message'  => 'Please provide a Marketing Authorization Holder'
			),
		),
		'therapeutic_group' => array(
			'conditionalNotBlank' => array(
				'rule' =>'notBlank',
				// 'required' => true,
				'message'  => 'Please provide Therapeutic Group Code'
			),
		),
		'authorised_indications' => array(
			'conditionalNotBlank' => array(
				'rule' =>'notBlank',
				'required' => true,
				'message'  => 'Please provide authorised indications'
			),
		),
		'form_strength' => array(
			'notBlank' => array(
				'rule' =>'notBlank',
				'required' => true,
				'message'  => 'Please provide pharmaceutical form(s)'
			),
		),


		// Added section  

		'interval_code' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide interval number'
			),
		),'submission_frequency' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide the submission frequency'
			),
		),

		'date_of_birth' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide the date of birth'
			),
		),
		 

		'reporter_name' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide the name of the reporter'
			),
		),
		'reporter_date' => array(
		    'notBlank' => array(
		        'rule'     => 'notBlank',
		        'required' => true,
		        'message'  => 'Please provide the date of submission of the report'
		    ),
		),
		'reporter_email' => array(
			'notBlank' => array(
				'rule'     => 'email',
				'required' => true,
				'message'  => 'Please provide a valid email address'
			),
		),
		'reporter_phone' => array(
		    'notBlank' => array(
		        'rule'     => 'notBlank',
		        'required' => true,
		        'message'  => 'Please provide a valid phone number'
		    ),
		),
		// ensure reporter phone is numeric and 10 digits
		'reporter_phone' => array(
		    'numeric' => array(
		        'rule' => array('numeric'),
		        'message' => 'Please provide a valid phone number',
		    ),
		    'minLength' => array(
		        'rule' => array('minLength', 10),
		        'message' => 'Please provide a valid phone number',
		    ),
		    'maxLength' => array(
		        'rule' => array('maxLength', 12),
		        'message' => 'Please provide a valid phone number',
		    ),
		),

	);
	public function ageOrDate($field = null)
    {
        return !empty($this->data['Aggregate']['date_of_birth']['year']);
    }
	public function seriousYes($field = null)
	{
		if ($this->data['Aggregate']['summary_available'] == 'Yes') return !empty($this->data['Aggregate']['brand_name']);
		else return true;
	}
	public function conditionalNotBlank($check, $field, $value)
	{
		if (isset($this->data[$this->alias][$field]) && $this->data[$this->alias][$field] === $value) {
			// Apply notBlank validation rule
			$key = key($check);
			return !empty($check[$key]);
		}
		// If the condition is not met, return true to bypass validation
		return true;
	}
 
	public function atleastOneAttachment($field = null)
	{
		return !empty($this->data['Attachment']);
	}
	// The Associations below have been created with all possible keys, those that are not needed can be removed

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
		),

		'County' => array(
			'className' => 'County',
			'foreignKey' => 'county_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'SubCounty' => array(
			'className' => 'SubCounty',
			'foreignKey' => 'sub_county_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Designation' => array(
			'className' => 'Designation',
			'foreignKey' => 'designation_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ParentAggregate' => array(
			'className' => 'Aggregate',
			'foreignKey' => 'aggregate_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),




	);

	public $hasMany = array(
		'AggregateListOfSignal' => array(
			'className' => 'AggregateListOfSignal',
			'foreignKey' => 'aggregate_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'dependent' => true,
			'conditions' => array('Attachment.model' => 'Aggregate', 'Attachment.group' => 'attachment'),
		),

		'ExternalComment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'dependent' => true,
			'conditions' => array('ExternalComment.model' => 'Aggregate', 'ExternalComment.category' => 'external'),
		),

		'ReviewerComment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'dependent' => true,
			'conditions' => array('ReviewerComment.model' => 'Aggregate', 'ReviewerComment.category' => 'review'),
		),
		'Recommendation' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'dependent' => true,
			'conditions' => array('Recommendation.model' => 'Aggregate', 'Recommendation.category' => 'recommendation'),
		),
		'ReviewerAggregate' => array(
			'className' => 'Aggregate',
			'foreignKey' => 'aggregate_id',
			'dependent' => true,
			'conditions' => ''
		),
	);


    // public function afterFind($results, $primary = false) {
    //     if ($primary) {
    //         foreach ($results as $key => $val) {
    //             if (isset($val['Aggregate']['id'])) {
    //                 // Fetch only one Post record
    //                 $post = $this->ReviewerAggregate->find('first', array(
    //                     'conditions' => array('ReviewerAggregate.aggregate_id' => $val['Aggregate']['id']),
    //                     'order' => array('ReviewerAggregate.created' => 'DESC')
    //                 ));
    //                 // Replace the array of Posts with a single ReviewerAggregate
    //                 $results[$key]['ReviewerAggregate'] = isset($post['ReviewerAggregate']) ? $post['ReviewerAggregate'] : null;
    //             }
    //         }
    //     }
    //     return $results;
    // }
}
