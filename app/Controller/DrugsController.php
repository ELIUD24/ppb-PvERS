<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeText', 'Utility');
App::uses('ThemeView', 'View');
App::uses('HtmlHelper', 'View/Helper');
App::uses('Router', 'Routing');
App::uses('HttpSocket', 'Network/Http');
/**
 * Drugs Controller
 * * @property Drug $Drug
 */
class DrugsController extends AppController {

/**
 * Scaffold
 *
 * @var mixed
 */
	public $scaffold; 
	public $components = array('Search.Prg');
    public $paginate = array();
    public $presetVars = true;
    public $page_options = array('25' => '25', '50' => '50', '100' => '100');
	public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('manager_index');

    }

            public function index() {
        $userType = $this->Auth->user('user_type');
        switch ($userType) {
            case 'Admin':
                $this->redirect(array('action' => 'admin_index'));
                break;
            case 'Manager':
                $this->redirect(array('action' => 'manager_index'));
                break;
            case 'Reviewer':
                $this->redirect(array('action' => 'reviewer_index'));
                break;
            case 'Partner':
                $this->redirect(array('action' => 'partner_index'));
                break;
            case 'Public Health Program':
                $this->redirect(array('action' => 'reporter_index'));
                break;
            default:
                $this->redirect(array('action' => 'reporter_index'));
        }
    }
    

	public function manager_index()
	{
		# code...
		$this->Prg->commonProcess();
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($this->page_options);
        
        $criteria = $this->Drug->parseCriteria($this->passedArgs);  
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Drug.created' => 'desc');       
        $this->set('page_options', $this->page_options); 
        $this->set('drugs', Sanitize::clean($this->paginate(), array('encode' => false)));
	}
    public function reporter_index()
    {
        # code...
        $this->Prg->commonProcess();
        $page_options = array('25' => '25', '20' => '20');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
            else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Drug->parseCriteria($this->passedArgs);
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Drug.created' => 'desc');
        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
          $this->csv_export($this->Drug->find('all', 
                  array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'])
              ));
        }
        //end pdf export

        $this->set('page_options', $page_options);
        $this->set('drugs', Sanitize::clean($this->paginate(), array('encode' => false)));
    }   

    public function manager_sync()
    {
        # code...
        $httpSocket = new HttpSocket();
        // string data
        $results = $httpSocket->get(Configure::read('drug_registry_api'),
            false,
            array('header' => array('Authorization' => Configure::read('drug_registry_header')))
        );
        if ($results->isOk()) {
            $data = $results->body;
			$data = json_decode($data); 
			// for each data in the array
			$this->Drug->query('TRUNCATE TABLE drugs');
			//create a array to store the data 
			foreach ($data as $drug) { 
				// save the drug to the database
				$this->Drug->create();
				$this->Drug->save(array(
					'batch_number' => $drug->registration_no,
					'brand_name' => $drug->brand_name,
					'inn_name' => $drug->inn_of_api,
					'manufacturer' => $drug->mah_name,   
                    'registration_status'=>$drug->registration_status,
                    'retention_status'=>$drug->retention_status,
                    'local_trade_rep'=>$drug->local_trade_rep
				));
			} 
			$this->Flash->success('Drug list successfully updated');
			$this->redirect($this->referer());
        } else {
            $body = $results->body; 
            $this->Flash->error('Error syncing... please try again later!!');
            $this->Flash->error($body);
            $this->redirect($this->referer());
        } 
    }

}
