<?php
App::uses('AppModel', 'Model');
/**
 * Ce2b Model
 *
 * @property User $User
 * @property County $County
 * @property SubCounty $SubCounty
 * @property Designation $Designation
 */
class Ce2b extends AppModel
{ 

	public $actsAs = array('Media.Transfer', 'Media.Coupler', 'Media.Meta', 'Search.Searchable', 'Containable');

	public $filterArgs = array(
		'reference_no' => array('type' => 'like', 'encode' => true),
		'sender_unique_identifier' => array('type' => 'like', 'encode' => true),
		'sender_organization' => array('type' => 'like', 'encode' => true),
		'start_date' => array('type' => 'query', 'method' => 'dummy'),
		'end_date' => array('type' => 'query', 'method' => 'dummy'),
		'reporter_email' => array('type' => 'like', 'encode' => true),
		'company_name' => array('type' => 'like', 'encode' => true),
		// 'serious' => array('type' => 'like', 'encode' => true),
		'serious' => array('type' => 'value'),
		'archived' => array('type' => 'value'),
		'range' => array('type' => 'expression', 'method' => 'makeRangeCondition', 'field' => 'CAST(Ce2b.reporter_date as DATE) BETWEEN ? AND ?'),
		'drug_name' => array('type' => 'query', 'method' => 'findByDrugName', 'encode' => true),
		'inn' => array('type' => 'query', 'method' => 'findByDrugINNName', 'encode' => true),
        'vigiflow' => array('type' => 'query', 'method' => 'findByVigiflowStatus', 'encode' => true),
	);

	   public function findByVigiflowStatus($data = array())
    {
        $cond = array();
        if (isset($data['vigiflow'])) {
            if ($data['vigiflow'] == 0) {
                $cond = array(
                    $this->alias . '.vigiflow_ref IS NOT NULL'
                );
            } else {
                $cond = array(
                    $this->alias . '.vigiflow_ref IS NULL'
                );
            }
        }
        return $cond;
    }
	public function findByDrugName($data = array())
	{
		$conditions = array();

		// Check if 'suspected_drug' is supplied
		if (isset($data['suspected_drug']) && !empty($data['suspected_drug'])) {
			$conditions[$this->alias . '.id'] = $this->Ce2bListOfDrug->find('list', array(
				'conditions' => array(
					'Ce2bListOfDrug.drug_name LIKE' => '%' . $data['drug_name'] . '%',
				),
				'fields' => array('ce2b_id', 'ce2b_id')
			));
		} else {
			// Include only the condition for 'brand_name'
			$conditions[$this->alias . '.id'] = $this->Ce2bListOfDrug->find('list', array(
				'conditions' => array(
					'Ce2bListOfDrug.drug_name LIKE' => '%' . $data['drug_name'] . '%',
				),
				'fields' => array('ce2b_id', 'ce2b_id')
			));
		}

		return $conditions;
	}

	public function findByDrugINNName($data = array())
	{
		$conditions = array();

		// Check if 'suspected_drug' is supplied
		if (isset($data['suspected_drug']) && !empty($data['suspected_drug'])) {
			$conditions[$this->alias . '.id'] = $this->Ce2bListOfDrug->find('list', array(
				'conditions' => array(
					'Ce2bListOfDrug.brand_name LIKE' => '%' . $data['inn'] . '%',
				),
				'fields' => array('ce2b_id', 'ce2b_id')
			));
		} else {
			// Include only the condition for 'drug_name'
			$conditions[$this->alias . '.id'] = $this->Ce2bListOfDrug->find('list', array(
				'conditions' => array(
					'Ce2bListOfDrug.brand_name LIKE' => '%' . $data['inn'] . '%',
				),
				'fields' => array('ce2b_id', 'ce2b_id')
			));
		}

		return $conditions;
	}
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
		'FacilityCode' => array(
			'className' => 'FacilityCode',
			'foreignKey' => 'company_code',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),

		// 
	);
	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'dependent' => true,
			'conditions' => array('Attachment.model' => 'Ce2b', 'Attachment.group' => 'attachment'),
		),
		'ExternalComment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'dependent' => true,
			'conditions' => array('ExternalComment.model' => 'Ce2b', 'ExternalComment.category' => 'external'),
		),
		'ReviewComment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'dependent' => true,
			'conditions' => array('ReviewComment.model' => 'Ce2b', 'ReviewComment.category' => 'review'),
		),
		'Ce2bListOfDrug' => array(
			'className' => 'Ce2bListOfDrug',
			'foreignKey' => 'ce2b_id',
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
		'Ce2bReaction' => array(
			'className' => 'Ce2bReaction',
			'foreignKey' => 'ce2b_id',
			'dependent' => true
		)

	);
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(

		'e2b_file' => array(
			'resource'   => array(
				'rule' => 'checkResource',
				'allowEmpty' => false,
				'message' => 'Please attach a file!'
			),
			'access'     => array('rule' => 'checkAccess'),
			'permission' => array('rule' => array('checkPermission', '*')),
			'size'       => array('rule' => array('checkSize', '5M')),
		),
		'reporter_email' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please specify the reporter_email'
			),
		),
		// 'e2b_type' => array(
		// 	'notBlank' => array(
		// 		'rule'     => 'notBlank',
		// 		'required' => true,
		// 		'message'  => 'Please specify the Type Format'
		// 	),
		// ),
	);
}
