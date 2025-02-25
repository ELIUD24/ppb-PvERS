<?php

App::uses('HttpSocket', 'Network/Http');
App::uses('String', 'Utility');
App::uses('Security', 'Utility');
App::uses('Sanitize', 'Utility');


class ProcessDataShell extends AppShell
{
    public $uses = array('Disproportionality', 'Aefi'); // Load required models



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

        $this->logMessage("✅ Finished processing.");
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
