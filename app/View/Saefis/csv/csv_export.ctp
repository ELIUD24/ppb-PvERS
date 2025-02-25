<?php

	$header = array('id' => '#', 'county_name' => 'County',  'district' => 'District','place_vaccination' => 'Place of vaccination',
		'vaccination_in' => 'Vaccination in ','reporter_name' => 'Reporters name','designations' => 'Reporter designation', 'report_date' => 'Date of report','start_date' => 'Report start date',
		'report_type' => 'Report type','telephone' => 'Reporters telephone' , 'mobile' => 'Reporters mobile', 'reporter_email' => 'Reporters email',
		'patient_name' => 'Patients name', 'date_of_birth' => 'date_of_birth','age_at_onset_months' => 'Age in months', 'age_at_onset_years' => 'Age in years', 
		'age_at_onset_days' => 'Age in days', 'age_group' => 'Age group',
		'gender' => 'Gender', 'patient_address' => 'Address','patient_phone' => 'Phone', 'patient_street_name' => 'Street name',
		'patient_house_number' => 'House number', 'vaccine_name' => 'Suspected vaccine','site_type' => 'Type of site', 'site_type_other' => 'Type of site other',
		'symptom_date' => 'Date of first symptom','hospitalization_date' => 'Date hospitalized','date_first_reported' => 'Date first reported','time_of_first_symptom' => 'Time of first symptom',
		'date_form_filled' => 'Date form filled','status_on_date' => 'Status on date','died_date' => 'Date died', 'autopsy_done' => 'Autopsy done?', 'autopsy_planned' => 'Autopsy planned', 'autopsy_planned_date' => 'Autopsy planned date',
		// 'past_history' => 'Past history of similar event','adverse_event' => 'Adverse event after previous vaccination(s)',
		// 'allergy_history' => 'History of allergy to vaccine, drug or food',
		// 'existing_illness' => 'Pre-existing illness (30 days) / congenital disorder','hospitalization_history' => 'History of hospitalization in last 30 days, with cause', 'medication_vaccination' => 'Patient currently on concomitant medication?','family_history' => 'Family history of any disease (relevant to AEFI) or allergy',
		'pregnant' => 'Currently pregnant?','pregnant_weeks' => 'Currently pregnant?','breastfeeding' => 'Currently breastfeeding?','infant'=>'The birth was:', 'birth_weight' => 'Birth Weight',
		'delivery_procedure'=>'Delivery procedure was', 'delivery_procedure_specify'=>'Delivery procedure specify',
		'source_examination' => 'Examination by the investigator', 'source_documents' => 'Documents','source_verbal' => 'Verbal autopsy', 'source_other' => 'Other',
		'description_of_reaction' => 'Brief details on the event', 'verbal_source' => 'verbal autopsy source','name_of_person_first_treated' => 'Name of the person who first examined/treated the patient', 'name_of_the_person_treating' => 'Name of other persons treating the patient',
		'other_source_of_info' => 'Other sources who provided information', 'signs_symptoms' => 'Signs and symptoms in chronological order from the time of vaccination',
		'vaccines' => 'Vaccines','vaccination_doses' => 'Vaccination doses',
		'vaccination_dates' => 'Vaccination dates','vaccination_times' => 'Vaccination times','vaccination_routes' => 'Vaccination routes',
		'vaccination_sites' => 'Vaccination sites','vaccination_batch' => 'Batch/Lot No.','manufacturers' => 'Vaccine Manufacturers',
		'vaccination_expiry' => 'Vaccine expiry','diluent_batch' => 'Diluent batch','diluent_manufacturers' => 'Diluent manufacturers',
		'diluent_expiry' => 'Diluent expiry', 'person_details'=>'Name and Contact', 'person_designation'=>'Designation', 'person_date'=>'Date',
		// 'when_vaccinated'=>'When was the patient immunized? (✓ the below and respond to ALL questions)', 
		// 'when_vaccinated_specify'=>'In case of multidose vials, was the vaccine given',
		// 'prescribing_error'=>'Was there an error in prescribing or non-adherence to recommendations for use of this vaccine?',
		// 'prescribing_error_specify'=>'Was there an error in prescribing or non-adherence to recommendations for use of this vaccine?', 
		// 'vaccine_unsterile'=>'Based on your investigation, do you feel that the vaccine (ingredients) administered could have been unsterile?',
		// 'vaccine_unsterile_specify'=>'Based on your investigation, do you feel that the vaccine (ingredients) administered could have been unsterile?',
		// 'vaccine_unsterile'=>'Based on your investigation, do you feel that the vaccine (ingredients) administered could have been unsterile?', 
		// 'vaccine_condition'=>'Based on your investigation, do you feel that the vaccines physical condition 
		// (e.g. colour, turbidity, foreign substances etc.) was abnormal at the time of administration',
		// 'vaccine_reconstitution'=>'Based on your investigation, do you feel that there was an error in vaccine reconstitution/preparation by the vaccinator 
		// (e.g. wrong product, wr																																																		ong diluent, improper mixing, improper syringe filling etc.)?', 
		// 'vaccine_handling'=>'Based on your investigation, do you feel that the vaccine was administered incorrectly 
		// (e.g. wrong dose, site or route of administration, wrong needle size, not following good injection practice etc.)?',
		// 'vaccinated_vial'=>'Number immunized from the concerned vaccine vial/ampoule', 'vaccinated_session'=>'Number immunized with the concerned vaccine in the same session', 
		// 'vaccinated_locations'=>'Number immunized with the concerned vaccine having the same batch number in other locations. Specify locations',
		// 'vaccinated_cluster'=>'Is this case a part of a cluster?', 'vaccinated_cluster_number'=>'If yes, how many other cases have been detected in the cluster?', 
		// 'vaccinated_cluster_vial'=>'Did all the cases in the cluster receive vaccine from the same vial?',
		// 'vaccinated_cluster_vial_number'=>'If no, number of vials used in the cluster (enter details separately)', 
		// 'syringes_used'=>'Are AD syringes used for immunization?', 'syringes_used_specify'=>'If no, specify the type of syringes used:', 
		// 'syringes_used_other_specify'=>'Syringes used other:',
		// 'syringes_used_findings'=>'Specific key findings/additional observations and comments:', 
		// 'reconstitution_multiple'=>'Same reconstitution syringe used for multiple vials of same vaccine?', 
		// 'reconstitution_different'=>'Same reconstitution syringe used for reconstituting different vaccines?', 
		// 'reconstitution_vial'=>'Separate reconstitution syringe for each vaccine vial?',
		// 'reconstitution_syringe'=>'Separate reconstitution syringe for each vaccination?', 'reconstitution_vaccines'=>'Are the vaccines and diluents used the same as those recommended by the manufacturer?', 
		// 'reconstitution_observations'=>'Specific key findings/additional observations and comments', 'injection_dose_route'=>'Correct dose and route?', 
		// 'injection_time_mentioned'=>'Time of reconstitution mentioned on the vial? (in case of freeze dried vaccines)', 
		// 'injection_no_touch'=>'Non-touch technique followed?',
		// 'injection_contraindications'=>'Contraindications screened prior to vaccination?', 'injection_reported'=>'How many AEFI were reported from the centre that distributed the vaccine in the last 30 days?', 
		// 'vaccinator_training'=>'Training received by the vaccinator? (If Yes, specify the date of last training)', 'injection_observations'=>'Specific key findings/additional observations and comments:',
		// 'cold_temperature'=>'Is the temperature of the vaccine storage refrigerator monitored?', 'cold_temperature_deviation'=>'If “yes”, was there any deviation outside of 2-8 ° C after the vaccine was placed inside?', 
		// 'cold_temperature_specify'=>'If “yes”, provide details of monitoring separately.', 'procedure_followed'=>'Was the correct procedure for storing vaccines, diluents and syringes followed?', 
		// 'other_items'=>'Was any other item (other than EPI vaccines and diluents) in the refrigerator or freezer?', 'partial_vaccines'=>'Were any partially used reconstituted vaccines in the refrigerator?', 
		// 'unusable_vaccines'=>'Were any unusable vaccines (expired, no label, VVM at stages 3 or 4, frozen) in the refrigerator?', 'unusable_diluents'=>'Were any unusable diluents (expired, manufacturer not matched, cracked, dirty ampoule) in the store?',
		// 'additional_observations'=>'Specific key findings/additional observations and comments:', 'cold_transportation'=>'Type of vaccine carrier used',
		// 'vaccine_carrier'=>'Was the vaccine carrier sent to the site on the same day as vaccination?', 'transport_findings'=>'Was the vaccine carrier returned from the site on the same day as vaccination?', 
		// 'coolant_packs'=>'Was a conditioned ice-pack used?', 'transport_findings'=>'Specific key findings/additional observations and comments:',  'similar_events'=>'Were any similar events reported within a time period similar to when the adverse event occurred and in the same locality?',
		// 'similar_events_describe'=>'If yes, describe:', 'similar_events_episodes'=>'If yes, how many events/episodes?', 'affected_vaccinated'=>'Vaccinated:', 'affected_not_vaccinated'=>'Not vaccinated:', 
		// 'affected_unknown'=>'Unknown', 'community_comments'=>'Other comments:', 'relevant_findings'=>'Other findings/observations/comments', 
		'reporter_name'=>'NAME OF PERSON REPORTING:', 
		'designations' => 'Reporter designation', 'reporter_email'=>'E-MAIL ADDRESS:',
		'reporter_phone'=>'PHONE NO', 'reporter_date'=>'Date:', 'reporter_name_diff'=>'NAME OF PERSON REPORTING:', 'reporter_email_diff'=>'E-MAIL ADDRESS:', 'reporter_designation_diff'=>'DESIGNATION:', 'reporter_phone_diff'=>'PHONE NO.',  'reporter_date_diff'=>'Date:','submitted_date'=>'Date Submitted'

		);

	if($this->Session->read('Auth.User.user_type') != 'Public Health Program') {
		$header['reporter_name'] = 'Reporter';
		$header['reporter_email'] = 'Reporter email';
		$header['reporter_phone'] = 'Reporter phone';
		$header['patient_name'] = 'Patient name';
	}

	//Additional free text columns
	$header['aefi_symptoms'] = 'Describe Adverse Event Following Immunization';
	$header['description_of_reaction'] = 'Brief details on thDate:e event';
	$header['medical_history'] = 'Past medical history';
	$header['treatment_given'] = 'Treatment given';


	// if header has patient_name then order it to follow the sub_counties
	if(isset($header['patient_name'])) {
		$patient_name = $header['patient_name'];
		unset($header['patient_name']);
		$header = array_slice($header, 0, 7, true) + array('patient_name' => $patient_name) + array_slice($header, 7, count($header) - 1, true);
	}


	echo implode(',', $header)."\n";
	foreach ($saefis as $saefi):
		$content = '';
		$row = [];
		foreach ($header as $key => $val) {
			if (array_key_exists($key, $saefi['Saefi'])) {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi'][$key]) . '"';
			} elseif ($key == 'county_name') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['County']['county_name']) . '"';
			}
			elseif ($key == 'district') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['District']['district']) . '"';
			}
			elseif ($key == 'place_vaccination') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['place_vaccination']) . '"';
			}
			elseif ($key == 'vaccination_in') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccination_in']) . '"';
			}
			elseif ($key == 'reporter_name') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reporter_name']) . '"';
			}
			elseif ($key == 'report_date') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['report_date']) . '"';
			}
			elseif ($key == 'start_date') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['start_date']) . '"';
			}
			elseif ($key == 'report_type') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['report_type']) . '"';
			}
			elseif ($key == 'telephone') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['telephone']) . '"';
			}
			elseif ($key == 'mobile') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['mobile']) . '"';
			}
			elseif ($key == 'patient_name') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['patient_name']) . '"';
			}	elseif ($key == 'date_of_birth') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['date_of_birth']) . '"';
			}	elseif ($key == 'age_at_onset_months') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['age_at_onset_months']) . '"';
			}
			elseif ($key == 'age_at_onset_years') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['age_at_onset_years']) . '"';
			}
			elseif ($key == 'age_group') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['age_group']) . '"';
			}
			elseif ($key == 'gender') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['gender']) . '"';
			}
			elseif ($key == 'patient_address') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['patient_address']) . '"';
			}
			elseif ($key == 'patient_phone') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['patient_phone']) . '"';
			}
			elseif ($key == 'patient_street_name') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['patient_street_name']) . '"';
			}
			elseif ($key == 'patient_house_number') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['patient_house_number']) . '"';
			}
			elseif ($key == 'vaccine_name') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccine_name']) . '"';
			}
			elseif ($key == 'designations') {
							$row[$key] = '"' . preg_replace('/"/','""',$saefi['Designation']['name']) . '"';
			} elseif ($key == 'date_born') {
				$dob = ''; $bod = $saefi['Saefi']['date_of_birth'];
				if (!empty($bod['year'])) {
					(!empty($bod['day'])) ? $dob.=$bod['day'].'-' : $dob.='01-';
					(!empty($bod['month'])) ? $dob.=$bod['month'].'-' : $dob.='01-';
					(!empty($bod['year'])) ? $dob.=$bod['year'] : $dob.='1970';
				}
				($dob) ? $row[$key] = $dob : $row[$key] = '""';
			} elseif ($key == 'times_saefi_started') {
				$tas = ''; $sat = $saefi['Saefi']['time_saefi_started'];
				if (isset($sat['hour'])) {
					$tas.=$sat['hour'].':';
					$tas.=$sat['min'];
				}
				($tas) ? $row[$key] = $tas : $row[$key] = '""';
			} elseif ($key == 'vaccines') {
				foreach ($saefi['AefiListOfVaccine'] as $aefiListOfVaccine) {
					$val = (!empty($aefiListOfVaccine['Vaccine']['vaccine_name'])) ? $aefiListOfVaccine['Vaccine']['vaccine_name'] : '';
					(isset($row[$key])) ? $row[$key] .= '; '.$val : $row[$key] = $val;
				}
				(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
			} elseif ($key == 'vaccination_doses') {
				foreach ($saefi['AefiListOfVaccine'] as $aefiListOfVaccine) {
					(isset($row[$key])) ? $row[$key] .= '; '.$aefiListOfVaccine['dosage'] : $row[$key] = $aefiListOfVaccine['dosage'];
				}
				(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
			} elseif ($key == 'vaccination_dates') {
				foreach ($saefi['AefiListOfVaccine'] as $aefiListOfVaccine) {
					(isset($row[$key])) ? $row[$key] .= '; '.$aefiListOfVaccine['vaccination_date'] : $row[$key] = $aefiListOfVaccine['vaccination_date'];
				}
				(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
			} elseif ($key == 'vaccination_times') {
				foreach ($saefi['AefiListOfVaccine'] as $aefiListOfVaccine) {
					if(isset($aefiListOfVaccine['vaccination_time']['hour'])) (isset($row[$key])) ? $row[$key] .= '; '.$aefiListOfVaccine['vaccination_time']['hour'].':'.$aefiListOfVaccine['vaccination_time']['hour'] : $row[$key] = $aefiListOfVaccine['vaccination_time']['hour'].':'.$aefiListOfVaccine['vaccination_time']['hour'];
				}
				(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
			} elseif ($key == 'vaccination_routes') {
				foreach ($saefi['AefiListOfVaccine'] as $aefiListOfVaccine) {
					(isset($row[$key])) ? $row[$key] .= '; '.$aefiListOfVaccine['vaccination_route'] : $row[$key] = $aefiListOfVaccine['vaccination_route'];
				}
				(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
			} elseif ($key == 'vaccination_sites') {
				foreach ($saefi['AefiListOfVaccine'] as $aefiListOfVaccine) {
					(isset($row[$key])) ? $row[$key] .= '; '.$aefiListOfVaccine['vaccination_site'] : $row[$key] = $aefiListOfVaccine['vaccination_site'];
				}
				(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
			} elseif ($key == 'vaccination_batch') {
				foreach ($saefi['AefiListOfVaccine'] as $aefiListOfVaccine) {
					(isset($row[$key])) ? $row[$key] .= '; '.$aefiListOfVaccine['batch_number'] : $row[$key] = $aefiListOfVaccine['batch_number'];
				}
				(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
			} elseif ($key == 'manufacturers') {
				foreach ($saefi['AefiListOfVaccine'] as $aefiListOfVaccine) {
					(isset($row[$key])) ? $row[$key] .= '; '.$aefiListOfVaccine['vaccine_manufacturer'] : $row[$key] = $aefiListOfVaccine['vaccine_manufacturer'];
				}
				(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
			}  elseif ($key == 'vaccination_expiry') {
				foreach ($saefi['AefiListOfVaccine'] as $aefiListOfVaccine) {
					(isset($row[$key])) ? $row[$key] .= '; '.$aefiListOfVaccine['expiry_date'] : $row[$key] = $aefiListOfVaccine['expiry_date'];
				}
				(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
			}  elseif ($key == 'diluent_batch') {
				foreach ($saefi['AefiListOfVaccine'] as $aefiListOfVaccine) {
					(isset($row[$key])) ? $row[$key] .= '; '.$aefiListOfVaccine['diluent_batch_number'] : $row[$key] = $aefiListOfVaccine['diluent_batch_number'];
				}
				(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
			}  elseif ($key == 'diluent_manufacturers') {
				foreach ($saefi['AefiListOfVaccine'] as $aefiListOfVaccine) {
					(isset($row[$key])) ? $row[$key] .= '; '.$aefiListOfVaccine['diluent_manufacturer'] : $row[$key] = $aefiListOfVaccine['diluent_manufacturer'];
				}
				(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
			}  elseif ($key == 'diluent_expiry') {
				foreach ($saefi['AefiListOfVaccine'] as $aefiListOfVaccine) {
					(isset($row[$key])) ? $row[$key] .= '; '.$aefiListOfVaccine['diluent_expiry_date'] : $row[$key] = $aefiListOfVaccine['diluent_expiry_date'];
				}
				(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
			}
			elseif ($key == 'site_type') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['site_type']) . '"';
			}
			elseif ($key == 'site_type_other') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['site_type_other']) . '"';
			}
			elseif ($key == 'symptom_date') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['symptom_date']) . '"';
			}
			elseif ($key == 'hospitalization_date') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['hospitalization_date']) . '"';
			}
			elseif ($key == 'date_first_reported') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['date_first_reported']) . '"';
			}
			elseif ($key == 'time_of_first_symptom') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['time_of_first_symptom']) . '"';
			}
			elseif ($key == 'date_form_filled') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['date_form_filled']) . '"';
			}
			elseif ($key == 'status_on_date') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['status_on_date']) . '"';
			}
			elseif ($key == 'died_date') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['died_date']) . '"';
			}
			elseif ($key == 'autopsy_done') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['autopsy_done']) . '"';
			}
			elseif ($key == 'autopsy_planned') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['autopsy_planned']) . '"';
			}
			elseif ($key == 'autopsy_planned_date') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['autopsy_planned_date']) . '"';
			}
			// elseif ($key == 'past_history') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['past_history']) . '"';
			// }
			// elseif ($key == 'adverse_event') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['adverse_event']) . '"';
			// }
			// elseif ($key == 'allergy_history') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['allergy_history']) . '"';
			// }
			// elseif ($key == 'existing_illness') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['existing_illness']) . '"';
			// }
			// elseif ($key == 'hospitalization_history') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['hospitalization_history']) . '"';
			// }
			// elseif ($key == 'medication_vaccination') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['medication_vaccination']) . '"';
			// }
			// elseif ($key == 'family_history') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['family_history']) . '"';
			// }
			elseif ($key == 'pregnant') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['pregnant']) . '"';
			}
			elseif ($key == 'pregnant_weeks') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['pregnant_weeks']) . '"';
			}
			elseif ($key == 'breastfeeding') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['breastfeeding']) . '"';
			}
			elseif ($key == 'infant') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['infant']) . '"';
			}
			elseif ($key == 'birth_weight') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['birth_weight']) . '"';
			}
			elseif ($key == 'delivery_procedure') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['delivery_procedure']) . '"';
			}
			elseif ($key == 'delivery_procedure_specify') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['delivery_procedure_specify']) . '"';
			}
			// elseif ($key == 'source_examination') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['source_examination']) . '"';
			// }
			// elseif ($key == 'source_documents') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['source_documents']) . '"';
			// }
			// elseif ($key == 'source_verbal') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['source_verbal']) . '"';
			// }
			// elseif ($key == 'source_other') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['source_other']) . '"';
			// }
			// elseif ($key == 'description_of_reaction') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['description_of_reaction']) . '"';
			// }
			// elseif ($key == 'verbal_source') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['verbal_source']) . '"';
			// }
			// elseif ($key == 'name_of_person_first_treated') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['name_of_person_first_treated']) . '"';
			// }
			// elseif ($key == 'name_of_the_person_treating') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['name_of_the_person_treating']) . '"';
			// }
			// elseif ($key == 'other_source_of_info') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['other_source_of_info']) . '"';
			// }
			// elseif ($key == 'signs_symptoms') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['signs_symptoms']) . '"';
			// }
			// elseif ($key == 'person_details') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['person_details']) . '"';
			// }
			// elseif ($key == 'person_designation') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['person_designation']) . '"';
			// }
			// elseif ($key == 'person_date') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['person_date']) . '"';
			// }
			// elseif ($key == 'when_vaccinated') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['when_vaccinated']) . '"';
			// }
			// elseif ($key == 'when_vaccinated_specify') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['when_vaccinated_specify']) . '"';
			// }
			// elseif ($key == 'prescribing_error') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['prescribing_error']) . '"';
			// }
			// elseif ($key == 'prescribing_error_specify') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['prescribing_error_specify']) . '"';
			// }
			// elseif ($key == 'vaccine_unsterile') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccine_unsterile']) . '"';
			// }
			// elseif ($key == 'vaccine_unsterile_specify') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccine_unsterile_specify']) . '"';
			// }
			// elseif ($key == 'vaccine_unsterile') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccine_unsterile']) . '"';
			// }
			// elseif ($key == 'vaccine_condition') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccine_condition']) . '"';
			// }
			// elseif ($key == 'vaccine_reconstitution') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccine_reconstitution']) . '"';
			// }
			// elseif ($key == 'vaccine_handling') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccine_handling']) . '"';
			// }
			// elseif ($key == 'vaccinated_vial') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccinated_vial']) . '"';
			// }
			// elseif ($key == 'vaccinated_session') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccinated_session']) . '"';
			// }
			// elseif ($key == 'vaccinated_locations') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccinated_locations']) . '"';
			// }
			// elseif ($key == 'vaccinated_cluster') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccinated_cluster']) . '"';
			// }
			// elseif ($key == 'vaccinated_cluster_number') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccinated_cluster_number']) . '"';
			// }
			// elseif ($key == 'vaccinated_cluster_vial') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccinated_cluster_vial']) . '"';
			// }
			// elseif ($key == 'vaccinated_cluster_vial_number') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccinated_cluster_vial_number']) . '"';
			// }
			// elseif ($key == 'syringes_used') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['syringes_used']) . '"';
			// }
			// elseif ($key == 'syringes_used_specify') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['syringes_used_specify']) . '"';
			// }
			// elseif ($key == 'syringes_used_other_specify') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['syringes_used_other_specify']) . '"';
			// }
			// elseif ($key == 'syringes_used_findings') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['syringes_used_findings']) . '"';
			// }
			// elseif ($key == 'reconstitution_multiple') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reconstitution_multiple']) . '"';
			// }
			// elseif ($key == 'reconstitution_different') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reconstitution_different']) . '"';
			// }
			// elseif ($key == 'reconstitution_vial') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reconstitution_vial']) . '"';
			// }
			// elseif ($key == 'reconstitution_syringe') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reconstitution_syringe']) . '"';
			// }
			// elseif ($key == 'reconstitution_vaccines') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reconstitution_vaccines']) . '"';
			// }
			// elseif ($key == 'reconstitution_observations') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reconstitution_observations']) . '"';
			// }
			// elseif ($key == 'injection_dose_route') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['injection_dose_route']) . '"';
			// }
			// elseif ($key == 'injection_time_mentioned') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['injection_time_mentioned']) . '"';
			// }
			// elseif ($key == 'injection_no_touch') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['injection_no_touch']) . '"';
			// }
			// elseif ($key == 'injection_contraindications') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['injection_contraindications']) . '"';
			// }
			// elseif ($key == 'injection_reported') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['injection_reported']) . '"';
			// }
			// elseif ($key == 'vaccinator_training') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccinator_training']) . '"';
			// }
			// elseif ($key == 'injection_observations') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['injection_observations']) . '"';
			// }
			// elseif ($key == 'cold_temperature') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['cold_temperature']) . '"';
			// }
			// elseif ($key == 'cold_temperature_deviation') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['cold_temperature_deviation']) . '"';
			// }
			// elseif ($key == 'cold_temperature_specify') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['cold_temperature_specify']) . '"';
			// }
			// elseif ($key == 'procedure_followed') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['procedure_followed']) . '"';
			// }
			// elseif ($key == 'other_items') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['other_items']) . '"';
			// }
			// elseif ($key == 'partial_vaccines') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['partial_vaccines']) . '"';
			// }
			// elseif ($key == 'unusable_vaccines') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['unusable_vaccines']) . '"';
			// }
			// elseif ($key == 'unusable_diluents') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['unusable_diluents']) . '"';
			// }
			// elseif ($key == 'additional_observations') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['additional_observations']) . '"';
			// }
			// elseif ($key == 'cold_transportation') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['cold_transportation']) . '"';
			// }
			// elseif ($key == 'vaccine_carrier') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['vaccine_carrier']) . '"';
			// }
			// elseif ($key == 'transport_findings') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['transport_findings']) . '"';
			// }
			// elseif ($key == 'coolant_packs') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['coolant_packs']) . '"';
			// }
			// elseif ($key == 'transport_findings') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['transport_findings']) . '"';
			// }
			// elseif ($key == 'similar_events') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['similar_events']) . '"';
			// }
			// elseif ($key == 'similar_events_describe') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['similar_events_describe']) . '"';
			// }
			// elseif ($key == 'similar_events_episodes') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['similar_events_episodes']) . '"';
			// }
			// elseif ($key == 'affected_vaccinated') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['affected_vaccinated']) . '"';
			// }
			// elseif ($key == 'affected_not_vaccinated') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['affected_not_vaccinated']) . '"';
			// }
			// elseif ($key == 'affected_unknown') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['affected_unknown']) . '"';
			// }
			// elseif ($key == 'community_comments') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['community_comments']) . '"';
			// }
			// elseif ($key == 'relevant_findings') {
			// 	$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['relevant_findings']) . '"';
			// }
			elseif ($key == 'reporter_name') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reporter_name']) . '"';
			}
			elseif ($key == 'designations') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['designations']) . '"';
			}
			elseif ($key == 'reporter_email') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reporter_email']) . '"';
			}
			elseif ($key == 'reporter_phone') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reporter_phone']) . '"';
			}
			elseif ($key == 'reporter_date') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reporter_date']) . '"';
			}
			elseif ($key == 'reporter_name_diff') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reporter_name_diff']) . '"';
			}
			elseif ($key == 'reporter_email_diff') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reporter_email_diff']) . '"';
			}
			elseif ($key == 'reporter_designation_diff') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reporter_designation_diff']) . '"';
			}
			elseif ($key == 'reporter_phone_diff') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reporter_phone_diff']) . '"';
			}
			elseif ($key == 'reporter_date_diff') {
				$row[$key] = '"' . preg_replace('/"/','""',$saefi['Saefi']['reporter_date_diff']) . '"';
			}
		}
		echo implode(',', $row) . "\n";
	endforeach;