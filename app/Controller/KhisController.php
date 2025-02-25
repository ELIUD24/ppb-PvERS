<?php
App::uses('AppController', 'Controller');
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');
/**
 * Khis Controller
 */
class KhisController extends AppController
{

    /**
     * Scaffold
     *
     * @var mixed
     */
    public $scaffold;

    public $uses = array('Sadr', 'Aefi', 'Saefi', 'Comment', 'Pqmp', 'Device', 'Medication', 'Transfusion', 'Sae', 'DrugDictionary', 'Ce2b');
    public $components = array(
        // 'Security' => array('csrfExpires' => '+1 hour', 'validatePost' => false), 
        'Search.Prg',
        // 'RequestHandler'
    );
    public $paginate = array();
    public $presetVars = true;
    public $is_mobile = false;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->sync_indicators();
        $this->update_organizations();

        if ($this->RequestHandler->isMobile()) {
            // $this->layout = 'Emails/html/default';
            $this->is_mobile = true;
        }
        $this->set('is_mobile', $this->is_mobile);
    }
    public function update_organizations()
    {

        $this->loadModel('County');
        $apiUrl = Configure::read('khis_org_units_url');
        $username = Configure::read('khis_usename');
        $password =  Configure::read('khis_password');

        //load indicators
        $ch1 = curl_init($apiUrl);

        // Set cURL options
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch1, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
        // Execute cURL session and get the response
        $response1 = curl_exec($ch1);
        $statusCode1 = curl_getinfo($ch1, CURLINFO_HTTP_CODE);

        // Check for cURL errors
        if (curl_errno($ch1)) {
            echo 'Curl error: ' . curl_error($ch1);
        }

        // Close cURL session
        curl_close($ch1);

        if ($statusCode1 >= 200 && $statusCode1 < 300) {
            $data = json_decode($response1, true);

            // Check if dataSetElements is set and is an array
            if (isset($data['organisationUnits']) && is_array($data['organisationUnits'])) {
                // Loop through each dataSetElement 
                foreach ($data['organisationUnits'] as $element) {
                    // Access the name and id of dataElement
                    $elementName = $element['displayName'];
                    $elementId = $element['id'];

                    //load model County where name is like name and update the column org_unit 
                    if ($elementName != "Nairobi County") {
                        $elementNameWithoutCounty = trim(str_replace('County', '', $elementName));
                    } else {
                        $elementNameWithoutCounty = $elementName;
                    }
                    // Find the record where the name is like $firstPart
                    $record = $this->County->find('first', [
                        'conditions' => ['county_name LIKE' => $elementNameWithoutCounty],
                    ]);

                    if ($record) {
                        $record['County']['org_unit'] = $elementId;
                        $this->County->save($record);
                    }
                }
            }
        }
    }

    public function sync_indicators()
    {

        $this->loadModel('Khis');
        $apiUrl = Configure::read('khis_data_elements_url');
        $username = Configure::read('khis_usename');
        $password =  Configure::read('khis_password');

        //load indicators
        $ch1 = curl_init($apiUrl);

        // Set cURL options
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch1, CURLOPT_USERPWD, "$username:$password");
        // Execute cURL session and get the response
        $response1 = curl_exec($ch1);
        $statusCode1 = curl_getinfo($ch1, CURLINFO_HTTP_CODE);

        // Check for cURL errors
        if (curl_errno($ch1)) {
            echo 'Curl error: ' . curl_error($ch1);
        }

        // Close cURL session
        curl_close($ch1);

        if ($statusCode1 >= 200 && $statusCode1 < 300) {
            $data = json_decode($response1, true);

            // Check if dataSetElements is set and is an array
            if (isset($data['dataSetElements']) && is_array($data['dataSetElements'])) {
                // Loop through each dataSetElement
                $this->Khis->query('TRUNCATE TABLE khis');
                foreach ($data['dataSetElements'] as $element) {
                    // Check if dataElement is set and is an array
                    if (isset($element['dataElement']) && is_array($element['dataElement'])) {
                        // Access the name and id of dataElement
                        $elementName = $element['dataElement']['name'];
                        $elementId = $element['dataElement']['id'];

                        $this->Khis->create();
                        $this->Khis->save(array(
                            'elementId' => $elementId,
                            'elementName' => $elementName
                        ));
                    }
                }
            }
        }
    }
    public function manager_index()
    {

        $sadrsSummary = $this->sadrs_summary();
        $aefiSummary = $this->aefi_summary();
        $currentYear = date('Y');
        $years = range($currentYear, $currentYear - 19);

        $this->set('sadrsSummary', $sadrsSummary);
        $this->set('aefiSummary', $aefiSummary);
        $this->set('years', $years);
        if (isset($this->request->data['uploadReport'])) {
            $this->prepare_upload_data();
        }


        if ($this->Session->read('Auth.User.group_id') == 2) {
            $this->render('khis_summary');
        }
    }


    public function prepare_county_gender_data_values($criteria, $org_unit, $period)
    {
        $dataValues = array();
        $gender = $this->Aefi->find('all', array(
            'fields' => array('gender', 'COUNT(*) as cnt'),
            'contain' => array(),
            'recursive' => -1,
            'conditions' => $criteria,
            'group' => array('gender'),
            'having' => array('COUNT(*) >' => 0),
        ));

        foreach ($gender as $key => $result) {
            if ($result['Aefi']['gender'] == "Female") {
                $dataValues[] = [
                    "dataElement" => "YRy6ZboTEnh",
                    "categoryOptionCombo" => "HSgm52qfmu9",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Aefi']['gender'] == "Male") {
                $dataValues[] = [
                    "dataElement" => "YRy6ZboTEnh",
                    "categoryOptionCombo" => "HZqsa0U6ivP",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
        }
        return $dataValues;
    }
    public function prepare_county_month_data_values($criteria, $org_unit, $period)
    {
        $dataValues = array();
        $month = $this->Aefi->find('all', array(
            'fields' => array('DATE_FORMAT(reporter_date, "%b %Y")  as month', 'month(ifnull(reporter_date, reporter_date)) as salit', 'COUNT(*) as cnt'),
            'contain' => array(),
            'recursive' => -1,
            'conditions' => $criteria,
            'group' => array('DATE_FORMAT(reporter_date, "%b %Y")', 'salit'), // Include 'salit' in the GROUP BY clause
            'order' => array('salit'),
            'having' => array('COUNT(*) >' => 0),
        ));

        foreach ($month as $key => $value) {
            $dataValues[] = [
                "dataElement" => "q25dTTsh47j",
                "categoryOptionCombo" => "NhSoXUMPK2K",
                "period" => $period,
                "orgUnit" => $org_unit,
                "value" => $value[0]['cnt']
            ];
        }
        return $dataValues;
    }

    public function prepare_county_age_data_values($criteria, $org_unit, $period)
    {
        $dataValues = array();
        $case = "((case 
        when trim(age_months) in ('neonate', 'infant', 'child', 'adolescent', 'adult', 'elderly') then age_months
        when age_months > 0 and age_months < 1 then 'neonate'
        when age_months < 13 then 'infant'
        when age_months > 13 then 'child'
        when year(now()) - right(date_of_birth, 4) between 0 and 1 then 'infant'
        when year(now()) - right(date_of_birth, 4) between 1 and 10 then 'child'
        when year(now()) - right(date_of_birth, 4) between 18 and 65 then 'adult'
        when year(now()) - right(date_of_birth, 4) between 10 and 18 then 'adolescent'
        when year(now()) - right(date_of_birth, 4) between 65 and 155 then 'elderly'
        else 'unknown'
       end))";

        $age = $this->Aefi->find('all', array(
            'fields' => array($case . ' as ager', 'COUNT(*) as cnt'),
            'contain' => array(),
            'conditions' => $criteria,
            'group' => array($case),
            'having' => array('COUNT(*) >' => 0),
        ));

        foreach ($age as $key => $result) {
            if ($result[0]['ager'] == "elderly") {
                $dataValues[] = [
                    "dataElement" => "XWs9UY7rVqV",
                    "categoryOptionCombo" => "D1wVwZpCZxk",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result[0]['ager'] == "adult") {
                $dataValues[] = [
                    "dataElement" => "XWs9UY7rVqV",
                    "categoryOptionCombo" => "YB86GwI0Xwk",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result[0]['ager'] == "adolescent") {
                $dataValues[] = [
                    "dataElement" => "XWs9UY7rVqV",
                    "categoryOptionCombo" => "xp9OJZGm7S8",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result[0]['ager'] == "child") {
                $dataValues[] = [
                    "dataElement" => "XWs9UY7rVqV",
                    "categoryOptionCombo" => "AjVBULP0Qz9",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result[0]['ager'] == "infant") {
                $dataValues[] = [
                    "dataElement" => "XWs9UY7rVqV",
                    "categoryOptionCombo" => "uZJ1ke751Tr",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result[0]['ager'] == "neonate") {
                $dataValues[] = [
                    "dataElement" => "XWs9UY7rVqV",
                    "categoryOptionCombo" => "GIn7lBrO466",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
        }

        return $dataValues;
    }

    public function prepare_county_vaccines_data_values($criteria, $org_unit, $period)
    {
        $dataValues = array();

        $aefiIds = $this->Aefi->find('list', array(
            'fields' => array('Aefi.id'),
            'conditions' => $criteria
        ));
        $criteriav['AefiListOfVaccine.aefi_id'] = $aefiIds;

        $vaccine = $this->Aefi->AefiListOfVaccine->find('all', array(
            'fields' => array('Vaccine.vaccine_name as vaccine_name', 'COUNT(distinct AefiListOfVaccine.aefi_id) as cnt'),
            'contain' => array('Vaccine'),
            'recursive' => -1,
            'conditions' => $criteriav,
            'group' => array('Vaccine.vaccine_name', 'Vaccine.id'),
            'having' => array('COUNT(distinct AefiListOfVaccine.aefi_id) >' => 0),
        ));

        foreach ($vaccine as $result) {
            // debug($result['Vaccine']['vaccine_name']);
            if ($result['Vaccine']['vaccine_name'] == "Measles Rubella Vaccine") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "O6GZbn5NeQz",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "Pentavalent  Vaccine (DTP-HepB-Hib)") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "bkV02yl15NP",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "Human Papiloma virus vaccine") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "bCchLF54Yny",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "Malaria (RTSS)Vaccine") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "ycuc2y0fDXt",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "Pneumococcal conjugate vaccine") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "Sc7xJnZJ7gk",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "Rota virus vaccine") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "VDXUoBlY3F0",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "BCG") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "Z6wzg4Jiwyu",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "COVID -19 Vaccine- ASTRAZENECA") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "tJeWBrf6hAY",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "Bivalent oral Polio vaccine") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "UfqetIpenw0",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "COVID-19 Vaccine - PFIZER/BioNTech") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "Z7MLnbvSBcB",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "COVID-19 Vaccine-SPUTNIK V") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "rwlm0VJiIZP",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "COVID-19Vaccine-COVISHIELD") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "LAgjDEk2drU",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "COVID -19 Vaccine- MODERNA") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "FByR0P9oEWy",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }

            if ($result['Vaccine']['vaccine_name'] == "COVID-19 Vaccine - (JOHNSON&JOHNSONS)JANSSEN") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "n0Uvcnd2Lz2",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "COVID -19 Vaccine- SINOPHARM") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "w2QHHuUGh0E",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "COVID -19 Vaccine- VAXZEVRIA") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "A2qFEzm6VRE",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }

            if ($result['Vaccine']['vaccine_name'] == "Hepatitis B Vaccine") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "FVetBgxhuI1",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "Inactivated polio vaccine") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "M7feACojOHg",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "Rabies vaccine") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "IzksPENNmY3",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "Anti Snake venom") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "N5cgWeeUJgS",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "Tetanus Diptheria Vaccine") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "WlnLdfF9izd",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Vaccine']['vaccine_name'] == "Yellow fever virus vaccine") {
                $dataValues[] = [
                    "dataElement" => "St9YPLSwhzr",
                    "categoryOptionCombo" => "zfVJtQ3Dlat",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
        }

        return $dataValues;
    }
    public function prepare_upload_data()
    {
        //prepare AEFI Data
        $dataValues = array();

        $criteria['Aefi.submitted'] = array(1, 2);
        $criteria['Aefi.copied !='] = '1';
        $criteria['Aefi.deleted'] = false;
        $criteria['Aefi.archived'] = false;
        if (empty($this->request->data['Report']['start_date']) || empty($this->request->data['Report']['end_date'])) {
            $this->Session->setFlash(__('Please provide both the year and the month of submission'), 'alerts/flash_error');
            $this->redirect($this->referer());
        }
        if (!empty($this->request->data['Report']['start_date']) && !empty($this->request->data['Report']['end_date'])) {
            $monthdata = $this->request->data['Report']['start_date'];
            $year = $this->request->data['Report']['end_date'];

            // Calculate the start and end dates for the given month and year
            $startDate = date('Y-m-01 00:00:00', strtotime("$year-$monthdata"));
            $endDate = date('Y-m-t 23:59:59', strtotime("$year-$monthdata"));

            if (strtotime($startDate) > strtotime(date('Y-m-01 00:00:00')) || strtotime($endDate) > strtotime(date('Y-m-t 23:59:59'))) {
                $this->Session->setFlash(__('Aggregate data should be from the previous month'), 'alerts/flash_error');
                $this->redirect($this->referer());
            } else {

                $criteria['Aefi.reporter_date between ? and ?'] = array(date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate)));
            }
        }

        if (empty($this->request->data['Report']['county_id'])) {

            //prepare data for the whole country with the affected counties:
            $geo = $this->Aefi->find('all', array(
                'fields' => array('County.county_name', 'County.org_unit', 'COUNT(*) as cnt'),
                'contain' => array('County'),
                'conditions' => $criteria,
                'group' => array('County.county_name', 'County.org_unit', 'County.id'),
                'having' => array('COUNT(*) >' => 0),
            ));

            //loop through each county
            $gender_data_values = array();
            $age_data_values = array();
            $month_data_values = array();
            $vaccines_data_values = array();
            $period = $year . "" . $monthdata;
            foreach ($geo as $single) {
                $org_unit = $single['County']['org_unit'];
                $criteria['Aefi.county_id'] = $single['County']['id'];
                $gender_data_values = array_merge($gender_data_values, $this->prepare_county_gender_data_values($criteria, $org_unit, $period));
                $age_data_values = array_merge($age_data_values, $this->prepare_county_age_data_values($criteria, $org_unit, $period));
                $month_data_values = array_merge($month_data_values, $this->prepare_county_month_data_values($criteria, $org_unit, $period));
                $vaccines_data_values = array_merge($vaccines_data_values, $this->prepare_county_vaccines_data_values($criteria, $org_unit, $period));
            }

            $dataValues = array_merge($gender_data_values, $age_data_values, $month_data_values, $vaccines_data_values);

            $sadr_data_values = $this->prepare_upload_sadr();

            $dataValues = array_merge($dataValues, $sadr_data_values);

            $payload = [
                "dataSet" => "khmkmn2RRx4",
                "dataValues" => $dataValues
            ];
        } else {
            $criteria['Aefi.county_id'] = $this->request->data['Report']['county_id'];

            // AEFI Gender
            $gender = $this->Aefi->find('all', array(
                'fields' => array('gender', 'COUNT(*) as cnt'),
                'contain' => array(),
                'recursive' => -1,
                'conditions' => $criteria,
                'group' => array('gender'),
                'having' => array('COUNT(*) >' => 0),
            ));

            foreach ($gender as $key => $result) {
                if ($result['Aefi']['gender'] == "Female") {

                    $dataValues[] = [
                        "dataElement" => "YRy6ZboTEnh",
                        "categoryOptionCombo" => "HSgm52qfmu9",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Aefi']['gender'] == "Male") {

                    $dataValues[] = [
                        "dataElement" => "YRy6ZboTEnh",
                        "categoryOptionCombo" => "HZqsa0U6ivP",
                        "value" => $result[0]['cnt']
                    ];
                }
            }

            // AEFI Age
            $case = "((case 
            when trim(age_months) in ('neonate', 'infant', 'child', 'adolescent', 'adult', 'elderly') then age_months
            when age_months > 0 and age_months < 1 then 'neonate'
            when age_months < 13 then 'infant'
            when age_months > 13 then 'child'
            when year(now()) - right(date_of_birth, 4) between 0 and 1 then 'infant'
            when year(now()) - right(date_of_birth, 4) between 1 and 10 then 'child'
            when year(now()) - right(date_of_birth, 4) between 18 and 65 then 'adult'
            when year(now()) - right(date_of_birth, 4) between 10 and 18 then 'adolescent'
            when year(now()) - right(date_of_birth, 4) between 65 and 155 then 'elderly'
            else 'unknown'
           end))";

            $age = $this->Aefi->find('all', array(
                'fields' => array($case . ' as ager', 'COUNT(*) as cnt'),
                'contain' => array(),
                'conditions' => $criteria,
                'group' => array($case),
                'having' => array('COUNT(*) >' => 0),
            ));

            foreach ($age as $key => $result) {
                if ($result[0]['ager'] == "elderly") {
                    $dataValues[] = [
                        "dataElement" => "XWs9UY7rVqV",
                        "categoryOptionCombo" => "D1wVwZpCZxk",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result[0]['ager'] == "adult") {
                    $dataValues[] = [
                        "dataElement" => "XWs9UY7rVqV",
                        "categoryOptionCombo" => "YB86GwI0Xwk",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result[0]['ager'] == "adolescent") {
                    $dataValues[] = [
                        "dataElement" => "XWs9UY7rVqV",
                        "categoryOptionCombo" => "xp9OJZGm7S8",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result[0]['ager'] == "child") {
                    $dataValues[] = [
                        "dataElement" => "XWs9UY7rVqV",
                        "categoryOptionCombo" => "AjVBULP0Qz9",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result[0]['ager'] == "infant") {
                    $dataValues[] = [
                        "dataElement" => "XWs9UY7rVqV",
                        "categoryOptionCombo" => "uZJ1ke751Tr",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result[0]['ager'] == "neonate") {
                    $dataValues[] = [
                        "dataElement" => "XWs9UY7rVqV",
                        "categoryOptionCombo" => "GIn7lBrO466",
                        "value" => $result[0]['cnt']
                    ];
                }
            }
            // AEFI Month
            $month = $this->Aefi->find('all', array(
                'fields' => array('DATE_FORMAT(reporter_date, "%b %Y")  as month', 'month(ifnull(reporter_date, reporter_date)) as salit', 'COUNT(*) as cnt'),
                'contain' => array(),
                'recursive' => -1,
                'conditions' => $criteria,
                'group' => array('DATE_FORMAT(reporter_date, "%b %Y")', 'salit'), // Include 'salit' in the GROUP BY clause
                'order' => array('salit'),
                'having' => array('COUNT(*) >' => 0),
            ));

            foreach ($month as $key => $value) {
                $dataValues[] = [
                    "dataElement" => "q25dTTsh47j",
                    "categoryOptionCombo" => "NhSoXUMPK2K",
                    "value" => $value[0]['cnt']
                ];
            }

            // AEFI Vaccine
            $aefiIds = $this->Aefi->find('list', array(
                'fields' => array('Aefi.id'),
                'conditions' => $criteria
            ));
            $criteriav['AefiListOfVaccine.aefi_id'] = $aefiIds;

            $vaccine = $this->Aefi->AefiListOfVaccine->find('all', array(
                'fields' => array('Vaccine.vaccine_name as vaccine_name', 'COUNT(distinct AefiListOfVaccine.aefi_id) as cnt'),
                'contain' => array('Vaccine'),
                'recursive' => -1,
                'conditions' => $criteriav,
                'group' => array('Vaccine.vaccine_name', 'Vaccine.id'),
                'having' => array('COUNT(distinct AefiListOfVaccine.aefi_id) >' => 0),
            ));

            foreach ($vaccine as $result) {
                // debug($result['Vaccine']['vaccine_name']);
                if ($result['Vaccine']['vaccine_name'] == "Measles Rubella Vaccine") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "O6GZbn5NeQz",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "Pentavalent  Vaccine (DTP-HepB-Hib)") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "bkV02yl15NP",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "Human Papiloma virus vaccine") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "bCchLF54Yny",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "Malaria (RTSS)Vaccine") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "ycuc2y0fDXt",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "Pneumococcal conjugate vaccine") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "Sc7xJnZJ7gk",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "Rota virus vaccine") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "VDXUoBlY3F0",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "BCG") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "Z6wzg4Jiwyu",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "COVID -19 Vaccine- ASTRAZENECA") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "tJeWBrf6hAY",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "Bivalent oral Polio vaccine") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "UfqetIpenw0",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "COVID-19 Vaccine - PFIZER/BioNTech") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "Z7MLnbvSBcB",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "COVID-19 Vaccine-SPUTNIK V") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "rwlm0VJiIZP",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "COVID-19Vaccine-COVISHIELD") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "LAgjDEk2drU",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "COVID -19 Vaccine- MODERNA") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "FByR0P9oEWy",
                        "value" => $result[0]['cnt']
                    ];
                }

                if ($result['Vaccine']['vaccine_name'] == "COVID-19 Vaccine - (JOHNSON&JOHNSONS)JANSSEN") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "n0Uvcnd2Lz2",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "COVID -19 Vaccine- SINOPHARM") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "w2QHHuUGh0E",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "COVID -19 Vaccine- VAXZEVRIA") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "A2qFEzm6VRE",
                        "value" => $result[0]['cnt']
                    ];
                }

                if ($result['Vaccine']['vaccine_name'] == "Hepatitis B Vaccine") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "FVetBgxhuI1",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "Inactivated polio vaccine") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "M7feACojOHg",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "Rabies vaccine") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "IzksPENNmY3",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "Anti Snake venom") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "N5cgWeeUJgS",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "Tetanus Diptheria Vaccine") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "WlnLdfF9izd",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Vaccine']['vaccine_name'] == "Yellow fever virus vaccine") {
                    $dataValues[] = [
                        "dataElement" => "St9YPLSwhzr",
                        "categoryOptionCombo" => "zfVJtQ3Dlat",
                        "value" => $result[0]['cnt']
                    ];
                }
            }
            $orgUnit = $this->extract_organization_unit($this->request->data['Report']['county_id']);
            if (empty($orgUnit)) {
                $this->Session->setFlash(__('County Detials not updated, please sync data'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'khis', 'action' => 'index'));
            }

            $sadr_data_values = $this->prepare_upload_sadr();
            //add this sadr values to the main $dataValues to make one complete array 
            $dataValues = array_merge($dataValues, $sadr_data_values);



            $currentDate = date('Y-m-d');
            $payload = [
                "dataSet" => "khmkmn2RRx4",
                "completeDate" => $currentDate,
                "period" => $year . "" . $monthdata,
                "orgUnit" => $orgUnit,
                "dataValues" => $dataValues
            ];
        }


        // debug($payload);
        // exit;
        $apiUrl = Configure::read('khis_data_values_url');
        $username = Configure::read('khis_usename');
        $password =  Configure::read('khis_password');

        $ch = curl_init($apiUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        // curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload)); // Convert the payload to a query string
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json', // Set the content type 
        ));


        // Execute cURL session and get the response
        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = "";

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        if ($statusCode >= 200 && $statusCode < 300) {
            // $data = json_decode($response, true);
            $this->Session->setFlash(__('Integration Successful, data posted successfully'), 'alerts/flash_success');
            $this->redirect(array('controller' => 'khis', 'action' => 'index'));
        } else {
            $this->Session->setFlash(__('Experienced problems submitting data, please try again' . $error . '\n Response ' . $response), 'alerts/flash_error');
            $this->redirect($this->referer());
        }
    }

    public function prepare_age_group_data($criteria)
    {

        $case = "((case 
        when trim(age_months) in ('neonate', 'infant', 'child', 'adolescent', 'adult', 'elderly') then age_months
        when age_months > 0 and age_months < 1 then 'neonate'
        when age_months < 13 then 'infant'
        when age_months > 13 then 'child'
        when year(now()) - right(date_of_birth, 4) between 0 and 1 then 'infant'
        when year(now()) - right(date_of_birth, 4) between 1 and 10 then 'child'
        when year(now()) - right(date_of_birth, 4) between 18 and 65 then 'adult'
        when year(now()) - right(date_of_birth, 4) between 10 and 18 then 'adolescent'
        when year(now()) - right(date_of_birth, 4) between 65 and 155 then 'elderly'
        else 'unknown'
       end))";

        $age = $this->Aefi->find('all', array(
            'fields' => array($case . ' as ager', 'COUNT(*) as cnt'),
            'contain' => array(),
            'conditions' => $criteria,
            'group' => array($case),
            'having' => array('COUNT(*) >' => 0),
        ));

        foreach ($age as $key => $result) {
            if ($result[0]['ager'] == "elderly") {
                $dataValues[] = [
                    "dataElement" => "XWs9UY7rVqV",
                    "categoryOptionCombo" => "D1wVwZpCZxk",
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result[0]['ager'] == "adult") {
                $dataValues[] = [
                    "dataElement" => "XWs9UY7rVqV",
                    "categoryOptionCombo" => "YB86GwI0Xwk",
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result[0]['ager'] == "adolescent") {
                $dataValues[] = [
                    "dataElement" => "XWs9UY7rVqV",
                    "categoryOptionCombo" => "xp9OJZGm7S8",
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result[0]['ager'] == "child") {
                $dataValues[] = [
                    "dataElement" => "XWs9UY7rVqV",
                    "categoryOptionCombo" => "AjVBULP0Qz9",
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result[0]['ager'] == "infant") {
                $dataValues[] = [
                    "dataElement" => "XWs9UY7rVqV",
                    "categoryOptionCombo" => "uZJ1ke751Tr",
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result[0]['ager'] == "neonate") {
                $dataValues[] = [
                    "dataElement" => "XWs9UY7rVqV",
                    "categoryOptionCombo" => "GIn7lBrO466",
                    "value" => $result[0]['cnt']
                ];
            }
        }
    }

    public function prepare_upload_sadr()
    {
        //prepare SADR Data
        $dataValues = array();
        $criteria['Sadr.submitted'] = array(1, 2);
        $criteria['Sadr.copied !='] = '1';
        $criteria['Sadr.deleted'] = false;
        $criteria['Sadr.archived'] = false;
        if (!empty($this->request->data['Report']['start_date']) && !empty($this->request->data['Report']['end_date'])) {
            $month = $this->request->data['Report']['start_date'];
            $year = $this->request->data['Report']['end_date'];

            // Calculate the start and end dates for the given month and year
            $startDate = date('Y-m-01 00:00:00', strtotime("$year-$month"));
            $endDate = date('Y-m-t 23:59:59', strtotime("$year-$month"));
            $criteria['Sadr.reporter_date between ? and ?'] = array(date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate)));
        }

        if (empty($this->request->data['Report']['county_id'])) {
            $geo = $this->Sadr->find('all', array(
                'fields' => array('County.county_name', 'County.org_unit', 'COUNT(*) as cnt'),
                'contain' => array('County'),
                'conditions' => $criteria,
                'group' => array('County.county_name', 'County.org_unit', 'County.id'),
                'having' => array('COUNT(*) >' => 0),
            ));

            //loop through each county
            $gender_data_values = array();
            $age_data_values = array();
            $month_data_values = array();
            $period = $year . "" . $month;
            foreach ($geo as $single) {
                $org_unit = $single['County']['org_unit'];
                $criteria['Sadr.county_id'] = $single['County']['id'];
                $gender_data_values = array_merge($gender_data_values, $this->prepare_county_sadr_gender_data_values($criteria, $org_unit, $period));
                $age_data_values = array_merge($age_data_values, $this->prepare_county_sadr_age_data_values($criteria, $org_unit, $period));
                $month_data_values = array_merge($month_data_values, $this->prepare_county_sadr_month_data_values($criteria, $org_unit, $period));
            }
            $dataValues = array_merge($gender_data_values, $age_data_values, $month_data_values);


            return $dataValues;
        } else {
            $criteria['Sadr.county_id'] = $this->request->data['Report']['county_id'];

            // Sadr Gender
            $sadr_gender = $this->Sadr->find('all', array(
                'fields' => array('gender', 'COUNT(*) as cnt'),
                'contain' => array(),
                'recursive' => -1,
                'conditions' => $criteria,
                'group' => array('gender'),
                'having' => array('COUNT(*) >' => 0),
            ));
            foreach ($sadr_gender as $key => $result) {
                if ($result['Sadr']['gender'] == "Female") {

                    $dataValues[] = [
                        "dataElement" => "zhcRP1VMKRQ",
                        "categoryOptionCombo" => "HSgm52qfmu9",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result['Sadr']['gender'] == "Male") {

                    $dataValues[] = [
                        "dataElement" => "zhcRP1VMKRQ",
                        "categoryOptionCombo" => "HZqsa0U6ivP",
                        "value" => $result[0]['cnt']
                    ];
                }
            }
            // Sadr Age
            $case = "((case 
            when trim(age_group) in ('neonate', 'infant', 'child', 'adolescent', 'adult', 'elderly') then age_group
            when year(now()) - right(date_of_birth, 4) between 0 and 1 then 'infant'
            when year(now()) - right(date_of_birth, 4) between 1 and 10 then 'child'
            when year(now()) - right(date_of_birth, 4) between 18 and 65 then 'adult'
            when year(now()) - right(date_of_birth, 4) between 10 and 18 then 'adolescent'
            when year(now()) - right(date_of_birth, 4) between 65 and 155 then 'elderly'
            else 'unknown'
           end))";

            $sadr_age = $this->Sadr->find('all', array(
                'fields' => array($case . ' as ager', 'COUNT(*) as cnt'),
                'contain' => array(),
                'conditions' => $criteria,
                'group' => array($case),
                'having' => array('COUNT(*) >' => 0),
            ));
            foreach ($sadr_age as $key => $result) {
                if ($result[0]['ager'] == "elderly") {
                    $dataValues[] = [
                        "dataElement" => "WBTIuVZIHeV",
                        "categoryOptionCombo" => "D1wVwZpCZxk",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result[0]['ager'] == "adult") {
                    $dataValues[] = [
                        "dataElement" => "WBTIuVZIHeV",
                        "categoryOptionCombo" => "YB86GwI0Xwk",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result[0]['ager'] == "adolescent") {
                    $dataValues[] = [
                        "dataElement" => "WBTIuVZIHeV",
                        "categoryOptionCombo" => "xp9OJZGm7S8",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result[0]['ager'] == "child") {
                    $dataValues[] = [
                        "dataElement" => "WBTIuVZIHeV",
                        "categoryOptionCombo" => "AjVBULP0Qz9",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result[0]['ager'] == "infant") {
                    $dataValues[] = [
                        "dataElement" => "WBTIuVZIHeV",
                        "categoryOptionCombo" => "uZJ1ke751Tr",
                        "value" => $result[0]['cnt']
                    ];
                }
                if ($result[0]['ager'] == "neonate") {
                    $dataValues[] = [
                        "dataElement" => "WBTIuVZIHeV",
                        "categoryOptionCombo" => "GIn7lBrO466",
                        "value" => $result[0]['cnt']
                    ];
                }
            }
            // SADR Month
            $monthly = $this->Sadr->find('all', array(
                'fields' => array('DATE_FORMAT(reporter_date, "%b %Y")  as month', 'month(ifnull(reporter_date, reporter_date)) as salit', 'COUNT(*) as cnt'),
                'contain' => array(),
                'recursive' => -1,
                'conditions' => $criteria,
                'group' => array('DATE_FORMAT(reporter_date, "%b %Y")', 'salit'), // Include 'salit' in the GROUP BY clause
                'order' => array('salit'),
                'having' => array('COUNT(*) >' => 0),
            ));


            foreach ($monthly as $key => $value) {
                $dataValues[] = [
                    "dataElement" => "xesjTUtnEpH",
                    "categoryOptionCombo" => "NhSoXUMPK2K",
                    "value" => $value[0]['cnt']
                ];
            }
        }
        return $dataValues;
    }

    public function prepare_county_sadr_gender_data_values($criteria, $org_unit, $period)
    {
        $dataValues = array();
        $sadr_gender = $this->Sadr->find('all', array(
            'fields' => array('gender', 'COUNT(*) as cnt'),
            'contain' => array(),
            'recursive' => -1,
            'conditions' => $criteria,
            'group' => array('gender'),
            'having' => array('COUNT(*) >' => 0),
        ));
        foreach ($sadr_gender as $key => $result) {
            if ($result['Sadr']['gender'] == "Female") {

                $dataValues[] = [
                    "dataElement" => "zhcRP1VMKRQ",
                    "categoryOptionCombo" => "HSgm52qfmu9",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result['Sadr']['gender'] == "Male") {

                $dataValues[] = [
                    "dataElement" => "zhcRP1VMKRQ",
                    "categoryOptionCombo" => "HZqsa0U6ivP",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
        }

        return $dataValues;
    }
    public function prepare_county_sadr_age_data_values($criteria, $org_unit, $period)
    {
        $dataValues = array();
        $case = "((case 
        when trim(age_group) in ('neonate', 'infant', 'child', 'adolescent', 'adult', 'elderly') then age_group
        when year(now()) - right(date_of_birth, 4) between 0 and 1 then 'infant'
        when year(now()) - right(date_of_birth, 4) between 1 and 10 then 'child'
        when year(now()) - right(date_of_birth, 4) between 18 and 65 then 'adult'
        when year(now()) - right(date_of_birth, 4) between 10 and 18 then 'adolescent'
        when year(now()) - right(date_of_birth, 4) between 65 and 155 then 'elderly'
        else 'unknown'
       end))";

        $sadr_age = $this->Sadr->find('all', array(
            'fields' => array($case . ' as ager', 'COUNT(*) as cnt'),
            'contain' => array(),
            'conditions' => $criteria,
            'group' => array($case),
            'having' => array('COUNT(*) >' => 0),
        ));
        foreach ($sadr_age as $key => $result) {
            if ($result[0]['ager'] == "elderly") {
                $dataValues[] = [
                    "dataElement" => "WBTIuVZIHeV",
                    "categoryOptionCombo" => "D1wVwZpCZxk",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result[0]['ager'] == "adult") {
                $dataValues[] = [
                    "dataElement" => "WBTIuVZIHeV",
                    "categoryOptionCombo" => "YB86GwI0Xwk",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result[0]['ager'] == "adolescent") {
                $dataValues[] = [
                    "dataElement" => "WBTIuVZIHeV",
                    "categoryOptionCombo" => "xp9OJZGm7S8",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result[0]['ager'] == "child") {
                $dataValues[] = [
                    "dataElement" => "WBTIuVZIHeV",
                    "categoryOptionCombo" => "AjVBULP0Qz9",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result[0]['ager'] == "infant") {
                $dataValues[] = [
                    "dataElement" => "WBTIuVZIHeV",
                    "categoryOptionCombo" => "uZJ1ke751Tr",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
            if ($result[0]['ager'] == "neonate") {
                $dataValues[] = [
                    "dataElement" => "WBTIuVZIHeV",
                    "categoryOptionCombo" => "GIn7lBrO466",
                    "period" => $period,
                    "orgUnit" => $org_unit,
                    "value" => $result[0]['cnt']
                ];
            }
        }

        return $dataValues;
    }

    public function prepare_county_sadr_month_data_values($criteria, $org_unit, $period)
    {
        $dataValues = array();
        $monthly = $this->Sadr->find('all', array(
            'fields' => array('DATE_FORMAT(reporter_date, "%b %Y")  as month', 'month(ifnull(reporter_date, reporter_date)) as salit', 'COUNT(*) as cnt'),
            'contain' => array(),
            'recursive' => -1,
            'conditions' => $criteria,
            'group' => array('DATE_FORMAT(reporter_date, "%b %Y")', 'salit'), // Include 'salit' in the GROUP BY clause
            'order' => array('salit'),
            'having' => array('COUNT(*) >' => 0),
        ));


        foreach ($monthly as $key => $value) {
            $dataValues[] = [
                "dataElement" => "xesjTUtnEpH",
                "categoryOptionCombo" => "NhSoXUMPK2K",
                "period" => $period,
                "orgUnit" => $org_unit,
                "value" => $value[0]['cnt']
            ];
        }

        return $dataValues;
    }

    public function extract_organization_unit($id = null)
    {
        $this->loadModel('County');
        $this->County->id = $id;
        if (!$this->County->exists()) {
            throw new NotFoundException(__('Invalid County Information'));
        }
        $report = $this->County->read(null, $id);
        if ($report) {
            return $report['County']['org_unit'];
        } else {
            return null;
        }
    }
    public function extract_indicator_element($name, $aCount)
    {
        $indicator_value = array();
        $this->loadModel('Khis');
        $indicators = $this->Khis->find('all', array('order' => array('Khis.id' => 'ASC')));
        foreach ($indicators as $key => $value) {
            $id = $value;
            $elementId = $value['Khis']['elementId'];
            $elementName = $value['Khis']['elementName'];

            if (strpos($elementName, $name) !== false) {
                $indicator_value = [
                    "dataElement" => $elementId,
                    "value" => $aCount
                ];
            }
        }

        return $indicator_value;
    }

    public function generate_reports_per_vaccines($drug_name = null, $aefiIds = array())
    {
        # code...   add a check to return where AefiListOfVaccine.aefi_id  is in the list of array
        $cond = $this->Aefi->AefiListOfVaccine->find('list', array(
            'conditions' => array(
                'AefiListOfVaccine.vaccine_id' => $drug_name,
                'AefiListOfVaccine.aefi_id IS NOT NULL',
                'AefiListOfVaccine.aefi_id IN' => $aefiIds
            ),
            'fields' => array('aefi_id', 'aefi_id')
        ));
        return $cond;
    }

    public function sadrs_summary()
    {
        // debug($this->request->data);
        // exit;
        $criteria['Sadr.submitted'] = array(1, 2);
        $criteria['Sadr.copied !='] = '1';
        $criteria['Sadr.deleted'] = false;
        $criteria['Sadr.archived'] = false;

        if (!empty($this->request->data['Report']['start_date']) && !empty($this->request->data['Report']['end_date'])) {
            $month = $this->request->data['Report']['start_date'];
            $year = $this->request->data['Report']['end_date'];

            // Calculate the start and end dates for the given month and year
            $startDate = date('Y-m-01 00:00:00', strtotime("$year-$month"));
            $endDate = date('Y-m-t 23:59:59', strtotime("$year-$month"));
            // debug($startDate);
            // debug($endDate);
            // exit;
            $criteria['Sadr.reporter_date between ? and ?'] = array(date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate)));
        }
        if (!empty($this->request->data['Report']['county_id'])) {
            $criteria['Sadr.county_id'] = $this->request->data['Report']['county_id'];
        }

        $sadrsIds = $this->Sadr->find('list', array(
            'fields' => array('Sadr.id'),
            'conditions' => $criteria
        ));
        $sadrsIds = array_keys($sadrsIds);
        $id_arrays = array();

        $monthly = $this->Sadr->find('all', array(
            'fields' => array('DATE_FORMAT(reporter_date, "%b %Y")  as month', 'month(ifnull(reporter_date, reporter_date)) as salit', 'COUNT(*) as cnt'),
            'contain' => array(),
            'recursive' => -1,
            'conditions' => $criteria,
            'group' => array('DATE_FORMAT(reporter_date, "%b %Y")', 'salit'), // Include 'salit' in the GROUP BY clause
            'order' => array('salit'),
            'having' => array('COUNT(*) >' => 0),
        ));

        //get all the counties in the system without any relation
        $counties = $this->Sadr->County->find('list', array('order' => 'County.county_name ASC'));


        // Get All SADRs by Gender 
        $sadr_gender = $this->Sadr->find('all', array(
            'fields' => array('gender', 'COUNT(*) as cnt'),
            'contain' => array(),
            'recursive' => -1,
            'conditions' => $criteria,
            'group' => array('gender'),
            'having' => array('COUNT(*) >' => 0),
        ));


        // GET SUMMARY BY AGE GROUP
        $case = "((case 
                when trim(age_group) in ('neonate', 'infant', 'child', 'adolescent', 'adult', 'elderly') then age_group
                when year(now()) - right(date_of_birth, 4) between 0 and 1 then 'infant'
                when year(now()) - right(date_of_birth, 4) between 1 and 10 then 'child'
                when year(now()) - right(date_of_birth, 4) between 18 and 65 then 'adult'
                when year(now()) - right(date_of_birth, 4) between 10 and 18 then 'adolescent'
                when year(now()) - right(date_of_birth, 4) between 65 and 155 then 'elderly'
                else 'unknown'
               end))";

        $sadr_age = $this->Sadr->find('all', array(
            'fields' => array($case . ' as ager', 'COUNT(*) as cnt'),
            'contain' => array(),
            'conditions' => $criteria,
            'group' => array($case),
            'having' => array('COUNT(*) >' => 0),
        ));

        // SADRs per Year
        $year = $this->Sadr->find('all', array(
            'fields' => array('year(ifnull(created, created)) as year', 'COUNT(*) as cnt'),
            'contain' => array(),
            'recursive' => -1,
            'conditions' => $criteria,
            'group' => array('year(ifnull(created, created))'),
            'order' => array('year'),
            'having' => array('COUNT(*) >' => 0),
        ));

        $this->set(compact('sadr_gender'));
        $this->set(compact('sadr_age'));
        $this->set(compact('monthly'));
        $this->set(compact('year'));
        $this->set('_serialize', 'sadr_gender', 'sadr_age', 'monthly', 'year');
    }


    public function aefi_summary()
    {


        // Load Data for Counties 
        $id_arrays = array(0);
        $criteria['Aefi.submitted'] = array(1, 2);
        $criteria['Aefi.copied !='] = '1';
        $criteria['Aefi.deleted'] = false;
        $criteria['Aefi.archived'] = false;

        if (!empty($this->request->data['Report']['start_date']) && !empty($this->request->data['Report']['end_date'])) {
            $month = $this->request->data['Report']['start_date'];
            $year = $this->request->data['Report']['end_date'];

            // Calculate the start and end dates for the given month and year
            $startDate = date('Y-m-01 00:00:00', strtotime("$year-$month"));
            $endDate = date('Y-m-t 23:59:59', strtotime("$year-$month"));
            $criteria['Aefi.reporter_date between ? and ?'] = array(date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate)));
        }

        // Filters
        if (!empty($this->request->data['Report']['county_id'])) {
            $criteria['Aefi.county_id'] = $this->request->data['Report']['county_id'];
        }


        $aefiIds = $this->Aefi->find('list', array(
            'fields' => array('Aefi.id'),
            'conditions' => $criteria
        ));
        $aefiIds = array_keys($aefiIds);


        //get all the counties in the system without any relation
        $counties = $this->Aefi->County->find('list', array('order' => 'County.county_name ASC'));
        $geo = $this->Aefi->find('all', array(
            'fields' => array('County.county_name', 'COUNT(*) as cnt'),
            'contain' => array('County'),
            'conditions' => $criteria,
            'group' => array('County.county_name', 'County.id'),
            'having' => array('COUNT(*) >' => 0),
        ));

        // Get All AEFIs by Gender
        $sex = $this->Aefi->find('all', array(
            'fields' => array('gender', 'COUNT(*) as cnt'),
            'contain' => array(),
            'recursive' => -1,
            'conditions' => $criteria,
            'group' => array('gender'),
            'having' => array('COUNT(*) >' => 0),
        ));


        // GET SUMMARY BY AGE GROUP 


        $case = "((case 
        when trim(age_months) in ('neonate', 'infant', 'child', 'adolescent', 'adult', 'elderly') then age_months
        when age_months > 0 and age_months < 1 then 'neonate'
        when age_months < 13 then 'infant'
        when age_months > 13 then 'child'
        when year(now()) - right(date_of_birth, 4) between 0 and 1 then 'infant'
        when year(now()) - right(date_of_birth, 4) between 1 and 10 then 'child'
        when year(now()) - right(date_of_birth, 4) between 18 and 65 then 'adult'
        when year(now()) - right(date_of_birth, 4) between 10 and 18 then 'adolescent'
        when year(now()) - right(date_of_birth, 4) between 65 and 155 then 'elderly'
        else 'unknown'
       end))";
        //    debug($case);
        //    exit;

        $age = $this->Aefi->find('all', array(
            'fields' => array($case . ' as ager', 'COUNT(*) as cnt'),
            'contain' => array(),
            'conditions' => $criteria,
            'group' => array($case),
            'having' => array('COUNT(*) >' => 0),
        ));

        // SADRs per Year
        $year = $this->Aefi->find('all', array(
            'fields' => array('year(ifnull(created, created)) as year', 'COUNT(*) as cnt'),
            'contain' => array(),
            'recursive' => -1,
            'conditions' => $criteria,
            'group' => array('year(ifnull(created, created))'),
            'order' => array('year'),
            'having' => array('COUNT(*) >' => 0),
        ));

        $months = $this->Aefi->find('all', array(
            'fields' => array('DATE_FORMAT(reporter_date, "%b %Y")  as month', 'month(ifnull(reporter_date, reporter_date)) as salit', 'COUNT(*) as cnt'),
            'contain' => array(),
            'recursive' => -1,
            'conditions' => $criteria,
            'group' => array('DATE_FORMAT(reporter_date, "%b %Y")', 'salit'), // Include 'salit' in the GROUP BY clause
            'order' => array('salit'),
            'having' => array('COUNT(*) >' => 0),
        ));
        $conditions = array_merge($criteria, array('serious_yes IS NOT NULL'));

        $vaccines = $this->Aefi->AefiListOfVaccine->Vaccine->find('list');

        $vaccine = $this->Aefi->AefiListOfVaccine->find('all', array(
            'fields' => array(
                'Vaccine.vaccine_name as vaccine_name',
                'COUNT(distinct AefiListOfVaccine.aefi_id) as cnt'
            ),
            'joins' => array(
                array(
                    'table' => 'vaccines',
                    'alias' => 'Vaccine1',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'AefiListOfVaccine.vaccine_id = Vaccine.id'
                    )
                )
            ),
            'conditions' => array(
                'AefiListOfVaccine.aefi_id' => $aefiIds,
            ),
            'group' => array('Vaccine.vaccine_name'),
            'having' => array('COUNT(distinct AefiListOfVaccine.aefi_id) >' => 0),
        ));
        // foreach ($vaccine as $result) {
        //     debug($result['Vaccine']['vaccine_name']);
        // }

        $this->set(compact('vaccines'));
        $this->set(compact('counties'));
        $this->set(compact('sex'));
        $this->set(compact('age'));
        $this->set(compact('year'));
        $this->set(compact('vaccine'));
        $this->set(compact('months'));

        $this->set('_serialize', 'vaccines', 'vaccine', 'counties', 'sex', 'age', 'year', 'months');
    }
}
