<?php
App::uses('AppController', 'Controller');
/**
 * FacilityCodes Controller
 *
 * @property FacilityCode $FacilityCode
 */
class FacilityCodesController extends AppController
{

	public $components = array('Search.Prg');
	public $paginate = array();
	public $presetVars = array(
		array('field' => 'facility_code', 'type' => 'value'),
		array('field' => 'facility_name', 'type' => 'value'),
	);


	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('index','admin_upload_pharmacy', 'autocomplete', 'api_autocomplete', 'api_index', 'wards');
	}

	public function admin_upload_pharmacy() {
		if ($this->request->is('post')) {
			// Handle form submission
			$file = $this->request->data['Upload']['csv_file'];

			if (!empty($file['tmp_name'])) {
				$csvData = array_map('str_getcsv', file($file['tmp_name']));
				$header = array_shift($csvData);
				set_time_limit(600);

				// Validate and save each row
				foreach ($csvData as $row) {

					// $existingRecord = $this->FacilityCode->findByFacilityCode($this->verify_code($row[0]));

					$data = array(
						'facility_code' => null,//$this->verify_code($row[0]),
						'facility_name' => $row[0],
						'keph_level' => null,//$row[4],
						'type' => $row[1],
						'owner' => null,//$row[7],
						'beds' => null,//$row[10],
						'cots' =>null,// $row[11],
						'province' => $this->get_province_by_county($row[2]),
						'county' => $row[2],
						'constituency' => null,//$row[13],
						'sub_county' => $row[3],
						'ward' => null,//$row[15],
						'operational_status' => null,//$row[16],
						'open_weekends' => null,//$row[20],
						'open_24hrs' => null,//$this->determine_24_hour($row[18], $row[21]),

						// Add more columns as needed
					);
					// if ($existingRecord) {
					// 	// Update the existing record
					// 	$this->FacilityCode->id = $existingRecord['FacilityCode']['id'];
					// } else {
						// Create a new record
						$this->FacilityCode->create();
					// }
					$this->FacilityCode->set($data);

					if ($this->FacilityCode->save($data,['validate'=>false])) {
						// Record saved successfully
					} else {
						$errors = $this->FacilityCode->validationErrors;
						// Handle validation errors
						$this->Session->setFlash(__('Experienced problems uploading data' . $errors), 'flash_error');
						$this->redirect(array('action' => 'index'));
					}
				}
				$this->Session->setFlash(__('Facilities uploaded successfully'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				// Handle file upload error
				$this->Session->setFlash(__('Facilities uploaded successfully'), 'flash_error');
				$this->redirect(array('action' => 'index'));
			}
		}
		$this->Session->setFlash(__('Facilities uploaded successfully'), 'flash_success');
		$this->redirect(array('action' => 'index'));
	}
	public function autocomplete($query = null)
	{
		$this->RequestHandler->setContent('json', 'application/json');
		if (is_numeric($this->request->query['term'])) {
			$coders = $this->FacilityCode->finder($this->request->query['term'], 'N');
		} else {
			$coders = $this->FacilityCode->finder($this->request->query['term'], 'A');
		}
		$codes = array();
		foreach ($coders as $key => $value) {
			$codes[] = array(
				'value' => $value['FacilityCode']['facility_code'], 'label' => $value['FacilityCode']['facility_name'],  'sub_county' => $value['FacilityCode']['district'],
				'desc' => $value['FacilityCode']['county'], 'addr' => $value['FacilityCode']['official_address'], 'phone' => $value['FacilityCode']['official_mobile']
			);
		}
		$this->set('codes', $codes);
		$this->set('_serialize', 'codes');
	}
	public function wards($query = null)
	{
		$this->RequestHandler->setContent('json', 'application/json'); 
		$suggestions = $this->FacilityCode->find('all', array(
			'fields' => array('DISTINCT FacilityCode.ward'), // Use DISTINCT to get unique ward values
			'conditions' => array('FacilityCode.sub_county LIKE' => '%' . $query . '%'),
			'limit' => 10,
			'recursive' => -1
		)); 
		// Now $uniqueWards contains the unique ward values based on the sub_county condition
		
		$codes = array();
		foreach ($suggestions as $key => $value) {
			$codes[] = array( 
				'ward' => $value['FacilityCode']['ward']
			);
		}
		$this->set('codes', $codes);
		$this->set('_serialize', 'codes');
	}

	public function api_autocomplete($query = null)
	{
		$this->RequestHandler->setContent('json', 'application/json');
		if (is_numeric($this->request->query['term'])) {
			$coders = $this->FacilityCode->finder($this->request->query['term'], 'N');
		} else {
			$coders = $this->FacilityCode->finder($this->request->query['term'], 'A');
		}
		$codes = array();
		foreach ($coders as $key => $value) {
			$codes[] = array(
				'value' => $value['FacilityCode']['facility_code'], 'label' => $value['FacilityCode']['facility_name'],  'sub_county' => $value['FacilityCode']['district'],
				'desc' => $value['FacilityCode']['county'], 'addr' => $value['FacilityCode']['official_address'], 'phone' => $value['FacilityCode']['official_mobile']
			);
		}
		$this->set('codes', $codes);
		$this->set('_serialize', 'codes');
	}


	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->FacilityCode->recursive = 0;
		$this->set('facilityCodes', $this->paginate());
	}

	public function admin_index()
	{
		$this->Prg->commonProcess();
		$criteria = $this->FacilityCode->parseCriteria($this->passedArgs);
		// $criteria['Sadr.user_id'] = $this->Auth->user('id');
		$this->paginate['conditions'] = $criteria;
		$this->FacilityCode->recursive = -1;
		$this->paginate['limit'] = 20;
		$this->paginate['order'] = array('FacilityCode.facility_name' => 'asc');
		$this->set('facility_Codes', $this->paginate());
	}

	public function api_index()
	{
		$this->FacilityCode->recursive = -1;
		$result =  $this->FacilityCode->find('all', array(
			'fields' =>  array('FacilityCode.facility_code', 'FacilityCode.facility_name',  'FacilityCode.official_address', 'FacilityCode.official_mobile', 'FacilityCode.district', 'FacilityCode.county'),
			'order' => array('FacilityCode.facility_name' => 'asc')
		));
		$facilityCodes = array();
		foreach ($result as $key => $value) {
			$facilityCodes[] = $value['FacilityCode'];
			// print_r($value);
		}
		$this->set('facilityCodes', $facilityCodes);
		$this->set('_serialize', array('facilityCodes'));
	}

	/**
	 * view method
	 *
	 * @param string $id
	 * @return void
	 */
	public function view($id = null)
	{
		$this->FacilityCode->id = $id;
		if (!$this->FacilityCode->exists()) {
			throw new NotFoundException(__('Invalid facility code'));
		}
		$this->set('facilityCode', $this->FacilityCode->read(null, $id));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		if ($this->request->is('post')) {
			$this->FacilityCode->create();
			if ($this->FacilityCode->save($this->request->data)) {
				$this->Session->setFlash(__('The facility code has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The facility code could not be saved. Please, try again.'));
			}
		}
	}

	public function admin_add()
	{
		if ($this->request->is('post')) {
			$this->FacilityCode->create();
			if ($this->FacilityCode->save($this->request->data)) {
				$this->Session->setFlash(__('The facility code has been saved'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The facility code could not be saved. Please, try again.'), 'flash_error');
			}
		}
	}

	/**
	 * edit method
	 *
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null)
	{
		$this->FacilityCode->id = $id;
		if (!$this->FacilityCode->exists()) {
			throw new NotFoundException(__('Invalid facility code'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->FacilityCode->save($this->request->data)) {
				$this->Session->setFlash(__('The facility code has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The facility code could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->FacilityCode->read(null, $id);
		}
	}

	public function admin_edit($id = null)
	{
		$this->FacilityCode->id = $id;
		if (!$this->FacilityCode->exists()) {
			throw new NotFoundException(__('Invalid facility code'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->FacilityCode->save($this->request->data)) {
				$this->Session->setFlash(__('The facility code has been saved'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The facility code could not be saved. Please, try again.'), 'flash_error');
			}
		} else {
			$this->request->data = $this->FacilityCode->read(null, $id);
		}
	}

	/**
	 * delete method
	 *
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null)
	{
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->FacilityCode->id = $id;
		if (!$this->FacilityCode->exists()) {
			throw new NotFoundException(__('Invalid facility code'));
		}
		if ($this->FacilityCode->delete()) {
			$this->Session->setFlash(__('Facility code deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Facility code was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

	public function admin_delete($id = null)
	{
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->FacilityCode->id = $id;
		if (!$this->FacilityCode->exists()) {
			throw new NotFoundException(__('Invalid facility code'));
		}
		if ($this->FacilityCode->delete()) {
			$this->Session->setFlash(__('Facility code deleted'), 'flash_success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Facility code was not deleted'), 'flash_error');
		$this->redirect(array('action' => 'index'));
	}

	public function admin_upload()
	{
		if ($this->request->is('post')) {
			// Handle form submission
			$file = $this->request->data['Upload']['csv_file'];

			if (!empty($file['tmp_name'])) {
				$csvData = array_map('str_getcsv', file($file['tmp_name']));
				$header = array_shift($csvData);
				set_time_limit(600);

				// Validate and save each row
				foreach ($csvData as $row) {

					$existingRecord = $this->FacilityCode->findByFacilityCode($this->verify_code($row[0]));

					$data = array(
						'facility_code' => $this->verify_code($row[0]),
						'facility_name' => $row[1],
						'keph_level' => $row[4],
						'type' => $row[5],
						'owner' => $row[7],
						'beds' => $row[10],
						'cots' => $row[11],
						'province' => $this->get_province_by_county($row[12]),
						'county' => $row[12],
						'constituency' => $row[13],
						'sub_county' => $row[14],
						'ward' => $row[15],
						'operational_status' => $row[16],
						'open_weekends' => $row[20],
						'open_24hrs' => $this->determine_24_hour($row[18], $row[21]),

						// Add more columns as needed
					);
					if ($existingRecord) {
						// Update the existing record
						$this->FacilityCode->id = $existingRecord['FacilityCode']['id'];
					} else {
						// Create a new record
						$this->FacilityCode->create();
					}
					$this->FacilityCode->set($data);

					if ($this->FacilityCode->save($data)) {
						// Record saved successfully
					} else {
						$errors = $this->FacilityCode->validationErrors;
						// Handle validation errors
						$this->Session->setFlash(__('Experienced problems uploading data' . $errors), 'flash_error');
						$this->redirect(array('action' => 'index'));
					}
				}
				$this->Session->setFlash(__('Facilities uploaded successfully'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				// Handle file upload error
				$this->Session->setFlash(__('Facilities uploaded successfully'), 'flash_error');
				$this->redirect(array('action' => 'index'));
			}
		}
		$this->Session->setFlash(__('Facilities uploaded successfully'), 'flash_success');
		$this->redirect(array('action' => 'index'));
	}

	public function verify_code($code)
	{
		if ($code == "None") {
			return null;
		}
		return $code;
	}

	public function determine_24_hour($day, $night)
	{
		$fal = "No";
		if ($day == "Yes" && $night == "Yes") {
			$fal = "Yes";
		}
		return $fal;
	}
	public function get_province_by_county($countyName)
	{
		$countyName = strtolower($countyName);

		$provinceMapping = array(
			'nairobi' => 'Nairobi',
			'mombasa' => 'Coast',
			'kwale' => 'Coast',
			'kilifi' => 'Coast',
			'tana river' => 'Coast',
			'lamu' => 'Coast',
			'taita-taveta' => 'Coast',
			'garissa' => 'North Eastern',
			'wajir' => 'North Eastern',
			'mandera' => 'North Eastern',
			'marsabit' => 'Eastern',
			'isiolo' => 'Eastern',
			'meru' => 'Eastern',
			'tharaka-nithi' => 'Eastern',
			'embu' => 'Eastern',
			'kitui' => 'Eastern',
			'machakos' => 'Eastern',
			'makueni' => 'Eastern',
			'nyandarua' => 'Central',
			'nyeri' => 'Central',
			'kirinyaga' => 'Central',
			'murang\'a' => 'Central',
			'kiambu' => 'Central',
			'turkana' => 'Rift Valley',
			'west pokot' => 'Rift Valley',
			'samburu' => 'Rift Valley',
			'trans nzoia' => 'Rift Valley',
			'uasin gishu' => 'Rift Valley',
			'elgeyo-marakwet' => 'Rift Valley',
			'nandi' => 'Rift Valley',
			'baringo' => 'Rift Valley',
			'laikipia' => 'Rift Valley',
			'nakuru' => 'Rift Valley',
			'narok' => 'Rift Valley',
			'kajiado' => 'Rift Valley',
			'kericho' => 'Rift Valley',
			'bomet' => 'Rift Valley',
			'kakamega' => 'Western',
			'vihiga' => 'Western',
			'bungoma' => 'Western',
			'busia' => 'Western',
			'siaya' => 'Nyanza',
			'kisumu' => 'Nyanza',
			'homa bay' => 'Nyanza',
			'migori' => 'Nyanza',
			'kisii' => 'Nyanza',
			'nyamira' => 'Nyanza'
		);

		if (isset($provinceMapping[$countyName])) {
			return $provinceMapping[$countyName];
		}

		return null;
	}
}
