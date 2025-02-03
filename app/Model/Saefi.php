<?php
App::uses('AppModel', 'Model');
App::uses('Time', 'Utility');

/**
 * Saefi Model
 *
 * @property User $User
 * @property Saefi $Saefi
 * @property County $County 
 * @property SubCounty $SubCounty
 * @property Designation $Designation
 * @property Saefi $Saefi
 */
class Saefi extends AppModel
{

	public $recursive = 1;
	public $actsAs = array('Search.Searchable', 'Containable');

	public $filterArgs = array(
		'reference_no' => array('type' => 'like', 'encode' => true),
		// 'report_title' => array('type' => 'like', 'encode' => true),
		// 'name_of_institution' => array('type' => 'like', 'encode' => true),
		// 'serious' => array('type' => 'like', 'encode' => true),
		'range' => array('type' => 'expression', 'method' => 'makeRangeCondition', 'field' => 'CAST(Saefi.submitted_date as DATE) BETWEEN ? AND ?'),
		'reportrange' => array('type' => 'expression', 'method' => 'makeRangeCondition', 'field' => 'CAST(Saefi.reporter_date as DATE) BETWEEN ? AND ?'),
        'filter_by' => array('type' => 'query', 'method' => 'dummy'),
		'start_date' => array('type' => 'query', 'method' => 'dummy'),
		'end_date' => array('type' => 'query', 'method' => 'dummy'),
		'county_id' => array('type' => 'value'),
		'archived' => array('type' => 'value'),
		// 'vaccine_name' => array('type' => 'query', 'method' => 'findByVaccineName', 'encode' => true),
		// 'health_program' => array('type' => 'query', 'method' => 'findByHealthProgram', 'encode' => true),
		// 'bcg' => array('type' => 'value'),
		// 'convulsion' => array('type' => 'value'),
		// 'urticaria' => array('type' => 'value'),
		// 'high_fever' => array('type' => 'value'),
		// 'abscess' => array('type' => 'value'),
		// 'local_reaction' => array('type' => 'value'),
		// 'anaphylaxis' => array('type' => 'value'),
		// 'paralysis' => array('type' => 'value'),
		// 'toxic_shock' => array('type' => 'value'),
		// 'complaint_other' => array('type' => 'value'),
		// 'complaint_other_specify' => array('type' => 'like', 'encode' => true),
		// 'patient_name' => array('type' => 'like', 'encode' => true),
		// 'report_type' => array('type' => 'value'),
		// 'serious_yes' => array('type' => 'value'),
		// 'outcome' => array('type' => 'value'),
		// 'reporter' => array('type' => 'query', 'method' => 'reporterFilter', 'encode' => true),
		'designation_id' => array('type' => 'value'),
		// 'gender' => array('type' => 'value'),
		'submitted' => array('type' => 'value'),
		// 'submit' => array('type' => 'query', 'method' => 'orConditions', 'encode' => true),
	);
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(

		'date_of_birth' => array(
			'ageOrDate' => array(
				'rule'     => 'ageOrDate',
				// 'required' => false,
				'allowEmpty' => true,
				'message'  => 'Please specify the patient\'s date / Year of birth or age in months'
			),
		),
		'province_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please select a county',
			),
		),
		'district' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please select a sub county',
			),
		), 'name_of_vaccination_site' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please specify vaccinaition site'
			),
		),
		'mobile' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide the mobile number of the reporter'
			),
		),
		'place_vaccination' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide the place of vaccination'
			),
		),


		'designation_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please specify your designation',
			),
		),
		'gender' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please specify the patient\'s gender'
			),
		),
		'list' => array(
			'atLeastOneVaccine' => array(
				'rule'     => 'atLeastOneVaccine',
				// 'required' => true,
				'message'  => 'Please add at least one vaccine below'
			),
		),
		'reporter_name' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide the name of the reporter'
			),
		),
		'reporter_email' => array(
			'notBlank' => array(
				'rule'     => 'email',
				'required' => true,
				'message'  => 'Please provide a valid email address'
			),
		),
		'vaccination_in' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide vaccination type'
			),
		), 'report_date' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide report date'
			),
		),
		'start_date' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide start date'
			),
		),
		'site_type' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide site type'
			),
		),
		'symptom_date' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide symptom date'
			),
		),
		'hospitalization_date' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide symptom date'
			),
		),

		// Section Here
		'examiner_name' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide the name of the investigator'
			),
		),
		'person_details' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide the person details'
			),
		),
		'person_date' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide the date'
			),
		),

		'person_designation' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide the designation'
			),
		),
		'final_diagnosis' => array(
			'notBlank' => array(
				'rule'     => 'notBlank',
				'required' => true,
				'message'  => 'Please provide the Final Diagnosis'
			),
		),
		// Start Here
		'past_history_remarks' => array(
			'pastHistoryYes' => array(
				'rule'     => 'pastHistoryYes',
				'required' => false,
				'message'  => 'Please specify the reason for past history!!'
			),
		),
		'adverse_event_remarks' => array(
			'adverseEventYes' => array(
				'rule'     => 'adverseEventYes',
				'required' => false,
				'message'  => 'Please specify the reason for adverse event!!'
			),
		),
		'allergy_history_remarks' => array(
			'allergyHistoryYes' => array(
				'rule'     => 'allergyHistoryYes',
				'required' => false,
				'message'  => 'Please specify the reason for allergy history!!'
			),
		),
		'comorbidity_disorder_remarks' => array(
			'comorbidityDisorderYes' => array(
				'rule'     => 'comorbidityDisorderYes',
				'required' => false,
				'message'  => 'Please specify the reason for comorbidity disorder!!'
			),
		),
		'existing_illness_remarks' => array(
			'existingIllnessYes' => array(
				'rule'     => 'existingIllnessYes',
				'required' => false,
				'message'  => 'Please specify the reason for existing illness!!'
			),
		),
		'covid_positive_remarks' => array(
			'covidPositiveYes' => array(
				'rule'     => 'covidPositiveYes',
				'required' => false,
				'message'  => 'Please specify the reason for covid positive!!'
			),
		),
		'hospitalization_history_remarks' => array(
			'hospitalizationHistoryYes' => array(
				'rule'     => 'hospitalizationHistoryYes',
				'required' => false,
				'message'  => 'Please specify the reason for hospitalization history!!'
			),
		),
		'medication_vaccination_remarks' => array(
			'medicationVaccinationYes' => array(
				'rule'     => 'medicationVaccinationYes',
				'required' => false,
				'message'  => 'Please specify the reason for medication vaccination!!'
			),
		),

		'faith_healers_remarks' => array(
			'faithHealersYes' => array(
				'rule'     => 'faithHealersYes',
				'required' => false,
				'message'  => 'Please specify the reason for faith healers!!'
			),
		),
		'family_history_remarks' => array(
			'familyHistoryYes' => array(
				'rule'     => 'familyHistoryYes',
				'required' => false,
				'message'  => 'Please specify the reason for family history!!'
			),
		),
		'prescribing_error_specify' => array(
			'prescribingErrorYes' => array(
				'rule'     => 'prescribingErrorYes',
				'required' => false,
				'message'  => 'Please specify the reason for prescribing error!!'
			),
		),

		'vaccine_unsterile_specify' => array(
			'vaccineUnsterileYes' => array(
				'rule'     => 'vaccineUnsterileYes',
				'required' => false,
				'message'  => 'Please specify the reason for vaccine unsterile!!'
			),
		),

		'vaccine_condition_specify' => array(
			'vaccineConditionYes' => array(
				'rule'     => 'vaccineConditionYes',
				'required' => false,
				'message'  => 'Please specify the reason for vaccine condition!'
			),
		),
		'vaccine_reconstitution_specify' => array(
			'vaccineReconstitutionYes' => array(
				'rule'     => 'vaccineReconstitutionYes',
				'required' => false,
				'message'  => 'Please specify the reason for vaccine reconstitution!'
			),
		),
		'vaccine_handling_specify' => array(
			'vaccineHandlingYes' => array(
				'rule'     => 'vaccineHandlingYes',
				'required' => false,
				'message'  => 'Please specify the reason for vaccine handling!'
			),
		),

		'vaccine_administered_specify' => array(
			'vaccineAdministeredYes' => array(
				'rule'     => 'vaccineAdministeredYes',
				'required' => false,
				'message'  => 'Please specify the reason for vaccine administered!'
			),
		),
	);

	public function vaccineAdministeredYes($field = null)
	{
		if ($this->data['Saefi']['vaccine_administered'] == 'Yes') return !empty($this->data['Saefi']['vaccine_administered_specify']);
		else return true;
	}

	public function vaccineHandlingYes($field = null)
	{
		if ($this->data['Saefi']['vaccine_handling'] == 'Yes') return !empty($this->data['Saefi']['vaccine_handling_specify']);
		else return true;
	}
	public function vaccineReconstitutionYes($field = null)
	{
		if ($this->data['Saefi']['vaccine_reconstitution'] == 'Yes') return !empty($this->data['Saefi']['vaccine_reconstitution_specify']);
		else return true;
	}
	public function vaccineConditionYes($field = null)
	{
		if ($this->data['Saefi']['vaccine_condition'] == 'Yes') return !empty($this->data['Saefi']['vaccine_condition_specify']);
		else return true;
	}
	public function vaccineUnsterileYes($field = null)
	{
		if ($this->data['Saefi']['vaccine_unsterile'] == 'Yes') return !empty($this->data['Saefi']['vaccine_unsterile_specify']);
		else return true;
	}
	public function prescribingErrorYes($field = null)
	{
		if ($this->data['Saefi']['prescribing_error'] == 'Yes') return !empty($this->data['Saefi']['prescribing_error_specify']);
		else return true;
	}
	public function familyHistoryYes($field = null)
	{
		if ($this->data['Saefi']['family_history'] == 'Yes') return !empty($this->data['Saefi']['family_history_remarks']);
		else return true;
	}
	public function faithHealersYes($field = null)
	{
		if ($this->data['Saefi']['faith_healers'] == 'Yes') return !empty($this->data['Saefi']['faith_healers_remarks']);
		else return true;
	}

	public function medicationVaccinationYes($field = null)
	{
		if ($this->data['Saefi']['medication_vaccination'] == 'Yes') return !empty($this->data['Saefi']['medication_vaccination_remarks']);
		else return true;
	}

	public function hospitalizationHistoryYes($field = null)
	{
		if ($this->data['Saefi']['hospitalization_history'] == 'Yes') return !empty($this->data['Saefi']['hospitalization_history_remarks']);
		else return true;
	}

	public function covidPositiveYes($field = null)
	{
		if ($this->data['Saefi']['covid_positive'] == 'Yes') return !empty($this->data['Saefi']['covid_positive_remarks']);
		else return true;
	}

	public function existingIllnessYes($field = null)
	{
		if ($this->data['Saefi']['existing_illness'] == 'Yes') return !empty($this->data['Saefi']['existing_illness_remarks']);
		else return true;
	}

	public function comorbidityDisorderYes($field = null)
	{
		if ($this->data['Saefi']['comorbidity_disorder'] == 'Yes') return !empty($this->data['Saefi']['comorbidity_disorder_remarks']);
		else return true;
	}
	public function allergyHistoryYes($field = null)
	{
		if ($this->data['Saefi']['allergy_history'] == 'Yes') return !empty($this->data['Saefi']['allergy_history_remarks']);
		else return true;
	}
	public function adverseEventYes($field = null)
	{
		if ($this->data['Saefi']['adverse_event'] == 'Yes') return !empty($this->data['Saefi']['adverse_event_remarks']);
		else return true;
	}
	public function pastHistoryYes($field = null)
	{
		if ($this->data['Saefi']['past_history'] == 'Yes') return !empty($this->data['Saefi']['past_history_remarks']);
		else return true;
	}
	public function checkFieldNotEmpty($mainField = null, $subField = null)
	{
		if ($this->data['Saefi'][$mainField] == 'Yes') {
			return !empty($this->data['Saefi'][$subField]);
		} else {
			return true;
		}
	}


	public function atLeastOneVaccine($field = null)
	{
		if (!empty($this->data['AefiListOfVaccine'])) {
			return count($this->data['AefiListOfVaccine']) > 0;
		}
		return false;
	}

	public function ageOrDate($field = null)
	{
		return !empty($this->data['Saefi']['date_of_birth']) || !empty($this->data['Saefi']['age_at_onset_years']) || !empty($this->data['Saefi']['age_group']);
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
			'foreignKey' => 'province_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'SubCounty' => array(
			'className' => 'SubCounty',
			'foreignKey' => 'district',
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
		)
	);

	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
		'Saefi' => array(
			'className' => 'Saefi',
			'foreignKey' => 'saefi_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'AefiListOfVaccine' => array(
			'className' => 'AefiListOfVaccine',
			'foreignKey' => 'saefi_id',
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
			'conditions' => array('Attachment.model' => 'Saefi', 'Attachment.group' => 'attachment'),
		),
		'ExternalComment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'dependent' => true,
			'conditions' => array('ExternalComment.model' => 'Saefi', 'ExternalComment.category' => 'external'),
		),
		'Review' => array(
			'className' => 'Review',
			'foreignKey' => 'saefi_id',
			'dependent' => true,
		),
	);

	public function beforeSave($options = array())
	{
		if (!empty($this->data[$this->alias]['report_date'])) {
			$this->data[$this->alias]['report_date'] = date('Y-m-d', strtotime($this->data[$this->alias]['report_date']));
		}
		if (!empty($this->data[$this->alias]['start_date'])) {
			$this->data[$this->alias]['start_date'] = date('Y-m-d', strtotime($this->data[$this->alias]['start_date']));
		}
		if (!empty($this->data[$this->alias]['symptom_date'])) {
			$this->data[$this->alias]['symptom_date'] = date('Y-m-d', strtotime($this->data[$this->alias]['symptom_date']));
		}
		if (!empty($this->data[$this->alias]['hospitalization_date'])) {
			$this->data[$this->alias]['hospitalization_date'] = date('Y-m-d', strtotime($this->data[$this->alias]['hospitalization_date']));
		}
		if (!empty($this->data[$this->alias]['died_date'])) {
			$this->data[$this->alias]['died_date'] = date('Y-m-d', strtotime($this->data[$this->alias]['died_date']));
		}
		if (!empty($this->data[$this->alias]['autopsy_done_date'])) {
			$this->data[$this->alias]['autopsy_done_date'] = date('Y-m-d', strtotime($this->data[$this->alias]['autopsy_done_date']));
		}

		if (!empty($this->data[$this->alias]['reporter_date'])) {
			$this->data[$this->alias]['reporter_date'] = date('Y-m-d', strtotime($this->data[$this->alias]['reporter_date']));
		}

		if (!empty($this->data[$this->alias]['complete_date'])) {
			$this->data[$this->alias]['complete_date'] = date('Y-m-d', strtotime($this->data[$this->alias]['complete_date']));
		}
		if (!empty($this->data[$this->alias]['person_date'])) {
			$this->data[$this->alias]['person_date'] = date('Y-m-d', strtotime($this->data[$this->alias]['person_date']));
		}
		if (!empty($this->data[$this->alias]['date_of_birth'])) {
			$this->data[$this->alias]['date_of_birth'] = date('Y-m-d', strtotime($this->data[$this->alias]['date_of_birth']));
		}

		// Error: SQLSTATE[22007]: Invalid datetime format: 1292 Incorrect date value: '2011-02-30' for column '' at row 1

		// if (!empty($this->data['Saefi']['time_of_first_symptom'])) {
		//     $time = strtotime($this->data['Saefi']['time_of_first_symptom']);
		//     $this->data['Saefi']['time_of_first_symptom'] = date('H:i:s', $time);
		// }
		// if (!empty($this->data['Saefi']['hour']) && !empty($this->data['Saefi']['minute'])) {
		//     $time = new Time($this->data['Saefi']['hour'] . ':' . $this->data['Saefi']['minute']);
		//     $this->data['Saefi']['time_of_first_symptom'] = $time->format('H:i:s');
		// }
		// if (!empty($this->data['Saefi']['time_of_first_symptom'])) {
		//     $this->data['Saefi']['time_of_first_symptom'] = implode('-', $this->data['Saefi']['time_of_first_symptom']);
		// } else {
		//     $this->data['Saefi']['time_of_first_symptom'] = '';
		// }

		return true;
	}
}
