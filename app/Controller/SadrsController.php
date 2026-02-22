<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeText', 'Utility');
App::uses('ThemeView', 'View');
App::uses('HtmlHelper', 'View/Helper');
App::uses('HttpSocket', 'Network/Http');
App::uses('Router', 'Routing');

/**
 * Sadrs Controller
 *
 * @property Sadr $Sadr
 */
class SadrsController extends AppController
{
    public $components = array(
        // 'Security' => array('csrfExpires' => '+1 hour', 'validatePost' => false), 
        'Search.Prg',
        // 'RequestHandler'
    );
    public $paginate = array();
    public $presetVars = true;
    public $page_options = array('25' => '25', '50' => '50', '100' => '100');

    // Short Term Goal 
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('yellowcard','manager_bulk_action', 'guest_add', 'guest_edit', 'manager_check_missing');
    }

    public function manager_bulk_action() {
// debug($this->request->data);
// exit;
$this->manager_index();
	}

    public function admin_file_upload()
    {
        if ($this->request->is('post')) {

            // debug($this->request->data);
            // exit;
            if (!empty($this->request->data['Sadr']['file'])) {
                $file = $this->request->data['Sadr']['file'];

                // Check if file is a CSV
                $allowedTypes = array('text/csv', 'application/vnd.ms-excel');
                $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);

                if (!in_array($file['type'], $allowedTypes) || strtolower($fileExt) !== 'csv') {
                    $this->Session->setFlash(__('Only CSV files are allowed.'), 'default', array('class' => 'alert alert-danger'));
                    return $this->redirect($this->referer());
                }

                // Define upload directory (ensure it exists and is writable)
                $uploadPath = WWW_ROOT . 'files' . DS . 'sadrs' . DS;
                $fileName = time() . '_' . $file['name']; // Unique filename
                $uploadFile = $uploadPath . $fileName;

                if (move_uploaded_file($file['tmp_name'], $uploadFile)) {

                    // Let's manupulate the data

                    // $handle = fopen($file['tmp_name'], "r");
                    $handle = fopen($uploadFile, "r");
                    if ($handle !== false) {
                        $header = fgetcsv($handle); // Read header row

                        // Ensure header matches expected fields
                        $expectedColumns = array(
                            'report_type',
                            'county',
                            'sub_county',
                            'report_title',
                            'patient_name',
                            'date_of_birth',
                            'age_group',
                            'gender',
                            'height',
                            'weight',
                            'report_sadr',
                            'report_therapeutic',
                            'product_category',
                            'medicinal_product',
                            'blood_products',
                            'herbal_product',
                            'cosmeceuticals',
                            'product_other',
                            'product_specify',
                            'name_of_institution',
                            'institution_code',
                            'pregnancy_status',
                            'known_allergy',
                            'known_allergy_specify',
                            'onset_date',
                            'reaction',
                            'reactions',
                            'drugs',
                            'brands',
                            'batch_no',
                            'manufacturers',
                            'start_date',
                            'end_date',
                            'drug_indication',
                            'suspected_drug',
                            'suspected_drug_name',
                            'suspected_drug_brand',
                            'suspected_drug_manufacturer',
                            'med_drug_name',
                            'med_brand_name',
                            'med_batch_no',
                            'med_manufacturer',
                            'med_dose',
                            'med_start_date',
                            'med_stop_date',
                            'med_indication',
                            'reaction_resolve',
                            'reaction_reappear',
                            'severity',
                            'serious',
                            'serious_reason',
                            'action_taken',
                            'outcome',
                            'designations',
                            'reporter_date',
                            'reporter_name',
                            'reporter_email',
                            'reporter_phone',
                            'diagnosis',
                            'description_of_reaction',
                            'medical_history',
                            'lab_investigation',
                            'any_other_comment',
                            'person_submitting',
                            'reporter_name_diff',
                            'reporter_designation_diff',
                            'reporter_email_diff',
                            'reporter_phone_diff',
                            'reporter_date_diff'
                        );

                        $savedRecords = 0;
                        while (($row = fgetcsv($handle)) !== false) {
                            $data = array();
                            $listOfDrugs = [];

                            // Map CSV values based on column names from the header
                            foreach ($header as $i => $columnName) {
                                $columnName = trim($columnName); // Ensure spaces are trimmed
                                if (in_array($columnName, $expectedColumns)) {
                                    $data['Sadr'][$columnName] = isset($row[$i]) ? trim($row[$i]) : null;
                                }
                            }

                            // Process Drugs with corresponding data
                            $drugs = !empty($data['Sadr']['drugs']) ? explode(';', $data['Sadr']['drugs']) : [];
                            $brands = !empty($data['Sadr']['brands']) ? explode(';', $data['Sadr']['brands']) : [];
                            $batchNos = !empty($data['Sadr']['batch_no']) ? explode(';', $data['Sadr']['batch_no']) : [];
                            $manufacturers = !empty($data['Sadr']['manufacturers']) ? explode(';', $data['Sadr']['manufacturers']) : [];
                            $startDates = !empty($data['Sadr']['start_date']) ? explode(';', $data['Sadr']['start_date']) : [];
                            $stopDates = !empty($data['Sadr']['end_date']) ? explode(';', $data['Sadr']['end_date']) : [];
                            $indications = !empty($data['Sadr']['drug_indication']) ? explode(';', $data['Sadr']['drug_indication']) : [];
                            $suspectedDrugs = !empty($data['Sadr']['suspected_drug']) ? explode(';', $data['Sadr']['suspected_drug']) : [];

                            foreach ($drugs as $index => $drug) {
                                $listOfDrugs[] = [
                                    'dose_id' => null,
                                    'route_id' => null,
                                    'frequency_id' => null,
                                    'frequency_id_other' => null,
                                    'drug_name' => trim($drug),
                                    'brand_name' => isset($brands[$index]) ? trim($brands[$index]) : null,
                                    'batch_no' => isset($batchNos[$index]) ? trim($batchNos[$index]) : null,
                                    'manufacturer' => isset($manufacturers[$index]) ? trim($manufacturers[$index]) : null,
                                    'dose' => null,
                                    'start_date' => isset($startDates[$index]) ? trim($startDates[$index]) : null,
                                    'stop_date' => isset($stopDates[$index]) ? trim($stopDates[$index]) : null,
                                    'indication' => isset($indications[$index]) ? trim($indications[$index]) : null,
                                    'suspected_drug' => isset($suspectedDrugs[$index]) ? trim($suspectedDrugs[$index]) : null
                                ];
                            }

                            $data['SadrListOfDrug'] = !empty($listOfDrugs) ? $listOfDrugs : null;



                            // Medications


                            $medicines = !empty($data['Sadr']['med_drug_name']) ? explode(';', $data['Sadr']['med_drug_name']) : [];
                            $medBrands = !empty($data['Sadr']['med_brand_name']) ? explode(';', $data['Sadr']['med_brand_name']) : [];
                            $medBatchNos = !empty($data['Sadr']['med_batch_no']) ? explode(';', $data['Sadr']['med_batch_no']) : [];
                            $medManufacturers = !empty($data['Sadr']['med_manufacturer']) ? explode(';', $data['Sadr']['med_manufacturer']) : [];
                            $medDoses = !empty($data['Sadr']['med_dose']) ? explode(';', $data['Sadr']['med_dose']) : [];
                            $medStartDates = !empty($data['Sadr']['med_start_date']) ? explode(';', $data['Sadr']['med_start_date']) : [];
                            $medStopDates = !empty($data['Sadr']['med_stop_date']) ? explode(';', $data['Sadr']['med_stop_date']) : [];
                            $medIndications = !empty($data['Sadr']['med_indication']) ? explode(';', $data['Sadr']['med_indication']) : [];

                            $listOfMedicines = [];

                            foreach ($medicines as $index => $medicine) {
                                $listOfMedicines[] = [
                                    'dose_id' => null,
                                    'route_id' => null,
                                    'frequency_id' => null,
                                    'frequency_id_other' => null,
                                    'drug_name' => trim($medicine),
                                    'brand_name' => isset($medBrands[$index]) ? trim($medBrands[$index]) : null,
                                    'batch_no' => isset($medBatchNos[$index]) ? trim($medBatchNos[$index]) : null,
                                    'manufacturer' => isset($medManufacturers[$index]) ? trim($medManufacturers[$index]) : null,
                                    'dose' => isset($medDoses[$index]) ? trim($medDoses[$index]) : null,
                                    'start_date' => isset($medStartDates[$index]) ? trim($medStartDates[$index]) : null,
                                    'stop_date' => isset($medStopDates[$index]) ? trim($medStopDates[$index]) : null,
                                    'indication' => isset($medIndications[$index]) ? trim($medIndications[$index]) : null
                                ];
                            }

                            $data['SadrListOfMedicine'] = !empty($listOfMedicines) ? $listOfMedicines : null;



                            // Process reactions
                            $reactions = !empty($data['Sadr']['reactions']) ? explode(';', $data['Sadr']['reactions']) : [];

                            $listOfReactions = [];

                            foreach ($reactions as $reaction) {
                                $listOfReactions[] = ['reaction' => trim($reaction)];
                            }

                            // Store in SadrReaction
                            $data['SadrReaction'] = !empty($listOfReactions) ? $listOfReactions : null;
                            // add the basic fields

                            $data['Sadr']['user_id'] = $this->Auth->User('id');
                            $data['Sadr']['reference_no'] = $this->generate_inner_reference();
                            $data['Sadr']['report_type'] = 'Initial';
                            $data['Sadr']['submitted'] = 2;
                            $data['Sadr']['submitted_date'] = date("Y-m-d H:i:s");

                            // Save record in the database
                            // debug($data);
                            // exit;
                            $this->Sadr->create();
                            // if ($this->Sadr->save($data, array('validate' => false, 'deep' => true))) {
                            if ($this->Sadr->saveAssociated($data, array('deep' => true, 'validate' => false))) {
                                $savedRecords++;
                            }
                        }
                        fclose($handle);

                        $this->Session->setFlash(__("File uploaded successfully! \n $savedRecords records have been successfully imported."), 'flash_success');
                        return $this->redirect($this->referer());
                    } else {
                        $this->Session->setFlash(__('Could not open the file!'), 'flash_error');
                    }

                    // End of Manipulation

                } else {
                    $this->Session->setFlash(__('File upload failed. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                }
            } else {
                $this->Session->setFlash(__('No file selected.'), 'default', array('class' => 'alert alert-danger'));
            }
        }

        $this->redirect($this->referer());
    }

    public function admin_index()
    {
        $this->Prg->commonProcess();
        // debug($this->request->query['pages']);
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) {
            if (!empty($this->passedArgs['filter_by'])) {
                $this->passedArgs['reportrange'] = true;
            } else {
                $this->passedArgs['range'] = true;
            }
        }
        if (!empty($this->request->query['pages'])) $this->paginate['limit'] = $this->request->query['pages'];
        else $this->paginate['limit'] = reset($this->page_options);

        $criteria = $this->Sadr->parseCriteria($this->passedArgs);

        $criteria['Sadr.copied !='] = '1';
        // check if the user has select unsubmited sadrs
        if (isset($this->request->query['submitted']) && $this->request->query['submitted'] == 1) {
            $criteria['Sadr.submitted'] = array(0, 1);
        } else {
            $criteria['Sadr.submitted'] = array(2, 3);
        }
        // add deleted condition to criteria
        $criteria['Sadr.deleted'] = false;
        if (!empty($this->passedArgs['archived'])) {
            $criteria['Sadr.archived'] = true;
        } else {
            $criteria['Sadr.archived'] = false;
        }

        // if (!isset($this->passedArgs['submit'])) $criteria['Sadr.submitted'] = array(2, 3);
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Sadr.submitted_date' => 'desc');
        $this->paginate['contain'] = array('County', 'SadrListOfDrug', 'SadrListOfMedicine', 'SadrDescription', 'Designation', 'User');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {

            $sadrs = $this->Sadr->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'limit' => 1000)
            );
            // debug($sadrs);
            // exit;
            $this->csv_export($this->Sadr->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'limit' => 10000)
            ));
        }
        //end csv export
        $this->set('page_options', $this->page_options);
        $counties = $this->Sadr->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $designations = $this->Sadr->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $this->set('sadrs', Sanitize::clean($this->paginate(), array('encode' => false)));
    }
    public function manager_check_missing()
    {
        $data = $this->Sadr->find(
            'all',
            array(
                'conditions' => array(
                    'Sadr.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s")),
                    'Sadr.reference_no !=' => 'new'
                ),
                'order' => array('Sadr.id' => 'DESC')
            )
        );
        foreach ($data as $dt) {
            debug($dt['Sadr']['reference_no']);
            exit;
        }
    }

    public function generate_inner_reference()
    {
        # code...
        $count = $this->Sadr->find('count',  array(
            'fields' => 'Sadr.reference_no',
            'conditions' => array(
                'Sadr.submitted_date BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s")),
                'Sadr.reference_no !=' => 'new'
            )
        ));
        $count++;
        $count = ($count < 10) ? "0$count" : $count;
        $reference_number = 'SADR/' . date('Y') . '/' . $count;
        return $reference_number;
    }
    /**
     * index method
     */


    /**
     * index method
     */

    public function reporter_index()
    {
        $this->Prg->commonProcess();
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) {
            if (!empty($this->passedArgs['filter_by'])) {
                $this->passedArgs['reportrange'] = true;
            } else {
                $this->passedArgs['range'] = true;
            }
        }
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($this->page_options);
        //Health program fiasco
        if ($this->Session->read('Auth.User.user_type') == 'Public Health Program') {
            $this->passedArgs['health_program'] = $this->Session->read('Auth.User.health_program');
        }

        $criteria = $this->Sadr->parseCriteria($this->passedArgs);
        if ($this->Session->read('Auth.User.user_type') != 'Public Health Program') {
            if ($this->Session->read('Auth.User.user_type') == 'County Pharmacist') {
                $criteria['OR'] = array(
                    'Sadr.user_id' => $this->Auth->user('id'),
                    array(
                        'Sadr.serious' => 'Yes',
                        'Sadr.submitted' => array(2, 3),
                        'Sadr.county_id' => $this->Auth->user('county_id')
                    )
                );
            } else {
                $criteria['Sadr.user_id'] = $this->Auth->User('id');
            }
        }
        if ($this->Session->read('Auth.User.user_type') == 'Public Health Program') {
            $criteria['Sadr.submitted'] = array(2);
        } else {
            if (isset($this->request->query['submitted'])) {

                if ($this->request->query['submitted'] == 1) {
                    $criteria['Sadr.submitted'] = array(0, 1);
                } else {
                    $criteria['Sadr.submitted'] = array(2, 3);
                }
            } else {
                $criteria['Sadr.submitted'] = array(0, 1, 2, 3);
            }
        }
        // add deleted condition to criteria
        $criteria['Sadr.deleted'] = false;
        $criteria['Sadr.archived'] = false;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Sadr.created' => 'desc');
        $this->paginate['contain'] = array('County', 'SadrListOfDrug', 'SadrDescription', 'Designation', 'SadrListOfMedicine');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Sadr->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end csv export
        $this->set('page_options', $this->page_options);
        $counties = $this->Sadr->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $designations = $this->Sadr->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $this->set('sadrs', Sanitize::clean($this->paginate(), array('encode' => false)));
    }

    public function api_index()
    {
        $this->Prg->commonProcess();
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;

        $page_options = array('5' => '5', '10' => '10', '25' => '25');
        (!empty($this->request->query('pages'))) ? $this->paginate['limit'] = $this->request->query('pages') :  $this->paginate['limit'] = reset($page_options);


        $criteria = $this->Sadr->parseCriteria($this->passedArgs);
        $criteria['Sadr.user_id'] = $this->Auth->User('id');
        $criteria['Sadr.deleted'] = false;
        $criteria['Sadr.archived'] = false;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Sadr.created' => 'desc');
        $this->paginate['contain'] = array('County', 'SadrListOfDrug', 'SadrListOfMedicine', 'SadrDescription', 'Designation');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Sadr->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end csv export

        $this->set([
            'page_options',
            $page_options,
            'sadrs' => Sanitize::clean($this->paginate(), array('encode' => false)),
            'paging' => $this->request->params['paging'],
            '_serialize' => ['sadrs', 'page_options', 'paging']
        ]);
    }

    public function partner_index()
    {
        $this->Prg->commonProcess();
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) {
            if (!empty($this->passedArgs['filter_by'])) {
                $this->passedArgs['reportrange'] = true;
            } else {
                $this->passedArgs['range'] = true;
            }
        }
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($this->page_options);

        $criteria = $this->Sadr->parseCriteria($this->passedArgs);
        $criteria['Sadr.name_of_institution'] = $this->Auth->User('name_of_institution');
        if (isset($this->request->query['submitted']) && $this->request->query['submitted'] == 1) {
            $criteria['Sadr.submitted'] = array(0, 1);
        } else {
            $criteria['Sadr.submitted'] = array(2, 3);
        }
        // add deleted condition to criteria
        $criteria['Sadr.deleted'] = false;
        $criteria['Sadr.archived'] = false;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Sadr.created' => 'desc');
        $this->paginate['contain'] = array('County', 'SadrListOfDrug', 'SadrListOfMedicine', 'SadrDescription', 'Designation');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Sadr->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end csv export
        $this->set('page_options', $this->page_options);
        $counties = $this->Sadr->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $designations = $this->Sadr->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $this->set('sadrs', Sanitize::clean($this->paginate(), array('encode' => false)));
    }

    public function manager_index()
    {
        $this->Prg->commonProcess();
        // debug($this->request->query['pages']);
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) {
            if (!empty($this->passedArgs['filter_by'])) {
                $this->passedArgs['reportrange'] = true;
            } else {
                $this->passedArgs['range'] = true;
            }
        }
        if (!empty($this->request->query['pages'])) $this->paginate['limit'] = $this->request->query['pages'];
        else $this->paginate['limit'] = reset($this->page_options);

        $criteria = $this->Sadr->parseCriteria($this->passedArgs);

        $criteria['Sadr.copied !='] = '1';
        // check if the user has select unsubmited sadrs
        if (isset($this->request->query['submitted']) && $this->request->query['submitted'] == 1) {
            $criteria['Sadr.submitted'] = array(0, 1);
        } else {
            $criteria['Sadr.submitted'] = array(2, 3);
        }
        // add deleted condition to criteria
        $criteria['Sadr.deleted'] = false;
        if (!empty($this->passedArgs['archived'])) {
            $criteria['Sadr.archived'] = true;
        } else {
            $criteria['Sadr.archived'] = false;
        }

        // if (!isset($this->passedArgs['submit'])) $criteria['Sadr.submitted'] = array(2, 3);
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Sadr.submitted_date' => 'desc');
        $this->paginate['contain'] = array('County','ExternalComment', 'SadrListOfDrug', 'SadrListOfMedicine', 'SadrDescription', 'Designation', 'User');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {

            $sadrs = $this->Sadr->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'limit' => 10000)
            );
            // debug($sadrs);
            // exit;
            $this->csv_export($this->Sadr->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'limit' => 10000)
            ));
        }
        //end csv export
        $this->set('page_options', $this->page_options);
        $counties = $this->Sadr->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $designations = $this->Sadr->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $this->set('sadrs', Sanitize::clean($this->paginate(), array('encode' => false)));
    }


    public function reviewer_index()
    {
        # code...

        $this->Prg->commonProcess();
        // debug($this->request->query['pages']);
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) {
            if (!empty($this->passedArgs['filter_by'])) {
                $this->passedArgs['reportrange'] = true;
            } else {
                $this->passedArgs['range'] = true;
            }
        }
        if (!empty($this->request->query['pages'])) $this->paginate['limit'] = $this->request->query['pages'];
        else $this->paginate['limit'] = reset($this->page_options);
        //  $criteria['Sadr.submitted'] = 2;
        $criteria['Sadr.copied !='] = '1';
        $criteria['Sadr.deleted'] = false;
        $criteria['Sadr.archived'] = false;
        // check if the user has select unsubmited sadrs
        if (isset($this->request->query['submitted']) && $this->request->query['submitted'] == 1) {
            $criteria['Sadr.submitted'] = array(0, 1);
        } else {
            $criteria['Sadr.submitted'] = array(2, 3);
        }

        $criteria['Sadr.assigned_to'] = $this->Auth->User('id');
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Sadr.created' => 'desc');
        $this->paginate['contain'] = array('County', 'SadrListOfDrug', 'SadrListOfMedicine', 'SadrDescription', 'Designation', 'User');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $sadrs = $this->Sadr->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'limit' => 1000)
            );
            // debug($sadrs);
            // exit;
            $this->csv_export($sadrs);
        }
        //end csv export
        $this->set('page_options', $this->page_options);
        $counties = $this->Sadr->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $designations = $this->Sadr->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $this->set('sadrs', Sanitize::clean($this->paginate(), array('encode' => false)));
    }

    private function csv_export($csadrs = '')
    {
        /*$_serialize = 'csadrs';
        $_header = array('Id');
        $_extract = array('Sadr.id');

        // $this->response->download('SADRS_'.date('Ymd_Hi').'.csv'); // <= setting the file name
        $this->viewClass = 'CsvView.Csv';
        $this->set(compact('csadrs', '_serialize', '_header', '_extract'));*/
        $this->response->download('SADRs_' . date('Ymd_Hi') . '.csv'); // <= setting the file name
        $this->set(compact('csadrs'));
        $this->layout = false;
        $this->render('csv_export');
    }

    public function institutionCodes()
    {
        if ($this->Auth->user('institution_code')) {
            $this->Sadr->recursive = -1;
            $this->paginate = array(
                'conditions' => array('Sadr.institution_code' => $this->Auth->user('institution_code')),
                'fields' => array('Sadr.report_title', 'Sadr.created'),
                'limit' => 25,
                'order' => array(
                    'Sadr.created' => 'asc'
                )
            );
            $this->set('sadrs', $this->paginate());
        }
    }
    /**
     * view methods
     */
    public function reporter_view($id = null)
    {
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            $this->Session->setFlash(__('Could not verify the SADR report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['continueEditing'])) {
                $this->Sadr->saveField('submitted', 0);
                $this->Session->setFlash(__('You can continue editing the report'), 'flash_success');
                $this->redirect(array('action' => 'edit', $this->Sadr->id));
            }
            if (isset($this->request->data['sendToPPB'])) {
                $this->Sadr->saveField('submitted', 2);
                $this->Session->setFlash(__('Thank you for submitting your report. You will get an email with a link to the pdf copy of the report.'), 'flash_success');
                $this->redirect('/');
            }
        }

        $sadr = $this->Sadr->find('first', array(
            'conditions' => array('Sadr.id' => $id),
            'contain' => array('SadrListOfDrug', 'SadrDescription', 'SadrReaction', 'SadrListOfDrug.Route', 'SadrListOfDrug.Frequency', 'SadrListOfDrug.Dose', 'SadrListOfMedicine', 'SadrListOfMedicine.Route', 'SadrListOfMedicine.Frequency', 'SadrListOfMedicine.Dose', 'County', 'SubCounty', 'Attachment', 'Designation',  'ReviewComment', 'SadrOriginal.ReviewComment', 'ReviewComment.Attachment', 'ExternalComment')
        ));
        $this->set('sadr', $sadr);

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'SADR_' . $id . '.pdf',  'orientation' => 'portrait');
            $this->response->download('SADR_' . $sadr['Sadr']['id'] . '.pdf');
        }
    }

    public function api_view($id = null)
    {
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) { //TODO: Also confirm if the user is the same user_id
            $this->set([
                'status' => 'failed',
                'message' => 'Could not verify the SADR report ID. Please ensure the ID is correct.',
                '_serialize' => ['status', 'message']
            ]);
        } else {

            if (strpos($this->request->url, 'pdf') !== false) {
                $this->pdfConfig = array('filename' => 'SADR_' . $id . '.pdf',  'orientation' => 'portrait');
                // $this->response->download('SADR_'.$sadr['Sadr']['id'].'.pdf');
            }

            $sadr = $this->Sadr->find('first', array(
                'conditions' => array('Sadr.id' => $id),
                'contain' => array('SadrListOfDrug', 'SadrDescription', 'SadrListOfDrug.Route', 'SadrListOfDrug.Frequency', 'SadrListOfDrug.Dose', 'SadrListOfMedicine', 'SadrListOfMedicine.Route', 'SadrListOfMedicine.Frequency', 'SadrListOfMedicine.Dose', 'County', 'SubCounty', 'Attachment', 'Designation', 'ExternalComment')
            ));

            $this->set([
                'status' => 'success',
                'sadr' => $sadr,
                '_serialize' => ['status', 'sadr']
            ]);
        }
    }

    public function manager_view($id = null)
    {
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            $this->Session->setFlash(__('Could not verify the SADR report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }
        $this->general_viewer($id);
    }

    public function reviewer_view($id = null)
    {
        # code...
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            $this->Session->setFlash(__('Could not verify the SADR report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }
        $this->general_viewer($id);
    }

    public function general_viewer($id = null)
    {
        # code... 
        $sadr = $this->Sadr->find('first', array(
            'conditions' => array('Sadr.id' => $id),
            'contain' => array(
                'SadrListOfDrug',
                'SadrDescription',
                'SadrReaction',
                'SadrListOfDrug.Route',
                'SadrListOfDrug.Frequency',
                'SadrListOfDrug.Dose',
                'SadrListOfMedicine',
                'SadrListOfMedicine.Route',
                'SadrListOfMedicine.Frequency',
                'SadrListOfMedicine.Dose',
                'County',
                'SubCounty',
                'Attachment',
                'Designation',
                'ExternalComment',
                'ExternalComment.Attachment',
                'ReviewComment',
                'ReviewComment.Attachment',
                'SadrOriginal',
                'SadrOriginal.SadrReaction',
                'SadrOriginal.SadrDescription',
                'SadrOriginal.SadrListOfDrug',
                'SadrOriginal.SadrListOfDrug.Route',
                'SadrOriginal.SadrListOfDrug.Frequency',
                'SadrOriginal.SadrListOfDrug.Dose',
                'SadrOriginal.SadrListOfMedicine',
                'SadrOriginal.SadrListOfMedicine.Route',
                'SadrOriginal.SadrListOfMedicine.Frequency',
                'SadrOriginal.SadrListOfMedicine.Dose',
                'SadrOriginal.County',
                'SadrOriginal.SubCounty',
                'SadrOriginal.Attachment',
                'SadrOriginal.Designation',
                'SadrOriginal.ExternalComment',
                'SadrOriginal.ReviewComment'
            )
        ));
        $managers = $this->Sadr->User->find('list', array(
            'conditions' => array(
                'User.group_id' => 6,
                'User.is_active' => 1
            )
        ));


        $this->set(['sadr' => $sadr, 'managers' => $managers]);


        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'SADR_' . $id . '.pdf',  'orientation' => 'portrait');

            $this->response->download('SADR_' . $sadr['Sadr']['id'] . '.pdf');
        }
    }

    // Assign the report to the evaluator
    public function manager_assign()
    {
        # code...
        $id = $this->request->data['Sadr']['report_id'];
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            $this->Session->setFlash(__('Could not verify the SADR report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }
        $this->Sadr->saveField('assigned_by', $this->request->data['Sadr']['assigned_by']);
        $this->Sadr->saveField('assigned_to', $this->request->data['Sadr']['assigned_to']);
        $this->Sadr->saveField('assigned_date', date("Y-m-d H:i:s"));

        // Send an asignment alert::::


        $this->Session->setFlash(__('The SADR has been assigned successfully'), 'alerts/flash_success');
        $this->redirect(array('action' => 'view', $id));
    }

    public function manager_unassign($id = null)
    {
        # code...
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            $this->Session->setFlash(__('Could not verify the SADR report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }
        $this->Sadr->saveField('assigned_by', '');
        $this->Sadr->saveField('assigned_to', '');
        $this->Sadr->saveField('assigned_date', '');

        // Send an asignment alert::::


        $this->Session->setFlash(__('The SADR has been unassigned successfully'), 'alerts/flash_success');
        $this->redirect(array('action' => 'view', $id));
    }

    public function partner_view($id = null)
    {
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            $this->Session->setFlash(__('Could not verify the SADR report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'SADR_' . $id . '.pdf',  'orientation' => 'portrait');
            // $this->response->download('SADR_'.$sadr['Sadr']['id'].'.pdf');
        }

        $sadr = $this->Sadr->find('first', array(
            'conditions' => array('Sadr.id' => $id),
            'contain' => array(
                'SadrListOfDrug',
                'SadrDescription',
                'SadrListOfDrug.Route',
                'SadrListOfDrug.Frequency',
                'SadrListOfDrug.Dose',
                'SadrListOfMedicine',
                'SadrListOfMedicine.Route',
                'SadrListOfMedicine.Frequency',
                'SadrListOfMedicine.Dose',
                'County',
                'SubCounty',
                'Attachment',
                'Designation',
                'ExternalComment',
                'SadrOriginal',
                'SadrOriginal.SadrListOfDrug',
                'SadrOriginal.SadrListOfDrug.Route',
                'SadrOriginal.SadrListOfDrug.Frequency',
                'SadrOriginal.SadrListOfDrug.Dose',
                'SadrOriginal.SadrListOfMedicine',
                'SadrOriginal.SadrListOfMedicine.Route',
                'SadrOriginal.SadrListOfMedicine.Frequency',
                'SadrOriginal.SadrListOfMedicine.Dose',
                'SadrOriginal.County',
                'SadrOriginal.SubCounty',
                'SadrOriginal.Attachment',
                'SadrOriginal.Designation',
                'SadrOriginal.ExternalComment'
            )
        ));
        $this->set('sadr', $sadr);


        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'SADR_' . $id . '.pdf',  'orientation' => 'portrait');
            $this->response->download('SADR_' . $sadr['Sadr']['id'] . '.pdf');
        }
    }

    /**
     * download methods
     */
    public function download($id = null)
    {
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            $this->Session->setFlash(__('Could not verify the suspected adverse drug report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        $sadr = $this->Sadr->find('first', array(
            'conditions' => array('Sadr.id' => $id),
            'contain' => array('SadrListOfDrug', 'SadrDescription', 'SadrListOfDrug.Route', 'SadrListOfDrug.Frequency', 'SadrListOfDrug.Dose', 'County', 'SubCounty', 'Attachment', 'Designation')
        ));
        if (empty($sadr['Sadr']['reaction'])) {
            $this->Session->setFlash(__('Please provide a reaction'), 'flash_error');
            $this->redirect($this->referer());
        }

        $sadr['Sadr']['medra'] = $this->generate_meddra_code($sadr['Sadr']['reaction']);
        $sadr = Sanitize::clean($sadr, array('escape' => true));
        $this->set('sadr', $sadr);
        $this->response->download('SADR_' . $sadr['Sadr']['id'] . '.xml');
    }

    public function generate_meddra_code($name = null)
    {
        $this->loadModel('Meddra');
        $meddra = $this->Meddra->find('first', array(
            'conditions' => array(
                'Meddra.llt_name' => $name
            ),
            'fields' => array('Meddra.llt_name', 'Meddra.pt_code')
        ));

        if ($meddra) {
            // Record found 
            return $meddra['Meddra']['pt_code'];
        }
        return null;
    }

    public function vigiflow($id = null)
    {
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            $this->Session->setFlash(__('Could not verify the suspected adverse drug report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        $sadr = $this->Sadr->find('first', array(
            'conditions' => array('Sadr.id' => $id),
            'contain' => array('SadrListOfDrug', 'SadrDescription', 'SadrListOfDrug.Route', 'SadrListOfDrug.Frequency', 'SadrListOfDrug.Dose', 'County', 'SubCounty', 'Attachment', 'Designation')
        ));
        $sadr = Sanitize::clean($sadr, array('escape' => true));

        $view = new View($this, false);
        $view->viewPath = 'Sadrs/xml';  // Directory inside view directory to search for .ctp files
        $view->layout = false; // if you want to disable layout
        $view->set('sadr', $sadr); // set your variables for view here
        $html = $view->render('download');

        // debug($html);
        // exit;
        $HttpSocket = new HttpSocket();
        // string data
        $results = $HttpSocket->post(
            Configure::read('vigiflow_api'),
            $html,
            array('header' => array(
                'umc-vigiflow-web-radr-access-key' => Configure::read('vigiflow_key'),
                'Content-Type' => 'application/xml'
            ))
        );

        if ($results->isOk()) {
            $body = $results->body;
            $this->Sadr->saveField('vigiflow_message', $body);
            $this->Sadr->saveField('vigiflow_date', date('Y-m-d H:i:s'));
            $resp = json_decode($body, true);
            if (json_last_error() == JSON_ERROR_NONE) {
                $this->Sadr->saveField('vigiflow_ref', $resp);
            }
            $this->Flash->success('Vigiflow integration success!!');
            $this->Flash->success($body);
            $this->redirect($this->referer());
        } else {
            $body = $results->body;
            $this->Sadr->saveField('vigiflow_message', $body);
            $this->Flash->error('Error sending report to vigiflow:');
            $this->Flash->error($body);
            $this->redirect($this->referer());
        }
        $this->autoRender = false;
    }

    public function e2b($id = null)
    {
        $sadr = $this->Sadrs->get($id, [
            'contain' => ['SadrListOfDrugs', 'SadrDescription', 'SadrOtherDrugs', 'Attachments', 'ReportStages', 'Reactions']
        ]);

        $stage1  = $this->Sadrs->ReportStages->newEntity();
        $stage1->model = 'Sadrs';
        $stage1->stage = 'VigiBase';
        $stage1->description = 'Stage 9';
        $stage1->stage_date = date("Y-m-d H:i:s");
        $sadr->report_stages = [$stage1];
        $sadr->status = 'VigiBase';
        $this->Sadrs->save($sadr);

        $designations = $this->Sadrs->Designations->find('list', array('order' => 'Designations.name ASC'));
        $provinces = $this->Sadrs->Provinces->find('list', ['limit' => 200]);
        $doses = $this->Sadrs->SadrListOfDrugs->Doses->find('list', ['keyField' => 'id', 'valueField' => 'icsr_code']);
        $routes = $this->Sadrs->SadrListOfDrugs->Routes->find('list', ['keyField' => 'id', 'valueField' => 'icsr_code']);
        $frequencies = $this->Sadrs->SadrListOfDrugs->Frequencies->find('list', ['limit' => 200]);
        $this->set('_serialize', false);
        $this->set(compact('sadr', 'doses', 'routes'));
        /*$query = $this->Sadrs->query();
        $query->update()
                    ->set(['status' => 'E2B'])
                    ->where(['id' => $sadr->id])
                    ->execute();*/

        $this->response->download(($sadr->submitted == 2) ? str_replace('/', '_', $sadr->reference_number) . '.xml' : 'ADR_' . $sadr->created->i18nFormat('dd-MM-yyyy_HHmmss') . '.xml');
    }

    /**
     * add method
     *
     * @return void
     */

    public function reporter_followup($id = null)
    {
        if ($this->request->is('post')) {
            $this->Sadr->id = $id;
            if (!$this->Sadr->exists()) {
                throw new NotFoundException(__('Invalid SADR'));
            }
            $sadr = Hash::remove($this->Sadr->find(
                'first',
                array(
                    'contain' => array('SadrListOfDrug', 'SadrReaction', 'SadrDescription', 'SadrListOfMedicine'),
                    'conditions' => array('Sadr.id' => $id)
                )
            ), 'Sadr.id');

            $sadr = Hash::remove($sadr, 'SadrListOfDrug.{n}.id');
            $sadr = Hash::remove($sadr, 'SadrListOfMedicine.{n}.id');
            $data_save = $sadr['Sadr'];
            $data_save['SadrListOfDrug'] = $sadr['SadrListOfDrug'];
            if (isset($sadr['SadrListOfMedicine'])) $data_save['SadrListOfMedicine'] = $sadr['SadrListOfMedicine'];
            $data_save['sadr_id'] = $id;

            $count = $this->Sadr->find('count',  array('conditions' => array(
                'Sadr.reference_no LIKE' => $sadr['Sadr']['reference_no'] . '%',
            )));
            $count = ($count < 10) ? "0$count" : $count;
            $data_save['reference_no'] = $sadr['Sadr']['reference_no']; //.'_F'.$count;
            $data_save['report_type'] = 'Followup';
            $data_save['submitted'] = 0;
            $now = date('Y-m-d H:i:s');
            $data_save['created'] = $now;
            $data_save['modified'] = $now;

            if ($this->Sadr->saveAssociated($data_save, array('deep' => true, 'validate' => false))) {
                $this->Session->setFlash(__('Follow up ' . $data_save['reference_no'] . ' has been created'), 'alerts/flash_info');
                $this->redirect(array('action' => 'edit', $this->Sadr->id));
            } else {
                $this->Session->setFlash(__('The followup could not be saved. Please, try again.'), 'alerts/flash_error');
                $this->redirect($this->referer());
            }
        }
    }


    public function reporter_add($id = null)
    {
        $this->Sadr->create();
        $this->Sadr->save(['Sadr' => [
            'user_id' => $this->Auth->User('id'),
            'reference_no' => 'new', //'SADR/'.date('Y').'/'.$count,
            'report_type' => 'Initial',
            'pqmp_id' => $id,
            'medication_id' => $id,
            'designation_id' => $this->Auth->User('designation_id'),
            'county_id' => $this->Auth->User('county_id'),
            'institution_code' => $this->Auth->User('institution_code'),
            'address' => $this->Auth->User('institution_address'),
            'reporter_name' => $this->Auth->User('name'),
            'reporter_email' => $this->Auth->User('email'),
            'reporter_phone' => $this->Auth->User('phone_no'),
            'contact' => $this->Auth->User('institution_contact'),
            'name_of_institution' => $this->Auth->User('name_of_institution')
        ]], false);
        $this->Session->setFlash(__('The SADR has been created'), 'alerts/flash_success');

        // Log the activity


        $user = $this->Auth->user();
        $type = 'New Sadr Report';
        $message = 'A new SADR report with ID: ' . $this->Sadr->id . ' has been created by ' . $user['name'];
        $this->createAuditTrail($type, $message);

        $this->redirect(array('action' => 'edit', $this->Sadr->id));
    }
    public function createAuditTrail($type, $message)
    {

        $user = $this->Auth->user();

        $this->loadModel('Queue.QueuedTask');
        $clientInfo = $this->getRequestClientInfo();
        $this->QueuedTask->createJob('AuditTrail', array_merge([
            'foreign_key'  => $user['id'],
            'model'        => $type,
            'message'      => $message,
            'ip'           => $this->getUserIpAddress(), // or $this->request->clientIp()
            'uri'          => $this->request->here(),
            'hostname'     => gethostname()
        ], $clientInfo));
    }
    function getRequestClientInfo()
    {
        return [
            'refer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
        ];
    }

    public function getUserIpAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // IP from shared internet
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // IP passed from proxy/load balancer
            $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ipList[0]); // return the first IP
        }

        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            // IP from Cloudflare
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }

        // Default remote address
        return $_SERVER['REMOTE_ADDR'];
    }
    public function reporter_addme($id = null)
    {
        $this->Sadr->create();
        $this->Sadr->save(['Sadr' => [
            'user_id' => $this->Auth->User('id'),
            'reference_no' => 'new', //'SADR/'.date('Y').'/'.$count,
            'report_type' => 'Initial',
            'medication_id' => $id,
            'designation_id' => $this->Auth->User('designation_id'),
            'county_id' => $this->Auth->User('county_id'),
            'institution_code' => $this->Auth->User('institution_code'),
            'address' => $this->Auth->User('institution_address'),
            'reporter_name' => $this->Auth->User('name'),
            'reporter_email' => $this->Auth->User('email'),
            'reporter_phone' => $this->Auth->User('phone_no'),
            'contact' => $this->Auth->User('institution_contact'),
            'name_of_institution' => $this->Auth->User('name_of_institution')
        ]], false);
        $this->Session->setFlash(__('The SADR has been created'), 'alerts/flash_success');
        $this->redirect(array('action' => 'edit', $this->Sadr->id));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function generateReferenceNumber()
    {
        $count = $this->Sadr->find('count',  array(
            'fields' => 'Sadr.reference_no',
            'conditions' => array(
                'Sadr.submitted_date BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s")),
                'Sadr.reference_no !=' => 'new'
            )
        ));
        $count++;
        $count = ($count < 10) ? "0$count" : $count;
        $reference = 'SADR/' . date('Y') . '/' . $count;
        //ensure that the reference number is unique
        $exists = $this->Sadr->find('count',  array(
            'fields' => 'Sadr.reference_no',
            'conditions' => array('Sadr.reference_no' => $reference)
        ));
        if ($exists > 0) {
            $reference = $this->generateReferenceNumber();
        }

        return $reference;
    }

    public function reporter_edit($id = null)
    {
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            throw new NotFoundException(__('Invalid SADR'));
        }
        $sadr = $this->Sadr->read(null, $id);
        if ($sadr['Sadr']['submitted'] > 1) {
            $this->Session->setFlash(__('The sadr has been submitted'), 'alerts/flash_info');
            $this->redirect(array('action' => 'view', $this->Sadr->id));
        }
        if ($sadr['Sadr']['user_id'] !== $this->Auth->user('id')) {
            $this->Session->setFlash(__('You don\'t have permission to edit this SADR!!'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $validate = false;
            if (isset($this->request->data['submitReport'])) {
                $validate = 'first';
            }
            // debug($this->request->data);
            // exit;
            if ($this->Sadr->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                if (isset($this->request->data['submitReport'])) {
                    $this->Sadr->saveField('submitted', 2);
                    $this->Sadr->saveField('submitted_date', date("Y-m-d H:i:s"));
                    //lucian
                    // if(empty($sadr->reference_no)) {
                    if (!empty($sadr['Sadr']['reference_no']) && $sadr['Sadr']['reference_no'] == 'new') {
                        $count = $this->Sadr->find('count',  array(
                            'fields' => 'Sadr.reference_no',
                            'conditions' => array(
                                'Sadr.submitted_date BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s")),
                                'Sadr.reference_no !=' => 'new'
                            )
                        ));
                        $count++;
                        $count = ($count < 10) ? "0$count" : $count;
                        $reference = 'SADR/' . date('Y') . '/' . $count;
                        $this->Sadr->saveField('reference_no', $reference);
                    }
                    //bokelo
                    $sadr = $this->Sadr->read(null, $id);

                    //******************       Send Email and Notifications to Reporter and Managers          *****************************
                    $this->loadModel('Message');
                    $html = new HtmlHelper(new ThemeView());
                    $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_sadr_submit')));
                    $variables = array(
                        'name' => $this->Auth->User('name'),
                        'reference_no' => $sadr['Sadr']['reference_no'],
                        'reference_link' => $html->link(
                            $sadr['Sadr']['reference_no'],
                            array('controller' => 'sadrs', 'action' => 'view', $sadr['Sadr']['id'], 'reporter' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'modified' => $sadr['Sadr']['modified']
                    );
                    $datum = array(
                        'email' => $sadr['Sadr']['reporter_email'],
                        'id' => $id,
                        'user_id' => $this->Auth->User('id'),
                        'type' => 'reporter_sadr_submit',
                        'model' => 'Sadr',
                        'subject' => CakeText::insert($message['Message']['subject'], $variables),
                        'message' => CakeText::insert($message['Message']['content'], $variables)
                    );

                    $this->loadModel('Queue.QueuedTask');
                    $this->QueuedTask->createJob('GenericEmail', $datum);
                    $this->QueuedTask->createJob('GenericNotification', $datum);


                    //Send SMS
                    if (!empty($sadr['Sadr']['reporter_phone']) && strlen(substr($sadr['Sadr']['reporter_phone'], -9)) == 9 && is_numeric(substr($sadr['Sadr']['reporter_phone'], -9))) {
                        $datum['phone'] = '254' . substr($sadr['Sadr']['reporter_phone'], -9);
                        $variables['reference_url'] = Router::url(['controller' => 'sadrs', 'action' => 'view', $sadr['Sadr']['id'], 'reporter' => true, 'full_base' => true]);
                        $datum['sms'] = CakeText::insert($message['Message']['sms'], $variables);
                        $this->QueuedTask->createJob('GenericSms', $datum);
                    }

                    //Notify managers
                    $users = $this->Sadr->User->find('all', array(
                        'contain' => array(),
                        'conditions' => array('User.group_id' => 2, 'User.is_active' => '1')
                    ));
                    foreach ($users as $user) {
                        $variables = array(
                            'name' => $user['User']['name'],
                            'reference_no' => $sadr['Sadr']['reference_no'],
                            'reference_link' => $html->link(
                                $sadr['Sadr']['reference_no'],
                                array('controller' => 'sadrs', 'action' => 'view', $sadr['Sadr']['id'], 'manager' => true, 'full_base' => true),
                                array('escape' => false)
                            ),
                            'modified' => $sadr['Sadr']['modified']
                        );
                        $datum = array(
                            'email' => $user['User']['email'],
                            'id' => $id,
                            'user_id' => $user['User']['id'],
                            'type' => 'reporter_sadr_submit',
                            'model' => 'Sadr',
                            'subject' => CakeText::insert($message['Message']['subject'], $variables),
                            'message' => CakeText::insert($message['Message']['content'], $variables)
                        );

                        $this->QueuedTask->createJob('GenericEmail', $datum);
                        $this->QueuedTask->createJob('GenericNotification', $datum);
                    }
                    //**********************************    END   *********************************

                    $user = $this->Auth->user();
                    $type = 'New Sadr Report';
                    $message = 'A new SADR report with reference no: ' . $sadr['Sadr']['reference_no'] . ' has been submitted by ' . $user['name'];
                    $this->createAuditTrail($type, $message);
                    // If the report is serious sent an alert:
                    $serious = $sadr['Sadr']['serious'];
                    if ($serious == "Yes") {
                        $this->notifyCountyPharmacist($sadr);
                    }

                    $this->Session->setFlash(__('The SADR has been submitted to PPB'), 'alerts/flash_success');
                    $this->redirect(array('action' => 'view', $this->Sadr->id));
                }
                // debug($this->request->data);
                $this->Session->setFlash(__('The SADR has been saved'), 'alerts/flash_success');
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('The SADR could not be saved. Please review the error(s) and resubmit and try again.'), 'alerts/flash_error');
            }
        } else {
            $this->request->data = $this->Sadr->read(null, $id);
        }

        //$sadr = $this->request->data;

        $counties = $this->Sadr->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $sub_counties = $this->Sadr->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        $this->set(compact('sub_counties'));
        $designations = $this->Sadr->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $routes = $this->Sadr->SadrListOfDrug->Route->find('list');
        $this->set(compact('routes'));
        $frequency = $this->Sadr->SadrListOfDrug->Frequency->find('list');
        $this->set(compact('frequency'));
        $dose = $this->Sadr->SadrListOfDrug->Dose->find('list');
        $this->set(compact('dose'));
    }
    public function notifyCountyPharmacist($sadr = null)
    {
        # code...

        $this->loadModel('Message');
        $html = new HtmlHelper(new ThemeView());
        $message = $this->Message->find('first', array('conditions' => array('name' => 'serious_sadr')));

        $county_id = $sadr['Sadr']['county_id'];

        $users = $this->Sadr->User->find('all', array(
            'contain' => array(),
            'conditions' => array(
                'OR' => array(
                    array(
                        'User.group_id' => 2,
                        'User.is_active' => 1
                    ),
                    array(
                        'User.county_id' => $county_id,
                        'User.user_type' => 'County Pharmacist',
                        'User.is_active' => 1
                    )
                )
            ),
            'order' => array(
                'User.id' => 'DESC'
            )
        ));

        foreach ($users as $user) {
            $model =  'reporter';
            if ($user['User']['group_id'] == 2) {
                $model =  'manager';
            }

            $variables = array(
                'name' => $user['User']['name'],
                'reference_no' => $sadr['Sadr']['reference_no'],
                'reference_link' => $html->link(
                    $sadr['Sadr']['reference_no'],
                    array(
                        'controller' => 'sadrs',
                        'action' => 'view',
                        $sadr['Sadr']['id'],
                        $model => true,
                        'full_base' => true
                    ),
                    array('escape' => false)
                ),
                'modified' => $sadr['Sadr']['modified']
            );
            $datum = array(
                'email' => $user['User']['email'],
                'id' => $sadr['Sadr']['id'],
                'user_id' => $user['User']['id'],
                'type' => 'serious_sadr',
                'model' => 'Sadr',
                'subject' => CakeText::insert($message['Message']['subject'], $variables),
                'message' => CakeText::insert($message['Message']['content'], $variables)
            );
            $this->loadModel('Queue.QueuedTask');
            // $this->QueuedTask->createJob('GenericEmail', $datum);
            // $this->QueuedTask->createJob('GenericNotification', $datum);
        }
    }
    public function api_add()
    {

        $this->Sadr->create();
        $this->_attachments('Sadr');

        // $this->redirect(array('action' => 'edit', $this->Sadr->id));
        $save_data = $this->request->data;
        $save_data['Sadr']['user_id'] = $this->Auth->user('id');
        $save_data['Sadr']['submitted'] = 2;
        $save_data['Sadr']['submitted_date'] = date("Y-m-d H:i:s");

        // check the report_type if it is like Intitial then change to Initial
        if (isset($save_data['Sadr']['report_type']) && $save_data['Sadr']['report_type'] == 'Intitial') {
            $save_data['Sadr']['report_type'] = 'Initial';
        }
        //lucian
        if (empty($save_data['Sadr']['reference_no'])) {
            $count = $this->Sadr->find('count',  array(
                'fields' => 'Sadr.reference_no',
                'conditions' => array(
                    'Sadr.submitted_date BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s")),
                    'Sadr.reference_no !=' => 'new'
                )
            ));
            $count++;
            // debug($count);
            // exit;
            $count = ($count < 10) ? "0$count" : $count;
            $save_data['Sadr']['reference_no'] = 'SADR/' . date('Y') . '/' . $count;
        }
        // $save_data['Sadr']['report_type'] = 'Initial';
        //bokelo

        if ($this->request->is('post') || $this->request->is('put')) {
            $validate = 'first';
            if ($this->Sadr->saveAssociated($save_data, array('validate' => $validate, 'deep' => true))) {

                $sadr = $this->Sadr->read(null, $this->Sadr->id);
                $id = $this->Sadr->id;
                $this->Sadr->saveField('submitted_date', date("Y-m-d H:i:s"));

                //******************       Send Email and Notifications to Reporter and Managers          *****************************
                $this->loadModel('Message');
                $html = new HtmlHelper(new ThemeView());
                $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_sadr_submit')));
                $variables = array(
                    'name' => $this->Auth->User('name'),
                    'reference_no' => $sadr['Sadr']['reference_no'],
                    'reference_link' => $html->link(
                        $sadr['Sadr']['reference_no'],
                        array('controller' => 'sadrs', 'action' => 'view', $sadr['Sadr']['id'], 'reporter' => true, 'full_base' => true),
                        array('escape' => false)
                    ),
                    'modified' => $sadr['Sadr']['modified']
                );
                $datum = array(
                    'email' => $sadr['Sadr']['reporter_email'],
                    'id' => $id,
                    'user_id' => $this->Auth->User('id'),
                    'type' => 'reporter_sadr_submit',
                    'model' => 'Sadr',
                    'subject' => CakeText::insert($message['Message']['subject'], $variables),
                    'message' => CakeText::insert($message['Message']['content'], $variables)
                );

                $this->loadModel('Queue.QueuedTask');
                $this->QueuedTask->createJob('GenericEmail', $datum);
                $this->QueuedTask->createJob('GenericNotification', $datum);

                //Send SMS
                if (!empty($sadr['Sadr']['reporter_phone']) && strlen(substr($sadr['Sadr']['reporter_phone'], -9)) == 9 && is_numeric(substr($sadr['Sadr']['reporter_phone'], -9))) {
                    $datum['phone'] = '254' . substr($sadr['Sadr']['reporter_phone'], -9);
                    $variables['reference_url'] = Router::url(['controller' => 'sadrs', 'action' => 'view', $sadr['Sadr']['id'], 'reporter' => true, 'full_base' => true]);
                    $datum['sms'] = CakeText::insert($message['Message']['sms'], $variables);
                    $this->QueuedTask->createJob('GenericSms', $datum);
                }

                //Notify managers
                $users = $this->Sadr->User->find('all', array(
                    'contain' => array(),
                    'conditions' => array('User.group_id' => 2, 'User.is_active' => '1')
                ));
                foreach ($users as $user) {
                    $variables = array(
                        'name' => $user['User']['name'],
                        'reference_no' => $sadr['Sadr']['reference_no'],
                        'reference_link' => $html->link(
                            $sadr['Sadr']['reference_no'],
                            array('controller' => 'sadrs', 'action' => 'view', $sadr['Sadr']['id'], 'manager' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'modified' => $sadr['Sadr']['modified']
                    );
                    $datum = array(
                        'email' => $user['User']['email'],
                        'id' => $id,
                        'user_id' => $user['User']['id'],
                        'type' => 'reporter_sadr_submit',
                        'model' => 'Sadr',
                        'subject' => CakeText::insert($message['Message']['subject'], $variables),
                        'message' => CakeText::insert($message['Message']['content'], $variables)
                    );

                    $this->QueuedTask->createJob('GenericEmail', $datum);
                    $this->QueuedTask->createJob('GenericNotification', $datum);
                }
                //**********************************    END   *********************************
                $user = $this->Auth->user();
                $type = 'New Sadr Report';
                $message = 'A new SADR report with reference no: ' . $sadr['Sadr']['reference_no'] . ' has been submitted by ' . $user['name'];
                $this->createAuditTrail($type, $message);
                $this->set([
                    'status' => 'success',
                    'message' => 'The SADR has been submitted to PPB',
                    'sadr' => $sadr,
                    '_serialize' => ['status', 'message', 'sadr']
                ]);
            } else {
                $this->set([
                    'status' => 'failed',
                    'message' => 'The SADR could not be saved. Please review the error(s) and resubmit and try again.',
                    'validation' => $this->Sadr->validationErrors,
                    'sadr' => $this->request->data,
                    '_serialize' => ['status', 'message', 'validation', 'sadr']
                ]);
            }
        } else {
            throw new MethodNotAllowedException();
        }
    }

    public function manager_copy($id = null)
    {
        if ($this->request->is('post')) {
            $this->Sadr->id = $id;
            if (!$this->Sadr->exists()) {
                throw new NotFoundException(__('Invalid SADR'));
            }
            $this->generate_copy($id);
        }
    }

    public function reviewer_copy($id = null)
    {
        if ($this->request->is('post')) {
            $this->Sadr->id = $id;
            if (!$this->Sadr->exists()) {
                throw new NotFoundException(__('Invalid SADR'));
            }
            $this->generate_copy($id);
        }
    }

    public function generate_copy($id)
    {
        # code...
        $sadr = Hash::remove($this->Sadr->find(
            'first',
            array(
                'contain' => array('SadrListOfDrug', 'SadrDescription', 'SadrReaction', 'SadrListOfMedicine'),
                'conditions' => array('Sadr.id' => $id)
            )
        ), 'Sadr.id');

        if ($sadr['Sadr']['copied']) {
            $this->Session->setFlash(__('A clean copy already exists. Click on edit to update changes.'), 'alerts/flash_error');
            return $this->redirect(array('action' => 'index'));
        }
        $sadr = Hash::remove($sadr, 'SadrListOfDrug.{n}.id');
        $sadr = Hash::remove($sadr, 'SadrListOfMedicine.{n}.id');
        $data_save = $sadr['Sadr'];
        $data_save['SadrListOfDrug'] = $sadr['SadrListOfDrug'];
        $data_save['SadrReaction'] = $sadr['SadrReaction'];
        if (isset($sadr['SadrListOfMedicine'])) $data_save['SadrListOfMedicine'] = $sadr['SadrListOfMedicine'];
        $data_save['sadr_id'] = $id;
        $data_save['user_id'] = $this->Auth->User('id');;
        $this->Sadr->saveField('copied', 1);
        $data_save['copied'] = 2;
        if (!empty($sadr['Sadr']['created'])) {
            $rawDate = $sadr['Sadr']['created'];
        } else {
            $rawDate = date('Y-m-d H:i:s');
        }
        $now = date('Y-m-d H:i:s', strtotime($rawDate));
        $data_save['created'] = $now;
        $data_save['modified'] = $now;

        if ($this->Sadr->saveAssociated($data_save, array('deep' => true, 'validate' => false))) {
            $this->Session->setFlash(__('Clean copy of ' . $data_save['reference_no'] . ' has been created'), 'alerts/flash_info');
            $user = $this->Auth->user();
            $type = 'Sadr Copy Created';
            $message = 'A clean copy of SADR report with ID: ' . $this->Sadr->id . ' has been created by ' . $user['name'];
            $this->createAuditTrail($type, $message);
            $this->redirect(array('action' => 'edit', $this->Sadr->id));
        } else {
            $this->Session->setFlash(__('The clean copy could not be created. Please, try again.'), 'alerts/flash_error');
            $this->redirect($this->referer());
        }
    }
    public function manager_edit($id = null)
    {
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            throw new NotFoundException(__('Invalid SADR'));
        }
        $this->general_editor($id);
    }
    public function reviewer_edit($id = null)
    {
        # code...
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            throw new NotFoundException(__('Invalid SADR'));
        }
        $this->general_editor($id);
    }

    public function clear_previous_seriousness($id)
    {
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            throw new NotFoundException(__('Invalid SADR'));
        }
        $this->Sadr->saveField('serious', "No", false);
        $this->Sadr->saveField('serious_reason', null, false);
    }

    public function general_editor($id = null)
    {
        # code...
        $sadr = $this->Sadr->read(null, $id);
        if ($this->request->is('post') || $this->request->is('put')) {
            $validate = false;
            // debug($this->request->data);
            // exit;
            // $validate = 'first';   
            if ($this->request->data['Sadr']['serious'] == "No") {
                $this->request->data['Sadr']['serious_reason'] = null;
            }
            if ($this->Sadr->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                if (isset($this->request->data['submitReport'])) {
                    $this->Sadr->saveField('submitted', 2);
                    $this->Sadr->saveField('submitted_date', date("Y-m-d H:i:s"));
                    $sadr = $this->Sadr->read(null, $id);

                    // check if there was a downgrade:

                    if ($sadr['Sadr']['serious'] == "No") {

                        $this->clear_previous_seriousness($sadr['SadrOriginal']['id']);
                    }

                    // debug($sadr);
                    // exit;                        
                    $this->Sadr->id = $id;
                    $this->Session->setFlash(__('The SADR has been saved'), 'alerts/flash_success');
                    $this->redirect(array('action' => 'view', $this->Sadr->id));
                }
                // debug($this->request->data);
                $this->Session->setFlash(__('The SADR has been saved'), 'alerts/flash_success');
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('The SADR could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $this->request->data = $this->Sadr->read(null, $id);
        }

        //Manager will always edit a copied report
        $sadr = $this->Sadr->find('first', array(
            'conditions' => array('Sadr.id' => $sadr['Sadr']['sadr_id']),
            'contain' => array('SadrListOfDrug', 'SadrDescription', 'SadrReaction', 'SadrListOfDrug.Route', 'SadrListOfDrug.Frequency', 'SadrListOfDrug.Dose', 'SadrListOfMedicine', 'SadrListOfMedicine.Route', 'SadrListOfMedicine.Frequency', 'SadrListOfMedicine.Dose', 'County', 'SubCounty', 'Attachment', 'Designation', 'ExternalComment')
        ));
        $this->set('sadr', $sadr);


        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'SADR_' . $id . '.pdf',  'orientation' => 'portrait');
            $this->response->download('SADR_' . $sadr['Sadr']['id'] . '.pdf');
        }

        $counties = $this->Sadr->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $sub_counties = $this->Sadr->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        $this->set(compact('sub_counties'));

        // get designations ordered by name descending
        $designations = $this->Sadr->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        // $designations = $this->Sadr->Designation->find('list');
        $this->set(compact('designations'));
        $routes = $this->Sadr->SadrListOfDrug->Route->find('list');
        $this->set(compact('routes'));
        $frequency = $this->Sadr->SadrListOfDrug->Frequency->find('list');
        $this->set(compact('frequency'));
        $dose = $this->Sadr->SadrListOfDrug->Dose->find('list');
        $this->set(compact('dose'));
    }

    public function manager_delete($id = null)
    {
        # code...
        $this->common_delete($id);
    }
    public function reporter_delete($id = null)
    {
        $this->common_delete($id);
    }

    public function common_delete($id = null)
    {
        # code...
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            throw new NotFoundException(__('Invalid SADR'));
        }
        $sadr = $this->Sadr->read(null, $id);
        if ($sadr['Sadr']['submitted'] == 2) {
            $this->Session->setFlash(__('You cannot delete a submitted SADR Report'), 'alerts/flash_error');
            $this->redirect($this->referer());
        }
        //update the field deleted to true and deleted_date to current date without validation 
        $sadr['Sadr']['deleted'] = true;
        $sadr['Sadr']['deleted_date'] = date("Y-m-d H:i:s");
        if ($this->Sadr->save($sadr, array('validate' => false))) {
            //displat message with reference number 
            $this->Session->setFlash(__('SADR Report ' . $sadr['Sadr']['reference_no'] . ' has been deleted'), 'alerts/flash_info');
            $user = $this->Auth->user();
            $type = 'Sadr Report Deleted';
            $message = 'SADR report with reference no: ' . $sadr['Sadr']['reference_no'] . ' has been deleted by ' . $user['name'];
            $this->createAuditTrail($type, $message);
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('SADR was not deleted'), 'alerts/flash_error');
        $this->redirect($this->referer());
    }
    public function guest_add($id = null)
    {
        $this->Sadr->create();
        $this->Sadr->save(['Sadr' => [
            'reference_no' => 'new',
            'report_type' => 'Initial',
            'pqmp_id' => $id,
        ]], false);
        $this->Session->setFlash(__('The SADR has been created'), 'alerts/flash_success');
        $this->redirect(array('action' => 'guest_edit', $this->Sadr->id));
    }
    public function guest_edit($id = null)
    {
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            throw new NotFoundException(__('Invalid SADR'));
        }
        $sadr = $this->Sadr->read(null, $id);

        if ($this->request->is('post') || $this->request->is('put')) {
            $validate = false;
            if (isset($this->request->data['submitReport'])) {
                $validate = 'first';
            }
            if ($this->Sadr->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                if (isset($this->request->data['submitReport'])) {
                    $this->Sadr->saveField('submitted', 2);
                    $this->Sadr->saveField('submitted_date', date("Y-m-d H:i:s"));
                    //lucian
                    // if(empty($sadr->reference_no)) {
                    if (!empty($sadr['Sadr']['reference_no']) && $sadr['Sadr']['reference_no'] == 'new') {
                        $reference = $this->generateReferenceNumber();
                        $this->Sadr->saveField('reference_no', $reference);
                    }
                    //bokelo
                    $sadr = $this->Sadr->read(null, $id);

                    //******************       Send Email and Notifications to Reporter and Managers          *****************************
                    $this->loadModel('Message');
                    $html = new HtmlHelper(new ThemeView());
                    $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_sadr_submit')));
                    $variables = array(
                        'name' => 'Guest',
                        'reference_no' => $sadr['Sadr']['reference_no'],
                        'reference_link' => $html->link(
                            $sadr['Sadr']['reference_no'],
                            array('controller' => 'sadrs', 'action' => 'view', $sadr['Sadr']['id'], 'reporter' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'modified' => $sadr['Sadr']['modified']
                    );
                    $datum = array(
                        'email' => $sadr['Sadr']['reporter_email'],
                        'id' => $id,
                        'user_id' => $this->Auth->User('id'),
                        'type' => 'reporter_sadr_submit',
                        'model' => 'Sadr',
                        'subject' => CakeText::insert($message['Message']['subject'], $variables),
                        'message' => CakeText::insert($message['Message']['content'], $variables)
                    );

                    $this->loadModel('Queue.QueuedTask');
                    $this->QueuedTask->createJob('GenericEmail', $datum);
                    $this->QueuedTask->createJob('GenericNotification', $datum);


                    //Send SMS
                    if (!empty($sadr['Sadr']['reporter_phone']) && strlen(substr($sadr['Sadr']['reporter_phone'], -9)) == 9 && is_numeric(substr($sadr['Sadr']['reporter_phone'], -9))) {
                        $datum['phone'] = '254' . substr($sadr['Sadr']['reporter_phone'], -9);
                        $variables['reference_url'] = Router::url(['controller' => 'sadrs', 'action' => 'view', $sadr['Sadr']['id'], 'reporter' => true, 'full_base' => true]);
                        $datum['sms'] = CakeText::insert($message['Message']['sms'], $variables);
                        $this->QueuedTask->createJob('GenericSms', $datum);
                    }

                    //Notify managers
                    $users = $this->Sadr->User->find('all', array(
                        'contain' => array(),
                        'conditions' => array('User.group_id' => 2, 'User.is_active' => '1')
                    ));
                    foreach ($users as $user) {
                        $variables = array(
                            'name' => $user['User']['name'],
                            'reference_no' => $sadr['Sadr']['reference_no'],
                            'reference_link' => $html->link(
                                $sadr['Sadr']['reference_no'],
                                array('controller' => 'sadrs', 'action' => 'view', $sadr['Sadr']['id'], 'manager' => true, 'full_base' => true),
                                array('escape' => false)
                            ),
                            'modified' => $sadr['Sadr']['modified']
                        );
                        $datum = array(
                            'email' => $user['User']['email'],
                            'id' => $id,
                            'user_id' => $user['User']['id'],
                            'type' => 'reporter_sadr_submit',
                            'model' => 'Sadr',
                            'subject' => CakeText::insert($message['Message']['subject'], $variables),
                            'message' => CakeText::insert($message['Message']['content'], $variables)
                        );

                        $this->QueuedTask->createJob('GenericEmail', $datum);
                        $this->QueuedTask->createJob('GenericNotification', $datum);
                    }
                    //**********************************    END   *********************************

                    $user = $this->Auth->user();
                    $type = 'New Sadr Report';
                    $message = 'A new SADR report with reference no: ' . $sadr['Sadr']['reference_no'] . ' has been submitted by ' . $user['name'];
                    $this->createAuditTrail($type, $message);

                    $this->Session->setFlash(__('The SADR has been submitted to PPB'), 'alerts/flash_success');
                    $this->redirect(array('controller' => 'pages', 'action' => 'home'));
                }
                // debug($this->request->data);
                $this->Session->setFlash(__('The SADR has been saved'), 'alerts/flash_success');
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('The SADR could not be saved. Please review the error(s) and resubmit and try again.'), 'alerts/flash_error');
            }
        } else {
            $this->request->data = $this->Sadr->read(null, $id);
        }

        //$sadr = $this->request->data;

        $counties = $this->Sadr->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $sub_counties = $this->Sadr->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        $this->set(compact('sub_counties'));
        $designations = $this->Sadr->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $routes = $this->Sadr->SadrListOfDrug->Route->find('list');
        $this->set(compact('routes'));
        $frequency = $this->Sadr->SadrListOfDrug->Frequency->find('list');
        $this->set(compact('frequency'));
        $dose = $this->Sadr->SadrListOfDrug->Dose->find('list');
        $this->set(compact('dose'));
    }
    public function manager_archive($id = null)
    {
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            throw new NotFoundException(__('Invalid SADR'));
        }
        $sadr = $this->Sadr->read(null, $id);

        $input = $sadr['Sadr']['created'];
        $created = DateTime::createFromFormat('d-m-Y', $input)->format('Y-m-d H:i:s');
        $sadr['Sadr']['created'] = $created;
        $sadr['Sadr']['archived'] = true;
        $sadr['Sadr']['archived_date'] = date("Y-m-d H:i:s");
        if ($this->Sadr->save($sadr, array('validate' => false))) {
            $this->Session->setFlash(__('SADR Archived successfully'), 'alerts/flash_success');
            $user = $this->Auth->user();
            $type = 'Sadr Report Archived';
            $message = 'SADR report with reference no: ' . $sadr['Sadr']['reference_no'] . ' has been archived by ' . $user['name'];
            $this->createAuditTrail($type, $message);
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('SADR was not archied'), 'alerts/flash_error');
        $this->redirect($this->referer());
    }
    public function manager_restore_archive($id = null)
    {
        $this->Sadr->id = $id;
        if (!$this->Sadr->exists()) {
            throw new NotFoundException(__('Invalid SADR'));
        }
        $sadr = $this->Sadr->read(null, $id);
        $input = $sadr['Sadr']['created'];
        $created = DateTime::createFromFormat('d-m-Y', $input)->format('Y-m-d H:i:s');
        $sadr['Sadr']['created'] = $created;
        $sadr['Sadr']['archived'] = false;
        // $sadr['Sadr']['archived_date'] = date("Y-m-d H:i:s");
        if ($this->Sadr->save($sadr, array('validate' => false))) {
            $this->Session->setFlash(__('SADR Report Restored successfully'), 'alerts/flash_success');
            $user = $this->Auth->user();
            $type = 'Sadr Report Restored';
            $message = 'SADR report with reference no: ' . $sadr['Sadr']['reference_no'] . ' has been restored by ' . $user['name'];
            $this->createAuditTrail($type, $message);
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('SADR was not restored'), 'alerts/flash_error');
        $this->redirect($this->referer());
    }
}
