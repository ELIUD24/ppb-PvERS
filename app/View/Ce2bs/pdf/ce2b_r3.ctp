<section id="sadrsview">

    <div class="row-fluid" id="abonokode">
        <div class="span12">

            <div id="printAreade">
                <div class="formbackd">

                    <p><b>(FOM001/HPT/VMS/SOP/001)</b></p>
                    <div class="row-fluid">
                        <div class="span12">
                            <?php
                            echo ($this->Html->image('arms.png', array('alt' => 'in confidence', 'style' => 'margin-left: 45%;', 'fullBase' => true, 'class' => 'pull-right')));

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

                    <div class="row-fluid">
                        <div class="span12">
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
                                            <td><?php echo $ce2b['Ce2b']['creation_time']; ?></td>
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


                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 20%;">NAME OF PERSON REPORTING:</td>
                                            <td style="width: 30%;"><strong><?php echo $ce2b['Ce2b']['reporter_name'] ?></strong></td>
                                            <td style="width: 15%;">Qualification:</td>
                                            <td style="width: 15%;"><strong><?php echo $ce2b['Designation']['name'] ?></strong></td>
                                            <td style="width: 10%;">TELEPHONE:</td>
                                            <td style="width: 10%;"><strong><?php echo $ce2b['Ce2b']['reporter_phone'] ?></strong></td>
                                        <tr>
                                            <td style="width: 25%;">E-MAIL ADDRESS: </td>
                                            <td style="width: 40%;"><strong><?php echo $ce2b['Ce2b']['reporter_email'] ?></strong></td>
                                            <td style="width: 15%;">PHONE NO.</td>
                                            <td style="width: 20%;"><strong><?php echo $ce2b['Ce2b']['reporter_phone'] ?></strong></td>
                                        </tr>
                                    </table>
 
                                    <h5 style="background: #18C4D1; padding:20px;">Information on Sender of Case Safety Report</h5>
                                    <table style="width: 100%;">
                                        <tr width="100%">
                                            <th style="width: 25%;">Sender's Name</th>
                                            <th style="width: 25%;">Sender's Email Address</th>
                                            <th style="width: 25%;">Sender's Telephone</th>
                                            <th style="width: 25%;">Sender's Organization</th>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;"><?php ?></td>
                                            <td style="width: 25%;"><?php  ?></td>
                                            <td style="width: 25%;"><?php  ?></td>
                                            <td style="width: 25%;"><?php echo $ce2b['Ce2b']['sender_organization']; ?></td>
                                        </tr>
                                        <tr>
                                            <th style="width: 25%;">Sender's Department</th>
                                            <th style="width: 25%;">Sender's Physicall Address </th>
                                            <th style="width: 25%;">Sender's Fax</th>
                                            <th style="width: 25%;">Sender's Qualification</th>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;"><?php echo $ce2b['Ce2b']['sender_department']; ?></td>
                                            <td style="width: 25%;"><?php echo $ce2b['Ce2b']['sender_address']; ?></td>
                                            <td style="width: 25%;"><?php ?></td>
                                            <td style="width: 25%;"></td>
                                        </tr>

                                    </table>
                                    <h5 style="background: #18C4D1; padding:20px;">Literature Reference(s)</h5>

                                    <table style="width: 100%;">
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

                                    <table style="width: 100%;">
                                        <tr width="100%">
                                            <th style="width: 25%;">Patient's Name or Initials</th>
                                            <th style="width: 25%;">Patient's medical record number</th>
                                            <th style="width: 25%;">Patient's Age</th>
                                            <th style="width: 25%;">Patient's Date of Birth</th>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;"><?php echo $ce2b['Ce2b']['patient_name']; ?></td>
                                            <td style="width: 25%;"><?php echo $ce2b['Ce2b']['patient_number']; ?></td>
                                            <td style="width: 25%;"></td>
                                            <td style="width: 25%;"><?php
                                                                    echo date('Y-m-d', strtotime($ce2b['Ce2b']['patient_dob'])); ?></td>
                                        </tr>

                                        <tr>
                                            <th style="width: 25%;">Body weight (kg)</th>
                                            <th style="width: 25%;">Height (cm)</th>
                                            <th style="width: 25%;">Sex</th>
                                            <th>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;"></td>
                                            <td style="width: 25%;"></td>
                                            <td style="width: 25%;"><?php
                                                                    if (isset($ce2b['Ce2b']['patient_sex'])) {
                                                                        $patientSex = $ce2b['Ce2b']['patient_sex'];
                                                                        if ($patientSex == 2) {
                                                                            echo 'Female';
                                                                        } elseif ($patientSex == 1) {
                                                                            echo 'Male';
                                                                        }
                                                                    }
                                                                    ?></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <th colspan="4">Text for relevant medical history and concurrent conditions (not including reaction/event)</th>
                                        </tr>
                                        <tr>
                                            <td colspan="4"><?php echo $ce2b['Ce2b']['past_medical']; ?></td>
                                        </tr>

                                    </table>

                                    <h5 style="background: #18C4D1; padding:20px;">Reaction(s)/Event(s)</h5>

                                    <table class="table" style="width: 100%;">
                                        <tr>
                                            <th style="width: 30%;">Reaction Name</th>
                                            <th style="width: 10%;">Start Date</th>
                                            <th style="width: 10%;">MedDRA Code</th>
                                            <th style="width: 5%;">Results in death</th>
                                            <th style="width: 5%;">Life threatening</th>
                                            <th style="width: 5%;">Caused / prolonged hospitalization </th>
                                            <th style="width: 5%;">Disabling / Incapacitating</th>
                                            <th style="width: 10%;">Congenital anomaly / birth defect</th>
                                            <th style="width: 10%;">Occurence Country</th>
                                            <th style="width: 10%;">Outcome</th>
                                        </tr>
                                        <?php

                                        foreach ($ce2b['Ce2bReaction'] as $reaction) {
                                        ?>
                                            <tr>
                                                <td style="width: 30%;"><?php echo $reaction['reaction_name'] ?></td>
                                                <td style="width: 10%;"><?php echo $reaction['start_date'] ?></td>
                                                <td style="width: 10%;"><?php echo $reaction['meddra_code'] ?></td>
                                                <td style="width: 5%;"><?php if (!empty($reaction['criteria_death_value'])) {
                                                                            echo $reaction['criteria_death_value'];
                                                                        } else {
                                                                            echo  $reaction['criteria_death_null'];
                                                                        } ?></td>
                                                <td style="width: 5%;"><?php if (!empty($reaction['life_hreatening_value'])) {
                                                                            echo $reaction['life_hreatening_value'];
                                                                        } else {
                                                                            echo  $reaction['life_hreatening_null'];
                                                                        } ?></td>
                                                <td style="width: 5%;"><?php if (!empty($reaction['prolonged_hospitalisation_value'])) {
                                                                            echo $reaction['prolonged_hospitalisation_value'];
                                                                        } else {
                                                                            echo  $reaction['prolonged_hospitalisation_null'];
                                                                        } ?></td>
                                                <td style="width: 5%;"><?php if (!empty($reaction['incapacitating_value'])) {
                                                                            echo $reaction['incapacitating_value'];
                                                                        } else {
                                                                            echo  $reaction['incapacitating_null'];
                                                                        } ?></td>
                                                <td style="width: 10%;"><?php if (!empty($reaction['birth_defect_value'])) {
                                                                            echo $reaction['birth_defect_value'];
                                                                        } else {
                                                                            echo  $reaction['birth_defect_null'];
                                                                        } ?></td>
                                                <td style="width: 10%;"><?php echo $reaction['source_country'] ?></td>
                                                <td style="width: 10%;"><?php
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

                                        <?php } ?>
                                    </table>

                                    <h5 style="background: #18C4D1; padding:20px;">Drugs Information</h5>

                                    <table style="width: 100%;">
                                        <thead>
                                            <td style="width: 25%;">Drug Name</th>
                                            <td style="width: 25%;">Brand</th>
                                            <td style="width: 25%;">Dose</th>
                                            <td style="width: 25%;">Route</th>
                                        </thead>
                                        <tbody>
                                            <?php

                                            foreach ($ce2b['Ce2bListOfDrug'] as $drug) {
                                            ?>
                                                <tr>
                                                    <td style="width: 25%;"><?php echo $drug['drug_name'] ?></td>
                                                    <td style="width: 25%;"><?php echo $drug['brand_name'] ?></td>
                                                    <td style="width: 25%;"><?php echo $drug['dose'] ?></td>
                                                    <td style="width: 25%;"><?php echo $drug['route'] ?></td>
                                                </tr>

                                            <?php } ?>

                                        </tbody>
                                    </table>

                                    <h5 style="background: #18C4D1; padding:20px;">Narrative case summary and further information</h5>

                                    <table style="width: 100%;">
                                        <tr>
                                            <td colspan="2"><?php echo $ce2b['Ce2b']['case_narrative']; ?></td>
                                        </tr>

                                    </table>
                                    <hr>

                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 25%;">NAME OF PERSON REPORTING:</td>
                                            <td style="width: 40%;"><strong><?php echo $ce2b['Ce2b']['reporter_name'] ?></strong></td>
                                            <td style="width: 15%;">DESIGNATION:</td>
                                            <td style="width: 20%;"><strong><?php echo $ce2b['Designation']['name'] ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;">E-MAIL ADDRESS: </td>
                                            <td style="width: 40%;"><strong><?php echo $ce2b['Ce2b']['reporter_email'] ?></strong></td>
                                            <td style="width: 15%;">PHONE NO.</td>
                                            <td style="width: 20%;"><strong><?php echo $ce2b['Ce2b']['reporter_phone'] ?></strong></td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>