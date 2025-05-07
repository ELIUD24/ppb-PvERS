<?php

$header = array(
	'id' => '#',
	'reference_no' => 'Reference No.',
	'company_name' => 'Company Name',
	'reporter_email' => 'Reporter Email',
	'sender_unique_identifier' => 'Sender Unique Identifier',
	'case_narrative' => 'Case Narrative',
	'date_first_received' => 'Date First Received',
	'sender_organization' => 'Sender Organization',
	'sender_department' => 'Sender Department', 
	'drugs' => 'Generic names',
	'brands' => 'Brand names',
	'reactions' => 'Reactions',
	'outcomes' => 'Outcomes',
	'created' => 'Date Created',
	'reporter_date' => 'Report Date',
	'submitted_date' => 'Date Submitted'
);
echo implode(',', $header) . "\n";
foreach ($ce2bs as $csadr) :
	$content = '';
	$row = [];
	foreach ($header as $key => $val) {
		if (array_key_exists($key, $csadr['Ce2b'])) {
			$row[$key] = '"' . preg_replace('/"/', '""', $csadr['Ce2b'][$key]) . '"';
		}
		elseif ($key == 'drugs') {
			foreach ($csadr['Ce2bListOfDrug'] as $sadrListOfDrug) {
				(isset($row[$key])) ? $row[$key] .= '; ' . $sadrListOfDrug['drug_name'] : $row[$key] = $sadrListOfDrug['drug_name'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/', '""', $row[$key]) . '"' : $row[$key] = '""';
		}
		elseif ($key == 'brands') {
			foreach ($csadr['Ce2bListOfDrug'] as $sadrListOfDrug) {
				(isset($row[$key])) ? $row[$key] .= '; ' . $sadrListOfDrug['brand_name'] : $row[$key] = $sadrListOfDrug['brand_name'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/', '""', $row[$key]) . '"' : $row[$key] = '""';
		}
		elseif ($key == 'reactions') {
			foreach ($csadr['Ce2bReaction'] as $reaction) {
				(isset($row[$key])) ? $row[$key] .= '; ' . $reaction['reaction_name'] : $row[$key] = $reaction['reaction_name'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/', '""', $row[$key]) . '"' : $row[$key] = '""';
		} 
		elseif ($key == 'outcomes') {
			foreach ($csadr['Ce2bReaction'] as $reaction) {
				(isset($row[$key])) ? $row[$key] .= '; ' . $reaction['reaction_outcome_value'] : $row[$key] = $reaction['reaction_outcome_value'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/', '""', $row[$key]) . '"' : $row[$key] = '""';
		} 

		
	}
	echo implode(',', $row) . "\n";
endforeach;
