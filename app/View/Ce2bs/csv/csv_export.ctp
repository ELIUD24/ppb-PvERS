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
	}
	echo implode(',', $row) . "\n";
endforeach;
