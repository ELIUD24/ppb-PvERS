<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeText', 'Utility');
App::uses('Security', 'Utility');
App::uses('ThemeView', 'View');
App::uses('HtmlHelper', 'View/Helper');
App::uses('Router', 'Routing');
App::uses('HttpSocket', 'Network/Http');



/**
 * Padrs Controller
 *
 * @property Padr $Padr
 * @property PaginatorComponent $Paginator
 */
class PadrsController extends AppController
{

    /**
     * Components
     *
     * @var array
     */
    // public $components = array('Paginator');
    public $components = array(
        // 'Security' => array('csrfExpires' => '+1 hour', 'validatePost' => false), 
        'Search.Prg',
        // 'Paginator'
    );
    public $paginate = array();
    public $presetVars = true;
    public $page_options = array('25' => '25', '50' => '50', '100' => '100');
    public $helpers = array('Tools.Captcha' => array('type' => 'active'));

    public function beforeFilter()
    {
        parent::beforeFilter();
        // remove initDb
        $this->Auth->allow('add', 'view', 'followup', 'edit');
    }
    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->Padr->recursive = 0;
        $this->set('padrs', $this->Paginator->paginate());
    }

    public function manager_index()
    {
        $this->Prg->commonProcess();
        // debug($this->request->query['pages']);
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (!empty($this->request->query['pages'])) $this->paginate['limit'] = $this->request->query['pages'];
        else $this->paginate['limit'] = reset($this->page_options);

        $criteria = $this->Padr->parseCriteria($this->passedArgs);

        $criteria['Padr.copied !='] = '1';
        $criteria['Padr.archived'] = false;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Padr.id' => 'DESC');
        $this->paginate['contain'] = array('County');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Padr->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'limit' => 10000)
            ));
        }

        //end pdf export
        $this->set('page_options', $this->page_options);
        $counties = $this->Padr->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $designations = $this->Padr->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $this->set('padrs', Sanitize::clean($this->paginate(), array('encode' => false)));
    }

    public function reviewer_index()
    {
        # code...
        $this->Prg->commonProcess();
        // debug($this->request->query['pages']);
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (!empty($this->request->query['pages'])) $this->paginate['limit'] = $this->request->query['pages'];
        else $this->paginate['limit'] = reset($this->page_options);

        $criteria = $this->Padr->parseCriteria($this->passedArgs); 
        $criteria['Padr.archived'] = false;
        $criteria['Padr.assigned_to'] = $this->Auth->User('id');
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Padr.id' => 'DESC');
        $this->paginate['contain'] = array('County');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Padr->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'limit' => 10000)
            ));
        }
        //end pdf export
        $this->set('page_options', $this->page_options);
        $counties = $this->Padr->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $designations = $this->Padr->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $this->set('padrs', Sanitize::clean($this->paginate(), array('encode' => false)));
    }

    private function csv_export($cpadrs = '')
    {
        $this->response->download('PADRs_' . date('Ymd_Hi') . '.csv'); // <= setting the file name
        $this->set(compact('cpadrs'));
        $this->layout = false;
        $this->render('csv_export');
    }
    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($token = null)
    {
        $id = $this->Padr->field('id', array('token' => $token));
        if (!$this->Padr->exists($id)) {
            throw new NotFoundException(__('Invalid padr'));
            $this->Flash->error(__('We could not identify the report. Please refer to the acknowledgement email sent by PPB.'));
        }
        $options = array('conditions' => array('Padr.' . $this->Padr->primaryKey => $id));
        $padr = $this->Padr->find('first', $options);
        $this->set('padr', $this->Padr->find('first', $options));

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'PADR_' . $id . '.pdf',  'orientation' => 'portrait');
            $this->response->download('PADR_' . str_replace($padr['Padr']['reference_no'], '/', '_') . '.pdf');
        }
    }

    public function manager_view($id = null)
    {
        $this->general_view($id);
    }

    public function reviewer_view($id = null)
    {
        # code...
        $this->general_view($id);
    }

    public function general_view($id = null)
    {
        # code...
        $this->Padr->id = $id;
        if (!$this->Padr->exists($id)) {
            throw new NotFoundException(__('Invalid padr'));
            $this->Flash->error(__('We could not identify the report. Please refer to the acknowledgement email sent by PPB.'));
        }
        $options = array('conditions' => array('Padr.' . $this->Padr->primaryKey => $id));
        $padr = $this->Padr->find('first', $options);
        $managers = $this->Padr->User->find('list', array(
            'conditions' => array(
                'User.group_id' => 6,
                'User.is_active' => 1
            )
        ));

        $this->set(['padr' => $this->Padr->find('first', $options), 'managers' => $managers]);

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'PADR_' . $id . '.pdf',  'orientation' => 'portrait');
            $this->response->download('PADR_' . str_replace($padr['Padr']['reference_no'], '/', '_') . '.pdf');
        }
    }

    // Assign the report to the evaluator
    public function manager_assign()
    {
        # code...
        $id = $this->request->data['Padr']['report_id'];
        $this->Padr->id = $id;
        if (!$this->Padr->exists()) {
            $this->Session->setFlash(__('Could not verify the Padr report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }
        $this->Padr->saveField('assigned_by', $this->request->data['Padr']['assigned_by']);
        $this->Padr->saveField('assigned_to', $this->request->data['Padr']['assigned_to']);
        $this->Padr->saveField('assigned_date', date("Y-m-d H:i:s"));

        // Send an asignment alert::::


        $this->Session->setFlash(__('The Padr has been assigned successfully'), 'alerts/flash_success');
        $this->redirect(array('action' => 'view', $id));
    }

    public function manager_unassign($id = null)
    {
        # code...
        $this->Padr->id = $id;
        if (!$this->Padr->exists()) {
            $this->Session->setFlash(__('Could not verify the Padr report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }
        $this->Padr->saveField('assigned_by', '');
        $this->Padr->saveField('assigned_to', '');
        $this->Padr->saveField('assigned_date', '');

        // Send an asignment alert::::


        $this->Session->setFlash(__('The Padr has been unassigned successfully'), 'alerts/flash_success');
        $this->redirect(array('action' => 'view', $id));
    }

    public function api_view($token = null)
    {
        $id = $this->Padr->field('id', array('token' => $token));
        if (!$this->Padr->exists($id)) {
            throw new NotFoundException(__('Invalid padr'));
        }
        $options = array('conditions' => array('Padr.' . $this->Padr->primaryKey => $id));
        $padr = $this->Padr->find('first', $options);
        if (empty($padr)) {  //TODO: Confirm if the user_id is allowed to access
            $this->set([
                'status' => 'failed',
                'message' => 'Could not verify the PADR report ID. Please ensure the ID is correct.',
                '_serialize' => ['status', 'message']
            ]);
        } else {

            $this->set([
                'status' => 'success',
                'padr' => $padr,
                '_serialize' => ['status', 'padr']
            ]);

            if (strpos($this->request->url, 'pdf') !== false) {
                $this->pdfConfig = array('filename' => 'PADR_' . $id . '.pdf',  'orientation' => 'portrait');
                $this->response->download('PADR_' . str_replace($padr['Padr']['reference_no'], '/', '_') . '.pdf');
            }
        }
    }

    public function followup($token = null)
    {
        if ($this->request->is('post')) {
            $id = $this->Padr->field('id', array('token' => $token));
            $this->Padr->id = $id;
            if (!$this->Padr->exists()) {
                throw new NotFoundException(__('Invalid PADR'));
                $this->Flash->error(__('We could not identify the report. Please refer to the acknowledgement email sent by PPB.'));
            }
            $padr = Hash::remove($this->Padr->find(
                'first',
                array(
                    'contain' => array('PadrListOfMedicine'),
                    'conditions' => array('Padr.id' => $id)
                )
            ), 'Padr.id');

            $padr = Hash::remove($padr, 'PadrListOfMedicine.{n}.id');
            $data_save = $padr['Padr'];
            if (isset($padr['PadrListOfMedicine'])) $data_save['PadrListOfMedicine'] = $padr['PadrListOfMedicine'];
            $data_save['padr_id'] = $id;

            $count = $this->Padr->find('count',  array('conditions' => array(
                'Padr.reference_no LIKE' => $padr['Padr']['reference_no'] . '%',
            )));
            $count = ($count < 10) ? "0$count" : $count;
            $data_save['reference_no'] = $padr['Padr']['reference_no'] . '_F' . $count;
            $data_save['report_type'] = 'Followup';
            $data_save['submitted'] = 0;

            if ($this->Padr->saveAssociated($data_save, array('deep' => true, 'validate' => false))) {
                $this->Padr->saveField('token', Security::hash($this->Padr->id));
                $padr = $this->Padr->read();
                $this->Flash->success(__('Follow up ' . $data_save['reference_no'] . ' has been created'));
                $this->redirect(array('action' => 'edit', $padr['Padr']['token']));
            } else {
                $this->Flash->error(__('The followup could not be saved. Please, try again.'));
                $this->redirect($this->referer());
            }
        }
    }
    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Padr->create();
            $this->Padr->Behaviors->attach('Tools.Captcha');
            // debug($this->request->data);
            if ($this->Padr->saveAssociated($this->request->data)) {
                $count = $this->Padr->find('count',  array('conditions' => array(
                    'Padr.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
                )));
                $count++;
                $count = ($count < 10) ? "0$count" : $count;
                $this->Padr->saveField('reference_no', 'PADR/' . date('Y') . '/' . $count);
                $this->Padr->saveField('token', Security::hash($this->Padr->id));

                //******************       Send Emails to Reporter and Managers          *****************************
                $this->loadModel('Message');
                $html = new HtmlHelper(new ThemeView());
                $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_padr_submit')));
                $padr = $this->Padr->read();
                $variables = array(
                    'name' => $padr['Padr']['reporter_name'], 'reference_no' => $padr['Padr']['reference_no'],
                    'reference_link' => $html->link(
                        $padr['Padr']['reference_no'],
                        array('controller' => 'padrs', 'action' => 'view', $padr['Padr']['token'], 'full_base' => true),
                        array('escape' => false)
                    ),
                    'modified' => $padr['Padr']['modified']
                );
                $datum = array(
                    'email' => $padr['Padr']['reporter_email'],
                    'id' => $this->Padr->id,  'type' => 'reporter_padr_submit', 'model' => 'Padr',
                    'subject' => CakeText::insert($message['Message']['subject'], $variables),
                    'message' => CakeText::insert($message['Message']['content'], $variables)
                );

                $this->loadModel('Queue.QueuedTask');
                $this->QueuedTask->createJob('GenericEmail', $datum);

                //Send SMS
                if (!empty($padr['Padr']['reporter_phone']) && strlen(substr($padr['Padr']['reporter_phone'], -9)) == 9 && is_numeric(substr($padr['Padr']['reporter_phone'], -9))) {
                    $datum['phone'] = '254' . substr($padr['Padr']['reporter_phone'], -9);
                    $variables['reference_url'] = Router::url(['controller' => 'padrs', 'action' => 'view', $padr['Padr']['token'], 'reporter' => true, 'full_base' => true]);
                    $datum['sms'] = CakeText::insert($message['Message']['sms'], $variables);
                    $this->QueuedTask->createJob('GenericSms', $datum);
                }

                //Notify managers
                $users = $this->Padr->User->find('all', array(
                    'contain' => array(),
                    'conditions' => array('User.group_id' => 2, 'User.is_active' => '1')
                ));
                foreach ($users as $user) {
                    $variables = array(
                        'name' => $user['User']['name'], 'reference_no' => $padr['Padr']['reference_no'],
                        'reference_link' => $html->link(
                            $padr['Padr']['reference_no'],
                            array('controller' => 'padrs', 'action' => 'view', $padr['Padr']['token'], 'manager' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'modified' => $padr['Padr']['modified']
                    );
                    $datum = array(
                        'email' => $user['User']['email'],
                        'id' => $this->Padr->id, 'user_id' => $user['User']['id'], 'type' => 'reporter_padr_submit', 'model' => 'Padr',
                        'subject' => CakeText::insert($message['Message']['subject'], $variables),
                        'message' => CakeText::insert($message['Message']['content'], $variables)
                    );

                    $this->QueuedTask->createJob('GenericEmail', $datum);
                    $this->QueuedTask->createJob('GenericNotification', $datum);
                }
                //**********************************    END   *********************************

                $this->Flash->success(__('Your report has been successfully submitted to PPB. Please check your email for the acknowldgement.'));
                return $this->redirect(array('action' => 'view', $padr['Padr']['token']));
            } else {
                $this->Flash->error(__('The report could not be created. Please, try again.'));
            }
        }

        $counties = $this->Padr->County->find('list', array('order' => array('County.county_name')));
        $this->set(compact('counties'));
    }
    public function manager_upload()
    {
        # code...
        if ($this->request->is('post')) {
            if (!empty($this->request->data['Padr']['excel_file']['tmp_name'])) {
                $file = $this->request->data['Padr']['excel_file'];
                $ext = substr(strtolower(strrchr($file['name'], '.')), 1);
                $arr_ext = array('csv');
                if (in_array($ext, $arr_ext)) {
                    $filename = $file['name'];
                    $file_tmp = $file['tmp_name'];
                    move_uploaded_file($file_tmp, WWW_ROOT . 'files/' . $filename);

                    $file = fopen(WWW_ROOT . 'files/' . $filename, 'r');
                    $firstLine = true;
                    while (($line = fgetcsv($file)) !== false) {
                        if ($firstLine) {
                            $firstLine = false;
                            continue;
                        }
                        $data = array(
                            'reporter_phone' => $line[0],
                            'reporter_name' => $line[1],
                            'patient_name' => $line[3],
                            'gender' => $line[8],
                            // 'submitted' => '2',
                            'submitted_date' => date("Y-m-d H:i:s"),
                            // etc.
                        );
                        //  `county_id`, `sub_county_id`, `designation_id`, ` `, ` `, ``, ` `, `report_title`, `report_type`, `report_sadr`, `sadr_vomiting`, `sadr_dizziness`, `sadr_headache`, `sadr_joints`, `sadr_rash`, `sadr_mouth`, `sadr_stomach`, `sadr_urination`, `sadr_eyes`, `sadr_died`, `pqmp_label`, ` `, `pqmp_material`, `date_of_birth`, `age_group`, `patient_address`, `pqmp_color`, ``, `pqmp_smell`, `pqmp_working`, `pqmp_bottle`, `pregnancy_status`, `weight`, `height`, `diagnosis`, `medical_history`, `date_of_onset_of_reaction`, `date_of_end_of_reaction`, `description_of_reaction`, `reaction_resolve`, `reaction_reappear`, `lab_investigation`, `severity`, `serious`, `serious_reason`, `action_taken`, `outcome`, `causality`, `any_other_comment`, ``, `reporter_email`, ``, ``, ``, `emails`, `active`, `device`, `deleted`, `deleted_date`, `notified`, `created`, `modified`, `reporter_date`, `reporter_name_diff`, `reporter_designation_diff`, `reporter_email_diff`, `reporter_phone_diff`, `reporter_date_diff`, `reaction_on` FROM `padrs` WHERE 1
                        $this->Padr->create();
                        if ($this->Padr->save($data, array('validate' => false))) {
                            $count = $this->Padr->find('count',  array('conditions' => array(
                                'Padr.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
                            )));
                            $count++;
                            $count = ($count < 10) ? "0$count" : $count;
                            $this->Padr->saveField('reference_no', 'PADR/' . date('Y') . '/' . $count);
                            $this->Padr->saveField('token', Security::hash($this->Padr->id));

                            // $this->Flash->success(__('File uploaded successfully.'), 'flash_success');
                        } else {

                            // $this->Flash->error(__('The report could not be saved. Please, try again.'), 'flash_error');
                        }
                    }
                    fclose($file);
                    $this->Flash->success(__('File uploaded successfully.'), 'flash_success');
                    $this->redirect($this->referer());
                } else {
                    $this->Flash->error(__('The report could not be saved. Please, try again.'), 'flash_error');
                    $this->redirect($this->referer());
                }
            } else {
                $this->Flash->error(__('The report could not be saved. Please, try again with a valid csv file'), 'flash_error');
                $this->redirect($this->referer());
            }
        }
        $counties = $this->Padr->County->find('list', array('order' => array('County.county_name')));
        $this->set(compact('counties'));
    }

    public function api_add()
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Padr->create();
            $this->_attachments('Padr');

            // debug($this->request->data);
            if ($this->Padr->saveAssociated($this->request->data)) {
                $count = $this->Padr->find('count',  array('conditions' => array(
                    'Padr.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
                )));
                $count++;
                $count = ($count < 10) ? "0$count" : $count;
                $this->Padr->saveField('reference_no', 'PADR/' . date('Y') . '/' . $count);
                $this->Padr->saveField('token', Security::hash($this->Padr->id));

                //******************       Send Emails to Reporter and Managers          *****************************
                $this->loadModel('Message');
                $html = new HtmlHelper(new ThemeView());
                $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_padr_submit')));
                $padr = $this->Padr->read();
                $variables = array(
                    'name' => $padr['Padr']['reporter_name'], 'reference_no' => $padr['Padr']['reference_no'],
                    'reference_link' => $html->link(
                        $padr['Padr']['reference_no'],
                        array('controller' => 'padrs', 'action' => 'view', $padr['Padr']['token'], 'full_base' => true),
                        array('escape' => false)
                    ),
                    'modified' => $padr['Padr']['modified']
                );
                $datum = array(
                    'email' => $padr['Padr']['reporter_email'],
                    'id' => $this->Padr->id,  'type' => 'reporter_padr_submit', 'model' => 'Padr',
                    'subject' => CakeText::insert($message['Message']['subject'], $variables),
                    'message' => CakeText::insert($message['Message']['content'], $variables)
                );

                $this->loadModel('Queue.QueuedTask');
                $this->QueuedTask->createJob('GenericEmail', $datum);

                //Send SMS
                if (!empty($padr['Padr']['reporter_phone']) && strlen(substr($padr['Padr']['reporter_phone'], -9)) == 9 && is_numeric(substr($padr['Padr']['reporter_phone'], -9))) {
                    $datum['phone'] = '254' . substr($padr['Padr']['reporter_phone'], -9);
                    $variables['reference_url'] = Router::url(['controller' => 'padrs', 'action' => 'view', $padr['Padr']['token'], 'reporter' => true, 'full_base' => true]);
                    $datum['sms'] = CakeText::insert($message['Message']['sms'], $variables);
                    $this->QueuedTask->createJob('GenericSms', $datum);
                }

                //Notify managers
                $users = $this->Padr->User->find('all', array(
                    'contain' => array(),
                    'conditions' => array('User.group_id' => 2, 'User.is_active' => '1')
                ));
                foreach ($users as $user) {
                    $variables = array(
                        'name' => $user['User']['name'], 'reference_no' => $padr['Padr']['reference_no'],
                        'reference_link' => $html->link(
                            $padr['Padr']['reference_no'],
                            array('controller' => 'padrs', 'action' => 'view', $padr['Padr']['token'], 'manager' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'modified' => $padr['Padr']['modified']
                    );
                    $datum = array(
                        'email' => $user['User']['email'],
                        'id' => $this->Padr->id, 'user_id' => $user['User']['id'], 'type' => 'reporter_padr_submit', 'model' => 'Padr',
                        'subject' => CakeText::insert($message['Message']['subject'], $variables),
                        'message' => CakeText::insert($message['Message']['content'], $variables)
                    );

                    $this->QueuedTask->createJob('GenericEmail', $datum);
                    $this->QueuedTask->createJob('GenericNotification', $datum);
                }
                //**********************************    END   *********************************


                $this->set([
                    'status' => 'success',
                    'message' => 'The PADR has been submitted to PPB',
                    'padr' => $padr,
                    '_serialize' => ['status', 'message', 'padr']
                ]);
            } else {
                $this->set([
                    'status' => 'failed',
                    'message' => 'The PADR could not be saved',
                    'validation' => $this->Padr->validationErrors,
                    'padr' => $this->request->data,
                    '_serialize' => ['status', 'message', 'validation', 'padr']
                ]);
            }
        } else {
            throw new MethodNotAllowedException();
        }
    }
    public function api_counties()
    {
        if ($this->request->is('get')) {
            $counties = $this->Padr->County->find('list', array('order' => array('County.county_name')));
            $this->set([
                'status' => 'success',
                'message' => 'County list successfully retrieved',
                'counties' => $counties,
                '_serialize' => ['status', 'message', 'counties']
            ]);
        } else {
            throw new MethodNotAllowedException();
        }
    }
    public function api_submit()
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Padr->create();
            $this->_attachments('Padr');

            //add the sender flag
            $this->request->data['Padr']['device'] = "3";
            $this->request->data['Padr']['consent'] = "Yes";

            if ($this->Padr->saveAssociated($this->request->data)) {
                $count = $this->Padr->find('count',  array('conditions' => array(
                    'Padr.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
                )));
                $count++;
                $count = ($count < 10) ? "0$count" : $count;
                $this->Padr->saveField('reference_no', 'PADR/' . date('Y') . '/' . $count);
                $this->Padr->saveField('token', Security::hash($this->Padr->id));
                $this->Padr->saveField('submitted_date', date("Y-m-d H:i:s"));
                $this->Padr->saveField('user_id', $this->Auth->User('id'));

                //******************       Send Emails to Reporter and Managers          *****************************
                $this->loadModel('Message');
                $html = new HtmlHelper(new ThemeView());
                $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_padr_submit')));
                $padr = $this->Padr->read();
                $variables = array(
                    'name' => $padr['Padr']['reporter_name'], 'reference_no' => $padr['Padr']['reference_no'],
                    'reference_link' => $html->link(
                        $padr['Padr']['reference_no'],
                        array('controller' => 'padrs', 'action' => 'view', $padr['Padr']['token'], 'full_base' => true),
                        array('escape' => false)
                    ),
                    'modified' => $padr['Padr']['modified']
                );
                $datum = array(
                    'email' => $padr['Padr']['reporter_email'],
                    'id' => $this->Padr->id,  'type' => 'reporter_padr_submit', 'model' => 'Padr',
                    'subject' => CakeText::insert($message['Message']['subject'], $variables),
                    'message' => CakeText::insert($message['Message']['content'], $variables)
                );

                $this->loadModel('Queue.QueuedTask');
                $this->QueuedTask->createJob('GenericEmail', $datum);

                //Send SMS
                if (!empty($padr['Padr']['reporter_phone']) && strlen(substr($padr['Padr']['reporter_phone'], -9)) == 9 && is_numeric(substr($padr['Padr']['reporter_phone'], -9))) {
                    $datum['phone'] = '254' . substr($padr['Padr']['reporter_phone'], -9);
                    $variables['reference_url'] = Router::url(['controller' => 'padrs', 'action' => 'view', $padr['Padr']['token'], 'reporter' => true, 'full_base' => true]);
                    $datum['sms'] = CakeText::insert($message['Message']['sms'], $variables);
                    $this->QueuedTask->createJob('GenericSms', $datum);
                }

                //Notify managers
                $users = $this->Padr->User->find('all', array(
                    'contain' => array(),
                    'conditions' => array('User.group_id' => 2, 'User.is_active' => '1')
                ));
                foreach ($users as $user) {
                    $variables = array(
                        'name' => $user['User']['name'], 'reference_no' => $padr['Padr']['reference_no'],
                        'reference_link' => $html->link(
                            $padr['Padr']['reference_no'],
                            array('controller' => 'padrs', 'action' => 'view', $padr['Padr']['token'], 'manager' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'modified' => $padr['Padr']['modified']
                    );
                    $datum = array(
                        'email' => $user['User']['email'],
                        'id' => $this->Padr->id, 'user_id' => $user['User']['id'], 'type' => 'reporter_padr_submit', 'model' => 'Padr',
                        'subject' => CakeText::insert($message['Message']['subject'], $variables),
                        'message' => CakeText::insert($message['Message']['content'], $variables)
                    );

                    $this->QueuedTask->createJob('GenericEmail', $datum);
                    $this->QueuedTask->createJob('GenericNotification', $datum);
                }
                //**********************************    END   *********************************

                $hey = array('reference' => $padr['Padr']['reference_no']);

                $this->set([
                    'status' => 'success',
                    'message' => 'The PADR has been submitted to PPB',
                    'padr' => $hey,
                    '_serialize' => ['status', 'message', 'padr']
                ]);
            } else {
                $this->set([
                    'status' => 'failed',
                    'message' => 'The PADR could not be saved',
                    'validation' => $this->Padr->validationErrors,
                    'padr' => $this->request->data,
                    '_serialize' => ['status', 'message', 'validation', 'padr']
                ]);
            }
        } else {
            throw new MethodNotAllowedException();
        }
    }
    public function api_list()
    {
        $this->Prg->commonProcess();
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($this->page_options);


        $criteria = $this->Padr->parseCriteria($this->passedArgs);
        $criteria['Padr.user_id'] = $this->Auth->User('id');
        $columns = [
            'id', 'user_id', 'reference_no', 'reporter_name', 'reporter_email',
            'reporter_phone', 'county_id', 'relation', 'patient_name', 'gender', 'date_of_birth', 'age_group',
            'report_sadr', 'description_of_reaction', 'reaction_on', 'any_other_comment', 'sadr_vomiting',
            'sadr_dizziness', 'sadr_headache', 'sadr_joints', 'sadr_rash', 'sadr_mouth', 'sadr_stomach',
            'sadr_urination', 'sadr_eyes', 'sadr_died', 'pqmp_label', 'pqmp_material', 'pqmp_color',
            'pqmp_smell', 'pqmp_working', 'pqmp_bottle', 'date_of_onset_of_reaction', 'report_title',
            'outcome', 'date_of_end_of_reaction', 'created'
        ];
        $this->paginate['conditions'] = $criteria;
        $this->paginate['fields'] = $columns;
        $this->paginate['contain'] = array('County' => ['id', 'county_name'], 'PadrListOfMedicine');
        $this->paginate['order'] = array('Padr.created' => 'desc');

        $this->set([
            'page_options', $this->page_options,
            'padrs' => Sanitize::clean($this->paginate(), array('encode' => false)),
            '_serialize' => ['padrs', 'page_options']
        ]);
    }
    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
 
     */

    public function download($id = null)
    {
        # code...
        $this->Padr->id = $id;
        if (!$this->Padr->exists()) {
            $this->Session->setFlash(__('Could not verify the suspected adverse drug report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        $padr = $this->Padr->find('first', array(
            'conditions' => array('Padr.id' => $id),
            'contain' => array('PadrListOfMedicine')
        ));
        $padr = Sanitize::clean($padr, array('escape' => true));
        // debug($padr);
        // exit;
        $this->set('padr', $padr);
        $this->response->download('PADR_' . $padr['Padr']['id'] . '.xml');
    }
    public function vigiflow($id = null)
    {
        $this->Padr->id = $id;
        if (!$this->Padr->exists()) {
            $this->Session->setFlash(__('Could not verify the public report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        $padr = $this->Padr->find('first', array(
            'conditions' => array('Padr.id' => $id),
            'contain' => array('PadrListOfMedicine')
        ));
        $padr = Sanitize::clean($padr, array('escape' => true));

        $view = new View($this, false);
        $view->viewPath = 'Sadrs/xml';  // Directory inside view directory to search for .ctp files
        $view->layout = false; // if you want to disable layout
        $view->set('padr', $padr); // set your variables for view here
        $html = $view->render('download');

        // debug($html);
        $HttpSocket = new HttpSocket();
        // string data
        $results = $HttpSocket->post(
            Configure::read('vigiflow_api'),
            $html,
            array('header' => array('umc-client-key' => Configure::read('vigiflow_key')))
        );
 
        if ($results->isOk()) {
            $body = $results->body;
            $this->Padr->saveField('vigiflow_message', $body);
            $this->Padr->saveField('vigiflow_date', date('Y-m-d H:i:s'));
            $resp = json_decode($body, true);
            if (json_last_error() == JSON_ERROR_NONE) {
                $this->Padr->saveField('vigiflow_ref', $resp);
            }
            $this->Flash->success('Vigiflow integration success!!');
            $this->Flash->success($body);
            $this->redirect($this->referer());
        } else {
            $body = $results->body;
            $this->Padr->saveField('vigiflow_message', $body);
            $this->Flash->error('Error sending report to vigiflow:');
            $this->Flash->error($body);
            $this->redirect($this->referer());
        }
        $this->autoRender = false;
    }
    public function edit($token = null)
    {
        if ($this->request->is('post')) {
            $id = $this->Padr->field('id', array('token' => $token));
            $this->Padr->id = $id;
            if (!$this->Padr->exists()) {
                throw new NotFoundException(__('Invalid PADR'));
                $this->Flash->error(__('We could not identify the report. Please refer to the acknowledgement email sent by PPB.'));
            }
            $this->Padr->Behaviors->attach('Tools.Captcha');
            if ($this->Padr->saveAssociated($this->request->data)) {

                //******************       Send Emails to Reporter and Managers          *****************************
                $this->loadModel('Message');
                $html = new HtmlHelper(new ThemeView());
                $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_padr_followup')));
                $padr = $this->Padr->read();
                $variables = array(
                    'name' => $padr['Padr']['reporter_name'], 'reference_no' => $padr['Padr']['reference_no'],
                    'reference_link' => $html->link(
                        $padr['Padr']['reference_no'],
                        array('controller' => 'padrs', 'action' => 'view', $padr['Padr']['token'], 'full_base' => true),
                        array('escape' => false)
                    ),
                    'modified' => $padr['Padr']['modified']
                );
                $datum = array(
                    'email' => $padr['Padr']['reporter_email'],
                    'id' => $this->Padr->id,  'type' => 'reporter_padr_followup', 'model' => 'Padr',
                    'subject' => CakeText::insert($message['Message']['subject'], $variables),
                    'message' => CakeText::insert($message['Message']['content'], $variables)
                );

                $this->loadModel('Queue.QueuedTask');
                $this->QueuedTask->createJob('GenericEmail', $datum);

                //Notify managers
                $users = $this->Padr->User->find('all', array(
                    'contain' => array(),
                    'conditions' => array('User.group_id' => 2, 'User.is_active' => '1')
                ));
                foreach ($users as $user) {
                    $variables = array(
                        'name' => $user['User']['name'], 'reference_no' => $padr['Padr']['reference_no'],
                        'reference_link' => $html->link(
                            $padr['Padr']['reference_no'],
                            array('controller' => 'padrs', 'action' => 'view', $padr['Padr']['token'], 'manager' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'modified' => $padr['Padr']['modified']
                    );
                    $datum = array(
                        'email' => $user['User']['email'],
                        'id' => $this->Padr->id, 'user_id' => $user['User']['id'], 'type' => 'reporter_padr_followup', 'model' => 'Padr',
                        'subject' => CakeText::insert($message['Message']['subject'], $variables),
                        'message' => CakeText::insert($message['Message']['content'], $variables)
                    );

                    $this->QueuedTask->createJob('GenericEmail', $datum);
                    $this->QueuedTask->createJob('GenericNotification', $datum);
                }
                //**********************************    END   *********************************

                $this->Flash->success(__('Your follow up report has been successfully submitted to PPB. Please check your email for the acknowldgement.'));
                return $this->redirect(array('action' => 'view', $padr['Padr']['token']));
            } else {
                $this->Flash->error(__('The followup report could not be created. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Padr.token' => $token));
            $this->request->data = $this->Padr->find('first', $options);
        }

        $counties = $this->Padr->County->find('list', array('order' => array('County.county_name')));
        $this->set(compact('counties'));
    }
    public function manager_copy($id=null) {
        if ($this->request->is('post')) {
            $this->Padr->id = $id;
            if (!$this->Padr->exists()) {
                throw new NotFoundException(__('Invalid PADR'));
            }
            $this->generate_copy($id);
        }
	}

    public function generate_copy($id)
    {
        # code...
        $padr = Hash::remove($this->Padr->find(
            'first',
            array(
                'contain' => array('PadrListOfMedicine'),
                'conditions' => array('Padr.id' => $id)
            )
        ), 'Padr.id');

        if ($padr['Padr']['copied']) {
            $this->Session->setFlash(__('A clean copy already exists. Click on edit to update changes.'), 'alerts/flash_error');
            return $this->redirect(array('action' => 'index'));
        }
        $padr = Hash::remove($padr, 'PadrListOfMedicine.{n}.id'); 
        $data_save = $padr['Padr'];
        if (isset($sadr['PadrListOfMedicine'])) $data_save['PadrListOfMedicine'] = $sadr['PadrListOfMedicine'];
        $data_save['padr_id'] = $id;
        $data_save['user_id'] = $this->Auth->User('id');;
        $this->Padr->saveField('copied', 1);
        $data_save['copied'] = 2;

        if ($this->Padr->saveAssociated($data_save, array('deep' => true, 'validate' => false))) {
            $this->Session->setFlash(__('Clean copy of ' . $data_save['reference_no'] . ' has been created'), 'alerts/flash_info');
            $this->redirect(array('action' => 'edit', $this->Padr->id));
        } else {
            $this->Session->setFlash(__('The clean copy could not be created. Please, try again.'), 'alerts/flash_error');
            $this->redirect($this->referer());
        }
    }
    public function manager_edit($id = null)
    {
        # code...
        $this->Padr->id = $id;
        if (!$this->Padr->exists()) {
            throw new NotFoundException(__('Invalid PADR'));
        }
        $this->general_editor($id);
    }
    public function general_editor($id = null)
    {
        # code...
        $padr = $this->Padr->read(null, $id);
        if ($this->request->is('post') || $this->request->is('put')) {
            $validate = false;
            // $validate = 'first';                
            if ($this->Padr->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                // if (isset($this->request->data['submitReport'])) {
                    $this->Padr->saveField('submitted', 2);
                    $this->Padr->saveField('submitted_date', date("Y-m-d H:i:s"));
                    $sadr = $this->Padr->read(null, $id);

                    $this->Session->setFlash(__('The PADR has been saved'), 'alerts/flash_success');
                    $this->redirect(array('action' => 'view', $this->Padr->id));
                // }
                // // debug($this->request->data);
                // $this->Session->setFlash(__('The PADR has been saved'), 'alerts/flash_success');
                // $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('The PADR could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $this->request->data = $this->Padr->read(null, $id);
        }

        //Manager will always edit a copied report
        $padr = $this->Padr->find('first', array(
            'conditions' => array('Padr.id' => $padr['Padr']['padr_id']),
            'contain' => array('PadrListOfMedicine')
        ));
        $this->set('padr', $padr);


        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'PADR_' . $id . '.pdf',  'orientation' => 'portrait');
            $this->response->download('PADR_' . $padr['Padr']['id'] . '.pdf');
        }

        $counties = $this->Padr->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $sub_counties = $this->Padr->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        $this->set(compact('sub_counties')); 
        $designations = $this->Padr->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations')); 
    }
    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {
        if (!$this->Padr->exists($id)) {
            throw new NotFoundException(__('Invalid padr'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Padr->delete($id)) {
            $this->Flash->success(__('The padr has been deleted.'));
        } else {
            $this->Flash->error(__('The padr could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }
    
	public function manager_archive($id=null) {

        $this->Padr->id = $id;
        if (!$this->Padr->exists()) {
            throw new NotFoundException(__('Invalid PADR'));
        }
        $report = $this->Padr->read(null, $id);
        $report['Padr']['archived'] = true;
        $report['Padr']['archived_date'] = date("Y-m-d H:i:s");
        if ($this->Padr->save($report, array('validate' => false))) {
            $this->Session->setFlash(__('PADR Archived successfully'), 'alerts/flash_success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('PADR was not archied'), 'alerts/flash_error');
        $this->redirect($this->referer());
	}
}
