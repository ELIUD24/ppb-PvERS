<?php
App::uses('AppModel', 'Model');
App::uses('CakeEmail', 'Network/Email');
/**
 * Sadr Model
 *
 * @property User $User
 * @property Attachment $Attachment
 * @property SadrListOfDrug $SadrListOfDrug
 */
class Sadr extends AppModel
{

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $actsAs = array('Search.Searchable', 'Containable');
    // public $drug_dictionary = ClassRegistry::init('DrugDictionary');

    public $filterArgs = array(
        'reference_no' => array('type' => 'like', 'encode' => true),
        'report_title' => array('type' => 'like', 'encode' => true),
        'name_of_institution' => array('type' => 'like', 'encode' => true),
        'serious' => array('type' => 'like', 'encode' => true),
        'range' => array('type' => 'expression', 'method' => 'makeRangeCondition', 'field' => 'CAST(Sadr.submitted_date as DATE) BETWEEN ? AND ?'),
        'reportrange' => array('type' => 'expression', 'method' => 'makeRangeCondition', 'field' => 'CAST(Sadr.reporter_date as DATE) BETWEEN ? AND ?'),
        'filter_by' => array('type' => 'query', 'method' => 'dummy'),
        'start_date' => array('type' => 'query', 'method' => 'dummy'),
        'end_date' => array('type' => 'query', 'method' => 'dummy'),
        'archived' => array('type' => 'value'),
        'has_review' => array('type' => 'query', 'method' => 'findByHasReview', 'encode' => true),
        'county_id' => array('type' => 'value'),
        'mah' => array('type' => 'query', 'method' => 'findByMarketAuthority', 'encode' => true),
        'drug_name' => array('type' => 'query', 'method' => 'findByDrugName', 'encode' => true),
        'health_program' => array('type' => 'query', 'method' => 'findByHealthProgram', 'encode' => true),
        'inn' => array('type' => 'query', 'method' => 'findByDrugINNName', 'encode' => true),
        'manufacturer' => array('type' => 'query', 'method' => 'findByManufacturerName', 'encode' => true),
        'vigiflow' => array('type' => 'query', 'method' => 'findByVigiflowStatus', 'encode' => true),
        'suspected_drug' => array('type' => 'query', 'method' => 'dummy'),
        'report_sadr' => array('type' => 'value'),
        'report_therapeutic' => array('type' => 'value'),
        'report_misuse' => array('type' => 'value'),
        'report_off_label' => array('type' => 'value'),
        'device' => array('type' => 'value'),
        'medicinal_product' => array('type' => 'value'),
        'blood_products' => array('type' => 'value'),
        'herbal_product' => array('type' => 'value'),
        'cosmeceuticals' => array('type' => 'value'),
        'product_other' => array('type' => 'value'),
        'product_specify' => array('type' => 'like', 'encode' => true),
        'patient_name' => array('type' => 'like', 'encode' => true),
        'report_type' => array('type' => 'value'),
        'serious_reason' => array('type' => 'value'),
        'outcome' => array('type' => 'value'),
        'reporter' => array('type' => 'query', 'method' => 'reporterFilter', 'encode' => true),
        'designation_id' => array('type' => 'value'),
        'gender' => array('type' => 'value'),
        'submitted' => array('type' => 'value'),
        'submit' => array('type' => 'query', 'method' => 'orConditions', 'encode' => true),
    );

    public function dummy($data = array())
    {
        return array('1' => '1');
    }

    public function findByHealthProgram($data = array())
    {
        $vdrugs = ClassRegistry::init('DrugDictionary')->find('list', array('conditions' => array('health_program' => $data['health_program']), 'fields' => array('drug_name', 'drug_name')));
        $cond = array($this->alias . '.id' => $this->SadrListOfDrug->find('list', array(
            'conditions' => array(
                'OR' => array(
                    'SadrListOfDrug.drug_name' => $vdrugs,
                    'SadrListOfDrug.brand_name' => $vdrugs,
                )
            ),
            'fields' => array('sadr_id', 'sadr_id')
        )));
        return $cond;
    }

    public function findByDrugName($data = array())
    {
        // $cond = array($this->alias . '.id' => $this->SadrListOfDrug->find('list', array(
        //     'conditions' => array(
        //         'SadrListOfDrug.brand_name LIKE' => '%' . $data['drug_name'] . '%',

        //     ),
        //     'fields' => array('sadr_id', 'sadr_id')
        // )));
        // return $cond;
        $conditions = array();

        // Check if 'suspected_drug' is supplied
        if (isset($data['suspected_drug']) && !empty($data['suspected_drug'])) {
            $conditions[$this->alias . '.id'] = $this->SadrListOfDrug->find('list', array(
                'conditions' => array(
                    'SadrListOfDrug.brand_name LIKE' => '%' . $data['inn'] . '%',
                    'SadrListOfDrug.suspected_drug LIKE' => '%' . $data['suspected_drug'] . '%',
                ),
                'fields' => array('sadr_id', 'sadr_id')
            ));
        } else {
            // Include only the condition for 'brand_name'
            $conditions[$this->alias . '.id'] = $this->SadrListOfDrug->find('list', array(
                'conditions' => array(
                    'SadrListOfDrug.brand_name LIKE' => '%' . $data['inn'] . '%',
                ),
                'fields' => array('sadr_id', 'sadr_id')
            ));
        }

        return $conditions;
    }

    public function findByDrugINNName($data = array())
    {

        $conditions = array();

        // Check if 'suspected_drug' is supplied
        if (isset($data['suspected_drug']) && !empty($data['suspected_drug'])) {
            $conditions[$this->alias . '.id'] = $this->SadrListOfDrug->find('list', array(
                'conditions' => array(
                    'SadrListOfDrug.drug_name LIKE' => '%' . $data['inn'] . '%',
                    'SadrListOfDrug.suspected_drug LIKE' => '%' . $data['suspected_drug'] . '%',
                ),
                'fields' => array('sadr_id', 'sadr_id')
            ));
        } else {
            // Include only the condition for 'drug_name'
            $conditions[$this->alias . '.id'] = $this->SadrListOfDrug->find('list', array(
                'conditions' => array(
                    'SadrListOfDrug.drug_name LIKE' => '%' . $data['inn'] . '%',
                ),
                'fields' => array('sadr_id', 'sadr_id')
            ));
        }

        return $conditions;
    }
    public function findByManufacturerName($data = array())
    {
        $cond = array($this->alias . '.id' => $this->SadrListOfDrug->find('list', array(
            'conditions' => array(
                'SadrListOfDrug.manufacturer LIKE' => '%' . $data['manufacturer'] . '%',

            ),
            'fields' => array('sadr_id', 'sadr_id')
        )));
        return $cond;
    }

    public function findByHasReview($data = [])
    {
        $conditions = [];

        $sadrIds = $this->ExternalComment->find('all', [
            'fields' => ['ExternalComment.foreign_key'],
            'conditions' => [
                'ExternalComment.model' => 'Sadr',
                'ExternalComment.category' => 'external',
            ],
            'group' => ['ExternalComment.foreign_key'],
            'recursive' => -1,
            'contain' => false
        ]);

        $sadrIdsWithComments = Hash::extract($sadrIds, '{n}.ExternalComment.foreign_key');

        if (!empty($sadrIdsWithComments)) {
            $conditions[$this->alias . '.id'] = $sadrIdsWithComments;
        } else {
            $conditions[$this->alias . '.id'] = 0;
        }

        return $conditions;
    }



    // public function findByHasReview($data = [])
    // {
    //     $conditions = [];

    //     $sadrIdsWithComments = $this->ExternalComment->find('list', [
    //         'fields' => ['ExternalComment.foreign_key', 'ExternalComment.foreign_key'],
    //         'conditions' => array(
    //             'ExternalComment.model' => 'Sadr', 'ExternalComment.category' => 'external'
    //         ),
    //         // 'group' => ['ExternalComment.foreign_key'],
    //         'recursive' => -1
    //     ]);

    //     if (!empty($sadrIdsWithComments)) {
    //         $conditions[$this->alias . '.id'] = array_values($sadrIdsWithComments);
    //     } else {
    //         $conditions[$this->alias . '.id'] = 0; // Match nothing
    //     }

    //     return $conditions;
    // }
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

    public function findByMedicineName($data = array())
    {
        // debug($data['inn']);

        $cond = array($this->alias . '.id' => $this->SadrListOfMedicine->find('list', array(
            'conditions' => array(
                'SadrListOfMedicine.drug_name LIKE' => '%' . $data['inn'] . '%',
            ),
            'fields' => array('sadr_id', 'sadr_id')
        )));
        // debug($cond);
        // exit;
        return $cond;
    }
    public function findByMarketAuthority($data = array())
    {
        $conditions = array();
        $filter = $data['mah'];
        if ($filter == '0') {
            $conditions = array(
                'user_type' => 'Market Authority'
            );
        } else {
            $conditions = array(
                'OR' => array(
                    'NOT' => array('user_type' => 'Market Authority'),
                    'user_type IS NULL',
                    'user_type' => ''
                )
            );
        }

        $user = ClassRegistry::init('User')->find(
            'list',
            array(
                'conditions' => $conditions,
                'fields' => array('id', 'id')
            )
        );

        $cond = array($this->alias . '.user_id' => $user);
        return $cond;
    }

    public function reporterFilter($data = array())
    {
        $filter = $data['reporter'];
        $cond = array(
            'OR' => array(
                $this->alias . '.reporter_name LIKE' => '%' . $filter . '%',
                $this->alias . '.reporter_email LIKE' => '%' . $filter . '%',
                $this->alias . '.reporter_name_diff LIKE' => '%' . $filter . '%',
                $this->alias . '.reporter_email_diff LIKE' => '%' . $filter . '%',
            )
        );
        return $cond;
    }

    public function orConditions($data = array())
    {
        $filter = $data['submit'];
        if ($filter == '0') {
            $cond = array(
                $this->alias . '.submitted' => array('1', '2', '3'),
                // $this->alias . '.active' => '1'
            );
        } else {
            $cond = array(
                $this->alias . '.submitted' => array('0', '1', '2', '3', '4', '5', '6'),
                // $this->alias . '.active' => '1'
            );
        }
        return $cond;
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
        'SadrOriginal' => array(
            'className' => 'Sadr',
            'foreignKey' => 'sadr_id',
            'dependent' => true,
            'conditions' => array('SadrOriginal.copied' => '1'),
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'SadrListOfDrug' => array(
            'className' => 'SadrListOfDrug',
            'foreignKey' => 'sadr_id',
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
        'SadrDescription' => array(
            'className' => 'SadrDescription',
            'foreignKey' => 'sadr_id',
            'dependent' => true,
            'conditions' => '',
        ),
        'Attachment' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Attachment.model' => 'Sadr', 'Attachment.group' => 'attachment'),
        ),
        'SadrListOfMedicine' => array(
            'className' => 'SadrListOfMedicine',
            'foreignKey' => 'sadr_id',
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
        'SadrFollowup' => array(
            'className' => 'Sadr',
            'foreignKey' => 'sadr_id',
            'dependent' => false,
            'conditions' => array('SadrFollowup.report_type' => 'Followup'),
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Reminder' => array(
            'className' => 'Reminder',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Reminder.model' => 'Sadr'),
        ),
        'ExternalComment' => array(
            'className' => 'Comment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('ExternalComment.model' => 'Sadr', 'ExternalComment.category' => 'external'),
        ),
        'ReviewComment' => array(
            'className' => 'Comment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('ReviewComment.model' => 'Sadr', 'ReviewComment.category' => 'review'),
        ),
        'SadrReaction' => array(
            'className' => 'SadrReaction',
            'foreignKey' => 'sadr_id',
            'dependent' => true
        )


    );

    public $validate = array(
        /*'list' => array(
            'notBlank' => array(
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => 'Please tick at least one option in the list of drugs below'
            ),
        ),
		'form_id' => array(
            'notBlank' => array(
                'rule'     => 'notBlank',
                'required' => true,
				'on'       => 'create',
                'message'  => 'Please provide a form ID'
            ),
			'formIdExists' => array(
                'rule'     => 'formIdExists',
                'required' => true,
				'on'       => 'create',
                'message'  => 'The Unique form ID provided does not exist.'
            ),
        ),*/
        'report_title' => array(
            'notBlank' => array(
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => 'Please provide a title for the report'
            ),
        ),
        'patient_name' => array(
            'notBlank' => array(
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => 'Please provide a patient\'s name or initials'
            ),
        ),
        'report_type' => array(
            'notBlank' => array(
                'rule'     => 'notBlank',
                'required' => true,
                'on'       => 'create',
                'message'  => 'Please specify the type of report'
            ),
        ),
        'report_sadr' => array(
            'reportOn' => array(
                'rule'     => 'reportOn',
                'allowEmpty' => true,
                'message'  => 'Please specify what the report is on'
            ),
        ),
        'medicinal_product' => array(
            'productCategory' => array(
                'rule'     => 'productCategory',
                'allowEmpty' => true,
                'message'  => 'Please specify at least one product category'
            ),
        ),

        'date_of_birth' => array(
            'ageOrDate' => array(
                'rule'     => 'ageOrDate',
                // 'required' => false,
                'allowEmpty' => true,
                'message'  => 'Please specify the patient\'s date / Year of birth or age in months'
            ),
        ),
        'county_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Please select a county',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'designation_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Please specify your designation',
            ),
        ),
        // 'patient_address' => array(
        //           'instOrAddress' => array(
        //               'rule'     => 'instOrAddress',
        //               // 'required' => true,
        //               'message'  => 'Please provide the patient\'s address or the name of the institution'
        //           ),
        //       ),
        'gender' => array(
            'notBlank' => array(
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => 'Please specify the patient\'s gender'
            ),
        ),
        'date_of_onset_of_reaction' => array(
            'atLeastYear' => array(
                'rule'     => 'atLeastYear',
                'allowEmpty' => true,
                'message'  => 'Please specify date \ Year of onset of the reaction'
            ),
            'notInFuture' => array(
                'rule'     => 'notInFuture',
                'allowEmpty' => true,
                'message'  => 'Date of onset of reaction greater than today'
            ),
            'dateAfterStartDates' => array(
                'rule'     => 'dateAfterStartDates',
                'allowEmpty' => true,
                'message'  => 'The date of onset of the reaction must come after a start date in the list of start dates below'
            ),
            'greaterBirth' => array(
                'rule'     => 'greaterBirth',
                'allowEmpty' => true,
                'message'  => 'The date / year of birth cannot be less than the date of birth of the patient'
            ),
        ),
        'reaction' => array(
            'notBlank' => array(
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => 'Please provide a reaction'
            ),
        ),
        'description_of_reaction' => array(
            'notBlank' => array(
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => 'Please provide a brief description of the reaction'
            ),
        ),
        'sample' => array(
            'atleastOneSuspect' => array(
                'rule'     => 'atleastOneSuspect',
                'required' => false,
                'message'  => 'Please select at least one suspect drug'
            ),

        ),
        'action_taken' => array(
            'notBlank' => array(
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => 'Please specify the action taken'
            ),
        ),

        'serious' => array(
            'notBlank' => array(
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => 'Please specify if the reaction was serious'
            ),

        ),
        'serious_reason' => array(
            'custom' => array(
                'rule' => array('validateSeriousReason'),
                'message' => 'Please specify reason for seriousness'
            ),
        ),
        'outcome' => array(
            'notBlank' => array(
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => 'Please specify the outcome'
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
        //ensure reporter phone is numeric and 10 digits
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
        'weight' => array(
            'rule'    => 'numeric',
            'allowEmpty' => true,
            'message' => 'Please specify the numeric weight in Kilograms.'
        ),
        'height' => array(
            'rule'    => 'numeric',
            'allowEmpty' => true,
            'message' => 'Please specify the height in Centimeters.'
        )
    );
    public function validateSeriousReason($check)
    {
        $seriousValue = $this->data[$this->alias]['serious'];
        $reasonValue = current($check);

        if ($seriousValue === 'Yes' && empty($reasonValue)) {
            return false;
        }

        return true;
    }
    public function atleastOneSuspect($field = null)
    {
        if (!empty($this->data['SadrListOfDrug'])) {
            foreach ($this->data['SadrListOfDrug'] as $val) {
                if ($val['suspected_drug'] == 1) {
                    return true;
                }
            }
        }
        return false;
    }
    public function dateAfterStartDates($field = null)
    {
        if (!empty($this->data['SadrListOfDrug'])) {
            foreach ($this->data['SadrListOfDrug'] as $val) {
                // Only check for the suspected medicine:
                if ($val['suspected_drug']) {
                    if (!empty($field['date_of_onset_of_reaction']['day']) && !empty($field['date_of_onset_of_reaction']['month']) && !empty($field['date_of_onset_of_reaction']['year'])) {
                        return strtotime(implode('-', $field['date_of_onset_of_reaction'])) >= strtotime($val['start_date']);
                    } else {
                        return $field['date_of_onset_of_reaction']['year'] >= date('Y', strtotime($val['start_date']));
                    }
                }
            }
        }
        return true;
    }




    public function formIdExists($field = null)
    {
        return $this->find('count', array('conditions' => array('Sadr.id' => $field['form_id']))) > 0;
    }

    public function atLeastYear($field = null)
    {
        return !empty($field['date_of_onset_of_reaction']['year']);
    }

    public function notInFuture($field = null)
    {
        if (!empty($field['date_of_onset_of_reaction']['day']) && !empty($field['date_of_onset_of_reaction']['month']) && !empty($field['date_of_onset_of_reaction']['year'])) {
            return strtotime(implode('-', $field['date_of_onset_of_reaction'])) <= strtotime("+4 hours");
        } else {
            return $field['date_of_onset_of_reaction']['year'] <= date('Y');
        }
    }

    public function ageOrDate($field = null)
    {
        return !empty($this->data['Sadr']['date_of_birth']['year']) || !empty($this->data['Sadr']['age_group']);
    }

    public function reportOn($field = null)
    {
        return !empty($this->data['Sadr']['report_sadr']) || !empty($this->data['Sadr']['report_therapeutic']) || !empty($this->data['Sadr']['report_misuse']) || !empty($this->data['Sadr']['report_off_label']);
    }

    public function productCategory($field = null)
    {
        return !empty($this->data['Sadr']['medicinal_product']) || !empty($this->data['Sadr']['blood_products']) || !empty($this->data['Sadr']['herbal_product']) || !empty($this->data['Sadr']['cosmeceuticals']) || !empty($this->data['Sadr']['product_other']);
    }

    public function instOrAddress($field = null)
    {
        return !empty($field['patient_address']) || !empty($this->data['Sadr']['name_of_institution']);
    }

    public function greaterBirth($field = null)
    {
        if (!empty($field['date_of_onset_of_reaction']['year']) && isset($this->data['Sadr']['date_of_birth']['year'])) {
            return $field['date_of_onset_of_reaction']['year'] >= $this->data['Sadr']['date_of_birth']['year'];
        }
        return  true;
    }

    public function beforeValidate($options = array())
    {
        if (isset($this->data['SadrListOfDrug'])) {
            foreach ($this->data['SadrListOfDrug'] as $val) {
                if ($val['suspected_drug'] == 1) {
                    $this->data['Sadr']['list'] = 1;
                }
            }
        }
        return true;
    }

    public function beforeSave($options = array())
    {

        // $now = date('Y-m-d H:i:s');

        // // Automatically override 'created' and 'modified' fields
        // if (array_key_exists('created', $this->schema()) && empty($this->data[$this->alias]['created'])) {
        //     $this->data[$this->alias]['created'] = $now;
        // }

        // if (array_key_exists('modified', $this->schema())) {
        //     $this->data[$this->alias]['modified'] = $now;
        // }

        if (!empty($this->data['Sadr']['date_of_birth'])) {
            if (is_array($this->data['Sadr']['date_of_birth'])) {
                $this->data['Sadr']['date_of_birth'] = implode('-', $this->data['Sadr']['date_of_birth']);
            } else {
                $this->data['Sadr']['date_of_birth'] = $this->data['Sadr']['date_of_birth'];
            }
        } else {
            $this->data['Sadr']['date_of_birth'] = '';
        }

        if (!empty($this->data['Sadr']['date_of_onset_of_reaction'])) {
            $this->data['Sadr']['date_of_onset_of_reaction'] = implode('-', $this->data['Sadr']['date_of_onset_of_reaction']);
        } else {
            $this->data['Sadr']['date_of_onset_of_reaction'] = '';
        }

        if (empty($this->data['Sadr']['pregnancy_status'])) {
            $this->data['Sadr']['pregnancy_status'] = '';
        }
        if (empty($this->data['Sadr']['age_group'])) {
            $this->data['Sadr']['age_group'] = '';
        }
        if (!empty($this->data['Sadr']['reporter_date'])) {
            $this->data['Sadr']['reporter_date'] = $this->dateFormatBeforeSave($this->data['Sadr']['reporter_date']);
        }
        if (!empty($this->data['Sadr']['reporter_date_diff'])) {
            $this->data['Sadr']['reporter_date_diff'] = $this->dateFormatBeforeSave($this->data['Sadr']['reporter_date_diff']);
        }
        if (!empty($this->data['Sadr']['product_category'])) {
            $data = $this->data['Sadr']['product_category'];
            if ($data == "Medicinal product") {
                $this->data['Sadr']['medicinal_product'] = '1';
            } else {
                $this->data['Sadr']['medicinal_product'] = '0';
            }
            if ($data == "Herbal product") {
                $this->data['Sadr']['herbal_product'] = '1';
            } else {
                $this->data['Sadr']['herbal_product'] = '0';
            }
            if ($data == "Cosmeceuticals") {
                $this->data['Sadr']['cosmeceuticals'] = '1';
            } else {
                $this->data['Sadr']['cosmeceuticals'] = '0';
            }
            if ($data == "Others") {
                $this->data['Sadr']['product_other'] = '1';
            } else {
                $this->data['Sadr']['product_other'] = '0';
            }
        }
        return true;
    }

    public function afterFind($results, $primary = false)
    {
        foreach ($results as $key => $val) {
            if (!empty($val['Sadr']['date_of_birth'])) {
                if (empty($val['Sadr']['date_of_birth'])) $val['Sadr']['date_of_birth'] = '--';
                $a = explode('-', $val['Sadr']['date_of_birth']);
                $results[$key]['Sadr']['date_of_birth'] = array('day' => $a[0], 'month' => $a[1], 'year' => $a[2]);
            }
            if (!empty($val['Sadr']['date_of_onset_of_reaction'])) {
                if (empty($val['Sadr']['date_of_onset_of_reaction'])) $val['Sadr']['date_of_onset_of_reaction'] = '--';
                $b = explode('-', $val['Sadr']['date_of_onset_of_reaction']);
                $results[$key]['Sadr']['date_of_onset_of_reaction'] = array('day' => $b[0], 'month' => $b[1], 'year' => $b[2]);
            }
            if (isset($val['Sadr']['created'])) {
                $results[$key]['Sadr']['created'] = $this->dateFormatAfterFind($val['Sadr']['created']);
            }
            if (isset($val['SadrListOfDrug'])) {
                foreach ($val['SadrListOfDrug'] as $kay => $vall) {
                    if (isset($vall['start_date'])) {
                        $results[$key]['SadrListOfDrug'][$kay]['start_date'] = $this->dateFormatAfterFind($vall['start_date']);
                    }
                    if (isset($vall['stop_date'])) {
                        $results[$key]['SadrListOfDrug'][$kay]['stop_date'] = $this->dateFormatAfterFind($vall['stop_date']);
                    }
                }
            }
            if (isset($val['Sadr']['reporter_date'])) {
                $results[$key]['Sadr']['reporter_date'] = $this->dateFormatAfterFind($val['Sadr']['reporter_date']);
            }
            if (isset($val['Sadr']['reporter_date_diff'])) {
                $results[$key]['Sadr']['reporter_date_diff'] = $this->dateFormatAfterFind($val['Sadr']['reporter_date_diff']);
            }
        }
        return $results;
    }
}
