<?php
$this->assign('CIOM', 'active');

$outcomes = array(
    '1' => 'recovered/resolved',
    '2' => 'recovering/resolving',
    '3' => 'not recovered/not resolved',
    '4' => 'recovered/resolved with sequelae',
    '5' => 'fatal',
    '6' => 'unknown'
);
$actiondrug = array(
    '1' => 'Drug withdrawn',
    '2' => 'Dose reduced',
    '3' => 'Dose increased',
    '4' => 'Dose not changed',
    '5' => 'Unknown',
    '6' => 'Not applicable'
);

$serious = array('1' => 'Yes', '2' => 'No');
$drugcharacterization = array('1' => 'Suspect', '2' => 'Concomitant', '3' => 'Interacting');
// debug($e2b);

$drugadministrationroute = [
    '001' => 'Auricular (otic)', '002' => 'Buccal', '003' => 'Cutaneous', '004' => 'Dental', '005' => 'Endocervical',
    '006' => 'Endosinusial', '007' => 'Endotracheal', '008' => 'Epidural', '009' => 'Extra-amniotic', '010' => 'Hemodialysis', '011' => 'Intra corpus cavernosum',
    '012' => 'Intra-amniotic', '013' => 'Intra-arterial', '014' => 'Intra-articular', '015' => 'Intra-uterine', '016' => 'Intracardiac', '017' => 'Intracavernous',
    '018' => 'Intracerebral', '019' => 'Intracervical', '020' => 'Intracisternal', '021' => 'Intracorneal', '022' => 'Intracoronary', '023' => 'Intradermal',
    '024' => 'Intradiscal (intraspinal)', '025' => 'Intrahepatic', '026' => 'Intralesional', '027' => 'Intralymphatic', '028' => 'Intramedullar (bone marrow)', '029' => 'Intrameningeal',
    '030' => 'Intramuscular', '031' => 'Intraocular', '032' => 'Intrapericardial', '033' => 'Intraperitoneal', '034' => 'Intrapleural', '035' => 'Intrasynovial',
    '036' => 'Intratumor', '037' => 'Intrathecal', '038' => 'Intrathoracic', '039' => 'Intratracheal', '040' => 'Intravenous bolus', '041' => 'Intravenous drip',
    '042' => 'Intravenous (not otherwise specified)', '043' => 'Intravesical', '044' => 'Iontophoresis', '045' => 'Nasal',
    '046' => 'Occlusive dressing technique', '047' => 'Ophthalmic', '048' => 'Oral', '049' => 'Oropharingeal', '050' => 'Other', '051' => 'Parenteral', '052' => 'Periarticular', '053' => 'Perineural', '054' => 'Rectal',
    '055' => 'Respiratory (inhalation)', '056' => 'Retrobulbar', '057' => 'Sunconjunctival', '058' => 'Subcutaneous', '059' => 'Subdermal', '060' => 'Sublingual', '061' => 'Topical', '062' => 'Transdermal',
    '063' => 'Transmammary', '064' => 'Transplacental', '065' => 'Unknown', '066' => 'Urethral', '067' => 'Vaginal'
];

$time_unit = ['801' => 'Year', '802' => 'Month', '803' => 'Week', '804' => 'Day', '805' => 'Hour', '806' => 'Minute'];

$actions = [
    '1' => 'Drug withdrawn',
    '2' => 'Dose reduced',
    '3' => 'Dose increased',
    '4' => 'Dose not changed',
    '5' => 'Unknown',
    '6' => 'Not applicable'
];

$drugrecurreadministration = ['1' => 'Yes', '2' => 'No', '3' => 'Unknown'];
$reporttype = ['1' => 'Spontaneous', '2' => 'Report from study', '3' => 'Other', '4' => 'Not available to sender (unknown)'];
$qualification = ['1' => 'Physician', '2' => 'Pharmacist', '3' => 'Other Health Professional', '4' => 'Lawyer', '5' => 'Consumer or other non-health professional'];
?>
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
                <div class="ciom-form">

                    <hr>
                    <table class="table" style="width: 100%;">
                    <tr>
                        <td style="width: 10%;"><b>E2B FILE:</b> </td>
                    </tr>
                    <tr>
                        <td style="width: 10%;">COMPANY NAME: </td>
                        <td style="width: 25%;">
                            <p><strong><?php echo $ce2b['Ce2b']['company_name'] ?></strong></p>
                        </td>
                        <td style="width: 10%;">REPORT ID: </td>
                        <td style="width: 20%;">
                            <p><strong><?php echo $ce2b['Ce2b']['reference_no'] ?></strong></p>
                        </td>
                        <td style="width: 10%;">COMMENTS: </td>
                        <td style="width: 25%;">
                            <p><strong><?php echo $ce2b['Ce2b']['comment'] ?></strong></p>
                        </td>
                    </tr>
                </table>
                    <table class="table  table-condensed">
                        <thead>
                            <tr style="background: #C5D9F0;">
                                <th>E2B Form</th>
                                <th>ICH-E2B field (R2)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background: #DAEDF3;">
                                <td colspan="2"> I. REACTION INFORMATION </td>
                            </tr>
                            <tr>
                                <td width="30%" class="table-label required">
                                    <p>1. Patient Initials <small class="muted">(first, last)</small> </p>
                                </td>
                                <td><?php
                                    // debug(Hash::extract($e2b, 'ichicsr.safetyreport.patient.patientinitial'));
                                    // debug(Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.patientinitial'));
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.patientinitial'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.patientinitial')));
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['patientinitial'])) ? $e2b['ichicsr']['safetyreport']['patient']['patientinitial'] : null; 
                                    ?></td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>1.a Country</p>
                                </td>
                                <td><?php
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.occurcountry'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.occurcountry')));
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['occurcountry'])) ? $e2b['ichicsr']['safetyreport']['occurcountry'] : null; 
                                    ?></td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>2. Date of birth</p>
                                </td>
                                <td><?php
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.patientbirthdate'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.patientbirthdate')));
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['patientbirthdate'])) ? $e2b['ichicsr']['safetyreport']['patient']['patientbirthdate'] : null; 
                                    ?></td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>2.a Age <small class="muted">(years)</small> </p>
                                </td>
                                <td><?php
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.patientonsetage'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.patientonsetage')));
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['patientonsetage'])) ? $e2b['ichicsr']['safetyreport']['patient']['patientonsetage'] : null; 
                                    ?></td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>2. Sex </p>
                                </td>
                                <td><?php
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.patientsex'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.patientsex')));
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['patientsex'])) ? $e2b['ichicsr']['safetyreport']['patient']['patientsex'] : null; 
                                    ?></td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>4-6. Reaction onset </p>
                                </td>
                                <td><?php echo (!empty($e2b['ichicsr']['safetyreport']['patient']['reaction']['reactionstartdate'])) ? $e2b['ichicsr']['safetyreport']['patient']['reaction']['reactionstartdate'] : null; ?></td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>7. Describe reaction(s) </p>
                                </td>
                                <td>
                                    <?php
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.reaction.primarysourcereaction'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.reaction.{n}.primarysourcereaction')));
                                    // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['reaction']['primarysourcereaction'])) ? $e2b['ichicsr']['safetyreport']['patient']['reaction']['primarysourcereaction'] : null; 
                                    echo "<br/>";
                                    $out = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.reaction.reactionoutcome'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.reaction.{n}.reactionoutcome'));
                                    array_walk($out, function (&$value, &$key) use ($outcomes) {
                                        $value = (isset($outcomes[$value])) ? $outcomes[$value] : $value;
                                    });
                                    echo implode(" | ", $out);
                                    // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['reaction']['reactionoutcome'])) ? $outcomes[$e2b['ichicsr']['safetyreport']['patient']['reaction']['reactionoutcome']] : null; 
                                    echo "<br/>";
                                    $ad = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.actiondrug'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.actiondrug'));
                                    array_walk($ad, function (&$value, &$key) use ($actiondrug) {
                                        $value = (isset($actiondrug[$value])) ? $actiondrug[$value] : $value;
                                    });
                                    echo implode(" | ", $ad);
                                    echo "<br/>";
                                    echo implode(" <br>|<br> ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.summary.narrativeincludeclinical'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.summary.narrativeincludeclinical')));
                                    // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['summary']['narrativeincludeclinical'])) ? $e2b['ichicsr']['safetyreport']['patient']['summary']['narrativeincludeclinical'] : null; 
                                    // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['reaction']['primarysourcereaction'])) ? $e2b['ichicsr']['safetyreport']['patient']['reaction']['primarysourcereaction'] : null; 
                                    // echo "<br/>";
                                    // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['reaction']['reactionoutcome'])) ? $outcomes[$e2b['ichicsr']['safetyreport']['patient']['reaction']['reactionoutcome']] : null; 
                                    // echo "<br/>";
                                    // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['actiondrug'])) ? $actiondrug[$e2b['ichicsr']['safetyreport']['patient']['drug']['actiondrug']] : null; 
                                    // echo "<br/>";
                                    // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['summary']['narrativeincludeclinical'])) ? $e2b['ichicsr']['safetyreport']['patient']['summary']['narrativeincludeclinical'] : null; 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>13. (including relevant test lab data) </p>
                                </td>
                                <td>
                                    <?php
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.resultstestsprocedures'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.resultstestsprocedures')));
                                    // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['resultstestsprocedures'])) ? $e2b['ichicsr']['safetyreport']['patient']['resultstestsprocedures'] : null; 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>8-12. Check all appropriate to adverse reaction </p>
                                </td>
                                <td>
                                    <p>Serious - at case level? </p>
                                    <?php
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['serious'])) ? $serious[$e2b['ichicsr']['safetyreport']['serious']] : null;
                                    $se = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.serious'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.serious'));
                                    array_walk($se, function (&$value, &$key) use ($serious) {
                                        $value = (isset($serious[$value])) ? $serious[$value] : $value;
                                    });
                                    echo implode(" | ", $se);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Patient died </p>
                                </td>
                                <td>
                                    <?php
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['seriousnessdeath'])) ? $serious[$e2b['ichicsr']['safetyreport']['seriousnessdeath']] : null; 
                                    $de = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.seriousnessdeath'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.seriousnessdeath'));
                                    array_walk($de, function (&$value, &$key) use ($serious) {
                                        $value = (isset($serious[$value])) ? $serious[$value] : $value;
                                    });
                                    echo implode(" | ", $de);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Involved or prolonged inpatient hospitalization </p>
                                </td>
                                <td>
                                    <?php
                                    // echo (!empty($e2b['ichicsr']['safetyreport']['seriousnesshospitalization'])) ? $serious[$e2b['ichicsr']['safetyreport']['seriousnesshospitalization']] : null; 
                                    $ho = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.seriousnesshospitalization'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.seriousnesshospitalization'));
                                    array_walk($ho, function (&$value, &$key) use ($serious) {
                                        $value = (isset($serious[$value])) ? $serious[$value] : $value;
                                    });
                                    echo implode(" | ", $ho);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Involved persistence or significant disability or incapacity </p>
                                </td>
                                <td><?php
                                    // echo (!empty($e2b['ichicsr']['safetyreport']['seriousnessdisabling'])) ? $serious[$e2b['ichicsr']['safetyreport']['seriousnessdisabling']] : null; 
                                    $bl = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.seriousnessdisabling'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.seriousnessdisabling'));
                                    array_walk($bl, function (&$value, &$key) use ($serious) {
                                        $value = (isset($serious[$value])) ? $serious[$value] : $value;
                                    });
                                    echo implode(" | ", $bl);
                                    ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Life threatening </p>
                                </td>
                                <td><?php
                                    // echo (!empty($e2b['ichicsr']['safetyreport']['seriousnesslifethreatening'])) ? $serious[$e2b['ichicsr']['safetyreport']['seriousnesslifethreatening']] : null; 
                                    $lt = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.seriousnesslifethreatening'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.seriousnesslifethreatening'));
                                    array_walk($lt, function (&$value, &$key) use ($serious) {
                                        $value = (isset($serious[$value])) ? $serious[$value] : $value;
                                    });
                                    echo implode(" | ", $lt);
                                    ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table  table-condensed">
                        <thead>
                            <tr style="background: #DAEDF3;">
                                <th class="table-label required">
                                    <p>SUSPECT/CONCOMITANT DRUG(S) INFORMATION</p>
                                </th>
                                <th>Drug characterization: <?php
                                                            // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugcharacterization'])) ? $drugcharacterization[$e2b['ichicsr']['safetyreport']['patient']['drug']['drugcharacterization']] : null; 
                                                            $dc = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugcharacterization'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugcharacterization'));
                                                            array_walk($dc, function (&$value, &$key) use ($drugcharacterization) {
                                                                $value = (isset($drugcharacterization[$value])) ? $drugcharacterization[$value] : $value;
                                                            });
                                                            echo implode(" | ", $dc);
                                                            ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="table-label required">
                                    <p>14. Suspect drug(s)</p>
                                </td>
                                <td><?php
                                    // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['medicinalproduct'])) ? $e2b['ichicsr']['safetyreport']['patient']['drug']['medicinalproduct'] : null; 
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.medicinalproduct'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.medicinalproduct')));
                                    ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Batch/lot number</p>
                                </td>
                                <td><?php
                                    // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugbatchnumb'])) ? $e2b['ichicsr']['safetyreport']['patient']['drug']['drugbatchnumb'] : null; 
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugbatchnumb'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugbatchnumb')));
                                    ?></td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>15. Daily dose(s)</p>
                                </td>
                                <td><?php
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugdosagetext'])) ? $e2b['ichicsr']['safetyreport']['patient']['drug']['drugdosagetext'] : null; 
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugdosagetext'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugdosagetext')));
                                    ?></td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>16. Route(s) of administration</p>
                                </td>
                                <td><?php
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugadministrationroute'])) ? $drugadministrationroute[$e2b['ichicsr']['safetyreport']['patient']['drug']['drugadministrationroute']] : null; 
                                    $ar = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugadministrationroute'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugadministrationroute'));
                                    array_walk($ar, function (&$value, &$key) use ($drugadministrationroute) {
                                        $value = (isset($drugadministrationroute[$value])) ? $drugadministrationroute[$value] : $value;
                                    });
                                    echo implode(" | ", $ar);
                                    ?></td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>17. Indication(s) for use</p>
                                </td>
                                <td><?php
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugindication'])) ? $e2b['ichicsr']['safetyreport']['patient']['drug']['drugindication'] : null; 
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugindication'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugindication')));
                                    ?></td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>18. Therapy dates</p>
                                </td>
                                <td>
                                    <p>Date of start of drug</p>
                                    <?php
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugstartdate'])) ? $e2b['ichicsr']['safetyreport']['patient']['drug']['drugstartdate'] : null; 
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugstartdate'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugstartdate')));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p></p>
                                </td>
                                <td>
                                    <p>Date of last administration</p>
                                    <?php
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugenddate'])) ? $e2b['ichicsr']['safetyreport']['patient']['drug']['drugenddate'] : null; 
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugenddate'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugenddate')));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>19. Therapy duration</p>
                                </td>
                                <td>
                                    <?php
                                    // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugtreatmentduration'])) ? $e2b['ichicsr']['safetyreport']['patient']['drug']['drugtreatmentduration'] : null; 
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugtreatmentduration'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugtreatmentduration')));
                                    echo "<br>";
                                    // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugtreatmentdurationunit'])) ? $time_unit[$e2b['ichicsr']['safetyreport']['patient']['drug']['drugtreatmentdurationunit']] : null; 
                                    $dr = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugtreatmentdurationunit'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugtreatmentdurationunit'));
                                    array_walk($dr, function (&$value, &$key) use ($time_unit) {
                                        $value = (isset($time_unit[$value])) ? $time_unit[$value] : $value;
                                    });
                                    echo implode(" | ", $dr);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>20. Did reaction abate after stopping drug?</p>
                                </td>
                                <td><?php
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['actiondrug'])) ? $actiondrug[$e2b['ichicsr']['safetyreport']['patient']['drug']['actiondrug']] : null; 
                                    $ad = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.actiondrug'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.actiondrug'));
                                    array_walk($ad, function (&$value, &$key) use ($actiondrug) {
                                        $value = (isset($actiondrug[$value])) ? $actiondrug[$value] : $value;
                                    });
                                    echo implode(" | ", $ad);
                                    ?></td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>21. Did reaction reappear after reintroduction?</p>
                                </td>
                                <td><?php
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugrecurreadministration'])) ? $drugrecurreadministration[$e2b['ichicsr']['safetyreport']['patient']['drug']['drugrecurreadministration']] : null; 
                                    $re = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugrecurreadministration'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugrecurreadministration'));
                                    array_walk($re, function (&$value, &$key) use ($drugrecurreadministration) {
                                        $value = (isset($drugrecurreadministration[$value])) ? $drugrecurreadministration[$value] : $value;
                                    });
                                    echo implode(" | ", $re);
                                    ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table  table-condensed">
                        <thead>
                            <tr style="background: #DAEDF3;">
                                <th colspan="2" class="table-label required">
                                    <p>MANUFACTURER INFORMATION</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="table-label required">
                                    <p>Name and address of manufacturer</p>
                                </td>
                                <td><?php
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['duplicatesource'])) ? $e2b['ichicsr']['safetyreport']['duplicatesource'] : null; 
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.duplicatesource'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.reportduplicate.{n}.duplicatesource')));
                                    ?></td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>MFR control no.</p>
                                </td>
                                <td><?php
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['duplicate'])) ? $e2b['ichicsr']['safetyreport']['duplicate'] : null; 
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.duplicate'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.duplicate')));
                                    ?></td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>Date received by manufacturer</p>
                                </td>
                                <td><?php
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['receiptdate'])) ? $e2b['ichicsr']['safetyreport']['receiptdate'] : null; 
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.receiptdate'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.receiptdate')));
                                    ?></td>
                            </tr>
                            <tr>
                                <td class="table-label required">
                                    <p>Report source</p>
                                </td>
                                <td>
                                    <p>Type of report</p>
                                    <?php
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['reporttype'])) ? $reporttype[$e2b['ichicsr']['safetyreport']['reporttype']] : null; 
                                    $re = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.reporttype'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.reporttype'));
                                    array_walk($re, function (&$value, &$key) use ($reporttype) {
                                        $value = (isset($reporttype[$value])) ? $reporttype[$value] : $value;
                                    });
                                    echo implode(" | ", $re);
                                    ?>
                                    <p>Literature reference(s)</p>
                                    <?php
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['primarysource']['literaturereference'])) ? $e2b['ichicsr']['safetyreport']['primarysource']['literaturereference'] : null; 
                                    echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.primarysource.literaturereference'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.primarysource.{n}.literaturereference')));
                                    ?>
                                    <p>Qualification</p>
                                    <?php
                                    //echo (!empty($e2b['ichicsr']['safetyreport']['primarysource']['qualification'])) ? $qualification[$e2b['ichicsr']['safetyreport']['primarysource']['qualification']] : null; 
                                    $qa = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.primarysource.qualification'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.primarysource.{n}.qualification'));
                                    array_walk($qa, function (&$value, &$key) use ($qualification) {
                                        $value = (isset($qualification[$value])) ? $qualification[$value] : $value;
                                    });
                                    echo implode(" | ", $qa);
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
<hr>
                    <?php
                if ($this->Session->read('Auth.User.user_type') != 'Public Health Program') {
                ?>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 25%;">NAME OF PERSON REPORTING:</td>
                            <td style="width: 25%;"><strong><?php echo $ce2b['Ce2b']['reporter_name'] ?></strong></td>
                            <td style="width: 25%;">E-MAIL ADDRESS: </td>
                            <td style="width: 25%;"><strong><?php echo $ce2b['Ce2b']['reporter_email'] ?></strong></td>
                        </tr>
                        <tr>
                            <td style="width: 25%;">DESIGNATION:</td>
                            <td style="width: 25%;"><strong><?php echo $ce2b['Designation']['name'] ?></strong></td>
                            <td style="width: 25%;">PHONE NO.</td>
                            <td style="width: 25%;"><strong><?php echo $ce2b['Ce2b']['reporter_phone'] ?></strong></td>
                        </tr>
                        <tr>
                            <td style="width: 25%;">Date:</td>
                            <td style="width: 25%;"><strong><?php echo $ce2b['Ce2b']['reporter_date'] ?></strong></td>
                            <td style="width: 25%;"></td>
                            <td style="width: 25%;"></td>
                        </tr>
                    </table>
                    <hr>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 50%;">Is the person submitting different from reporter?</td>
                            <td><strong><?php echo $ce2b['Ce2b']['person_submitting'] ?></strong></td>
                        </tr>
                    </table>
                    <hr>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 25%;">NAME OF PERSON REPORTING:</td>
                            <td style="width: 25%;"><strong><?php echo $ce2b['Ce2b']['reporter_name_diff'] ?></strong></td>
                            <td style="width: 25%;">E-MAIL ADDRESS: </td>
                            <td style="width: 25%;"><strong><?php echo $ce2b['Ce2b']['reporter_email_diff'] ?></strong></td>
                        </tr>
                        <tr>
                            <td style="width: 25%;">DESIGNATION:</td>
                            <td style="width: 25%;"><strong><?php echo $ce2b['Ce2b']['reporter_designation_diff'] ?></strong></td>
                            <td style="width: 25%;">PHONE NO.</td>
                            <td style="width: 25%;"><strong><?php echo $ce2b['Ce2b']['reporter_phone_diff'] ?></strong></td>
                        </tr>
                        <tr>
                            <td style="width: 25%;">Date:</td>
                            <td style="width: 25%;"><strong><?php echo $ce2b['Ce2b']['reporter_date_diff'] ?></strong></td>
                            <td style="width: 25%;"></td>
                            <td style="width: 25%;"></td>
                        </tr>
                    </table>
                    <hr>
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>