<?php
$this->assign('E2B', 'active');
$ichecked = "&#x2611;";
$nchecked = "&#x2610;";
?>

<!-- Ce2b
    ================================================== -->

<div class="row-fluid" id="abonokode">
    <div class="span12">

        <div id="printAreade">
            <div class="formbackc">

                <p><b>(FOM001/HPT/VMS/SOP/001)</b></p>
                <div class="row-fluid">
                    <div class="span12">
                        <?php
                        echo $this->Html->image('confidence.png', array('alt' => 'in confidence', 'class' => 'pull-right'));
                        echo $this->Html->image('coa.png', array('alt' => 'COA', 'style' => 'margin-left: 45%;'));
                        ?>
                        <div class="babayao" style="text-align: center;">
                            <h4>MINISTRY OF HEALTH</h4>
                            <h5>PHARMACY AND POISONS BOARD</h5>
                            <h5>P.O. Box 27663-00506 NAIROBI</h5>
                            <h5>Tel: +254795743049</h5>
                            <h5><b>Email:</b> pv@ppb.go.ke</h5>
                            <h5 style="color: red;">E2B</h5>
                        </div>
                    </div>
                </div>

                <!-- Updated UI -->
                <div class="row-fluid">
                    <div class="span12" style="padding-left: 20px; padding-right: 20px;">

                        <h5 style="background: #18C4D1; padding:20px;">Identification of the Case Safety Report </h5>
                        <table class="table" style="width: 100%;">
                            <tr width="100%">
                                <th colspan="2">Sender's Safety Report Unique Identifier</th>
                            </tr>
                            <tr>
                                <td colspan="2"><?php echo $ce2b['Ce2b']['sender_unique_identifier']; ?></td>
                            </tr>
                            <tr>
                                <th colspan="2">Type of Report</th>
                            </tr>
                            <tr>
                                <td colspan="2"><?php echo $ce2b['Ce2b']['e2b_type']; ?></td>
                            </tr>
                            <tr>
                                <th>Date of Creation</th>
                                <th>Date First Received from source</th>
                            </tr>
                            <tr>
                                <td><?php
                                    $dateString = $ce2b['Ce2b']['creation_time'];
                                    echo $dateString;

                                    ?></td>
                                <td><?php echo $ce2b['Ce2b']['date_first_received']; ?></td>
                            </tr>

                            <tr>
                                <th colspan="2">Worldwide unique case Identification</th>

                            </tr>
                            <tr>
                                <td colspan="2"><?php echo $ce2b['Ce2b']['worldwide_identifier']; ?></td>
                            </tr>
                            <tr>
                                <th colspan="2">Case Narrative</th>

                            </tr>
                            <tr>
                                <td colspan="2"><?php echo $ce2b['Ce2b']['case_narrative']; ?></td>
                            </tr>

                        </table>
                        <h5 style="background: #18C4D1; padding:20px;">Primary Sources</h5>

                        <table class="table" style="width: 100%;">
                            <tr width="100%">
                                <th>Reporter's Name</th>
                                <th>Reporter's Email Address</th>
                                <th>Reporter's Telephone</th>
                            </tr>
                            <tr>
                                <td><?php echo $ce2b['Ce2b']['reporter_name']; ?></td>
                                <td><?php echo $ce2b['Ce2b']['reporter_email']; ?></td>
                                <td><?php echo $ce2b['Ce2b']['reporter_phone']; ?></td>
                            </tr>
                            <tr>
                                <th>Reporter's Department</th>
                                <th colspan="2">Reporter's Physicall Address </th>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2"><?php echo $ce2b['Ce2b']['reporter_name']; ?></td>
                            </tr>
                            <tr>
                                <th colspan="3">Reporter's Qualification</th>
                            </tr>
                            <tr>
                                <td colspan="3"><?php echo $ce2b['Designation']['name']; ?></td>
                            </tr>
                        </table>
                        <h5 style="background: #18C4D1; padding:20px;">Information on Sender of Case Safety Report</h5>
                        <table class="table" style="width: 100%;">
                            <tr width="100%">
                                <th>Sender's Name</th>
                                <th>Sender's Email Address</th>
                                <th>Sender's Telephone</th>
                                <th>Sender's Organization</th>
                            </tr>
                            <tr>
                                <td><?php ?></td>
                                <td><?php  ?></td>
                                <td><?php  ?></td>
                                <td><?php echo $ce2b['Ce2b']['sender_organization']; ?></td>
                            </tr>
                            <tr>
                                <th>Sender's Department</th>
                                <th>Sender's Physicall Address </th>
                                <th>Sender's Fax</th>
                                <th>Sender's Qualification</th>
                            </tr>
                            <tr>
                                <td><?php echo $ce2b['Ce2b']['sender_department']; ?></td>
                                <td><?php echo $ce2b['Ce2b']['sender_address']; ?></td>
                                <td><?php ?></td>
                                <td></td>
                            </tr>

                        </table>
                        <h5 style="background: #18C4D1; padding:20px;">Literature Reference(s)</h5>

                        <table class="table" style="width: 100%;">
                            <tr width="100%">
                                <td>Literature Reference</td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <!-- <tr>
                                <td>Included documents</td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr> -->
                        </table>
                        <h5 style="background: #18C4D1; padding:20px;">Study Indetification</h5>

                        <table class="table" style="width: 100%;">
                            <tr width="100%">
                                <td>Study registration number</td>
                                <td>Study registration country</td>
                                <td>Study name</td>
                                <td>Sponsor study number</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Study type where reaction(s) / event (s) were observed</td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                        </table>
                        <h5 style="background: #18C4D1; padding:20px;">Patient Characteristics</h5>

                        <table class="table" style="width: 100%;">
                            <tr width="100%">
                                <th>Patient's Name or Initials</th>
                                <th>Patient's medical record number</th>
                                <th>Patient's Age</th>
                                <th>Patient's Date of Birth</th>
                            </tr>
                            <tr>
                                <td><?php echo $ce2b['Ce2b']['patient_name']; ?></td>
                                <td><?php echo $ce2b['Ce2b']['patient_number']; ?></td>
                                <td></td>
                                <td>
                                    <?php
                                    $dob = isset($ce2b['Ce2b']['patient_dob']) ? trim($ce2b['Ce2b']['patient_dob']) : '';

                                    if (!empty($dob) && strtotime($dob)) {
                                        echo date('Y-m-d', strtotime($dob));
                                    } else {
                                        echo 'N/A'; // Display a placeholder when the date is null or invalid
                                    }
                                    ?>
                                </td>
                            </tr> 
                            <tr>
                                <th>Body weight (kg)</th>
                                <th>Height (cm)</th>
                                <th colspan="2">Sex</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td colspan="2"><?php
                                                if (isset($ce2b['Ce2b']['patient_sex'])) {
                                                    $patientSex = $ce2b['Ce2b']['patient_sex'];
                                                    if ($patientSex == 2) {
                                                        echo 'Female';
                                                    } elseif ($patientSex == 1) {
                                                        echo 'Male';
                                                    }
                                                }
                                                ?></td>
                            </tr> 
                            <tr>
                                <th colspan="4">Text for relevant medical history and concurrent conditions (not including reaction/event)</th>
                            </tr>
                            <tr>
                                <td colspan="4"><?php echo $ce2b['Ce2b']['past_medical']; ?></td>
                            </tr> 
                        </table>
                        <!-- <h5>Structured information on relevant medical history</h5>
                        <table class="table" style="width: 100%;">
                            <tr width="100%">
                                <td>MedDRA Version for medical History</td>
                                <td>Medical History (MedDRA code)</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Start Date</td>
                                <td>Continuing</td>
                                <td>End Date</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Comments</td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Family History</td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                        </table> -->
                        <!-- <h5>Relevant past Drug History</h5>
                        <table class="table" style="width: 100%;">
                            <tr width="100%">
                                <td>Name of drug as reported</td>
                                <td>MPID version date/number</td>
                                <td>Medicinal Product Identifier (MPID)</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>PhPID version date/number</td>
                                <td>Pharmaceutical Product Identifier (PhPID)</td>

                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Start Date</td>
                                <td>End Date</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>MedDRA version for indication</td>
                                <td>Indication (MedDRA Code)</td>
                                <td>MedDRA version for reaction</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                        <table class="table" style="width: 100%;">
                            <tr width="100%">
                                <td>Date of Death</td>
                                <td>Reported cause(s) of death</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>MedDRA version for reported causes(s) of death</td>
                                <td>Reported cause(s) of death (MedDRA code)</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Reported cause(s) of death (free text)</td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Was the autopsy done?</td>

                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                        </table>
                        <h5>Autopsy-Determined cause(s) of Death</h5>
                        //Repeat if possible 
                        <table class="table" style="width: 100%;">
                            <tr width="100%">
                                <td>MedDRA version for autopsy-determined cause of death</td>
                                <td>Autopsy-determined cause(s) of death (MedDRA code)</td>
                                <td>Autopsy-determined cause(s) of death (free text)</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                        <br>
                        <table class="table" style="width: 100%;">
                            <tr width="100%">
                                <td>Parent Identification</td>
                                <td>Date of Birth of parent</td>
                                <td>Parent's Age</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Body weight (kg) of parent</td>
                                <td>Height (cm) of parent</td>
                                <td>Sex of parent</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Text for relevant medical history and concurent conditions of parent</td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                        </table>
                        <h5> Relevant Medical history and concurrent conditions of Parent</h5>

 
                        <table class="table" style="width: 100%;">
                            <tr width="100%">
                                <td>MedDRa version for medical history</td>
                                <td>Medical history (disease /surgical procedure /etc) (MedDRA code)</td>

                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Start Date</td>
                                <td>Continuing</td>
                                <td>End Date</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Comments</td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                        </table>
                        <br>
                        <h5>Relevant Past drug history of parent</h5>
                        
                        <table class="table" style="width: 100%;">
                            <tr width="100%">
                                <td>Name of drug as reported</td>
                                <td>MPID version date/number</td>
                                <td>MEdical Product Identifier (MPID)</td>
                                <td>PhPID version date/number</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Pharmaceutical Product Identifier (PhPID)</td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Start Date</td>
                                <td>End Date</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>MedDRA version for indication</td>
                                <td>Indication (MedDRA code)</td>
                                <td>MedDRA version for reaction</td>
                                <td>Reactions (MedDRA code)</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table> -->
                        <h5 style="background: #18C4D1; padding:20px;">Reaction(s)/Event(s)</h5>

                        <table class="table" style="width: 100%;">
                            <tr>
                                <th>Reaction Name</th>
                                <th>Start Date</th>
                                <th>MedDRA Code</th> 
                                <th>Results in death</th>
                                <th>Life threatening</th>
                                <th>Caused / prolonged hospitalization </th>
                                <th>Disabling / Incapacitating</th>
                                <th>Congenital anomaly / birth defect</th>
                                <th>Occurence Country</th>
                                <th>Outcome</th>
                            </tr>
                            <?php

                            foreach ($ce2b['Ce2bReaction'] as $reaction) {
                            ?>
                                <tr>
                                    <td><?php echo $reaction['reaction_name'] ?></td>
                                    <td><?php echo $reaction['start_date'] ?></td>
                                    <td><?php echo $reaction['meddra_code'] ?></td>
                                    <!-- <td></td> -->
                                    <td><?php if (!empty($reaction['criteria_death_value'])) {
                                            echo $reaction['criteria_death_value'];
                                        } else {
                                            echo  $reaction['criteria_death_null'];
                                        } ?></td>
                                    <td><?php if (!empty($reaction['life_threatening_value'])) {
                                            echo $reaction['life_threatening_value'];
                                        } else {
                                            echo  $reaction['life_threatening_null'];
                                        } ?></td>
                                    <td><?php if (!empty($reaction['prolonged_hospitalisation_value'])) {
                                            echo $reaction['prolonged_hospitalisation_value'];
                                        } else {
                                            echo  $reaction['prolonged_hospitalisation_null'];
                                        } ?></td>
                                    <td><?php if (!empty($reaction['incapacitating_value'])) {
                                            echo $reaction['incapacitating_value'];
                                        } else {
                                            echo  $reaction['incapacitating_null'];
                                        } ?></td>
                                    <td><?php if (!empty($reaction['birth_defect_value'])) {
                                            echo $reaction['birth_defect_value'];
                                        } else {
                                            echo  $reaction['birth_defect_null'];
                                        } ?></td>
                                    <td><?php echo $reaction['source_country'] ?></td>
                                    <td><?php
                                        $outcomes = array(
                                            '1' => 'Recovered/Resolved',
                                            '2' => 'Recovering/Resolving',
                                            '3' => 'Recovered/Resolved with sequelae',
                                            '4,' => 'Not recovered/Not resolved',
                                            '5' => 'Fatal',
                                            '6' => 'unknown',
                                        );
                                        if (!empty($reaction['reaction_outcome_value'])) echo $outcomes[strtolower($reaction['reaction_outcome_value'])];
                                        ?></td>
                                </tr>

                                <!-- 
                              
                            
                            
                            <tr width="100%">
                                <td>Reaction / event as reported by the primary source in native language</td>

                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Reaction / event as reported by the primary source language</td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Reaction / event as reported by the primary source for translation</td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td>MedDRA version for reaction/event</td>
                                <td>Reaction/event (MedDRA code)</td>
                                <td>Term highlighted by the reporter</td>
                                <td>Seriousness criteria at event level</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>--->
                                <!--  <tr>
                                <td>Results in death</td>
                                <td>Life threatening</td>
                                <td>Caused / prolonged hospitalization </td>
                                <td>Disabling / Incapacitating</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr> -->
                                <!-- <tr>
                                <td>Congenital anomaly / birth defect</td>
                                <td>Other medically important condition</td>
                            </tr> 
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Date of start of reaction / event</td>
                                <td>Date of end of reaction / event</td>
                                <td>Duration of reaction / event (number)</td>
                                <td>Duration of reaction / event (unit)</td>
                            </tr>
                            <tr>
                                <td>Outcome of Reaction / Event at the time of Last Observation</td>
                                <td>Medical confirmation by healthcare professional </td>
                                <td>Country where the reaction / event occured</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr> -->
                            <?php } ?>
                        </table>
                        <!-- <h5 style="background: #18C4D1; padding:20px;">Criteria</h5>
                        <table class="table" style="width: 20%;">

                            <tr>
                                <th>Results in death</th>
                                <th> 
                                <?php echo ($ce2b['Ce2b']['results_in_detah']   ? $ichecked : $nchecked); ?>
                            </th>
                            <tr>
                                <th>Life threatening</th>
                                <th> 
                                <?php echo ($ce2b['Ce2b']['life_threatening']   ? $ichecked : $nchecked); ?></th>
                            <tr>
                                <th>Caused / prolonged hospitalization </th>
                                <th> 
                                <?php echo ($ce2b['Ce2b']['prolonged_hospitalization']   ? $ichecked : $nchecked); ?></th>
                            <tr>
                                <th>Disabling / Incapacitating</th>
                                <th> 
                                <?php echo ($ce2b['Ce2b']['incapacitating']   ? $ichecked : $nchecked); ?></th>
                            </tr>
                            <tr>
                            <th>Congenital anomaly / birth defect</th>  <th> 
                            <?php echo ($ce2b['Ce2b']['incapacitating']   ? $ichecked : $nchecked); ?></th>
                            </tr>

                        </table> -->

                        <!-- <h5 style="background: #18C4D1; padding:20px;">Outcome</h5>
                        <table class="table" style="width: 20%;">

                            <tr> 
                                <th> 
                                <?php echo $ce2b['Ce2b']['results_in_detah']; ?>
                            </th>
                            </tr>
                        </table> -->
                        <!-- <h5 style="background: #18C4D1; padding:20px;">Results of Tests and Procedures Relevant to the Investigation of the Patient</h5>
                        <table class="table" style="width: 100%;"> -->
                        <!-- <tr width="100%">
                                <td>Test date</td>
                                <td>Test name</td>
                                <td>Test name (free text)</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>MedDRA version for test name</td>
                                <td>Test name (MedDRA code)</td> 
                            </tr>
                            <tr>
                                <td></td>
                                <td></td> 
                            </tr>
                            <tr>
                                <td>Test results</td>
                                <td>Test results (code)</td>
                                <td>Test results (value /qualifier)</td>
                                <td>Test results (unit)</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Results unstructured data (free text)</td> 
                            </tr>
                            <tr>
                                <td></td> 
                            </tr>
                            <tr>
                                <td>Normal low value</td>
                                <td>Normal high value</td> 
                            </tr>
                            <tr>
                                <td></td>
                                <td></td> 
                            </tr>
                            <tr>
                                <td>Comments (free text)</td> 
                            </tr>
                            <tr>
                                <td></td> 
                            </tr>
                            <tr>
                                <td>More information available</td> 
                            </tr>
                            <tr>
                                <td></td> 
                            </tr> -->
                        <!-- </table> -->
                        <h5 style="background: #18C4D1; padding:20px;">Drugs Information</h5>

                        <table class="table" style="width: 100%;">
                            <thead>
                                <th>Drug Name</th>
                                <th>Brand</th>
                                <th>Dose</th>
                                <th>Route</th>
                            </thead>
                            <tbody>
                                <?php

                                foreach ($ce2b['Ce2bListOfDrug'] as $drug) {
                                ?>
                                    <tr>
                                        <td><?php echo $drug['drug_name'] ?></td>
                                        <td><?php echo $drug['brand_name'] ?></td>
                                        <td><?php echo $drug['dose'] ?></td>
                                        <td><?php echo $drug['Route']['name'] ?></td>
                                    </tr>

                                <?php } ?>

                            </tbody>
                        </table>

                        <h5 style="background: #18C4D1; padding:20px;">Narrative case summary and further information</h5>

                        <table class="table" style="width: 100%;">


                            <tr>
                                <td colspan="2"><?php echo $ce2b['Ce2b']['case_narrative']; ?></td>
                            </tr>

                        </table>

                    </div>
                </div>


                <!-- End of Updated UI -->



            </div> <!-- /art-sheet -->
        </div> <!-- /art-sheet -->
    </div>
</div>