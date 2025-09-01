<?php

$header = array(
	'id' => '#',
	'reference_no' => 'Reference No.',
	'company_name' => 'Company Name',
	'reporter_email' => 'Reporter Email',
	'reporter_name' => 'Reporter Name',
	'reporter_phone' => 'Reporter Phone',  
	'brand_name' => 'Brand Name',
	'inn_name' => 'INN Name',
	'mah' => 'MAH',
	'local_technical' => 'Local Technical',
	'therapeutic_group' => 'Therapeutic Group',
	'authorised_indications' => 'Authorised Indications',
	'form_strength' => 'Form Strength',
	'interval_code' => 'Interval Code',
	'submission_frequency' => 'Submission Frequency',
	'date_of_birth' => 'Date of Birth',
	'introduction' => 'Introduction',
	'worldwide_marketing' => 'Worldwide Marketing',
	'action_taken' => 'Action Taken',
	'reference_changes' => 'Reference Changes',
	'estimated_exposure' => 'Estimated Exposure',
	'clinical_findings' => 'Clinical Findings',
	'efficacy' => 'Efficacy', 
	'late_breaking' => 'Late Breaking',
	'safety_concerns' => 'Safety Concerns',
	'risks_evaluation' => 'Risks Evaluation',
	'risks_characterisation' => 'Risks Characterisation',
	'benefit_evaluation' => 'Benefit Evaluation',
	'risk_balance' => 'Risk Balance',
	'recommendation' => 'Recommendation',
	'conclusion' => 'Conclusion', 	'data_lock' => 'Data Lock',
	'next_data_lock' => 'Next Data Lock',
	'data_interval' => 'Data Interval',
	'created' => 'Date Created',
	'reporter_date' => 'Report Date',
	'submitted_date' => 'Date Submitted'
);
echo implode(',', $header) . "\n";
foreach ($data as $csadr) :
	$content = '';
	$row = [];
	foreach ($header as $key => $val) {
		if (array_key_exists($key, $csadr['Aggregate'])) {
			$row[$key] = '"' . preg_replace('/"/', '""', $csadr['Aggregate'][$key]) . '"';
		}
		// elseif ($key == 'drugs') {
		// 	foreach ($csadr['Ce2bListOfDrug'] as $sadrListOfDrug) {
		// 		(isset($row[$key])) ? $row[$key] .= '; ' . $sadrListOfDrug['drug_name'] : $row[$key] = $sadrListOfDrug['drug_name'];
		// 	}
		// 	(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/', '""', $row[$key]) . '"' : $row[$key] = '""';
		// }
		// elseif ($key == 'brands') {
		// 	foreach ($csadr['Ce2bListOfDrug'] as $sadrListOfDrug) {
		// 		(isset($row[$key])) ? $row[$key] .= '; ' . $sadrListOfDrug['brand_name'] : $row[$key] = $sadrListOfDrug['brand_name'];
		// 	}
		// 	(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/', '""', $row[$key]) . '"' : $row[$key] = '""';
		// }
		// elseif ($key == 'reactions') {
		// 	foreach ($csadr['Ce2bReaction'] as $reaction) {
		// 		(isset($row[$key])) ? $row[$key] .= '; ' . $reaction['reaction_name'] : $row[$key] = $reaction['reaction_name'];
		// 	}
		// 	(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/', '""', $row[$key]) . '"' : $row[$key] = '""';
		// } 
		// elseif ($key == 'outcomes') {
		// 	foreach ($csadr['Ce2bReaction'] as $reaction) {
		// 		(isset($row[$key])) ? $row[$key] .= '; ' . $reaction['reaction_outcome_value'] : $row[$key] = $reaction['reaction_outcome_value'];
		// 	}
		// 	(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/', '""', $row[$key]) . '"' : $row[$key] = '""';
		// } 

		
	}
	echo implode(',', $row) . "\n";
endforeach;
