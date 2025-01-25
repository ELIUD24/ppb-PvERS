<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeText', 'Utility');
App::uses('ThemeView', 'View');
App::uses('HtmlHelper', 'View/Helper');
App::uses('Router', 'Routing');
/**
 * Saefis Controller
 */
class SaefisController extends AppController
{

    /**
     * Scaffold
     *
     * @var mixed
     */

    public $components = array('Search.Prg');
    public $paginate = array();
    public $presetVars = true;
    public $page_options = array('25' => '25', '50' => '50', '100' => '100');
    public function index()
    {
        $this->Saefi->recursive = 1;
    }

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

        $criteria = $this->Saefi->parseCriteria($this->passedArgs);
        if ($this->Session->read('Auth.User.user_type') == 'Public Health Program') $criteria['Saefi.submitted'] = array(2);
        if ($this->Session->read('Auth.User.user_type') != 'Public Health Program') {

            // check if county pharmacist
            if ($this->Session->read('Auth.User.user_type') == 'County Pharmacist') { 
                $criteria['Saefi.submitted'] = array(2);
                $criteria['Saefi.province_id'] = $this->Auth->User('county_id');
            }else{
                $criteria['Saefi.user_id'] = $this->Auth->User('id');
            }

        } 

        // Added criteria for reporter
        $criteria['Saefi.deleted'] = false;
        $criteria['Saefi.archived'] = false;
        
        if (isset($this->request->query['submitted'])) {
            if ($this->request->query['submitted'] == 1) {
                $criteria['Saefi.submitted'] = array(0, 1);
            } else {
                $criteria['Saefi.submitted'] = array(2, 3);
            }
        } else {
            $criteria['Saefi.submitted'] = array(0,1,2, 3);
        }

        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Saefi.created' => 'desc');
        $this->paginate['contain'] = array('County','AefiListOfVaccine', 'AefiListOfVaccine.Vaccine', 'Designation');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            // $this->csv_export($this->Saefi->find(
            //     'all',
            //     array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            // ));
        }
        //end csv export
        $this->set('page_options', $this->page_options);
        $counties = $this->Saefi->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        // $sub_counties = $this->Saefi->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        // $this->set(compact('sub_counties'));
        $designations = $this->Saefi->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $this->set('saefis', Sanitize::clean($this->paginate(), array('encode' => false)));
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

        $criteria = $this->Saefi->parseCriteria($this->passedArgs);
        $criteria['Saefi.name_of_institution'] = $this->Auth->User('name_of_institution');
        $criteria['Saefi.submitted'] = array(1, 2);
        $criteria['Saefi.deleted'] = false;
        $criteria['Saefi.archived'] = false;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Saefi.created' => 'desc');
        $this->paginate['contain'] = array('County', 'AefiListOfVaccine', 'AefiDescription', 'AefiListOfVaccine.Vaccine', 'Designation');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            // $this->csv_export($this->Saefi->find(
            //     'all',
            //     array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            // ));
        }
        //end pdf export
        $this->set('page_options', $this->page_options);
        $counties = $this->Saefi->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $sub_counties = $this->Saefi->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        $this->set(compact('sub_counties'));
        $designations = $this->Saefi->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $this->set('saefis', Sanitize::clean($this->paginate(), array('encode' => false)));
    }

    public function manager_index()
    {
        $this->Prg->commonProcess();
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) {
            if (!empty($this->passedArgs['filter_by'])) {
                $this->passedArgs['reportrange'] = true;
            } else {
                $this->passedArgs['range'] = true;
            }
        }
        if (!empty($this->request->query['pages'])) $this->paginate['limit'] = $this->request->query['pages'];
        else $this->paginate['limit'] = reset($this->page_options);

        $criteria = $this->Saefi->parseCriteria($this->passedArgs);
        $criteria['Saefi.deleted'] = false;
        if (!empty($this->passedArgs['archived'])) {
            $criteria['Saefi.archived'] = true;
        }else{
            $criteria['Saefi.archived'] = false;
        }
        $criteria['Saefi.copied !='] = '1';
        if (isset($this->request->query['submitted'])) {
            if ($this->request->query['submitted'] == 1) {
                $criteria['Saefi.submitted'] = array(0, 1);
            } else {
                $criteria['Saefi.submitted'] = array(2, 3);
            }
        } else {
            $criteria['Saefi.submitted'] = array(2, 3);
        }
        // if (!isset($this->passedArgs['submit'])) $criteria['Saefi.submitted'] = array(2, 3);
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Saefi.created' => 'desc');
        $this->paginate['contain'] = array('County', 'AefiListOfVaccine', 'AefiListOfVaccine.Vaccine', 'Designation');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Saefi->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'], 'limit' => 10000)
            ));
        }
        //end pdf export
        $this->set('page_options', $this->page_options);
        $counties = $this->Saefi->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $designations = $this->Saefi->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $saefis = Sanitize::clean($this->paginate(), array('encode' => false));
        // debug($saefis);
        // exit;
        $this->set('saefis', $saefis);
    }

    private function csv_export($csaefis = '')
    {
        //todo: check if data exists in $users
        $this->response->download('SAEFIs_' . date('Ymd_Hi') . '.csv'); // <= setting the file name
        $this->set(compact('saefis'));
        $this->layout = false;
        $this->render('csv_export');
    }

    public function reporter_add($id = null)
    {
        $this->Saefi->create();
        $this->Saefi->save(
            [
                'Saefi' => [
                    'user_id' => $this->Auth->User('id'),
                    'reference_no' => 'new',
                    'report_type' => 'Initial',
                    'initial_id' => $id,
                    'designation_id' => $this->Auth->User('designation_id'),
                    'county_id' => $this->Auth->User('county_id'),
                    'reporter_name' => $this->Auth->User('name'),
                    'reporter_email' => $this->Auth->User('email'),
                    'reporter_phone' => $this->Auth->User('phone_no'),
                ]
            ],
            false
        );
        $this->redirect(array('action' => 'edit', $this->Saefi->id));
    }
    public function generateReferenceNumber()
    {

        $count = $this->Saefi->find('count',  array(
            'fields' => 'Saefi.reference_no',
            'conditions' => array(
                'Saefi.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s")), 'Saefi.reference_no !=' => 'new'
            )
        ));
        $count++;
        $count = ($count < 10) ? "0$count" : $count;
        $reference = 'SAEFI/' . date('Y') . '/' . $count;

        //ensure that the reference number is unique
        $exists = $this->Saefi->find('count',  array(
            'fields' => 'Saefi.reference_no',
            'conditions' => array(
                'Saefi.reference_no' => $reference
            )
        ));
        if ($exists > 0) {
            $this->generateReferenceNumber();
        }

        return $reference;
    }
    public function reporter_edit($id = null)
    {
        $this->Saefi->recursive = 1;
        $saefi = $this->Saefi->read(null, $id);
        if (
            $this->request->is('put') || $this->request->is('post')
        ) {
            $validate = false;
            if (isset($this->request->data['submitReport'])) {
                $validate = 'first';
            }

            $data = $this->request->data;
            // debug($data);
            // exit;
            if ($this->Saefi->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {

                if (isset($this->request->data['submitReport'])) {
                    $this->Saefi->saveField('submitted', 2);
                    $this->Saefi->saveField('submitted_date', date("Y-m-d H:i:s"));
                    //lucian
                    if (!empty($saefi['Saefi']['reference_no']) && $saefi['Saefi']['reference_no'] == 'new') {
                        $reference = $this->generateReferenceNumber();
                        $this->Saefi->saveField('reference_no', $reference);
                    }
                    //bokelo
                    $aefi = $this->Saefi->read(null, $id);
                    //******************       Send Email and Notifications to Applicant and Managers          *****************************
                    $this->loadModel('Message');
                    $html = new HtmlHelper(new ThemeView());
                    $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_saefi_submit')));
                    $variables = array(
                        'name' => $this->Auth->User('name'), 'reference_no' => $aefi['Saefi']['reference_no'],
                        'reference_link' => $html->link(
                            $aefi['Saefi']['reference_no'],
                            array('controller' => 'saefis', 'action' => 'view', $aefi['Saefi']['id'], 'reporter' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'modified' => $aefi['Saefi']['modified']
                    );
                    $datum = array(
                        'email' => $aefi['Saefi']['reporter_email'],
                        'id' => $id, 'user_id' => $this->Auth->User('id'), 'type' => 'reporter_saefi_submit', 'model' => 'Saefi',
                        'subject' => CakeText::insert($message['Message']['subject'], $variables),
                        'message' => CakeText::insert($message['Message']['content'], $variables)
                    );

                    $this->loadModel('Queue.QueuedTask');
                    $this->QueuedTask->createJob('GenericEmail', $datum);
                    $this->QueuedTask->createJob('GenericNotification', $datum);

                    //Send SMS
                    if (!empty($aefi['Saefi']['reporter_phone']) && strlen(substr($aefi['Saefi']['reporter_phone'], -9)) == 9 && is_numeric(substr($aefi['Saefi']['reporter_phone'], -9))) {
                        $datum['phone'] = '254' . substr($aefi['Saefi']['reporter_phone'], -9);
                        $variables['reference_url'] = Router::url(['controller' => 'saefis', 'action' => 'view', $aefi['Saefi']['id'], 'reporter' => true, 'full_base' => true]);
                        $datum['sms'] = CakeText::insert($message['Message']['sms'], $variables);
                        $this->QueuedTask->createJob('GenericSms', $datum);
                    }


                    //Notify managers
                    $users = $this->Saefi->User->find('all', array(
                        'contain' => array(),
                        'conditions' => array('User.group_id' => 2, 'User.is_active' => '1')
                    ));
                    foreach ($users as $user) {
                        $variables = array(
                            'name' => $user['User']['name'], 'reference_no' => $aefi['Saefi']['reference_no'],
                            'reference_link' => $html->link(
                                $aefi['Saefi']['reference_no'],
                                array('controller' => 'saefis', 'action' => 'view', $aefi['Saefi']['id'], 'manager' => true, 'full_base' => true),
                                array('escape' => false)
                            ),
                            'modified' => $aefi['Saefi']['modified']
                        );
                        $datum = array(
                            'email' => $user['User']['email'],
                            'id' => $id, 'user_id' => $user['User']['id'], 
                            'type' => 'reporter_saefi_submit', 'model' => 'Saefi',
                            'subject' => CakeText::insert($message['Message']['subject'], $variables),
                            'message' => CakeText::insert($message['Message']['content'], $variables)
                        );

                        $this->QueuedTask->createJob('GenericEmail', $datum);
                        $this->QueuedTask->createJob('GenericNotification', $datum);
                    }
                    //**********************************    END   *********************************

                    $this->Session->setFlash(__('The Serious Adverse Event Following Immunization has been submitted to PPB'), 'alerts/flash_success');
                    $this->redirect(array('action' => 'view', $this->Saefi->id));
                }
                $this->Session->setFlash(__('The SAEFI has been saved'), 'alerts/flash_success');
            } else {
                $this->Session->setFlash(__('The SAEFI could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $this->request->data = $this->Saefi->read(null, $id);
        }
        //county
        $counties = $this->Saefi->County->find('list', array('order' => array('County.county_name' => 'asc')));
        $this->set('counties', $counties);
        $this->set('saefi', $saefi);

        $sub_counties = $this->Saefi->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        $this->set(compact('sub_counties'));
        //designation and vaccines
        $designations = $this->Saefi->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set('designations', $designations);
        $vaccines = $this->Saefi->AefiListOfVaccine->Vaccine->find('list');
        $this->set('vaccines', $vaccines);
    }
    public function reporter_view($id = null)
    {
        $this->Saefi->id = $id;
        if (!$this->Saefi->exists()) {
            $this->Session->setFlash(__('Could not verify the Adverse Event Following Immunization report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'SAEFI_' . $id,  'orientation' => 'portrait');
            // $this->response->download('AEFI_'.$aefi['Saefi']['id'].'.pdf');
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['continueEditing'])) {
                $this->Saefi->saveField('submitted', 0);
                $this->Session->setFlash(__('You can continue editing the report'), 'flash_success');
                $this->redirect(array('action' => 'edit', $this->Saefi->Luhn($this->Saefi->id)));
            }
            if (isset($this->request->data['sendToPPB'])) {
                $this->Saefi->saveField('submitted', 2);
                $this->Session->setFlash(__('Thank you for submitting your report. You will get an email with a link to the pdf copy of the report.'), 'flash_success');
                $this->redirect('/');
            }
        }

        // $aefi = $this->Saefi->read(null);
        $aefi = $this->Saefi->find('first', array(
            'conditions' => array('Saefi.id' => $id),
            'contain' => array(
                'AefiListOfVaccine', 'AefiListOfVaccine.Vaccine',
                 'County', 'Review', 'Review.User', 'Review.Attachment',  'Attachment', 'Designation', 'ExternalComment',

            )
        ));
        $this->set('saefi', $aefi);
        // $this->render('pdf/view');

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'SAEFI_' . $id . '.pdf',  'orientation' => 'portrait');
            $this->response->download('SAEFI_' . $aefi['Saefi']['id'] . '.pdf');
        }
    }

    public function manager_view($id = null)
    {
        $this->Saefi->id = $id;
        if (!$this->Saefi->exists()) {
            $this->Session->setFlash(__('Could not verify the medical devices report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        $this->general_view($id);
    }


    public function general_view($id = null)
    {
        # code...
       
        $saefi = $this->Saefi->find('first', array(
            'conditions' => array('Saefi.id' => $id),
            'contain' => array(
                'AefiListOfVaccine', 'AefiListOfVaccine.Vaccine', 'County', 'Review', 'Review.User', 'Review.Attachment', 'Attachment', 'Designation', 'ExternalComment',

            )
        ));
        // debug($saefi);
        // exit;
        $managers = $this->Saefi->User->find('list', array(
            'conditions' => array(
                'User.group_id' => 6
            )
        ));
        $this->set(['saefi' => $saefi, 'managers' => $managers]);


        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'AEFI_' . $id . '.pdf',  'orientation' => 'portrait');
            $this->response->download('SAEFI_' . $saefi['Saefi']['id'] . '.pdf');
        }
    }
    public function reviewer_view($id = null)
    {
        # code...
        $this->Saefi->id = $id;
        if (!$this->Saefi->exists()) {
            $this->Session->setFlash(__('Could not verify the medical devices report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        $this->general_view($id);
    }

    public function manager_copy($id = null)
    {
        $this->general_copy($id);
    }

    public function reviewer_copy($id = null)
    {
        # code...
        $this->general_copy($id);
    }
    public function general_copy($id = null)
    {
        # code...
        $this->Saefi->recursive = 1;
        if ($this->request->is('post')) {
            $this->Saefi->id = $id;
            if (!$this->Saefi->exists()) {
                throw new NotFoundException(__('Invalid Adverse Event Following Immunization'));
            }
            $aefi = Hash::remove($this->Saefi->find(
                'first',
                array(
                    'contain' => array('AefiListOfVaccine'),
                    'conditions' => array('Saefi.id' => $id)
                )
            ), 'Saefi.id');
            if ($aefi['Saefi']['copied']) {
                $this->Session->setFlash(__('A clean copy already exists. Click on edit to update changes.'), 'alerts/flash_error');
                return $this->redirect(array('action' => 'index'));
            }
            $aefi = Hash::remove($aefi, 'AefiListOfVaccine.{n}.id');
            $data_save = $aefi['Saefi'];
            if (isset($aefi['AefiListOfVaccine']))  $data_save['AefiListOfVaccine'] = $aefi['AefiListOfVaccine'];
            $data_save['saefi_id'] = $id;
            $data_save['user_id'] = $this->Auth->User('id');;
            $this->Saefi->saveField('copied', 1);
            $data_save['copied'] = 2;

            if ($this->Saefi->saveAssociated($data_save, array('deep' => true, 'validate' => false))) {
                $this->Session->setFlash(__('Clean copy of ' . $data_save['reference_no'] . ' has been created'), 'alerts/flash_info');
                return $this->redirect(array('action' => 'edit', $this->Saefi->id));
            } else {
                $this->Session->setFlash(__('The clean copy could not be created. Please, try again.'), 'alerts/flash_error');
                return $this->redirect($this->referer());
            }
        }
    }

    public function manager_edit($id = null)
    {
        $this->Saefi->id = $id;
        if (!$this->Saefi->exists()) {
            throw new NotFoundException(__('Invalid Serious Adverse Event Following Immunization'));
        }
        $this->general_edit($id);
    }
    public function reviewer_edit($id = null)
    {
        # code...
        $this->Saefi->id = $id;
        if (!$this->Saefi->exists()) {
            throw new NotFoundException(__('Invalid Serious Adverse Event Following Immunization'));
        }
        $this->general_edit($id);
    }
    public function general_edit($id = null)
    {
        # code...
        $this->Saefi->recursive = 1;
        $aefi = $this->Saefi->read(null, $id);

        if ($this->request->is('post') || $this->request->is('put')) {
            $validate = false;
            if (isset($this->request->data['submitReport'])) {
                $validate = 'first';
            }
            if ($this->Saefi->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                if (isset($this->request->data['submitReport'])) {
                    $this->Saefi->saveField('submitted', 2);
                    $this->Saefi->saveField('submitted_date', date("Y-m-d H:i:s"));
                    $aefi = $this->Saefi->read(null, $id);

                    $this->Session->setFlash(__('The Serious Adverse Event Following Immunization has been submitted to PPB'), 'alerts/flash_success');
                    $this->redirect(array('action' => 'view', $this->Saefi->id));
                }
                // debug($this->request->data);
                $this->Session->setFlash(__('The Adverse Event Following Immunization has been saved'), 'alerts/flash_success');
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('The Adverse Event Following Immunization could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $this->request->data = $this->Saefi->read(null, $id);
        }

        //Manager will always edit a copied report
        $aefi = $this->Saefi->find('first', array(
            'conditions' => array('Saefi.id' => $aefi['Saefi']['saefi_id']),
            'contain' => array('AefiListOfVaccine', 'County',  'Attachment', 'Designation', 'ExternalComment')
        ));
        $this->set('aefi', $aefi);

        $counties = $this->Saefi->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $designations = $this->Saefi->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $sub_counties = $this->Saefi->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        $this->set(compact('sub_counties'));
        $vaccines = $this->Saefi->AefiListOfVaccine->Vaccine->find('list');
        $this->set(compact('vaccines'));
    }


    // Reviews
    public function manager_review($id = null)
    {
        # code...
        $this->Saefi->recursive = 1;
        $aefi = $this->Saefi->read(null, $id);

        $data_save = $this->request->data;
        // debug($data_save);
        // exit;

        if ($this->Saefi->saveAssociated($data_save, array('deep' => true, 'validate' => false))) {
            $this->Session->setFlash(__('Review successfully submitted'), 'alerts/flash_info');
            return $this->redirect(array('action' => 'view', $this->Saefi->id));
        } else {
            $this->Session->setFlash(__('Failed to Post the Review,please try again'), 'alerts/flash_error');
            return $this->redirect($this->referer());
        }
    }
    public function manager_archive($id=null) {

        $this->Saefi->id = $id;
        if (!$this->Saefi->exists()) {
            throw new NotFoundException(__('Invalid SAEFI'));
        }
        $report = $this->Saefi->read(null, $id);
        $report['Saefi']['archived'] = true;
        $report['Saefi']['archived_date'] = date("Y-m-d H:i:s");
        if ($this->Saefi->save($report, array('validate' => false))) {
            $this->Session->setFlash(__('SAEFI Archived successfully'), 'alerts/flash_success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('SAEFI was not archied'), 'alerts/flash_error');
        $this->redirect($this->referer());
	}
    public function manager_restore_archive($id=null) {

        $this->Saefi->id = $id;
        if (!$this->Saefi->exists()) {
            throw new NotFoundException(__('Invalid SAEFI'));
        }
        $report = $this->Saefi->read(null, $id);
        $report['Saefi']['archived'] = 0;
        // $report['Saefi']['archived_date'] = date("Y-m-d H:i:s");
        if ($this->Saefi->save($report, array('validate' => false))) {
            $this->Session->setFlash(__('SAEFI Archive Restored successfully'), 'alerts/flash_success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('SAEFI was not restored'), 'alerts/flash_error');
        $this->redirect($this->referer());
	}
}
