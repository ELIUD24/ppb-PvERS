<?php
$this->assign('E2B', 'active');
$ichecked = "&#x2611;";
$nchecked = "&#x2610;";
$radio_selected = "&#x1F518;"; // 🔘
$radio_unselected = "&#x26AA;"; // ⚪

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
                                <td colspan="2">
                                    <?php
                                    if (isset($ce2b['Ce2b']['patient_sex'])) {
                                        $patientSex = $ce2b['Ce2b']['patient_sex'];
                                        if ($patientSex == 2) {
                                            echo 'Female';
                                        } elseif ($patientSex == 1) {
                                            echo 'Male';
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <th colspan="4">Text for relevant medical history and concurrent conditions (not including reaction/event)</th>
                            </tr>
                            <tr>
                                <td colspan="4"><?php echo $ce2b['Ce2b']['past_medical']; ?></td>
                            </tr>

                        </table>
                        <!-- show if serious -->
                        <h5>Serious</h5>

                        <?php

                        if (isset($ce2b['Ce2b']['serious']) && $ce2b['Ce2b']['serious'] == 1) {
                            echo '' . ' Yes';
                        } else {
                            echo ''  . ' No';
                        }

                        ?>
                        <br>
                        <br>

                        <h5 style="background: #18C4D1; padding:20px;">Reaction(s)/Event(s)</h5>

                        <table class="table" style="width: 100%;">
                            <tr>
                                <th>Reaction Name</th>
                                <th>Start Date</th>
                                <th>Stop Date</th>
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
                                    <td><?php echo $reaction['meddra_name'] ?>
                                        <br>
                                        <!--  Add a smaller span text with value of   -->
                                        <span style="font-size: 0.8em; color: #888;">
                                            <?php echo $reaction['reaction_name'] ?>
                                        </span>
                                    </td>
                                    <td><?php echo $reaction['start_date'] ?></td>
                                    <td><?php echo $reaction['stop_date'] ?></td>


                                    <td>
                                        <input type="checkbox" disabled <?php if (!empty($reaction['criteria_death_value']) && $reaction['criteria_death_value'] === "true") echo 'checked'; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" disabled <?php if (!empty($reaction['life_threatening_value']) && $reaction['life_threatening_value'] === "true") echo 'checked'; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" disabled <?php if (!empty($reaction['prolonged_hospitalisation_value']) && $reaction['prolonged_hospitalisation_value'] === "true") echo 'checked'; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" disabled <?php if (!empty($reaction['incapacitating_value']) && $reaction['incapacitating_value'] === "true") echo 'checked'; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" disabled <?php if (!empty($reaction['birth_defect_value']) && $reaction['birth_defect_value'] === "true") echo 'checked'; ?>>
                                    </td>

                                    <td><?php echo $reaction['source_country'] ?></td>
                                    <td><?php
                                        $outcomes = array(
                                            '1' => 'Recovered/Resolved',
                                            '2' => 'Recovering/Resolving',
                                            '3' => 'Recovered/Resolved with sequelae',
                                            '4' => 'Not recovered/Not resolved',
                                            '5' => 'Fatal',
                                            '0' => 'unknown',
                                        );

                                        if (isset($reaction['reaction_outcome_value'])) {

                                            $value = $reaction['reaction_outcome_value'];

                                            if (isset($outcomes[$value])) {
                                                echo $outcomes[$value];
                                            } else {
                                                echo ' ';
                                            }
                                        } else {
                                            echo '';
                                        }

                                        ?></td>
                                </tr>

                            <?php } ?>
                        </table>

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
                                        <td><?php echo $drug['route'] ?></td>
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




            </div> <!-- /art-sheet -->
        </div> <!-- /art-sheet -->
    </div>
</div>