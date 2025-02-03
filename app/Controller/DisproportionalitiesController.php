<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeText', 'Utility');
App::uses('ThemeView', 'View');
App::uses('HtmlHelper', 'View/Helper');
App::uses('Router', 'Routing');
/**
 * Disproportionalities Controller
 */
class DisproportionalitiesController extends AppController
{

    /**
     * Scaffold
     *  @property Disproportionality $Disproportionality
     *
     * @var mixed 
     */
    public $scaffold;

    public $components = array(
        'Search.Prg',
    );
    public $paginate = array();
    public $presetVars = true;
    public $page_options = array('25' => '25', '50' => '50', '100' => '100');

    public function get_total_approved_aefis()
    {
        $this->loadModel('Aefi');
        $criteria['Aefi.copied !='] = '1';
        $criteria['Aefi.submitted'] = array(2, 3);
        $criteria['Aefi.deleted'] = false;
        $criteria['Aefi.archived'] = false;

        $count = $this->Aefi->find(
            'count',
            array(
                'conditions' => $criteria
            )
        );
        return $count;
    }
    public function get_total_approved_sadrs()
    {
        $this->loadModel('Sadr');
        $criteria['Sadr.copied !='] = '1';
        $criteria['Sadr.submitted'] = array(2, 3);
        $criteria['Sadr.deleted'] = false;
        $criteria['Sadr.archived'] = false;

        $count = $this->Sadr->find(
            'count',
            array(
                'conditions' => $criteria
            )
        );
        return $count;
    }

    public function  get_total_approved_padrs()
    {
        $this->loadModel('Padr');
        $criteria['Padr.copied !='] = '1';
        $criteria['Padr.deleted'] = false;
        $criteria['Padr.archived'] = false;
        $criteria['Padr.report_sadr'] = 'Adverse Reaction';

        $count = $this->Padr->find(
            'count',
            array(
                'conditions' => $criteria
            )
        );
        return $count;
    }
    public function  get_total_approved_transfusions()
    {
        $this->loadModel('Transfusion');
        $criteria['Transfusion.copied !='] = '1';
        $criteria['Transfusion.deleted'] = false;
        $criteria['Transfusion.archived'] = false;

        $count = $this->Transfusion->find(
            'count',
            array(
                'conditions' => $criteria
            )
        );
        return $count;
    }
    public function  get_total_approved_medications()
    {
        $this->loadModel('Medication');
        $criteria['Medication.copied !='] = '1';
        $criteria['Medication.deleted'] = false;
        $criteria['Medication.archived'] = false;
        $criteria['Medication.reach_patient']="Yes";

        $count = $this->Medication->find(
            'count',
            array(
                'conditions' => $criteria
            )
        );
        return $count;
    }

    public function get_sadr_reports_with_reaction($reaction_name)
    {
        $criteria['Sadr.copied !='] = '1';
        $criteria['Sadr.submitted'] = array(2, 3);
        $criteria['Sadr.deleted'] = false;
        $criteria['Sadr.archived'] = false;
        $criteria['Sadr.reaction'] = $reaction_name;

        $this->loadModel('Sadr');
        $cond = $this->Sadr->find('list', array(
            'conditions' => $criteria,
            'fields' => array('id', 'id')
        ));

        // also checkout the reactions incase of multiples
        $condothers = $this->Sadr->SadrReaction->find('list', array(
            'conditions' => array(
                'SadrReaction.reaction' => $reaction_name
            ),
            'fields' => array('sadr_id', 'sadr_id')
        ));
        $mergedReports = array_unique(array_merge($cond, $condothers));

        return $mergedReports;
    }

    public function get_sadr_reports_with_drug($current_drug_name)
    {
        $criteria['Sadr.copied !='] = '1';
        $criteria['Sadr.submitted'] = array(2, 3);
        $criteria['Sadr.deleted'] = false;
        $criteria['Sadr.archived'] = false;

        $this->loadModel('Sadr');
        $this->loadModel('SadrListOfDrug');


        $sadrsIds = $this->Sadr->find('list', array(
            'fields' => array('Sadr.id'),
            'conditions' => $criteria
        ));
        $sadrsIds = array_keys($sadrsIds);

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

    public function get_sadr_reports_with_drug_and_reaction($drug_name, $reaction_name)

    {
        $reports_with_reaction = $this->get_sadr_reports_with_reaction($reaction_name);

        $reports_with_drug = $this->get_sadr_reports_with_drug($drug_name);

        $commonReports = array_intersect($reports_with_reaction, $reports_with_drug);

        return $commonReports;
    }


    public function get_aefi_reports()
    {
        $this->loadModel('Aefi');
        $criteria['Aefi.submitted'] = array(1, 2);
        $criteria['Aefi.copied !='] = '1';
        $criteria['Aefi.deleted'] = false;
        $criteria['Aefi.archived'] = false;
        $reportIds = $this->Aefi->find('list', array(
            'fields' => array('Aefi.id'),
            'conditions' => $criteria
        ));
        $reportIds = array_keys($reportIds);
        $reportReaction = array();
        $reactionLists = Configure::read('analytics');
        foreach ($reportIds as $id) {
            $reactions = [];
            $report = $this->Aefi->find('first', array(
                'conditions' => array('Aefi.id' => $id),
                'contain' => array('AefiDescription', 'AefiListOfVaccine.Vaccine'),

            ));
            $aefi = Sanitize::clean($report, array('escape' => true));

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
            $multiple = $aefi['AefiDescription'];
            if (!empty($multiple)) {
                foreach ($multiple as $other) {
                    $reactions[] = $other['description'];
                }
            }
            $reactions = array_intersect($reactionLists, $reactions);
            $medicine = Hash::extract($aefi['AefiListOfVaccine'], '{n}.Vaccine.vaccine_name');
            $reactions = array_values($reactions);
            $reactions = array_unique($reactions);
            $reportReaction[] = array(
                'aefi_id' => $id,
                'medicine' => $medicine,
                'reactions' => $reactions
            );
        }
        return $reportReaction;
    }
    public function count_aefis_with_reaction($reportReactions, $reaction_name)
    {
        $reactions = Hash::extract($reportReactions, '{n}.reactions.{n}');

        $reactionCount = 0;
        foreach ($reactions as $r) {
            if ($r === $reaction_name) {
                $reactionCount++;
            }
        }
        return $reactionCount;
    }


    public function count_aefis_with_drug($reportReactions, $drug_name)
    {
        //     debug($reportReactions);
        //     exit;
        $reactions = Hash::extract($reportReactions, '{n}.medicine.{n}');

        $reactionCount = 0;
        foreach ($reactions as $r) {
            if ($r === $drug_name) {
                $reactionCount++;
            }
        }
        return $reactionCount;
    }

    public function count_aefis_with_drug_and_reaction($totalReports, $drug_name, $reaction_name)
    {
        $count = 0;
        foreach ($totalReports as $report) {
            // Check if the report contains the specified medication
            if (in_array($drug_name, $report['medicine'])) {
                // Check if the report contains the specified reaction
                if (in_array($reaction_name, $report['reactions'])) {
                    // Increment the count if both conditions are met
                    $count++;
                }
            }
        }
        return $count;
    }
    public function padr_reports_found()
    {
        $criteria['Padr.copied !='] = '1';
        $criteria['Padr.deleted'] = false;
        $criteria['Padr.archived'] = false;
        $criteria['Padr.report_sadr'] = 'Adverse Reaction';

        $reportIds = $this->Padr->find('list', array(
            'fields' => array('Padr.id'),
            'conditions' => $criteria
        ));
        $reportIds = array_keys($reportIds);
        $reportReaction = array();

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
        return $reportReaction;
    }

    public function count_padrs_with_reaction($reportReactions, $reaction_name)
    {
        $reactions = Hash::extract($reportReactions, '{n}.reactions.{n}');
        $reactionCount = 0;
        foreach ($reactions as $r) {
            if ($r === $reaction_name) {
                $reactionCount++;
            }
        }
        // Return the count
        return $reactionCount;
    }

    public function count_padrs_with_drug($reportReactions, $drug_name)
    {
        $data = Hash::extract($reportReactions, '{n}.medicine_names.{n}');
        $count = 0;
        foreach ($data as $r) {
            if ($r === $drug_name) {
                $count++;
            }
        }
        // Return the count
        return $count;
    }

    public function count_padrs_with_drug_and_reaction($totalReports, $drug_name, $reaction_name)
    {

        $count = 0;
        foreach ($totalReports as $report) {
            // Check if the report contains the specified medication
            if (in_array($drug_name, $report['medicine_names'])) {
                // Check if the report contains the specified reaction
                if (in_array($reaction_name, $report['reactions'])) {
                    // Increment the count if both conditions are met
                    $count++;
                }
            }
        }
        return $count;
    }

    public function count_reactions_in_the_entire_system($reportReactions, $totalPadrReports, $reaction_name)
    {
        $count = 0;
        $reactionAefiCount = $this->count_aefis_with_reaction($reportReactions, $reaction_name);
        $reactionPsdrsCount = $this->count_padrs_with_reaction($totalPadrReports, $reaction_name);
        $reactionSadrsCount = count($this->get_sadr_reports_with_reaction($reaction_name));
        $count=$reactionAefiCount+$reactionPsdrsCount+$reactionSadrsCount;

        return $count;
    }
    public function count_drugs_in_the_entire_system($reportReactions,$totalPadrReports,$drug_name,$model)
    {
        $drugCount = 0; 
        if ($model == "Aefi") { 
            $drugCount = $this->count_aefis_with_drug($reportReactions,$drug_name);
        }
        if ($model == "Padr") { 
            $drugCount = $this->count_padrs_with_drug($totalPadrReports,$drug_name);
        }
        if ($model == "Sadr") { 
            $drugCount = count($this->get_sadr_reports_with_drug($drug_name)); 
        }

        return $drugCount;
    }
    public function count_drugs_and_reactions_in_the_entire_system($reportReactions,$totalPadrReports,$drug_name,$reaction_name,$model)
    {
        $drugReactionCount = 0; 
        if ($model == "Aefi") { 
            $drugReactionCount = $this->count_aefis_with_drug_and_reaction($reportReactions,$drug_name,$reaction_name);
        }
        if ($model == "Padr") { 
            $drugReactionCount = $this->count_padrs_with_drug_and_reaction($totalPadrReports,$drug_name,$reaction_name);
        }
        if ($model == "Sadr") { 
            $drugReactionCount = count($this->get_sadr_reports_with_drug_and_reaction($drug_name,$reaction_name)); 
        }

        return $drugReactionCount;
    }


    public function manager_index()
    {
        $this->Prg->commonProcess();
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (!empty($this->request->query['pages'])) $this->paginate['limit'] = $this->request->query['pages'];
        else $this->paginate['limit'] = reset($this->page_options);

        $criteria = $this->Disproportionality->parseCriteria($this->passedArgs);
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Disproportionality.created' => 'desc');

        $aefi_total = $this->get_total_approved_aefis();
        $sadr_total = $this->get_total_approved_sadrs();
        $padr_total = $this->get_total_approved_padrs();
        $transfusions_total = $this->get_total_approved_transfusions();
        $medications_total = $this->get_total_approved_medications();

        $total_reports = $aefi_total + $sadr_total + $padr_total + $transfusions_total + $medications_total;

        $datas = Sanitize::clean($this->paginate(), array('encode' => false));
        $data = array();

        $reportReactions = $this->get_aefi_reports();
        $totalPadrReports = $this->padr_reports_found();

        foreach ($datas as $dt) {
            $reaction_name = $dt['Disproportionality']['reaction_name'];
            $drug_name = $dt['Disproportionality']['drug_name'];
            $model = $dt['Disproportionality']['model'];

            $reactionCount = $this->count_reactions_in_the_entire_system($reportReactions, $totalPadrReports, $reaction_name);
            $drugCount = $this->count_drugs_in_the_entire_system($reportReactions,$totalPadrReports,$drug_name,$model);
            $drugReactionCount = $this->count_drugs_and_reactions_in_the_entire_system($reportReactions,$totalPadrReports,$drug_name,$reaction_name,$model);
            // exit;

            $expected_count_raw = ($drugCount * $reactionCount) / $total_reports;
            $expected_count = round($expected_count_raw, 5);

            $numerator = $drugReactionCount + 0.5;
            $denominator = $expected_count + 0.5;
            $calculated_data = $numerator / $denominator;

            // Observed vs. Expected -> IC (Information Component):

            $calculated_log_data_raw = log($calculated_data, 2);
            $calculated_log_data = round($calculated_log_data_raw, 5);

            $variance_of_ic_raw = 1 / ($numerator) + 1 / ($drugCount - $drugReactionCount + 0.5) + 1 / ($reactionCount - $drugReactionCount + 0.5) + 1 / ($total_reports - $drugCount - $reactionCount + $drugReactionCount + 0.5);

            $variance_of_ic = round($variance_of_ic_raw, 5);

            $standard_error = sqrt($variance_of_ic);
            $lower_bound = $calculated_log_data - 1.96 * $standard_error;

            $record = array(
                'Disproportionality' => array(
                    'drug_name' => $drug_name,
                    'reaction_name' => $reaction_name,
                    'model' => 'Sadr',
                    'B_reports_with_reaction' => $reactionCount,
                    'AB_reports_with_drug_and_reaction' => $drugReactionCount,
                    'E_(AB)_expected_count' => $expected_count,
                    'IC_raw_calculated_data' => $calculated_data,
                    'IC_raw_calculated_log_data' => $calculated_log_data,
                    'Var(IC)_Variance_of_IC' => $variance_of_ic,
                    'Standard_Error_(SE)_of_IC' => $standard_error,
                    '95%_Confidence_Interval' => $lower_bound
                )
            );
            $data[] = $record;
        }


        $this->set('page_options', $this->page_options);
        $this->set('data', $data);
        $this->set(compact('total_reports'));
    }
}
