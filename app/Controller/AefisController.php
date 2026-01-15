<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeText', 'Utility');
App::uses('ThemeView', 'View');
App::uses('HtmlHelper', 'View/Helper');
App::uses('Router', 'Routing');

/**
 * Aefis Controller
 *
 * @property Aefi $Aefi
 */
class AefisController extends AppController
{
    public $components = array('Search.Prg');
    public $paginate = array();
    public $presetVars = true;
    public $page_options = array('25' => '25', '50' => '50', '100' => '100');

    /**
     * index method
     */
    /*public function index() {
        $this->Aefi->recursive = 0;
        $this->set('aefis', $this->paginate());
    }*/

    // Short Term Goal 
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('yellowcard', 'manager_reset_reference', 'guest_add', 'guest_edit', 'dhis2');
    }

    public function manager_dhis2()
    {
        $data = array();
        $this->Prg->commonProcess();
        if (!empty($this->request->query['pages'])) $this->paginate['limit'] = $this->request->query['pages'];
        else $this->paginate['limit'] = reset($this->page_options);

        if (!empty($this->passedArgs['category']) || !empty($this->passedArgs['month']) || !empty($this->passedArgs['year'])) {
            $month = $this->passedArgs['month'];
            $year = $this->passedArgs['year'];
            $startDate = date('Y-m-01', strtotime($month . ' ' . $year));
            $endDate = date('Y-m-t', strtotime($month . ' ' . $year));
            $criteria['Aefi.deleted'] = false;
            $criteria['Aefi.archived'] = false;
            $criteria['Aefi.copied !='] = '1';
            $criteria['Aefi.submitted'] = array(2, 3);
            // $criteria['Aefi.submitted_date BETWEEN ? AND ?'] = array($startDate, $endDate);



            if ($this->passedArgs['category'] === "country") {

                $data = $this->generate_country_data($criteria);
            }
            if ($this->passedArgs['category'] === "county") {
                if (empty($this->passedArgs['county'])) {
                    $this->Session->setFlash(__('Please specify the county'), 'alerts/flash_error');
                    $this->redirect($this->referer());
                }
                $criteria['Aefi.county_id'] = $this->passedArgs['county'];
                $data = $this->generate_county_data($criteria);
            }
            if ($this->passedArgs['category'] === "ward") {
                if (empty($this->passedArgs['sub_county'])) {
                    $this->Session->setFlash(__('Please specify the sub county'), 'alerts/flash_error');
                    $this->redirect($this->referer());
                }
                $criteria['Aefi.sub_county_id'] = $this->passedArgs['sub_county'];
                $data = $this->generate_sub_county_data($criteria);
            }
            if ($this->passedArgs['category'] === "facility") {
                if (empty($this->passedArgs['ward'])) {
                    $this->Session->setFlash(__('Please specify the ward'), 'alerts/flash_error');
                    $this->redirect($this->referer());
                }
                $criteria['Aefi.sub_county_id'] = $this->passedArgs['sub_county'];
                $criteria['Aefi.patient_ward'] = $this->passedArgs['ward'];
                $data = $this->generate_facility_data($criteria, $this->passedArgs['sub_county']);
            }
        }
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        $currentYear = date('Y');
        $years = range($currentYear, $currentYear - 19);
        $years = array_combine($years, $years);
        $counties =  $this->Aefi->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $sub_counties = $this->Aefi->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));

        $this->set('counties', $counties);
        $this->set('years', $years);
        $this->set('sub_counties', $sub_counties);
        $this->set('months', $months);
        $this->set('page_options', $this->page_options);
        $this->set('data', Sanitize::clean($data, array('encode' => false)));
    }
    public function generate_facility_data($criteria, $sub_county)
    {

        $data = array();
        $facilities = $this->Aefi->find('all', array(
            'fields' => array('name_of_institution', 'COUNT(*) as cnt'),
            'contain' => array(),
            'recursive' => -1,
            'conditions' => $criteria,
            'group' => array('name_of_institution'),
            'order' => array('COUNT(*) DESC'),
            'having' => array('COUNT(*) >' => 0),
        ));

        $c = 0;
        foreach ($facilities as $ct) {
            $c++;
            $dt['county_id'] = $c;
            $dt['county'] = $ct['Aefi']['name_of_institution'];
            $bcg = $this->extract_reports_per_reaction('bcg', 'name_of_institution', $ct['Aefi']['name_of_institution']);
            $convulsion = $this->extract_reports_per_reaction('convulsion', 'name_of_institution', $ct['Aefi']['name_of_institution']);
            $urticaria = $this->extract_reports_per_reaction('urticaria', 'name_of_institution', $ct['Aefi']['name_of_institution']);
            $fever = $this->extract_reports_per_reaction('high_fever', 'name_of_institution', $ct['Aefi']['name_of_institution']);
            $abscess = $this->extract_reports_per_reaction('abscess', 'name_of_institution', $ct['Aefi']['name_of_institution']);
            $reaction = $this->extract_reports_per_reaction('local_reaction', 'name_of_institution', $ct['Aefi']['name_of_institution']);
            $anaphylaxis = $this->extract_reports_per_reaction('anaphylaxis', 'name_of_institution', $ct['Aefi']['name_of_institution']);
            $encephalopathy = $this->extract_reports_per_reaction('meningitis', 'name_of_institution', $ct['Aefi']['name_of_institution']);
            $paralysis = $this->extract_reports_per_reaction('paralysis', 'name_of_institution', $ct['Aefi']['name_of_institution']);
            $shock = $this->extract_reports_per_reaction('toxic_shock', 'name_of_institution', $ct['Aefi']['name_of_institution']);
            $total = $bcg + $convulsion + $urticaria + $fever + $abscess + $reaction + $anaphylaxis + $encephalopathy + $paralysis + $shock;

            $dt['bcg'] = $bcg;
            $dt['convulsion'] = $convulsion;
            $dt['urticaria'] = $urticaria;
            $dt['fever'] = $fever;
            $dt['abscess'] = $abscess;
            $dt['reaction'] = $reaction;
            $dt['anaphylaxis'] = $anaphylaxis;
            $dt['encephalopathy'] = $encephalopathy;
            $dt['paralysis'] = $paralysis;
            $dt['shock'] = $shock;
            $dt['total'] = $total;

            $data[] = $dt;
        }
        return $data;
    }
    public function generate_sub_county_data($criteria)
    {

        $data = array();
        $facilities = $this->Aefi->find('all', array(
            'fields' => array('patient_ward', 'sub_county_id', 'COUNT(*) as cnt'),
            'contain' => array(),
            'recursive' => -1,
            'conditions' => $criteria,
            'group' => array('patient_ward', 'sub_county_id'),
            'order' => array('COUNT(*) DESC'),
            'having' => array('COUNT(*) >' => 0),
        ));
        // debug($facilities);
        // exit;
        foreach ($facilities as $ct) {
            $dt['county_id'] = $ct['Aefi']['sub_county_id'];
            $dt['county'] = $ct['Aefi']['patient_ward'];
            $bcg = $this->extract_reports_per_reaction('bcg', 'sub_county_id', $ct['Aefi']['sub_county_id']);
            $convulsion = $this->extract_reports_per_reaction('convulsion', 'sub_county_id', $ct['Aefi']['sub_county_id']);
            $urticaria = $this->extract_reports_per_reaction('urticaria', 'sub_county_id', $ct['Aefi']['sub_county_id']);
            $fever = $this->extract_reports_per_reaction('high_fever', 'sub_county_id', $ct['Aefi']['sub_county_id']);
            $abscess = $this->extract_reports_per_reaction('abscess', 'sub_county_id', $ct['Aefi']['sub_county_id']);
            $reaction = $this->extract_reports_per_reaction('local_reaction', 'sub_county_id', $ct['Aefi']['sub_county_id']);
            $anaphylaxis = $this->extract_reports_per_reaction('anaphylaxis', 'sub_county_id', $ct['Aefi']['sub_county_id']);
            $encephalopathy = $this->extract_reports_per_reaction('meningitis', 'sub_county_id', $ct['Aefi']['sub_county_id']);
            $paralysis = $this->extract_reports_per_reaction('paralysis', 'sub_county_id', $ct['Aefi']['sub_county_id']);
            $shock = $this->extract_reports_per_reaction('toxic_shock', 'sub_county_id', $ct['Aefi']['sub_county_id']);
            $total = $bcg + $convulsion + $urticaria + $fever + $abscess + $reaction + $anaphylaxis + $encephalopathy + $paralysis + $shock;

            $dt['bcg'] = $bcg;
            $dt['convulsion'] = $convulsion;
            $dt['urticaria'] = $urticaria;
            $dt['fever'] = $fever;
            $dt['abscess'] = $abscess;
            $dt['reaction'] = $reaction;
            $dt['anaphylaxis'] = $anaphylaxis;
            $dt['encephalopathy'] = $encephalopathy;
            $dt['paralysis'] = $paralysis;
            $dt['shock'] = $shock;
            $dt['total'] = $total;

            $data[] = $dt;
        }
        return $data;
    }
    public function generate_county_data($criteria)
    {

        $data = array();
        $sub_counties = $this->Aefi->find('all', array(
            'fields' => array('SubCounty.sub_county_name', 'SubCounty.county_id', 'COUNT(*) as cnt'),
            'contain' => array('SubCounty'),
            'conditions' => $criteria,
            'group' => array('SubCounty.county_name', 'SubCounty.id', 'SubCounty.county_id'),
            'having' => array('COUNT(*) >' => 0),
        ));

        foreach ($sub_counties as $ct) {
            $dt['county_id'] = $ct['SubCounty']['id'];
            $dt['county'] = $ct['SubCounty']['sub_county_name'];
            $bcg = $this->extract_reports_per_reaction('bcg', 'sub_county_id', $ct['SubCounty']['id']);
            $convulsion = $this->extract_reports_per_reaction('convulsion', 'sub_county_id', $ct['SubCounty']['id']);
            $urticaria = $this->extract_reports_per_reaction('urticaria', 'sub_county_id', $ct['SubCounty']['id']);
            $fever = $this->extract_reports_per_reaction('high_fever', 'sub_county_id', $ct['SubCounty']['id']);
            $abscess = $this->extract_reports_per_reaction('abscess', 'sub_county_id', $ct['SubCounty']['id']);
            $reaction = $this->extract_reports_per_reaction('local_reaction', 'sub_county_id', $ct['SubCounty']['id']);
            $anaphylaxis = $this->extract_reports_per_reaction('anaphylaxis', 'sub_county_id', $ct['SubCounty']['id']);
            $encephalopathy = $this->extract_reports_per_reaction('meningitis', 'sub_county_id', $ct['SubCounty']['id']);
            $paralysis = $this->extract_reports_per_reaction('paralysis', 'sub_county_id', $ct['SubCounty']['id']);
            $shock = $this->extract_reports_per_reaction('toxic_shock', 'sub_county_id', $ct['SubCounty']['id']);
            $total = $bcg + $convulsion + $urticaria + $fever + $abscess + $reaction + $anaphylaxis + $encephalopathy + $paralysis + $shock;

            $dt['bcg'] = $bcg;
            $dt['convulsion'] = $convulsion;
            $dt['urticaria'] = $urticaria;
            $dt['fever'] = $fever;
            $dt['abscess'] = $abscess;
            $dt['reaction'] = $reaction;
            $dt['anaphylaxis'] = $anaphylaxis;
            $dt['encephalopathy'] = $encephalopathy;
            $dt['paralysis'] = $paralysis;
            $dt['shock'] = $shock;
            $dt['total'] = $total;

            $data[] = $dt;
        }
        return $data;
    }
    public function generate_country_data($criteria = array())
    {
        $data = array();
        $counties = $this->Aefi->find('all', array(
            'fields' => array('County.county_name', 'COUNT(*) as cnt'),
            'contain' => array('County'),
            'conditions' => $criteria,
            'group' => array('County.county_name', 'County.id'),
            'having' => array('COUNT(*) >' => 0),
        ));

        foreach ($counties as $ct) {
            $dt['county_id'] = $ct['County']['id'];
            $dt['county'] = $ct['County']['county_name'];
            $bcg = $this->extract_reports_per_reaction('bcg', 'county_id', $ct['County']['id']);
            $convulsion = $this->extract_reports_per_reaction('convulsion', 'county_id', $ct['County']['id']);
            $urticaria = $this->extract_reports_per_reaction('urticaria', 'county_id', $ct['County']['id']);
            $fever = $this->extract_reports_per_reaction('high_fever', 'county_id', $ct['County']['id']);
            $abscess = $this->extract_reports_per_reaction('abscess', 'county_id', $ct['County']['id']);
            $reaction = $this->extract_reports_per_reaction('local_reaction', 'county_id', $ct['County']['id']);
            $anaphylaxis = $this->extract_reports_per_reaction('anaphylaxis', 'county_id', $ct['County']['id']);
            $encephalopathy = $this->extract_reports_per_reaction('meningitis', 'county_id', $ct['County']['id']);
            $paralysis = $this->extract_reports_per_reaction('paralysis', 'county_id', $ct['County']['id']);
            $shock = $this->extract_reports_per_reaction('toxic_shock', 'county_id', $ct['County']['id']);
            $total = $bcg + $convulsion + $urticaria + $fever + $abscess + $reaction + $anaphylaxis + $encephalopathy + $paralysis + $shock;

            $dt['bcg'] = $bcg;
            $dt['convulsion'] = $convulsion;
            $dt['urticaria'] = $urticaria;
            $dt['fever'] = $fever;
            $dt['abscess'] = $abscess;
            $dt['reaction'] = $reaction;
            $dt['anaphylaxis'] = $anaphylaxis;
            $dt['encephalopathy'] = $encephalopathy;
            $dt['paralysis'] = $paralysis;
            $dt['shock'] = $shock;
            $dt['total'] = $total;

            $data[] = $dt;
        }
        return $data;
    }


    public function extract_reports_per_reaction($column, $parent, $county_id)
    {
        $criteria = array();
        $criteria['Aefi.deleted'] = false;
        $criteria['Aefi.archived'] = false;
        $criteria['Aefi.copied !='] = '1';
        $criteria['Aefi.submitted'] = array(2, 3);
        $criteria['Aefi.' . $parent] = $county_id;
        $criteria['Aefi.' . $column] = $column;
        $count = $this->Aefi->find('count', array(
            'conditions' => $criteria
        ));
        return $count;
    }
    public function dhis2($id)
    {
        if ($id < 1) {
            $this->set([
                'status' => 'failed',
                'message' => 'Month numbers can only be between 1 and 12',
                '_serialize' => ['status', 'message']
            ]);
            return;
        }
        if ($id > 12) {
            $this->set([
                'status' => 'failed',
                'message' => 'Month numbers can only be between 1 and 12',
                '_serialize' => ['status', 'message']
            ]);
            return;
        }
        $month = $id;
        $data = array(
            'facility' => array(),
            'ward' => array(),
            'sub-county' => array(),
            'county' => array(),
            'countrywide' => array()
        );
        $this->set([
            'status' => 'success',
            'month' => $month,
            'data' => $data,
            '_serialize' => ['status', 'month', 'data']
        ]);
    }



    public function yellowcard($id = null)
    {
        $this->autoRender = false;

        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            $this->Session->setFlash(__('Could not verify the Adverse Event Following Immunization report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        $aefi = $this->Aefi->find('first', array(
            'conditions' => array('Aefi.id' => $id),
            'contain' => array('AefiListOfVaccine', 'AefiDescription', 'County', 'Attachment', 'Designation', 'AefiListOfVaccine.Vaccine'),

        ));
        $aefi = Sanitize::clean($aefi, array('escape' => true));

        if ($aefi['Aefi']['bcg'] == '1') {
            $reactions[] = "BCG Lymphadenitis";
        }
        if ($aefi['Aefi']['convulsion'] == '1') {
            $reactions[] = "Generalized urticaria (hives)";
        }
        if ($aefi['Aefi']['urticaria'] == '1') {
            $reactions[] = "High Fever";
        }
        if ($aefi['Aefi']['high_fever'] == '1') {
            $reactions[] = "High Fever";
        }
        if ($aefi['Aefi']['abscess'] == '1') {
            $reactions[] = "Injection site abscess";
        }
        if ($aefi['Aefi']['local_reaction'] == '1') {
            $reactions[] = "Severe Local Reaction";
        }
        if ($aefi['Aefi']['anaphylaxis'] == '1') {
            $reactions[] = "Anaphylaxis";
        }
        if ($aefi['Aefi']['meningitis'] == '1') {
            $reactions[] = "Encephalopathy, Encephalitis/Meningitis";
        }
        if ($aefi['Aefi']['paralysis'] == '1') {
            $reactions[] = "Paralysis";
        }
        if ($aefi['Aefi']['toxic_shock'] == '1') {
            $reactions[] = "Toxic shock";
        }
        if ($aefi['Aefi']['complaint_other'] == '1') {
            $other = $aefi['Aefi']['complaint_other_specify'];
            if (!empty($other)) {
                $reactions[] = $other;
            }
        }
        $reactions[] = $aefi['Aefi']['aefi_symptoms'];

        // added reactions

        $multiple = $aefi['AefiDescription'];
        if (!empty($multiple)) {
            foreach ($multiple as $other) {
                $reactions[] = $other['description'];
            }
        }
        if (!empty($aefi['Aefi']['date_of_birth'])) {
            if (empty($aefi['Aefi']['date_of_birth']['day']) && empty($aefi['Aefi']['date_of_birth']['month'])) {
                $aefi['Aefi']['date_of_birth'] = "01-01-" . $aefi['Aefi']['date_of_birth']['year'];

                // Given birthdate
                $current = $aefi['Aefi']['date_of_birth']['year'] . "-01-01";
                $birthdate = new DateTime($current);
                // Current date
                $currentDate = new DateTime();

                // Calculate the difference in years and months
                $interval = $birthdate->diff($currentDate);
                $years = $interval->y;
                $months = $interval->m;

                // Convert years to months and add remaining months
                $totalMonths = $years * 12 + $months;
                $aefi['Aefi']['age_months'] = $totalMonths;
            }
        }

        $view = new View($this, false);
        $view->viewPath = 'Aefis/xml';  // Directory inside view directory to search for .ctp files
        $view->layout = false; // if you want to disable layout 
        $view->set('aefi', $aefi); // set your variables for view here
        $view->set('reactions', $reactions);
        $html = $view->render('json');
        libxml_use_internal_errors(TRUE);
        $xml = simplexml_load_string($html);
        $json = json_encode($xml);
        $report = json_decode($json, TRUE);



        // stream_context_set_default(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);
        $options = array(
            'ssl_verify_peer' => false
        );
        $HttpSocket = new HttpSocket($options);

        //Request Access Token
        $initiate = $HttpSocket->post(
            Configure::read('mhra_auth_api'),
            array(
                'email' => Configure::read('mhra_username'),
                'password' => Configure::read('mhra_password'),
                'platformId' => Configure::read('mhra_platform')
            ),
            array('header' => array('umc-client-key' => '5ab835c4-3179-4590-bcd2-ff3c27d6b8ff'))
        );
        if ($initiate->isOk()) {

            $body = $initiate->body;
            $resp = json_decode($body, true);
            $userId = $resp['id'];
            $token = $resp['token'];
            $organisationId = $resp['organisationId'];

            $payload = array(
                'userId' => $userId,
                'organisationId' => $organisationId,
                'report' => $report
            );

            // debug($payload);
            // exit;
            $results = $HttpSocket->post(
                Configure::read('mhra_incidents'),
                $payload,
                array('header' => array(
                    'X-App-Id' => Configure::read('mhra_xapp_id'),
                    'Authorization' => 'Bearer ' . $token, //original 
                    'Authorization' => 'API_KEY ' . Configure::read('mhra_api_key'),

                ))
            );

            if ($results->isOk()) {
                $body = $results->body;
                $resp = json_decode($body, true);
                $this->Aefi->saveField('webradr_message', $body);
                $this->Aefi->saveField('webradr_date', date('Y-m-d H:i:s'));
                $this->Aefi->saveField('webradr_ref', $resp['report']['extReportId']);
                $this->Flash->success('Yellow Card Scheme integration success!!');
                $this->Flash->success($body);
                $this->redirect($this->referer());
            } else {
                $body = $results->body;
                // $this->Aefi->saveField('webradr_message', $body);
                $this->Flash->error('Error sending report to Yello Card Scheme:');
                $this->Flash->error($body);
                $this->redirect($this->referer());
            }
        } else {
            $body = $initiate->body;
            $this->Aefi->saveField('webradr_message', $body);
            $this->Flash->error('Error initiating report to Yellow Card Scheme:');
            $this->Flash->error($body);
            $this->redirect($this->referer());
        }
    }

    public function yellowcard_recent($id = null)
    {
        $this->autoRender = false;

        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            $this->Session->setFlash(__('Could not verify the AEFI report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        $aefi = $this->Aefi->find('first', array(
            'conditions' => array('Aefi.id' => $id),
            'contain' => array('AefiListOfVaccine', 'AefiDescription', 'County', 'Attachment', 'Designation', 'AefiListOfVaccine.Vaccine'),

        ));
        $aefi = Sanitize::clean($aefi, array('escape' => true));

        if ($aefi['Aefi']['bcg'] == '1') {
            $reactions[] = "BCG Lymphadenitis";
        }
        if ($aefi['Aefi']['convulsion'] == '1') {
            $reactions[] = "Generalized urticaria (hives)";
        }
        if ($aefi['Aefi']['urticaria'] == '1') {
            $reactions[] = "High Fever";
        }
        if ($aefi['Aefi']['high_fever'] == '1') {
            $reactions[] = "High Fever";
        }
        if ($aefi['Aefi']['abscess'] == '1') {
            $reactions[] = "Injection site abscess";
        }
        if ($aefi['Aefi']['local_reaction'] == '1') {
            $reactions[] = "Severe Local Reaction";
        }
        if ($aefi['Aefi']['anaphylaxis'] == '1') {
            $reactions[] = "Anaphylaxis";
        }
        if ($aefi['Aefi']['meningitis'] == '1') {
            $reactions[] = "Encephalopathy, Encephalitis/Meningitis";
        }
        if ($aefi['Aefi']['paralysis'] == '1') {
            $reactions[] = "Paralysis";
        }
        if ($aefi['Aefi']['toxic_shock'] == '1') {
            $reactions[] = "Toxic shock";
        }
        if ($aefi['Aefi']['complaint_other'] == '1') {
            $other = $aefi['Aefi']['complaint_other_specify'];
            if (!empty($other)) {
                $reactions[] = $other;
            }
        }
        $reactions[] = $aefi['Aefi']['aefi_symptoms'];

        // added reactions

        $multiple = $aefi['AefiDescription'];
        if (!empty($multiple)) {
            foreach ($multiple as $other) {
                $reactions[] = $other['description'];
            }
        }

        $view = new View($this, false);
        $view->viewPath = 'Aefis/xml';  // Directory inside view directory to search for .ctp files
        $view->layout = false; // if you want to disable layout 
        $view->set('aefi', $aefi); // set your variables for view here
        $view->set('reactions', $reactions);
        $html = $view->render('json');
        libxml_use_internal_errors(TRUE);
        $xml = simplexml_load_string($html);
        $json = json_encode($xml);
        $report = json_decode($json, TRUE);

        // debug($report);
        // exit;
        $live = new HttpSocket();

        // //Request Access Token
        // $initiate = $live->post('https://med-safety-hub-api.redant.cloud/v1/login',
        //     array(
        //         "email"=>"gmurimi@pharmacyboardkenya.org",
        //         "password"=>"uxLPyc3FM1",
        //         "platformId"=>"ab1057ca-8e5d-4470-a595-36e7a3901697"
        //     )
        // );
        $httpSocket = new HttpSocket();
        $request = array(
            'method' => 'POST',
            'uri' => array(
                'schema' => 'https',
                'host' => 'med-safety-hub-api.redant.cloud',
                'path' => 'v1/login',
                'email' => 'gmurimi@pharmacyboardkenya.org',
                'password' => 'uxLPyc3FM1',
                'platformId' => 'ab1057ca-8e5d-4470-a595-36e7a3901697'
            ),
            'body' => array(
                'email' => 'gmurimi@pharmacyboardkenya.org',
                'password' => 'uxLPyc3FM1',
                'platformId' => 'ab1057ca-8e5d-4470-a595-36e7a3901697'
            )
        );
        $initiate = $httpSocket->request($request);
        // debug($initiate);
        // exit;
        if ($initiate->isOk()) {

            $body = $initiate->body;
            $resp = json_decode($body, true);
            $userId = $resp['id'];
            $token = $resp['token'];
            $organisationId = $resp['organisationId'];

            $payload = array(
                'userId' => $userId,
                'organisationId' => $organisationId,
                'report' => $report
            );

            // debug($token);
            // exit;
            // $results = $httpSocket->post(
            //     Configure::read('mhra_incidents'),
            //     $payload,
            //     array('header' => array(
            //         'X-App-Id' => Configure::read('mhra_xapp_id'),
            //         'Authorization' => 'Bearer ' . $token, //original 
            //         'Authorization' => 'API_KEY ' . Configure::read('mhra_api_key'),

            //     ))
            // );
            // debug($token);
            // exit;
            $httpSocket = new HttpSocket();
            $request2 = array(
                'method' => 'POST',
                'uri' => array(
                    'schema' => 'https',
                    'host' => 'med-safety-hub-api.redant.cloud',
                    'path' => 'v1/integration/incidents/reports/e2bjs',
                ),
                'body' => $payload,
                'header' => [
                    'X-App-Id' => '5d3298a9-14dc-4ee1-8318-4a3f25b04a99',
                    'Authorization' => 'Bearer ' . $token, //original 
                    'Authorization' => 'API_KEY d04aa2d0-92f8-480a-a3b9-54beb746e399'

                ],

            );
            $results = $httpSocket->request($request2);
            debug($results);
            exit;

            if ($results->isOk()) {
                $body = $results->body;
                $resp = json_decode($body, true);
                $this->Aefi->saveField('webradr_message', $body);
                $this->Aefi->saveField('webradr_date', date('Y-m-d H:i:s'));
                $this->Aefi->saveField('webradr_ref', $resp['report']['id']);
                $this->Flash->success('Yellow Card Scheme integration success!!');
                $this->Flash->success($body);
                $this->redirect($this->referer());
            } else {
                $body = $results->body;
                // $this->Aefi->saveField('webradr_message', $body);
                $this->Flash->error('Error sending report to Yello Card Scheme:');
                $this->Flash->error($body);
                $this->redirect($this->referer());
            }
        } else {
            $body = $initiate->body;
            $this->Aefi->saveField('webradr_message', $body);
            $this->Flash->error('Error initiating report to Yellow Card Scheme:');
            $this->Flash->error($body);
            $this->redirect($this->referer());
        }
    }


    public function generateJsonData($report)
    {
        $header = $report['ichicsrmessageheader'];
        $safety = $report['safetyreport'];

        $op = array_merge_recursive($header, $safety);

        return json_encode($op);
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
        $user_id = $this->Auth->User('id');
        $user_type = $this->Auth->User('user_type');

        $criteria = $this->Aefi->parseCriteria($this->passedArgs);
        if ($this->Session->read('Auth.User.user_type') == 'Public Health Program') {
            $criteria['Aefi.submitted'] = array(2);
            $criteria['Aefi.copied !='] = '2';
        }
        if ($this->Session->read('Auth.User.user_type') != 'Public Health Program') {
            if ($user_type === 'County Pharmacist') {
                $criteria['OR'] = array(
                    'Aefi.user_id' => $this->Auth->user('id'),
                    array(
                        'Aefi.serious' => 'Yes',
                        'Aefi.submitted' => array(2, 3),
                        'Aefi.county_id' => $this->Auth->user('county_id')
                    )
                );
            } else {
                $criteria['Aefi.user_id'] = $this->Auth->User('id');
            }
        }

        // Added criteria for reporter
        $criteria['Aefi.deleted'] = false;
        $criteria['Aefi.archived'] = false;
        if (isset($this->request->query['submitted']) && $this->request->query['submitted'] == 1) {
            $criteria['Aefi.submitted'] = array(0, 1);
        } elseif (isset($this->request->query['submitted']) && $this->request->query['submitted'] == 2) {
            $criteria['Aefi.submitted'] = array(2, 3);
        }

        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Aefi.created' => 'desc');
        $this->paginate['contain'] = array('County', 'SubCounty', 'AefiListOfVaccine', 'AefiDescription', 'AefiListOfVaccine.Vaccine', 'Designation');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Aefi->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end csv export
        $this->set('page_options', $this->page_options);
        $counties = $this->Aefi->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $sub_counties = $this->Aefi->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        $this->set(compact('sub_counties'));
        $designations = $this->Aefi->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $this->set('aefis', Sanitize::clean($this->paginate(), array('encode' => false)));
    }

    public function api_index()
    {
        $this->Prg->commonProcess();
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        $page_options = array('5' => '5', '10' => '10', '25' => '25');
        (!empty($this->request->query('pages'))) ? $this->paginate['limit'] = $this->request->query('pages') :  $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Aefi->parseCriteria($this->passedArgs);
        $criteria['Aefi.user_id'] = $this->Auth->User('id');
        $criteria['Aefi.deleted'] = false;
        $criteria['Aefi.archived'] = false;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Aefi.created' => 'desc');
        $this->paginate['contain'] = array('County', 'AefiListOfVaccine', 'AefiDescription', 'AefiListOfVaccine.Vaccine', 'Designation');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Aefi->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end csv export
        $this->set([
            'page_options',
            $page_options,
            'aefis' => Sanitize::clean($this->paginate(), array('encode' => false)),
            'paging' => $this->request->params['paging'],
            '_serialize' => ['aefis', 'page_options', 'paging']
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

        $criteria = $this->Aefi->parseCriteria($this->passedArgs);
        $criteria['Aefi.name_of_institution'] = $this->Auth->User('name_of_institution');
        $criteria['Aefi.submitted'] = array(1, 2);
        $criteria['Aefi.deleted'] = false;
        $criteria['Aefi.archived'] = false;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Aefi.created' => 'desc');
        $this->paginate['contain'] = array('County', 'AefiListOfVaccine', 'AefiDescription', 'AefiListOfVaccine.Vaccine', 'Designation');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Aefi->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end pdf export
        $this->set('page_options', $this->page_options);
        $counties = $this->Aefi->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $sub_counties = $this->Aefi->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        $this->set(compact('sub_counties'));
        $designations = $this->Aefi->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $this->set('aefis', Sanitize::clean($this->paginate(), array('encode' => false)));
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

        $criteria = $this->Aefi->parseCriteria($this->passedArgs);
        $criteria['Aefi.deleted'] = false;
        if (!empty($this->passedArgs['archived'])) {
            $criteria['Aefi.archived'] = true;
        } else {
            $criteria['Aefi.archived'] = false;
        }
        $criteria['Aefi.copied !='] = '1';
        if (isset($this->request->query['submitted'])) {
            if ($this->request->query['submitted'] == 1) {
                $criteria['Aefi.submitted'] = array(0, 1);
            } else {
                $criteria['Aefi.submitted'] = array(2, 3);
            }
        } else {
            $criteria['Aefi.submitted'] = array(2, 3);
        }
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Aefi.created' => 'desc');
        $this->paginate['contain'] = array('County', 'SubCounty', 'AefiListOfVaccine', 'AefiDescription', 'AefiListOfVaccine.Vaccine', 'Designation');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $csv_export = $this->Aefi->find(
                'all',
                array(
                    'conditions' => $this->paginate['conditions'],
                    'order' => $this->paginate['order'],
                    'contain' => $this->paginate['contain'],
                    'limit' => 10000
                )
            );
            // debug($csv_export);
            // exit;
            $this->csv_export($csv_export);
        }
        //end pdf export
        $this->set('page_options', $this->page_options);
        $counties = $this->Aefi->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $sub_counties = $this->Aefi->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        $this->set(compact('sub_counties'));
        $designations = $this->Aefi->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $this->set('aefis', Sanitize::clean($this->paginate(), array('encode' => false)));
    }

    public function reviewer_index()
    {
        # code...
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

        $criteria = $this->Aefi->parseCriteria($this->passedArgs);
        $criteria['Aefi.deleted'] = false;
        $criteria['Aefi.archived'] = false;
        $criteria['Aefi.copied !='] = '1';
        if (isset($this->request->query['submitted'])) {
            if ($this->request->query['submitted'] == 1) {
                $criteria['Aefi.submitted'] = array(0, 1);
            } else {
                $criteria['Aefi.submitted'] = array(2, 3);
            }
        } else {
            $criteria['Aefi.submitted'] = array(2, 3);
        }

        $criteria['Aefi.assigned_to'] = $this->Auth->User('id');
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Aefi.created' => 'desc');
        $this->paginate['contain'] = array('County', 'SubCounty', 'AefiListOfVaccine', 'AefiDescription', 'AefiListOfVaccine.Vaccine', 'Designation');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Aefi->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'], 'limit' => 10000)
            ));
        }
        //end pdf export
        $this->set('page_options', $this->page_options);
        $counties = $this->Aefi->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $sub_counties = $this->Aefi->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        $this->set(compact('sub_counties'));
        $designations = $this->Aefi->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $this->set('aefis', Sanitize::clean($this->paginate(), array('encode' => false)));
    }

    private function csv_export($caefis = '')
    {
        //todo: check if data exists in $users
        $this->response->download('AEFIs_' . date('Ymd_Hi') . '.csv'); // <= setting the file name
        $this->set(compact('caefis'));
        $this->layout = false;
        $this->render('csv_export');
    }

    public function institutionCodes()
    {
        if ($this->Auth->user('institution_code')) {
            $this->Aefi->recursive = -1;
            $this->paginate = array(
                'conditions' => array('Aefi.institution_code' => $this->Auth->user('institution_code')),
                'fields' => array('Aefi.report_title', 'Aefi.created'),
                'limit' => 25,
                'order' => array(
                    'Aefi.created' => 'asc'
                )
            );
            $this->set('aefis', $this->paginate());
        }
    }
    /**
     * view methods
     */
    public function reporter_view($id = null)
    {
        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            $this->Session->setFlash(__('Could not verify the Adverse Event Following Immunization report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }



        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['continueEditing'])) {
                $this->Aefi->saveField('submitted', 0);
                $this->Session->setFlash(__('You can continue editing the report'), 'flash_success');
                $this->redirect(array('action' => 'edit', $this->Aefi->Luhn($this->Aefi->id)));
            }
            if (isset($this->request->data['sendToPPB'])) {
                $this->Aefi->saveField('submitted', 2);
                $this->Session->setFlash(__('Thank you for submitting your report. You will get an email with a link to the pdf copy of the report.'), 'flash_success');
                $this->redirect('/');
            }
        }

        // $aefi = $this->Aefi->read(null);
        $aefi = $this->Aefi->find('first', array(
            'conditions' => array('Aefi.id' => $id),
            'contain' => array(
                'AefiListOfVaccine',
                'AefiListOfVaccine.Vaccine',
                'AefiDescription',
                'County',
                'SubCounty',
                'Attachment',
                'Designation',
                'ExternalComment',
                'AefiOriginal',
                'AefiOriginal.AefiListOfVaccine',
                'AefiOriginal.AefiDescription',
                'AefiOriginal.AefiListOfVaccine.Vaccine',
                'AefiOriginal.County',
                'AefiOriginal.SubCounty',
                'AefiOriginal.Attachment',
                'AefiOriginal.Designation',
                'AefiOriginal.ExternalComment'
            )
        ));
        $this->set('aefi', $aefi);
        // $this->render('pdf/view');
        if (strpos($this->request->url, 'pdf') !== false) {

            $this->pdfConfig = array('filename' => 'AEFI_' . $id . '.pdf',  'orientation' => 'portrait');
            $this->response->download('AEFI_' . $aefi['Aefi']['id'] . '.pdf');
        }
    }
    public function api_view($id = null)
    {
        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {  //TODO: Confirm if the user_id is allowed to access
            $this->set([
                'status' => 'failed',
                'message' => 'Could not verify the Adverse Event Following Immunization report ID. Please ensure the ID is correct.',
                '_serialize' => ['status', 'message']
            ]);
        } else {

            if (strpos($this->request->url, 'pdf') !== false) {
                $this->pdfConfig = array('filename' => 'AEFI_' . $id,  'orientation' => 'portrait');
                // $this->response->download('AEFI_'.$aefi['Aefi']['id'].'.pdf');
            }

            // $aefi = $this->Aefi->read(null);
            $aefi = $this->Aefi->find('first', array(
                'conditions' => array('Aefi.id' => $id),
                'contain' => array(
                    'AefiListOfVaccine',
                    'AefiListOfVaccine.Vaccine',
                    'AefiDescription',
                    'County',
                    'SubCounty',
                    'Attachment',
                    'Designation',
                    'ExternalComment',
                    'AefiOriginal',
                    'AefiOriginal.AefiListOfVaccine',
                    'AefiOriginal.AefiDescription',
                    'AefiOriginal.AefiListOfVaccine.Vaccine',
                    'AefiOriginal.County',
                    'AefiOriginal.SubCounty',
                    'AefiOriginal.Attachment',
                    'AefiOriginal.Designation',
                    'AefiOriginal.ExternalComment'
                )
            ));
            $this->set([
                'status' => 'success',
                'aefi' => $aefi,
                '_serialize' => ['status', 'aefi']
            ]);

            if (strpos($this->request->url, 'pdf') !== false) {
                $this->pdfConfig = array('filename' => 'AEFI_' . $id . '.pdf',  'orientation' => 'portrait');
                $this->response->download('AEFI_' . $aefi['Aefi']['id'] . '.pdf');
            }
        }
    }

    public function partner_view($id = null)
    {
        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            $this->Session->setFlash(__('Could not verify the medical devices report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }


        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['continueEditing'])) {
                $this->Aefi->saveField('submitted', 0);
                $this->Session->setFlash(__('You can continue editing the report'), 'flash_success');
                $this->redirect(array('action' => 'edit', $this->Aefi->id));
            }
            if (isset($this->request->data['sendToPPB'])) {
                $this->Aefi->saveField('submitted', 2);
                $this->Session->setFlash(__('Thank you for submitting your report. You will get an email with a link to the pdf copy of the report.'), 'flash_success');
                $this->redirect('/');
            }
        }

        // $aefi = $this->Aefi->read(null);
        $aefi = $this->Aefi->find('first', array(
            'conditions' => array('Aefi.id' => $id),
            'contain' => array(
                'AefiListOfVaccine',
                'AefiListOfVaccine.Vaccine',
                'AefiDescription',
                'County',
                'SubCounty',
                'Attachment',
                'Designation',
                'ExternalComment',
                'AefiOriginal',
                'AefiOriginal.AefiListOfVaccine',
                'AefiOriginal.AefiDescription',
                'AefiOriginal.AefiListOfVaccine.Vaccine',
                'AefiOriginal.County',
                'AefiOriginal.SubCounty',
                'AefiOriginal.Attachment',
                'AefiOriginal.Designation',
                'AefiOriginal.ExternalComment'
            )
        ));
        $this->set('aefi', $aefi);
        // $this->render('pdf/view');

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'AEFI_' . $id . '.pdf',  'orientation' => 'portrait');
            $this->response->download('AEFI_' . $aefi['Aefi']['id'] . '.pdf');
        }
    }

    public function manager_view($id = null)
    {
        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            $this->Session->setFlash(__('Could not verify the medical devices report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        $this->general_view($id);
    }


    // Assign the report to the evaluator
    public function manager_assign()
    {
        # code...
        $id = $this->request->data['Aefi']['report_id'];
        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            $this->Session->setFlash(__('Could not verify the Aefi report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }
        $this->Aefi->saveField('assigned_by', $this->request->data['Aefi']['assigned_by']);
        $this->Aefi->saveField('assigned_to', $this->request->data['Aefi']['assigned_to']);
        $this->Aefi->saveField('assigned_date', date("Y-m-d H:i:s"));

        // Send an asignment alert::::


        $this->Session->setFlash(__('The Aefi has been assigned successfully'), 'alerts/flash_success');
        $this->redirect(array('action' => 'view', $id));
    }

    public function manager_unassign($id = null)
    {
        # code...
        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            $this->Session->setFlash(__('Could not verify the Aefi report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }
        $this->Aefi->saveField('assigned_by', '');
        $this->Aefi->saveField('assigned_to', '');
        $this->Aefi->saveField('assigned_date', '');

        $this->Session->setFlash(__('The Aefi has been unassigned successfully'), 'alerts/flash_success');
        $this->redirect(array('action' => 'view', $id));
    }

    // Common functions
    public function general_view($id = null)
    {
        # code...


        $aefi = $this->Aefi->find('first', array(
            'conditions' => array('Aefi.id' => $id),
            'contain' => array(
                'AefiListOfVaccine',
                'AefiListOfVaccine.Vaccine',
                'AefiDescription',
                'County',
                'SubCounty',
                'Attachment',
                'Designation',
                'ExternalComment',
                'ExternalComment.Attachment',
                'ReviewComment',
                'ReviewComment.Attachment',
                'AefiOriginal',
                'AefiOriginal.AefiListOfVaccine',
                'AefiOriginal.AefiDescription',
                'AefiOriginal.AefiListOfVaccine.Vaccine',
                'AefiOriginal.County',
                'AefiOriginal.SubCounty',
                'AefiOriginal.Attachment',
                'AefiOriginal.Designation',
                'AefiOriginal.ExternalComment',
                'AefiOriginal.ReviewComment'
            )
        ));
        $managers = $this->Aefi->User->find('list', array(
            'conditions' => array(
                'User.group_id' => 6
            )
        ));
        $this->set(['aefi' => $aefi, 'managers' => $managers]);


        if (strpos($this->request->url, 'pdf') !== false) {

            $this->pdfConfig = array('filename' => 'AEFI_' . $id . '.pdf',  'orientation' => 'portrait');
            $this->response->download('AEFI_' . $aefi['Aefi']['id'] . '.pdf');
        }
    }

    public function general_copy($id = null)
    {
        # code...
        if ($this->request->is('post')) {
            $this->Aefi->id = $id;
            if (!$this->Aefi->exists()) {
                throw new NotFoundException(__('Invalid Adverse Event Following Immunization'));
            }
            $aefi = Hash::remove($this->Aefi->find(
                'first',
                array(
                    'contain' => array('AefiListOfVaccine'),
                    'conditions' => array('Aefi.id' => $id)
                )
            ), 'Aefi.id');
            if ($aefi['Aefi']['copied']) {
                $this->Session->setFlash(__('A clean copy already exists. Click on edit to update changes.'), 'alerts/flash_error');
                return $this->redirect(array('action' => 'index'));
            }
            $aefi = Hash::remove($aefi, 'AefiListOfVaccine.{n}.id');
            $data_save = $aefi['Aefi'];
            if (isset($aefi['AefiListOfVaccine']))  $data_save['AefiListOfVaccine'] = $aefi['AefiListOfVaccine'];
            $data_save['aefi_id'] = $id;
            $data_save['user_id'] = $this->Auth->User('id');
            $this->Aefi->saveField('copied', 1);
            $data_save['copied'] = 2;


            if (!empty($aefi['Aefi']['created'])) {
                $rawDate = $aefi['Aefi']['created'];
            } else {
                $rawDate = date('Y-m-d H:i:s');
            }
            $now = date('Y-m-d H:i:s', strtotime($rawDate));
            $data_save['created'] = $now;
            $data_save['modified'] = $now;

            if ($this->Aefi->saveAssociated($data_save, array('deep' => true, 'validate' => false))) {
                $this->Session->setFlash(__('Clean copy of ' . $data_save['reference_no'] . ' has been created'), 'alerts/flash_info');
                return $this->redirect(array('action' => 'edit', $this->Aefi->id));
            } else {
                $this->Session->setFlash(__('The clean copy could not be created. Please, try again.'), 'alerts/flash_error');
                return $this->redirect($this->referer());
            }
        }
    }

    public function general_edit($id = null)
    {
        # code...
        $aefi = $this->Aefi->read(null, $id);

        if ($this->request->is('post') || $this->request->is('put')) {
            $validate = false;
            if (isset($this->request->data['submitReport'])) {
                $validate = 'first';
            }

            // The if statement below should ensure whenever a report is being edited, the serious reason field is set to null if the serious field is set to No
            if (isset($this->request->data['Aefi']['serious']) && $this->request->data['Aefi']['serious'] == "No") {
                $this->request->data['Aefi']['serious_yes'] = '';
            }



            if ($this->Aefi->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                if (isset($this->request->data['submitReport'])) {
                    $this->Aefi->saveField('submitted', 2);
                    $this->Aefi->saveField('submitted_date', date("Y-m-d H:i:s"));
                    $aefi = $this->Aefi->read(null, $id);

                    $this->Session->setFlash(__('The Adverse Event Following Immunization has been submitted to PPB'), 'alerts/flash_success');
                    $this->redirect(array('action' => 'view', $this->Aefi->id));
                }
                // debug($this->request->data);
                $this->Session->setFlash(__('The Adverse Event Following Immunization has been saved'), 'alerts/flash_success');
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('The Adverse Event Following Immunization could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $this->request->data = $this->Aefi->read(null, $id);
        }

        //Manager will always edit a copied report
        $aefi = $this->Aefi->find('first', array(
            'conditions' => array('Aefi.id' => $id),
            'contain' => array(
                'AefiListOfVaccine',
                'AefiListOfVaccine.Vaccine',
                'AefiDescription',
                'County',
                'SubCounty',
                'Attachment',
                'Designation',
                'ExternalComment',
                'AefiOriginal',
                'AefiOriginal.AefiListOfVaccine',
                'AefiOriginal.AefiDescription',
                'AefiOriginal.AefiListOfVaccine.Vaccine',
                'AefiOriginal.County',
                'AefiOriginal.SubCounty',
                'AefiOriginal.Attachment',
                'AefiOriginal.Designation',
                'AefiOriginal.ExternalComment'
            )
        ));
        $this->set('aefi', $aefi);

        $counties = $this->Aefi->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $sub_counties = $this->Aefi->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));

        $this->set(compact('sub_counties'));
        $designations = $this->Aefi->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $vaccines = $this->Aefi->AefiListOfVaccine->Vaccine->find('list');
        $this->set(compact('vaccines'));
    }

    // EVALUATOR FUNCTIONS::::
    public function reviewer_view($id = null)
    {
        # code...
        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            $this->Session->setFlash(__('Could not verify the medical devices report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        $this->general_view($id);
    }

    public function reviewer_copy($id = null)
    {
        # code...
        $this->general_copy($id);
    }
    public function reviewer_edit($id = null)
    {
        # code...
        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            throw new NotFoundException(__('Invalid Adverse Event Following Immunization'));
        }
        $this->general_edit($id);
    }

    /**
     * download methods
     */
    public function download($id = null, $type)
    {


        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            $this->Session->setFlash(__('Could not verify the Adverse Event Following Immunization report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        $aefi = $this->Aefi->find('first', array(
            'conditions' => array('Aefi.id' => $id),
            'contain' => array(
                'AefiListOfVaccine',
                'AefiListOfVaccine.Vaccine',
                'AefiDescription',
                'County',
                'SubCounty',
                'Attachment',
                'Designation',
                'ExternalComment',
                'AefiOriginal',
                'AefiOriginal.AefiListOfVaccine',
                'AefiOriginal.AefiDescription',
                'AefiOriginal.AefiListOfVaccine.Vaccine',
                'AefiOriginal.County',
                'AefiOriginal.SubCounty',
                'AefiOriginal.Attachment',
                'AefiOriginal.Designation',
                'AefiOriginal.ExternalComment'
            )
        ));

        if (!empty($aefi['Aefi']['aefi_symptoms'])) {
            $aefi['AefiDescription'][] = array(
                'id' => $aefi['Aefi']['id'],
                'aefi_id' => $aefi['Aefi']['id'],
                'description' => $aefi['Aefi']['aefi_symptoms'],
                'created' => $aefi['Aefi']['created'],
                'modified' => $aefi['Aefi']['modified']
            );
        }


        // retrieve meddra equivalent of the description:
        if ($aefi['Aefi']['local_reaction']) {

            $aefi['AefiDescription'][] = array(
                'id' => $aefi['Aefi']['id'],
                'aefi_id' => $aefi['Aefi']['id'],
                'description' => "Severe",
                'created' => $aefi['Aefi']['created'],
                'modified' => $aefi['Aefi']['modified']
            );
        }

        if ($aefi['Aefi']['convulsion']) {

            $aefi['AefiDescription'][] = array(
                'id' => $aefi['Aefi']['id'],
                'aefi_id' => $aefi['Aefi']['id'],
                'description' => "Seizures",
                'created' => $aefi['Aefi']['created'],
                'modified' => $aefi['Aefi']['modified']
            );
        }
        if ($aefi['Aefi']['abscess']) {

            $aefi['AefiDescription'][] = array(
                'id' => $aefi['Aefi']['id'],
                'aefi_id' => $aefi['Aefi']['id'],
                'description' => "Abscess",
                'created' => $aefi['Aefi']['created'],
                'modified' => $aefi['Aefi']['modified']
            );
        }
        if ($aefi['Aefi']['bcg']) {

            $aefi['AefiDescription'][] = array(
                'id' => $aefi['Aefi']['id'],
                'aefi_id' => $aefi['Aefi']['id'],
                'description' => "BCG Lymphadenitis",
                'created' => $aefi['Aefi']['created'],
                'modified' => $aefi['Aefi']['modified']
            );
        }
        if ($aefi['Aefi']['meningitis']) {

            $aefi['AefiDescription'][] = array(
                'id' => $aefi['Aefi']['id'],
                'aefi_id' => $aefi['Aefi']['id'],
                'description' => "Encephalopathy",
                'created' => $aefi['Aefi']['created'],
                'modified' => $aefi['Aefi']['modified']
            );
        }
        if ($aefi['Aefi']['toxic_shock']) {

            $aefi['AefiDescription'][] = array(
                'id' => $aefi['Aefi']['id'],
                'aefi_id' => $aefi['Aefi']['id'],
                'description' => "Toxic shock",
                'created' => $aefi['Aefi']['created'],
                'modified' => $aefi['Aefi']['modified']
            );
        }
        if ($aefi['Aefi']['anaphylaxis']) {

            $aefi['AefiDescription'][] = array(
                'id' => $aefi['Aefi']['id'],
                'aefi_id' => $aefi['Aefi']['id'],
                'description' => "Anaphylaxis",
                'created' => $aefi['Aefi']['created'],
                'modified' => $aefi['Aefi']['modified']
            );
        }
        if ($aefi['Aefi']['high_fever']) {

            $aefi['AefiDescription'][] = array(
                'id' => $aefi['Aefi']['id'],
                'aefi_id' => $aefi['Aefi']['id'],
                'description' => "Fever",
                'created' => $aefi['Aefi']['created'],
                'modified' => $aefi['Aefi']['modified']
            );
        }
        if ($aefi['Aefi']['paralysis']) {

            $aefi['AefiDescription'][] = array(
                'id' => $aefi['Aefi']['id'],
                'aefi_id' => $aefi['Aefi']['id'],
                'description' => "Paralysis",
                'created' => $aefi['Aefi']['created'],
                'modified' => $aefi['Aefi']['modified']
            );
        }
        if ($aefi['Aefi']['urticaria']) {

            $aefi['AefiDescription'][] = array(
                'id' => $aefi['Aefi']['id'],
                'aefi_id' => $aefi['Aefi']['id'],
                'description' => "Generalized urticaria",
                'created' => $aefi['Aefi']['created'],
                'modified' => $aefi['Aefi']['modified']
            );
        }
        $medDRAList = array();
        if (!empty($aefi['AefiDescription'])) {

            $medDRAList = $this->generateMedDRACodeValue($aefi['AefiDescription']);
        }

        $aefi['Aefi']['reactions'] = $medDRAList;

        $aefi = Sanitize::clean($aefi, array('escape' => true));
        $this->set('aefi', $aefi);

        $this->response->type('xml');
        $this->response->download('AEFI_R' . $type . '_' . $aefi['Aefi']['id'] . '.xml'); // Set filename for download
        if ($type == 2) {
            // Render the XML view
            $this->render('download_r2', 'xml'); // Render the 'download_xml.ctp' view as XML
        } else {

            // Render the XML view
            $this->render('download_r3', 'xml'); // Render the 'download_xml.ctp' view as XML
        }
    }




    public  function generateMedDRACodeValue($medDRAs = array())
    {
        $this->loadModel('Meddra');
        $medDRAList = array();
        foreach ($medDRAs as $md) {
            $description = $md['description'];
            $meddra = $this->Meddra->find('first', array(
                'conditions' => array(
                    'Meddra.llt_name' => $description
                ),
                'fields' => array('Meddra.llt_name', 'Meddra.pt_code')
            ));

            if ($meddra) {
                // Record found
                $llt_name = $meddra['Meddra']['llt_name'];
                $pt_code = $meddra['Meddra']['pt_code'];
                // Do something with $llt_name and $pt_code
                $medDRAList[] = array(
                    'llt_name' => $llt_name,
                    'pt_code' => $pt_code
                );
            }
        }
        return $medDRAList;
    }

    public function vigiflow($id = null)
    {
        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            $this->Session->setFlash(__('Could not verify the Adverse Event Following Immunization report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        $aefi = $this->Aefi->find('first', array(
            'conditions' => array('Aefi.id' => $id),
            'contain' => array('AefiListOfVaccine', 'AefiDescription', 'County', 'Attachment', 'Designation')
        ));
        $aefi = Sanitize::clean($aefi, array('escape' => true));

        $view = new View($this, false);
        $view->viewPath = 'Aefis/xml';  // Directory inside view directory to search for .ctp files
        $view->layout = false; // if you want to disable layout
        $view->set('aefi', $aefi); // set your variables for view here
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

        // debug($results->code);
        // debug($results->body);
        // debug($results);
        // exit();
        if ($results->isOk()) {
            $body = $results->body;
            $this->Aefi->saveField('vigiflow_message', $body);
            $this->Aefi->saveField('vigiflow_date', date('Y-m-d H:i:s'));
            $resp = json_decode($body, true);
            if (json_last_error() == JSON_ERROR_NONE) {
                $this->Aefi->saveField('vigiflow_ref', $resp);
            }
            $this->Flash->success('Vigiflow integration success!!');
            $this->Flash->success($body);
            $this->redirect($this->referer());
        } else {
            $body = $results->body;
            $this->Aefi->saveField('vigiflow_message', $body);
            $this->Flash->error('Error sending report to vigiflow:');
            $this->Flash->error($body);
            $this->redirect($this->referer());
        }
        $this->autoRender = false;
    }
    /**
     * add method
     *
     * @return void
     */

    public function reporter_add($id = null)
    {
        $this->Aefi->create();
        $this->Aefi->save(['Aefi' => [
            'user_id' => $this->Auth->User('id'),
            'reference_no' => 'new', //'Adverse Event Following Immunization/'.date('Y').'/'.$count,
            'report_type' => 'Initial',
            'pqmp_id' => $id,
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
        $this->Session->setFlash(__('The Adverse Event Following Immunization has been created'), 'alerts/flash_success');
        $this->redirect(array('action' => 'edit', $this->Aefi->id));
    }

    public function reporter_followup($id = null)
    {
        if ($this->request->is('post')) {
            $this->Aefi->id = $id;
            if (!$this->Aefi->exists()) {
                throw new NotFoundException(__('Invalid Adverse Event Following Immunization'));
            }
            $aefi = Hash::remove($this->Aefi->find(
                'first',
                array(
                    'contain' => array('AefiListOfVaccine', 'AefiDescription'),
                    'conditions' => array('Aefi.id' => $id)
                )
            ), 'Aefi.id');

            $aefi = Hash::remove($aefi, 'AefiListOfVaccine.{n}.id');
            $data_save = $aefi['Aefi'];
            $data_save['AefiListOfVaccine'] = $aefi['AefiListOfVaccine'];
            $data_save['aefi_id'] = $id;
            $data_save['reference_no'] = $aefi['Aefi']['reference_no']; //.'_F'.$count;
            $data_save['report_type'] = 'Followup';
            $data_save['submitted'] = 0;
            $now = date('Y-m-d H:i:s');
            $data_save['created'] = $now;
            $data_save['modified'] = $now;

            if ($this->Aefi->saveAssociated($data_save, array('deep' => true, 'validate' => false))) {
                $this->Session->setFlash(__('Follow up ' . $data_save['reference_no'] . ' has been created'), 'alerts/flash_info');
                $this->redirect(array('action' => 'edit', $this->Aefi->id));
            } else {
                $this->Session->setFlash(__('The followup could not be saved. Please, try again.'), 'alerts/flash_error');
                $this->redirect($this->referer());
            }
        }
    }
    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function generateReferenceNumber()
    {

        $count = $this->Aefi->find('count',  array(
            'fields' => 'Aefi.reference_no',
            'conditions' => array(
                'Aefi.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s")),
                'Aefi.reference_no !=' => 'new'
            )
        ));
        $count++;
        $count = ($count < 10) ? "0$count" : $count;
        $reference = 'AEFI/' . date('Y') . '/' . $count;

        //ensure that the reference number is unique
        $exists = $this->Aefi->find('count',  array(
            'fields' => 'Aefi.reference_no',
            'conditions' => array(
                'Aefi.reference_no' => $reference
            )
        ));
        if ($exists > 0) {
            $this->generateReferenceNumber();
        }

        return $reference;
    }

    public function manager_reset_reference($id = null)
    {
        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            throw new NotFoundException(__('Invalid Adverse Event Following Immunization'));
        }
        $aefi = $this->Aefi->read(null, $id);
        if ($aefi['Aefi']['submitted'] > 1) {
            if (!empty($aefi['Aefi']['reference_no']) && $aefi['Aefi']['reference_no'] == 'new') {
                $reference = $this->generateReferenceNumber();
                $this->Aefi->saveField('reference_no', $reference);
            }
            $aefi = $this->Aefi->read(null, $id);
            return $aefi;
        }
        return "Not Done";
    }

    public function reporter_edit($id = null)
    {

        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            throw new NotFoundException(__('Invalid Adverse Event Following Immunization'));
        }
        $aefi = $this->Aefi->read(null, $id);
        if ($aefi['Aefi']['submitted'] > 1) {
            $this->Session->setFlash(__('The Adverse Event Following Immunization has been submitted'), 'alerts/flash_info');
            $this->redirect(array('action' => 'view', $this->Aefi->id));
        }
        if ($aefi['Aefi']['user_id'] !== $this->Auth->user('id')) {
            $this->Session->setFlash(__('You don\'t have permission to edit this Adverse Event Following Immunization!!'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            // debug($this->request->data);
            // return;
            $validate = false;
            if (isset($this->request->data['submitReport'])) {
                $validate = 'first';
            }
            // debug($this->request->data);
            // exit;
            if ($this->Aefi->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                if (isset($this->request->data['submitReport'])) {
                    $this->Aefi->saveField('submitted', 2);
                    $this->Aefi->saveField('submitted_date', date("Y-m-d H:i:s"));
                    //lucian
                    if (!empty($aefi['Aefi']['reference_no']) && $aefi['Aefi']['reference_no'] == 'new') {
                        // $reference = $this->generateReferenceNumber();
                        $count = $this->Aefi->find('count',  array(
                            'fields' => 'Aefi.reference_no',
                            'conditions' => array(
                                'Aefi.submitted_date BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s")),
                                'Aefi.reference_no !=' => 'new'
                            )
                        ));
                        $count++;
                        $count = ($count < 10) ? "0$count" : $count;
                        $reference = 'AEFI/' . date('Y') . '/' . $count;
                        $this->Aefi->saveField('reference_no', $reference);
                    }
                    //bokelo
                    $aefi = $this->Aefi->read(null, $id);

                    //******************       Send Email and Notifications to Applicant and Managers          *****************************
                    $this->loadModel('Message');
                    $html = new HtmlHelper(new ThemeView());
                    $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_aefi_submit')));
                    $variables = array(
                        'name' => $this->Auth->User('name'),
                        'reference_no' => $aefi['Aefi']['reference_no'],
                        'reference_link' => $html->link(
                            $aefi['Aefi']['reference_no'],
                            array('controller' => 'aefis', 'action' => 'view', $aefi['Aefi']['id'], 'reporter' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'modified' => $aefi['Aefi']['modified']
                    );
                    $datum = array(
                        'email' => $aefi['Aefi']['reporter_email'],
                        'id' => $id,
                        'user_id' => $this->Auth->User('id'),
                        'type' => 'reporter_aefi_submit',
                        'model' => 'Aefi',
                        'subject' => CakeText::insert($message['Message']['subject'], $variables),
                        'message' => CakeText::insert($message['Message']['content'], $variables)
                    );

                    $this->loadModel('Queue.QueuedTask');
                    $this->QueuedTask->createJob('GenericEmail', $datum);
                    $this->QueuedTask->createJob('GenericNotification', $datum);

                    //Send SMS
                    if (!empty($aefi['Aefi']['reporter_phone']) && strlen(substr($aefi['Aefi']['reporter_phone'], -9)) == 9 && is_numeric(substr($aefi['Aefi']['reporter_phone'], -9))) {
                        $datum['phone'] = '254' . substr($aefi['Aefi']['reporter_phone'], -9);
                        $variables['reference_url'] = Router::url(['controller' => 'aefis', 'action' => 'view', $aefi['Aefi']['id'], 'reporter' => true, 'full_base' => true]);
                        $datum['sms'] = CakeText::insert($message['Message']['sms'], $variables);
                        $this->QueuedTask->createJob('GenericSms', $datum);
                    }


                    //Notify managers
                    $users = $this->Aefi->User->find('all', array(
                        'contain' => array(),
                        'conditions' => array('User.group_id' => 2, 'User.is_active' => '1')
                    ));
                    foreach ($users as $user) {
                        $variables = array(
                            'name' => $user['User']['name'],
                            'reference_no' => $aefi['Aefi']['reference_no'],
                            'reference_link' => $html->link(
                                $aefi['Aefi']['reference_no'],
                                array('controller' => 'aefis', 'action' => 'view', $aefi['Aefi']['id'], 'manager' => true, 'full_base' => true),
                                array('escape' => false)
                            ),
                            'modified' => $aefi['Aefi']['modified']
                        );
                        $datum = array(
                            'email' => $user['User']['email'],
                            'id' => $id,
                            'user_id' => $user['User']['id'],
                            'type' => 'reporter_aefi_submit',
                            'model' => 'Aefi',
                            'subject' => CakeText::insert($message['Message']['subject'], $variables),
                            'message' => CakeText::insert($message['Message']['content'], $variables)
                        );

                        $this->QueuedTask->createJob('GenericEmail', $datum);
                        $this->QueuedTask->createJob('GenericNotification', $datum);
                    }
                    //**********************************    END   *********************************

                    $serious = $aefi['Aefi']['serious'];
                    if ($serious == "Yes") {
                        //                        $this->notifyCountyPharmacist($aefi);
                    }

                    $this->Session->setFlash(__('The Adverse Event Following Immunization has been submitted to PPB'), 'alerts/flash_success');
                    $this->redirect(array('action' => 'view', $this->Aefi->id));
                }
                // debug($this->request->data);
                $this->Session->setFlash(__('The Adverse Event Following Immunization has been saved'), 'alerts/flash_success');
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('The Adverse Event Following Immunization could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $this->request->data = $this->Aefi->read(null, $id);
        }

        //$aefi = $this->request->data;

        $counties = $this->Aefi->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $sub_counties = $this->Aefi->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        $this->set(compact('sub_counties'));
        $designations = $this->Aefi->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $vaccines = $this->Aefi->AefiListOfVaccine->Vaccine->find('list');
        // debug($vaccines);
        // exit;
        $this->set(compact('vaccines'));
    }
    public function generateSReferenceNumber()
    {

        $this->loadModel('Saefi');
        $count = $this->Saefi->find('count',  array(
            'fields' => 'Saefi.reference_no',
            'conditions' => array(
                'Saefi.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s")),
                'Saefi.reference_no !=' => 'new'
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
            $this->generateSReferenceNumber();
        }

        return $reference;
    }

    public function notifyCountyPharmacist($aefi = null)
    {
        # code...

        $this->loadModel('Message');
        $html = new HtmlHelper(new ThemeView());
        $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_serious_aefi')));

        $county_id = $aefi['Aefi']['county_id'];

        $users = $this->Aefi->User->find('all', array(
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
                'reference_no' => $aefi['Aefi']['reference_no'],
                'reference_link' => $html->link(
                    $aefi['Aefi']['reference_no'],
                    array(
                        'controller' => 'aefis',
                        'action' => 'view',
                        $aefi['Aefi']['id'],
                        $model => true,
                        'full_base' => true
                    ),
                    array('escape' => false)
                ),
                'modified' => $aefi['Aefi']['modified']
            );
            $datum = array(
                'email' => $user['User']['email'],
                'id' => $aefi['Aefi']['id'],
                'user_id' => $user['User']['id'],
                'type' => 'reporter_serious_aefi',
                'model' => 'Aefi',
                'subject' => CakeText::insert($message['Message']['subject'], $variables),
                'message' => CakeText::insert($message['Message']['content'], $variables)
            );
            $this->loadModel('Queue.QueuedTask');
            // $this->QueuedTask->createJob('GenericEmail', $datum);
            // $this->QueuedTask->createJob('GenericNotification', $datum);
        }
    }
    public function reporter_sedit($id = null)
    {

        $this->loadModel('Saefi');
        $this->Saefi->id = $id;
        if (!$this->Saefi->exists()) {
            throw new NotFoundException(__('Invalid Adverse Event Following Immunization'));
        }
        $aefi = $this->Saefi->read(null, $id);
        if ($aefi['Saefi']['submitted'] > 1) {
            $this->Session->setFlash(__('The Adverse Event Following Immunization has been submitted'), 'alerts/flash_info');
            $this->redirect(array('action' => 'sview', $this->Saefi->id));
        }
        if ($aefi['Saefi']['user_id'] !== $this->Auth->user('id')) {
            $this->Session->setFlash(__('You don\'t have permission to edit this Adverse Event Following Immunization!!'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            // debug($this->request->data);
            // return;
            $validate = false;
            if (isset($this->request->data['submitReport'])) {
                $validate = 'first';
            }

            if ($this->Saefi->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                if (isset($this->request->data['submitReport'])) {
                    $this->Saefi->saveField('submitted', 2);
                    $this->Saefi->saveField('submitted_date', date("Y-m-d H:i:s"));
                    //lucian
                    if (!empty($aefi['Saefi']['reference_no']) && $aefi['Saefi']['reference_no'] == 'new') {
                        $reference = $this->generateSReferenceNumber();
                        $this->Saefi->saveField('reference_no', $reference);
                    }
                    //bokelo
                    $aefi = $this->Saefi->read(null, $id);

                    //******************       Send Email and Notifications to Applicant and Managers          *****************************

                    $this->loadModel('Message');
                    $html = new HtmlHelper(new ThemeView());
                    $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_aefi_submit')));
                    $variables = array(
                        'name' => $this->Auth->User('name'),
                        'reference_no' => $aefi['Saefi']['reference_no'],
                        'reference_link' => $html->link(
                            $aefi['Saefi']['reference_no'],
                            array('controller' => 'saefis', 'action' => 'view', $aefi['Saefi']['id'], 'reporter' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'modified' => $aefi['Saefi']['modified']
                    );
                    $datum = array(
                        'email' => $aefi['Saefi']['reporter_email'],
                        'id' => $id,
                        'user_id' => $this->Auth->User('id'),
                        'type' => 'reporter_aefi_submit',
                        'model' => 'Saefi',
                        'subject' => CakeText::insert($message['Message']['subject'], $variables),
                        'message' => CakeText::insert($message['Message']['content'], $variables)
                    );

                    $aefi = $this->Aefi->read(null, $this->Aefi->id);
                    $id = $this->Aefi->id;

                    //******************       Send Email and Notifications to Applicant and Managers          *****************************
                    $this->loadModel('Message');
                    $html = new HtmlHelper(new ThemeView());
                    $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_aefi_submit')));
                    $variables = array(
                        'name' => $this->Auth->User('name'),
                        'reference_no' => $aefi['Aefi']['reference_no'],
                        'reference_link' => $html->link(
                            $aefi['Aefi']['reference_no'],
                            array('controller' => 'aefis', 'action' => 'view', $aefi['Aefi']['id'], 'reporter' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'modified' => $aefi['Aefi']['modified']
                    );
                    $datum = array(
                        'email' => $aefi['Aefi']['reporter_email'],
                        'id' => $id,
                        'user_id' => $this->Auth->User('id'),
                        'type' => 'reporter_aefi_submit',
                        'model' => 'Aefi',
                        'subject' => CakeText::insert($message['Message']['subject'], $variables),
                        'message' => CakeText::insert($message['Message']['content'], $variables)
                    );

                    $this->loadModel('Queue.QueuedTask');
                    $this->QueuedTask->createJob('GenericEmail', $datum);
                    $this->QueuedTask->createJob('GenericNotification', $datum);


                    //Send SMS
                    if (!empty($aefi['Aefi']['reporter_phone']) && strlen(substr($aefi['Aefi']['reporter_phone'], -9)) == 9 && is_numeric(substr($aefi['Aefi']['reporter_phone'], -9))) {
                        $datum['phone'] = '254' . substr($aefi['Aefi']['reporter_phone'], -9);
                        $variables['reference_url'] = Router::url(['controller' => 'aefis', 'action' => 'view', $aefi['Aefi']['id'], 'reporter' => true, 'full_base' => true]);
                        $datum['sms'] = CakeText::insert($message['Message']['sms'], $variables);
                        $this->QueuedTask->createJob('GenericSms', $datum);
                    }

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
                            'name' => $user['User']['name'],
                            'reference_no' => $aefi['Saefi']['reference_no'],
                            'reference_link' => $html->link(
                                $aefi['Saefi']['reference_no'],
                                array('controller' => 'saefis', 'action' => 'view', $aefi['Saefi']['id'], 'manager' => true, 'full_base' => true),
                                array('escape' => false)
                            ),
                            'modified' => $aefi['Saefi']['modified']
                        );
                        $datum = array(
                            'email' => $user['User']['email'],
                            'id' => $id,
                            'user_id' => $user['User']['id'],
                            'type' => 'reporter_aefi_submit',
                            'model' => 'Saefi',
                            'subject' => CakeText::insert($message['Message']['subject'], $variables),
                            'message' => CakeText::insert($message['Message']['content'], $variables)
                        );

                        $this->QueuedTask->createJob('GenericEmail', $datum);
                        $this->QueuedTask->createJob('GenericNotification', $datum);
                    }
                    // ************************** End of Alerts*********************************88
                    $this->Session->setFlash(__('The Adverse Event Following Immunization has been submitted to PPB'), 'alerts/flash_success');
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


        $counties = $this->Saefi->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $sub_counties = $this->Saefi->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        $this->set(compact('sub_counties'));
        $designations = $this->Saefi->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $vaccines = $this->Saefi->AefiListOfVaccine->Vaccine->find('list');
        $this->set(compact('vaccines'));
    }
    public function api_add()
    {
        $this->Aefi->create();

        $this->_attachments('Aefi');
        $save_data = $this->request->data;
        $save_data['Aefi']['user_id'] = $this->Auth->user('id');
        $save_data['Aefi']['submitted'] = 2;
        $save_data['Aefi']['submitted_date'] = date("Y-m-d H:i:s");

        // check if the report_type typoed to intitial then update to initial
        if (isset($save_data['Aefi']['report_type']) && $save_data['Aefi']['report_type'] == 'Intitial') {
            $save_data['Aefi']['report_type'] = 'Initial';
        }
        //lucian
        if (empty($save_data['Aefi']['reference_no'])) {
            $count = $this->Aefi->find('count',  array(
                'fields' => 'Aefi.reference_no',
                'conditions' => array(
                    'Aefi.submitted_date BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s")),
                    'Aefi.reference_no !=' => 'new'
                )
            ));
            $count++;
            $count = ($count < 10) ? "0$count" : $count;
            $save_data['Aefi']['reference_no'] = 'AEFI/' . date('Y') . '/' . $count;
        }
        // $save_data['Aefi']['report_type'] = 'Initial';
        //bokelo
        // debug($save_data);
        // return;
        if ($this->request->is('post') || $this->request->is('put')) {
            $validate = 'first';
            if ($this->Aefi->saveAssociated($save_data, array('validate' => $validate, 'deep' => true))) {


                $aefi = $this->Aefi->read(null, $this->Aefi->id);
                $id = $this->Aefi->id;

                //******************       Send Email and Notifications to Applicant and Managers          *****************************
                $this->loadModel('Message');
                $html = new HtmlHelper(new ThemeView());
                $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_aefi_submit')));
                $variables = array(
                    'name' => $this->Auth->User('name'),
                    'reference_no' => $aefi['Aefi']['reference_no'],
                    'reference_link' => $html->link(
                        $aefi['Aefi']['reference_no'],
                        array('controller' => 'aefis', 'action' => 'view', $aefi['Aefi']['id'], 'reporter' => true, 'full_base' => true),
                        array('escape' => false)
                    ),
                    'modified' => $aefi['Aefi']['modified']
                );
                $datum = array(
                    'email' => $aefi['Aefi']['reporter_email'],
                    'id' => $id,
                    'user_id' => $this->Auth->User('id'),
                    'type' => 'reporter_aefi_submit',
                    'model' => 'Aefi',
                    'subject' => CakeText::insert($message['Message']['subject'], $variables),
                    'message' => CakeText::insert($message['Message']['content'], $variables)
                );

                $this->loadModel('Queue.QueuedTask');
                $this->QueuedTask->createJob('GenericEmail', $datum);
                $this->QueuedTask->createJob('GenericNotification', $datum);


                //Send SMS
                if (!empty($aefi['Aefi']['reporter_phone']) && strlen(substr($aefi['Aefi']['reporter_phone'], -9)) == 9 && is_numeric(substr($aefi['Aefi']['reporter_phone'], -9))) {
                    $datum['phone'] = '254' . substr($aefi['Aefi']['reporter_phone'], -9);
                    $variables['reference_url'] = Router::url(['controller' => 'aefis', 'action' => 'view', $aefi['Aefi']['id'], 'reporter' => true, 'full_base' => true]);
                    $datum['sms'] = CakeText::insert($message['Message']['sms'], $variables);
                    $this->QueuedTask->createJob('GenericSms', $datum);
                }

                //Notify managers
                $users = $this->Aefi->User->find('all', array(
                    'contain' => array(),
                    'conditions' => array('User.group_id' => 2, 'User.is_active' => '1')
                ));
                foreach ($users as $user) {
                    $variables = array(
                        'name' => $user['User']['name'],
                        'reference_no' => $aefi['Aefi']['reference_no'],
                        'reference_link' => $html->link(
                            $aefi['Aefi']['reference_no'],
                            array('controller' => 'aefis', 'action' => 'view', $aefi['Aefi']['id'], 'manager' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'modified' => $aefi['Aefi']['modified']
                    );
                    $datum = array(
                        'email' => $user['User']['email'],
                        'id' => $id,
                        'user_id' => $user['User']['id'],
                        'type' => 'reporter_aefi_submit',
                        'model' => 'Aefi',
                        'subject' => CakeText::insert($message['Message']['subject'], $variables),
                        'message' => CakeText::insert($message['Message']['content'], $variables)
                    );

                    $this->QueuedTask->createJob('GenericEmail', $datum);
                    $this->QueuedTask->createJob('GenericNotification', $datum);
                    // CakeResque::enqueue('default', 'GenericEmailShell', array('sendEmail', $datum));
                    // CakeResque::enqueue('default', 'GenericNotificationShell', array('sendNotification', $datum));
                }
                //**********************************    END   *********************************

                $this->set([
                    'status' => 'success',
                    'message' => 'The Adverse Event Following Immunization has been submitted to PPB',
                    'aefi' => $aefi,
                    '_serialize' => ['status', 'message', 'aefi']
                ]);
            } else {
                $this->set([
                    'status' => 'failed',
                    'message' => 'The Adverse Event Following Immunization could not be saved',
                    'validation' => $this->Aefi->validationErrors,
                    'aefi' => $this->request->data,
                    '_serialize' => ['status', 'message', 'validation', 'aefi']
                ]);
            }
        } else {
            throw new MethodNotAllowedException();
        }
    }

    public function manager_copy($id = null)
    {
        $this->general_copy($id);
    }

    public function manager_edit($id = null)
    {
        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            throw new NotFoundException(__('Invalid Adverse Event Following Immunization'));
        }
        $this->general_edit($id);
    }


    // DELETE SECTION

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
        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            throw new NotFoundException(__('Invalid Adverse Event Following Immunization'));
        }
        //return flash message with the reference number
        $aefi = $this->Aefi->read(null, $id);
        $this->request->allowMethod('post', 'delete');
        //update the column deleted to 1 and deleted_date to current date and save
        $aefi['Aefi']['deleted'] = true;
        $aefi['Aefi']['deleted_date'] = date("Y-m-d H:i:s");
        //update the database
        if ($this->Aefi->save($aefi, array('validate' => false, 'deep' => true))) {
            $this->Session->setFlash(__('The Adverse Event Following Immunization with reference number ' . $aefi['Aefi']['reference_no'] . ' has been deleted'), 'alerts/flash_success');
        } else {
            // get the error message
            $errors = $this->Aefi->validationErrors;
            // debug($errors);
            // exit;
            $this->Session->setFlash(__('The Adverse Event Following Immunization could not be deleted. Please, try again.' . $errors), 'alerts/flash_error');
        }

        $this->redirect($this->referer());
    }
    public function reporter_investigation($id = null)
    {
        # code...
        // debug('saefis');
        // exit;
        $this->generate_investigation($id);
    }

    public function generate_investigation($id = null)
    {
        # code...

        if ($this->request->is('post')) {
            $this->Aefi->id = $id;
            if (!$this->Aefi->exists()) {
                throw new NotFoundException(__('Invalid Adverse Event Following Immunization'));
            }
            $aefi = Hash::remove($this->Aefi->find(
                'first',
                array(
                    'contain' => array('AefiListOfVaccine', 'AefiDescription'),
                    'conditions' => array('Aefi.id' => $id)
                )
            ), 'Aefi.id');

            $aefi = Hash::remove($aefi, 'AefiListOfVaccine.{n}.id');
            $aefi = Hash::remove($aefi, 'AefiListOfVaccine.{n}.aefi_id');
            unset($aefi['Aefi']['deleted']);
            $data_save = $aefi['Aefi'];

            $data_save['AefiListOfVaccine'] = $aefi['AefiListOfVaccine'];
            $data_save['initial_id'] = $id;
            $data_save['province_id'] = $aefi['Aefi']['county_id'];
            $data_save['district'] = $aefi['Aefi']['sub_county_id'];
            $data_save['age_at_onset_months'] = $aefi['Aefi']['age_months'];
            $data_save['reference_no'] = $aefi['Aefi']['reference_no'];
            $data_save['user_id'] = $this->Auth->user('id');
            $data_save['submitted'] = 0;
            $this->loadModel('Saefi');
            if ($this->Saefi->saveAssociated($data_save, array('deep' => true, 'validate' => false))) {
                $this->Session->setFlash(__('Investigation Form' . $data_save['reference_no'] . ' has been created'), 'alerts/flash_info');
                $this->redirect(array('controller' => 'saefis', 'action' => 'edit', $this->Saefi->id));
            } else {
                $this->Session->setFlash(__('The followup could not be saved. Please, try again.'), 'alerts/flash_error');
                $this->redirect($this->referer());
            }
        }
    }


    public function guest_add($id = null)
    {
        $this->Aefi->create();
        $this->Aefi->save(['Aefi' => [
            'reference_no' => 'new',
            'report_type' => 'Initial',
        ]], false);
        $this->Session->setFlash(__('The Adverse Event Following Immunization has been created'), 'alerts/flash_success');
        $this->redirect(array('action' => 'guest_edit', $this->Aefi->id));
    }
    public function guest_edit($id = null)
    {

        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            throw new NotFoundException(__('Invalid Adverse Event Following Immunization'));
        }
        $aefi = $this->Aefi->read(null, $id);

        if ($this->request->is('post') || $this->request->is('put')) {

            $validate = false;
            if (isset($this->request->data['submitReport'])) {
                $validate = 'first';
            }
            if ($this->Aefi->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                if (isset($this->request->data['submitReport'])) {
                    $this->Aefi->saveField('submitted', 2);
                    $this->Aefi->saveField('submitted_date', date("Y-m-d H:i:s"));
                    //lucian
                    if (!empty($aefi['Aefi']['reference_no']) && $aefi['Aefi']['reference_no'] == 'new') {
                        // $reference = $this->generateReferenceNumber();
                        $count = $this->Aefi->find('count',  array(
                            'fields' => 'Aefi.reference_no',
                            'conditions' => array(
                                'Aefi.submitted_date BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s")),
                                'Aefi.reference_no !=' => 'new'
                            )
                        ));
                        $count++;
                        $count = ($count < 10) ? "0$count" : $count;
                        $reference = 'AEFI/' . date('Y') . '/' . $count;
                        $this->Aefi->saveField('reference_no', $reference);
                    }
                    //bokelo
                    $aefi = $this->Aefi->read(null, $id);

                    //******************       Send Email and Notifications to Applicant and Managers          *****************************
                    $this->loadModel('Message');
                    $html = new HtmlHelper(new ThemeView());
                    $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_aefi_submit')));
                    $variables = array(
                        'name' => 'Guest',
                        'reference_no' => $aefi['Aefi']['reference_no'],
                        'reference_link' => $html->link(
                            $aefi['Aefi']['reference_no'],
                            array('controller' => 'aefis', 'action' => 'view', $aefi['Aefi']['id'], 'reporter' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'modified' => $aefi['Aefi']['modified']
                    );
                    $datum = array(
                        'email' => $aefi['Aefi']['reporter_email'],
                        'id' => $id,
                        'user_id' => $this->Auth->User('id'),
                        'type' => 'reporter_aefi_submit',
                        'model' => 'Aefi',
                        'subject' => CakeText::insert($message['Message']['subject'], $variables),
                        'message' => CakeText::insert($message['Message']['content'], $variables)
                    );

                    $this->loadModel('Queue.QueuedTask');
                    $this->QueuedTask->createJob('GenericEmail', $datum);
                    $this->QueuedTask->createJob('GenericNotification', $datum);

                    //Send SMS
                    if (!empty($aefi['Aefi']['reporter_phone']) && strlen(substr($aefi['Aefi']['reporter_phone'], -9)) == 9 && is_numeric(substr($aefi['Aefi']['reporter_phone'], -9))) {
                        $datum['phone'] = '254' . substr($aefi['Aefi']['reporter_phone'], -9);
                        $variables['reference_url'] = Router::url(['controller' => 'aefis', 'action' => 'view', $aefi['Aefi']['id'], 'reporter' => true, 'full_base' => true]);
                        $datum['sms'] = CakeText::insert($message['Message']['sms'], $variables);
                        $this->QueuedTask->createJob('GenericSms', $datum);
                    }


                    //Notify managers
                    $users = $this->Aefi->User->find('all', array(
                        'contain' => array(),
                        'conditions' => array('User.group_id' => 2, 'User.is_active' => '1')
                    ));
                    foreach ($users as $user) {
                        $variables = array(
                            'name' => $user['User']['name'],
                            'reference_no' => $aefi['Aefi']['reference_no'],
                            'reference_link' => $html->link(
                                $aefi['Aefi']['reference_no'],
                                array('controller' => 'aefis', 'action' => 'view', $aefi['Aefi']['id'], 'manager' => true, 'full_base' => true),
                                array('escape' => false)
                            ),
                            'modified' => $aefi['Aefi']['modified']
                        );
                        $datum = array(
                            'email' => $user['User']['email'],
                            'id' => $id,
                            'user_id' => $user['User']['id'],
                            'type' => 'reporter_aefi_submit',
                            'model' => 'Aefi',
                            'subject' => CakeText::insert($message['Message']['subject'], $variables),
                            'message' => CakeText::insert($message['Message']['content'], $variables)
                        );

                        $this->QueuedTask->createJob('GenericEmail', $datum);
                        $this->QueuedTask->createJob('GenericNotification', $datum);
                    }
                    //**********************************    END   *********************************

                    $serious = $aefi['Aefi']['serious'];
                    if ($serious == "Yes") {
                        //    Notify County Pharmacist & Managers
                        $county_id = $aefi['Aefi']['county_id'];
                        $users1 = $this->Aefi->User->find('all', array(
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

                        //******************       Send Email and Notifications to County Pharmacist and Managers          *****************************
                        $this->loadModel('Message');
                        $html = new HtmlHelper(new ThemeView());
                        $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_serious_aefi')));
                        $this->loadModel('Queue.QueuedTask');

                        //Notify managers

                        foreach ($users1 as $user) {
                            $user_phone = $user['User']['phone_no'];
                            $variables = array(
                                'name' => $user['User']['name'],
                                'reference_no' => $aefi['Aefi']['reference_no'],
                                'reference_link' => $html->link(
                                    $aefi['Aefi']['reference_no'],
                                    array('controller' => 'aefis', 'action' => 'view', $aefi['Aefi']['id'], 'manager' => true, 'full_base' => true),
                                    array('escape' => false)
                                ),
                                'modified' => $aefi['Aefi']['modified']
                            );
                            $datum = array(
                                'email' => $user['User']['email'],
                                'id' => $id,
                                'user_id' => $user['User']['id'],
                                'type' => 'reporter_serious_aefi',
                                'model' => 'Aefi',
                                'subject' => CakeText::insert($message['Message']['subject'], $variables),
                                'message' => CakeText::insert($message['Message']['content'], $variables)
                            );

                            // $this->QueuedTask->createJob('GenericEmail', $datum);
                            // $this->QueuedTask->createJob('GenericNotification', $datum);

                            $reporter = ($user['User']['group_id'] == 2) ? 'manager' : 'reporter';

                            //Send SMS
                            if (!empty($user_phone) && strlen(substr($user_phone, -9)) == 9 && is_numeric(substr($user_phone, -9))) {
                                $datum['phone'] = '254' . substr($user_phone, -9);
                                $variables['reference_url'] = Router::url(['controller' => 'aefis', 'action' => 'view', $aefi['Aefi']['id'], $reporter => true, 'full_base' => true]);
                                $datum['sms'] = CakeText::insert($message['Message']['sms'], $variables);
                                $this->QueuedTask->createJob('GenericSms', $datum);
                            }
                        }
                        //**********************************    END   *********************************

                    }
                    $this->Session->setFlash(__('The Adverse Event Following Immunization has been submitted to PPB'), 'alerts/flash_success');
                    $this->redirect(array('controller' => 'pages', 'action' => 'home'));
                }
                // debug($this->request->data);
                $this->Session->setFlash(__('The Adverse Event Following Immunization has been saved'), 'alerts/flash_success');
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('The Adverse Event Following Immunization could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $this->request->data = $this->Aefi->read(null, $id);
        }

        $counties = $this->Aefi->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $sub_counties = $this->Aefi->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        $this->set(compact('sub_counties'));
        $designations = $this->Aefi->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
        $vaccines = $this->Aefi->AefiListOfVaccine->Vaccine->find('list');
        $this->set(compact('vaccines'));
    }
    public function manager_archive($id = null)
    {

        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            throw new NotFoundException(__('Invalid AEFI'));
        }
        $report = $this->Aefi->read(null, $id);
        $report['Aefi']['archived'] = true;
        $report['Aefi']['archived_date'] = date("Y-m-d H:i:s");
        if ($this->Aefi->save($report, array('validate' => false))) {
            $this->Session->setFlash(__('AEFI Archived successfully'), 'alerts/flash_success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('AEFI was not archied'), 'alerts/flash_error');
        $this->redirect($this->referer());
    }
    public function manager_restore_archive($id = null)
    {

        $this->Aefi->id = $id;
        if (!$this->Aefi->exists()) {
            throw new NotFoundException(__('Invalid AEFI'));
        }
        $report = $this->Aefi->read(null, $id);
        $report['Aefi']['archived'] = 0;
        // $report['Aefi']['archived_date'] = date("Y-m-d H:i:s");
        if ($this->Aefi->save($report, array('validate' => false))) {
            $this->Session->setFlash(__('AEFI Archive Restored successfully'), 'alerts/flash_success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('AEFI was not restored'), 'alerts/flash_error');
        $this->redirect($this->referer());
    }
}
