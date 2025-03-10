<?php

App::uses('HttpSocket', 'Network/Http');
App::uses('String', 'Utility');
App::uses('Security', 'Utility');
App::uses('Sanitize', 'Utility');


class ProcessDataShell extends AppShell
{
    public $uses = array('Disproportionality', 'Aefi', 'Sadr','Padr'); // Load required models



    public function count_every_vaccine($vc)
    {
        $vc_name = $vc['Vaccine']['vaccine_name'];
        $cond = array(); // Initialize $cond with an empty array

        $subquery = $this->Aefi->AefiListOfVaccine->Vaccine->find('list', array(
            'conditions' => array(
                'Vaccine.vaccine_name LIKE' => '%' . $vc_name . '%',
            ),
            'fields' => array('id'),
            'recursive' => -1 // To avoid unnecessary recursive queries
        ));

        if ($subquery) {
            $cond = $this->Aefi->AefiListOfVaccine->find('list', array(
                'conditions' => array(
                    'AefiListOfVaccine.vaccine_id IN' => $subquery,
                    'AefiListOfVaccine.aefi_id IS NOT NULL' // Exclude null values

                ),
                'keyField' => 'aefi_id',
                'valueField' => 'aefi_id'
            ));
        }

        return $cond;
    }

    public function count_specific_reaction($data, $reactionName)
    {
        // Iterate through the data to count the occurrences of the reaction
        $reactionCount = 0;
        foreach ($data as $vaccine) {
            foreach ($vaccine['reports'] as $report) {
                foreach ($report['reactions'] as $reaction) {
                    if (stripos($reaction, $reactionName) !== false) {
                        $reactionCount++;
                    }
                }
            }
        }
        return $reactionCount;
    }

    public function count_specific_drug_reaction($vaccine, $reactionName)
    {
        $reactionCount = 0;
        foreach ($vaccine['reports'] as $report) {
            foreach ($report['reactions'] as $reaction) {
                if (stripos($reaction, $reactionName) !== false) {
                    $reactionCount++;
                }
            }
        }
        return $reactionCount;
    }
    public function main()
    {
        $this->out('🔄 Background processing started...');

        $params = isset($this->args[0]) ? json_decode($this->args[0], true) : [];


        if (!is_array($params)) {
            $this->logMessage("❌ Invalid parameters. Expected a JSON-encoded array.");
            return;
        }

        $this->logMessage("📦 Raw args: " . print_r($this->args, true));
        $this->logMessage("✅ Processing started with parameters: " . json_encode($params));
        $isSadr = isset($params['sadr']) ? $params['sadr'] : false;
        $isPublic = isset($params['public']) ? $params['public'] : false;
        if ($isSadr) {
            $this->logMessage("📦 Processing SADR data...");
            $this->sadr_startup($params);
        }else if($isPublic){
            $this->logMessage("📦 Processing SADR data...");
            $this->public_startup($params);
        }
         else {
            
            $this->logMessage("📦 Processing AEFI data...");
            $this->aefi_startup($params);

            $this->aefi_startup($params);
        }
        $this->logMessage("✅ Finished processing.");
    }


    public function generate_reaction_list($cm)
    {
        // Get unique reactions 
        $uniqueRecords = $this->Sadr->SadrReaction->find('all', array(
            'fields' => array('DISTINCT SadrReaction.reaction'),
            'conditions' => array(
                'SadrReaction.reaction IS NOT NULL',
                'SadrReaction.reaction !=' => ''
            )
        ));

        $reactionNames = array();

        // Extract the reaction names from the result set
        foreach ($uniqueRecords as $record) {
            $reactionNames[] = rtrim(trim($record['SadrReaction']['reaction'], ', '));
        }

        // Now add the parent reaction

        $sadrs = $this->Sadr->find('all', array(
            'fields' => array('DISTINCT Sadr.reaction'),
            'conditions' => array('Sadr.id' => $cm),
            'contain' => array(),

        ));

        $originalReactionNames = array();

        // Extract the reaction names from the result set
        foreach ($sadrs as $record) {
            $originalReactionNames[] = rtrim(trim($record['Sadr']['reaction'], ', '));
        }
        $mergedReactionNames = array_unique(array_merge($reactionNames, $originalReactionNames));

        return $mergedReactionNames;
    }
    public function get_sadr_reports_with_reaction($reactionName)
    {
        // Get reports with the reaction
        $cond = $this->Sadr->find('list', array(
            'conditions' => array(
                'Sadr.reaction' => $reactionName
                // 'LOWER(Sadr.reaction) LIKE' => '%' . strtolower($reactionName) . '%',
            ),
            'fields' => array('id', 'id')
        ));

        // also checkout the reactions incase of multiples
        $condothers = $this->Sadr->SadrReaction->find('list', array(
            'conditions' => array(
                'SadrReaction.reaction' => $reactionName
                // 'LOWER(SadrReaction.reaction) LIKE' => '%' . strtolower($reactionName) . '%',
            ),
            'fields' => array('sadr_id', 'sadr_id')
        ));
        $mergedReports = array_unique(array_merge($cond, $condothers));

        return $mergedReports;
    }

    public function get_reactions_caused_by_suspected_drug($current_drug_name, $sadrsIds)
    {

        $cond = array(); // Initialize $cond with an empty array

        $cond = $this->Sadr->SadrListOfDrug->find('list', array(
            'conditions' => array(
                'SadrListOfDrug.drug_name LIKE' => '%' . $current_drug_name . '%',
                'SadrListOfDrug.sadr_id IN' => $sadrsIds,
                'SadrListOfDrug.sadr_id IS NOT NULL'
            ),
            'keyField' => 'sadr_id',
            'valueField' => 'sadr_id'
        ));


        return $cond;
    }
    public function get_sadr_reports_with_drug_and_reaction($reactionName, $current_drug_name, $sadrsIds)
    {
        $reports_with_reaction = $this->get_sadr_reports_with_reaction($reactionName);

        $reports_with_drug = $this->get_reactions_caused_by_suspected_drug($current_drug_name, $sadrsIds);

        $commonReports = array_intersect($reports_with_reaction, $reports_with_drug);

        return $commonReports;
    }
    public function sadr_startup($params)
    {
        $sadrsIds = isset($params['sadrsIds']) ? $params['sadrsIds'] : [];
        $criteria = isset($params['criteria']) ? $params['criteria'] : [];
        $suspected = isset($params['suspected']) ? $params['suspected'] : [];
        $reactionLists = $this->generate_reaction_list($sadrsIds);
        $total_report_count = count($sadrsIds);

        $this->loadModel('Disproportionality');
        $this->Disproportionality->query('TRUNCATE TABLE disproportionalities;');
        foreach ($suspected as $vc) {
            $current_drug_name = $vc['SadrListOfDrug']['drug_name'];
            $drug_related_reports = $vc[0]['cnt'];
            $reactionDetails = [];
            foreach ($reactionLists as $reactionName) {

                $reactionCount = count($this->get_sadr_reports_with_reaction($reactionName));
                $drugReactionCount = count($this->get_sadr_reports_with_drug_and_reaction($reactionName, $current_drug_name, $sadrsIds));
                $expected_count_raw = ($drug_related_reports * $reactionCount) / $total_report_count;
                $expected_count = round($expected_count_raw, 5);

                $numerator = $drugReactionCount + 0.5;
                $denominator = $expected_count + 0.5;
                $calculated_data = $numerator / $denominator;

                $calculated_log_data_raw = log($calculated_data, 2);
                $calculated_log_data = round($calculated_log_data_raw, 5);


                $variance_of_ic_raw = 1 / ($numerator) + 1 / ($drug_related_reports - $drugReactionCount + 0.5) + 1 / ($reactionCount - $drugReactionCount + 0.5) + 1 / ($total_report_count - $drug_related_reports - $reactionCount + $drugReactionCount + 0.5);

                $variance_of_ic = round($variance_of_ic_raw, 5);

                $standard_error = sqrt($variance_of_ic);

                $lower_bound = $calculated_log_data - 1.96 * $standard_error;

                $reactionDetails[] = array(
                    'B_reports_with_reaction' => $reactionCount,
                    'AB_reports_with_drug_and_reaction' => $drugReactionCount,
                    'reaction_at_hand' => $reactionName,
                    'E_(AB)_expected_count' => $expected_count,
                    'IC_raw_calculated_data' => $calculated_data,
                    'IC_raw_calculated_log_data' => $calculated_log_data,
                    'Var(IC)_Variance_of_IC' => $variance_of_ic,
                    'Standard_Error_(SE)_of_IC' => $standard_error,
                    '95%_Confidence_Interval' => $lower_bound
                );
            }

            $inputData[] = array(
                'current_drug_name' => $current_drug_name,
                'N_total_reports' => $total_report_count,
                'A_reports_with_drug' => $drug_related_reports,
                'reactionDetails' => $reactionDetails
            );
        }
        $this->loadModel('Disproportionality');
        $total = $total_report_count;
        foreach ($inputData as $dt) {

            foreach ($dt['reactionDetails'] as $kk) {
                // debug($dt);
                // exit;
                $drug_name = $dt['current_drug_name'];
                $reaction_name = $kk['reaction_at_hand'];
                $b_reports = $kk['B_reports_with_reaction'];
                $ab_reports = $kk['AB_reports_with_drug_and_reaction'];
                $eab_expected = $kk['E_(AB)_expected_count'];
                $ic_raw_data = $kk['IC_raw_calculated_data'];
                $ic_calculated_data = $kk['IC_raw_calculated_log_data'];
                $ic_variance = $kk['Var(IC)_Variance_of_IC'];
                $standard_error = $kk['Standard_Error_(SE)_of_IC'];
                $confidence_interval = $kk['95%_Confidence_Interval'];
                $data = array(
                    'Disproportionality' => array(
                        'total' => $total,
                        'drug_name' => $drug_name,
                        'reaction_name' => $reaction_name,
                        'model' => 'Sadr',
                        'b_reports' => $b_reports,
                        'ab_reports' => $ab_reports,
                        'eab_expected' => $eab_expected,
                        'ic_raw_data' => $ic_raw_data,
                        'ic_calculated_data' => $ic_calculated_data,
                        'ic_variance' => $ic_variance,
                        'standard_error' => $standard_error,
                        'confidence_interval' => $confidence_interval
                    )
                );
                // check if the drug and reaction exists, ignore else create
                $existing = $this->Disproportionality->find('first', array(
                    'conditions' => array('Disproportionality.drug_name' => $drug_name, 'Disproportionality.reaction_name' => $reaction_name)
                ));
                if ($existing) {
                    $data['Disproportionality']['id'] = $existing['Disproportionality']['id'];
                }
                $this->Disproportionality->create();
                $this->Disproportionality->save($data);
            }
        }
    }

    public function padr_reports_with_reaction($reportReactions, $reaction)
    {
        $reactions = Hash::extract($reportReactions, '{n}.reactions.{n}');
        $reactionCount = 0;
        foreach ($reactions as $r) {
            if ($r === $reaction) {
                $reactionCount++;
            }
        }
        // Return the count
        return $reactionCount;
    }
    public function padr_reports_with_drug($reportReactions, $med)
    {
        $data = Hash::extract($reportReactions, '{n}.medicine_names.{n}');
        $count = 0;
        foreach ($data as $r) {
            if ($r === $med) {
                $count++;
            }
        }
        // Return the count
        return $count;
    }
    public function padr_reports_with_drug_and_reaction($totalReports, $medication, $reaction)
    {
        $medicines = Hash::extract($totalReports, '{n}.medicine_names.{n}');
        $reactions = Hash::extract($totalReports, '{n}.reactions.{n}');
        $count = 0;
        foreach ($totalReports as $report) {
            // Check if the report contains the specified medication
            if (in_array($medication, $report['medicine_names'])) {
                // Check if the report contains the specified reaction
                if (in_array($reaction, $report['reactions'])) {
                    // Increment the count if both conditions are met
                    $count++;
                }
            }
        }
        return $count;
    }
    public function public_startup($params)
    {
        $reportReaction = array();
        $reportIds = isset($params['reportIds']) ? $params['reportIds'] : [];
        $criteria = isset($params['criteria']) ? $params['criteria'] : [];
        foreach ($reportIds as $id) {
            $reactions = [];
            $report = $this->Padr->find('first', array(
                'conditions' => array('Padr.id' => $id),
                'contain' => array('PadrListOfMedicine'),

            ));
            $report = Sanitize::clean($report, array('escape' => true));

            if ($report['Padr']['sadr_vomiting'] == '1') {
                $reactions[] = "Vomiting or diarrhoea";
            }
            if ($report['Padr']['sadr_dizziness'] == '1') {
                $reactions[] = "Dizziness or drowsiness";
            }
            if ($report['Padr']['sadr_headache'] == '1') {
                $reactions[] = "Headache";
            }
            if ($report['Padr']['sadr_joints'] == '1') {
                $reactions[] = "Joints and muscle pain";
            }
            if ($report['Padr']['sadr_rash'] == '1') {
                $reactions[] = "Rash, itching, swelling on skin";
            }
            if ($report['Padr']['sadr_mouth'] == '1') {
                $reactions[] = "Pain or bleeding in the mouth";
            }
            if ($report['Padr']['sadr_stomach'] == '1') {
                $reactions[] = "Pain in the stomach";
            }
            if ($report['Padr']['sadr_urination'] == '1') {
                $reactions[] = "Abnormal changes with urination";
            }
            if ($report['Padr']['sadr_eyes'] == '1') {
                $reactions[] = "Red, painful eyes";
            }
            $reactions = array_unique($reactions);
            $medicine = Hash::extract($report['PadrListOfMedicine'], '{n}.product_name');

            $reportReaction[] = array(
                'id' => $id,
                'medicine_names' => $medicine,
                'reactions' => $reactions
            );
        }
        $this->loadModel('Disproportionality');
        $inputData = [];
        $total_report_count = count($reportReaction);
        $reactionNames = [];
        $allMedicineNames = [];
        foreach ($reportReaction as $repo) {
            foreach ($repo['medicine_names'] as $med) {
                if (!empty($med)) {
                    $allMedicineNames[] = $med;
                }
            }
            foreach ($repo['reactions'] as $rec) {
                if (!empty($rec)) {
                    $reactionNames[] = $rec;
                }
            }
        }
        $allMedicineNames = array_unique($allMedicineNames);
        $reactionNames = array_unique($reactionNames);

        foreach ($allMedicineNames as $medi) {
            $reactionDetails = [];
            $drug_related_reports = $this->padr_reports_with_drug($reportReaction, $med);
            foreach ($reactionNames as $reaction) {

                $data = array(
                    'Disproportionality' => array(
                        'drug_name' => $medi,
                        'reaction_name' => $reaction,
                        'model' => 'Padr'
                    )
                );
                // check if the drug and reaction exists, ignore else create
                $existing = $this->Disproportionality->find('first', array(
                    'conditions' => array('Disproportionality.drug_name' => $medi, 'Disproportionality.reaction_name' => $reaction)
                ));
                if (!$existing) {
                    $this->Disproportionality->create();
                    $this->Disproportionality->save($data);
                }

                $reactionCount = $this->padr_reports_with_reaction($reportReaction, $reaction);
                $drugReactionCount = $this->padr_reports_with_drug_and_reaction($reportReaction, $medi, $reaction);

                $expected_count_raw = ($drug_related_reports * $reactionCount) / $total_report_count;
                $expected_count = round($expected_count_raw, 5);

                $numerator = $drugReactionCount + 0.5;
                $denominator = $expected_count + 0.5;
                $calculated_data = $numerator / $denominator;

                $calculated_log_data_raw = log($calculated_data, 2);
                $calculated_log_data = round($calculated_log_data_raw, 5);

                $variance_of_ic_raw = 1 / ($numerator) + 1 / ($drug_related_reports - $drugReactionCount + 0.5) + 1 / ($reactionCount - $drugReactionCount + 0.5) + 1 / ($total_report_count - $drug_related_reports - $reactionCount + $drugReactionCount + 0.5);

                $variance_of_ic = round($variance_of_ic_raw, 5);
                $standard_error = 0;
                if ($variance_of_ic >= 0) {
                    $standard_error = sqrt($variance_of_ic);
                }
                $lower_bound = $calculated_log_data - 1.96 * $standard_error;

                $reactionDetails[] = array(

                    'B_reports_with_reaction' => $reactionCount,
                    'AB_reports_with_drug_and_reaction' => $drugReactionCount,
                    'reaction_at_hand' => $reaction,
                    'E_(AB)_expected_count' => $expected_count,
                    'IC_raw_calculated_data' => $calculated_data,
                    'IC_raw_calculated_log_data' => $calculated_log_data,
                    'Var(IC)_Variance_of_IC' => $variance_of_ic,
                    'Standard_Error_(SE)_of_IC' => $standard_error,
                    '95%_Confidence_Interval' => $lower_bound
                );
            }

            // foreach
            $inputData[] = array(
                'current_drug_name' => $medi,
                'N_total_reports' => $total_report_count,
                'A_reports_with_drug' => $drug_related_reports,
                'reactionDetails' => $reactionDetails
            );
        }

        $this->loadModel('Disproportionality');
        $this->Disproportionality->query('TRUNCATE TABLE disproportionalities;');
        $total = $total_report_count;
        foreach ($inputData as $dt) {

            foreach ($dt['reactionDetails'] as $kk) {
                // debug($dt);
                // exit;
                $drug_name = $dt['current_drug_name'];
                $reaction_name = $kk['reaction_at_hand'];
                $b_reports = $kk['B_reports_with_reaction'];
                $ab_reports = $kk['AB_reports_with_drug_and_reaction'];
                $eab_expected = $kk['E_(AB)_expected_count'];
                $ic_raw_data = $kk['IC_raw_calculated_data'];
                $ic_calculated_data = $kk['IC_raw_calculated_log_data'];
                $ic_variance = $kk['Var(IC)_Variance_of_IC'];
                $standard_error = $kk['Standard_Error_(SE)_of_IC'];
                $confidence_interval = $kk['95%_Confidence_Interval'];
                $data = array(
                    'Disproportionality' => array(
                        'total' => $total,
                        'drug_name' => $drug_name,
                        'reaction_name' => $reaction_name,
                        'model' => 'Public',
                        'b_reports' => $b_reports,
                        'ab_reports' => $ab_reports,
                        'eab_expected' => $eab_expected,
                        'ic_raw_data' => $ic_raw_data,
                        'ic_calculated_data' => $ic_calculated_data,
                        'ic_variance' => $ic_variance,
                        'standard_error' => $standard_error,
                        'confidence_interval' => $confidence_interval
                    )
                );
                // check if the drug and reaction exists, ignore else create
                $existing = $this->Disproportionality->find('first', array(
                    'conditions' => array('Disproportionality.drug_name' => $drug_name, 'Disproportionality.reaction_name' => $reaction_name)
                ));
                if ($existing) {
                    $data['Disproportionality']['id'] = $existing['Disproportionality']['id'];
                }
                $this->Disproportionality->create();
                $this->Disproportionality->save($data);
            }
        }
    }
    public function aefi_startup($params)
    {
        $aefiIds = isset($params['aefiIds']) ? $params['aefiIds'] : [];
        $criteria = isset($params['criteria']) ? $params['criteria'] : [];

        $vaccine = $this->Aefi->AefiListOfVaccine->find('all', array(
            'fields' => array(
                'Vaccine.vaccine_name as vaccine_name',
                'COUNT(distinct AefiListOfVaccine.aefi_id) as cnt'
            ),
            'contain' => array('Vaccine'), // Include the Vaccine model to access vaccine_name
            'conditions' => array(
                'AefiListOfVaccine.aefi_id' => $aefiIds,
                'AefiListOfVaccine.vaccine_name IS NOT NULL',
            ),
            'group' => array('Vaccine.vaccine_name', 'Vaccine.id'),
            'having' => array('COUNT(distinct AefiListOfVaccine.aefi_id) >' => 0),
        ));
        //loop through to get specific report:

        $data = [];
        foreach ($vaccine as $vc) {
            if (!is_null($vc['Vaccine']['vaccine_name'])) {

                $cond = $this->count_every_vaccine($vc);
                // Find the intersection of the two arrays
                $commonElements = array_intersect($cond, $aefiIds);
                $reports = [];
                foreach ($commonElements as $key => $cm) {
                    $reactions = [];
                    $aefi = $this->Aefi->find('first', array(
                        'conditions' => array('Aefi.id' => $cm),
                        'contain' => array('AefiDescription', 'AefiListOfVaccine.Vaccine'),

                    ));
                    $aefi = Sanitize::clean($aefi, array('escape' => true));

                    if ($aefi['Aefi']['bcg'] == '1') {
                        $reactions[] = "BCG Lymphadenitis";
                    }
                    if ($aefi['Aefi']['convulsion'] == '1') {
                        $reactions[] = "Convulsion";
                    }
                    if ($aefi['Aefi']['urticaria'] == '1') {
                        $reactions[] = "Generalized urticaria (hives)";
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
                    $reactions[] = $aefi['Aefi']['aefi_symptoms'];

                    // added reactions

                    $multiple = $aefi['AefiDescription'];
                    if (!empty($multiple)) {
                        foreach ($multiple as $other) {
                            $reactions[] = $other['description'];
                        }
                    }
                    $reports[] = array(
                        'aefi_id' => $cm,
                        'reactions' => $reactions
                    );
                }
                $data[] = array(
                    'total_reports' => count($aefiIds),
                    'name' => $vc['Vaccine']['vaccine_name'],
                    'drug_reports' => count($reports),
                    'reports' => $reports
                );
            }
        }

        $config['analytics'] = [
            'anaphylaxis' => 'Anaphylaxis',
            'bcg' => 'BCG Lymphadenitis',
            'convulsion' => 'Convulsion',
            'urticaria' => 'Generalized urticaria (hives)',
            'fever' => 'High Fever',
            'abscess' => 'Injection site abscess',
            'local_reaction' => 'Severe Local Reaction',
            'meningitis' => 'Encephalopathy, Encephalitis/Meningitis',
            'paralysis' => 'Paralysis',
            'toxic_shock' => 'Toxic shock'
        ];

        $reactionLists = array_values($config['analytics']);

        $total_report_count = count($aefiIds);  // N
        $inputData = [];
        foreach ($data as $dt) {
            // Initialize count
            $current_drug_name =  $dt['name'];
            $drug_related_reports = count($dt['reports']); //A

            $reactionDetails = [];
            foreach ($reactionLists as $reactionName) {
                $reactionCount = $this->count_specific_reaction($data, $reactionName); // B
                $drugReactionCount = $this->count_specific_drug_reaction($dt, $reactionName); //AB

                // Calculating Expected Counts
                // $expected_count = (($drug_related_reports +  $drugReactionCount) * ($reactionCount + $drugReactionCount)) / $total_report_count;
                $expected_count_raw = ($drug_related_reports * $reactionCount) / $total_report_count;
                $expected_count = round($expected_count_raw, 5);

                $numerator = $drugReactionCount + 0.5;
                $denominator = $expected_count + 0.5;
                $calculated_data = $numerator / $denominator;

                // Observed vs. Expected -> IC (Information Component):

                $calculated_log_data_raw = log($calculated_data, 2);
                $calculated_log_data = round($calculated_log_data_raw, 5);


                $variance_of_ic_raw = 1 / ($numerator) + 1 / ($drug_related_reports - $drugReactionCount + 0.5) + 1 / ($reactionCount - $drugReactionCount + 0.5) + 1 / ($total_report_count - $drug_related_reports - $reactionCount + $drugReactionCount + 0.5);

                $variance_of_ic = round($variance_of_ic_raw, 5);

                $standard_error = sqrt($variance_of_ic);

                $lower_bound = $calculated_log_data - 1.96 * $standard_error;

                $reactionDetails[] = array(

                    'B_reports_with_reaction' => $reactionCount,
                    'AB_reports_with_drug_and_reaction' => $drugReactionCount,
                    'reaction_at_hand' => $reactionName,
                    'E_(AB)_expected_count' => $expected_count,
                    'IC_raw_calculated_data' => $calculated_data,
                    'IC_raw_calculated_log_data' => $calculated_log_data,
                    'Var(IC)_Variance_of_IC' => $variance_of_ic,
                    'Standard_Error_(SE)_of_IC' => $standard_error,
                    '95%_Confidence_Interval' => $lower_bound
                );
            }

            $inputData[] = array(
                'current_drug_name' => $current_drug_name,
                'N_total_reports' => $total_report_count,
                'A_reports_with_drug' => $drug_related_reports,
                'reactionDetails' => $reactionDetails
            );
        }

        $total = $total_report_count;

        $this->loadModel('Disproportionality');
        $this->Disproportionality->query('TRUNCATE TABLE disproportionalities;');


        foreach ($inputData as $dt) {

            foreach ($dt['reactionDetails'] as $kk) {
                // debug($dt);
                // exit;
                $drug_name = $dt['current_drug_name'];
                $reaction_name = $kk['reaction_at_hand'];
                $b_reports = $kk['B_reports_with_reaction'];
                $ab_reports = $kk['AB_reports_with_drug_and_reaction'];
                $eab_expected = $kk['E_(AB)_expected_count'];
                $ic_raw_data = $kk['IC_raw_calculated_data'];
                $ic_calculated_data = $kk['IC_raw_calculated_log_data'];
                $ic_variance = $kk['Var(IC)_Variance_of_IC'];
                $standard_error = $kk['Standard_Error_(SE)_of_IC'];
                $confidence_interval = $kk['95%_Confidence_Interval'];
                $data = array(
                    'Disproportionality' => array(
                        'total' => $total,
                        'drug_name' => $drug_name,
                        'reaction_name' => $reaction_name,
                        'model' => 'Aefi',
                        'b_reports' => $b_reports,
                        'ab_reports' => $ab_reports,
                        'eab_expected' => $eab_expected,
                        'ic_raw_data' => $ic_raw_data,
                        'ic_calculated_data' => $ic_calculated_data,
                        'ic_variance' => $ic_variance,
                        'standard_error' => $standard_error,
                        'confidence_interval' => $confidence_interval
                    )
                );
                // check if the drug and reaction exists, ignore else create
                $existing = $this->Disproportionality->find('first', array(
                    'conditions' => array('Disproportionality.drug_name' => $drug_name, 'Disproportionality.reaction_name' => $reaction_name)
                ));
                if ($existing) {
                    $data['Disproportionality']['id'] = $existing['Disproportionality']['id'];
                }
                $this->Disproportionality->create();
                $this->Disproportionality->save($data);
            }
        }
    }
    private function logMessage($message)
    {
        $logFile = APP . 'tmp/logs/process_data.log'; // Full path

        if (!file_put_contents($logFile, date('Y-m-d H:i:s') . " - $message\n", FILE_APPEND | LOCK_EX)) {
            $this->out("❌ Failed to write to log file: $logFile");
        } else {
            $this->out("✅ Logged message: $message");
        }
    }
}
