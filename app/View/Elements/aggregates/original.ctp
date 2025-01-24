<?php
$this->assign('ParentAggregate', 'active');
$ichecked = "&#x2611;";
$nchecked = "&#x2610;";
?>

<!-- ParentAggregate
    ================================================== -->

<div class="row-fluid" id="abonokode">
    <div class="span12">

        <div id="printAreade">
            <div class="formbackd">

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
                            <h5 style="color: red;">PSUR Report</h5>
                        </div>
                    </div>
                </div>

                <table class="table" style="width: 100%;">
                    <tr>
                    </tr>
                    <tr>
                        <td style="width: 10%;">COMPANY NAME: </td>
                        <td style="width: 25%;">
                            <p><strong><?php echo $aggregate['ParentAggregate']['company_name'] ?></strong></p>
                        </td>
                        <td style="width: 10%;">REPORT ID: </td>
                        <td style="width: 20%;">
                            <p><strong><?php echo $aggregate['ParentAggregate']['reference_no'] ?></strong></p>
                        </td>
                    </tr>
                </table>
                <hr>
                <div class="row-fluid" style="margin:10px ;">
                    <div class="row-fluid">
                        <div class="span3">
                            <p> <strong>Brand Name</strong></p>
                        </div>
                        <div class="span3">
                            <p><strong>INN Name</strong></p>
                        </div>
                        <div class="span3">
                            <p><strong>Market authorization Holder</strong></p>
                        </div>
                        <div class="span3">
                            <p><strong>Local Technical Rep</strong></p>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3">
                            <p><?php echo $aggregate['ParentAggregate']['brand_name'] ?></p>

                        </div>
                        <div class="span3">
                            <p><?php echo $aggregate['ParentAggregate']['inn_name'] ?></p>
                        </div>
                        <div class="span3">
                            <p><?php echo $aggregate['ParentAggregate']['mah'] ?></p>
                        </div>
                        <div class="span3">
                            <p><?php echo $aggregate['ParentAggregate']['local_technical'] ?></p>

                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3">
                            <p> <strong>Therapeutic Group Code</strong></p>
                        </div>
                        <div class="span3">
                            <p><strong>Authorized Indications</strong></p>
                        </div>
                        <div class="span3">
                            <p><strong>Pharmaceutical Forms</strong></p>
                        </div>
                        <div class="span3">
                        </div>

                    </div>
                    <div class="row-fluid">
                        <div class="span3">
                            <?php echo $aggregate['ParentAggregate']['therapeutic_group'] ?> </div>
                        <div class="span3">
                            <?php echo $aggregate['ParentAggregate']['authorised_indications'] ?> </div>
                        <div class="span3">
                            <?php echo $aggregate['ParentAggregate']['form_strength'] ?> </div>
                        <div class="span3">
                        </div>
                    </div>
                    <hr>

                    <h5>1. Introduction</h5>
                    <div class="row-fluid">
                        <div class="span11">
                            <p><?php echo $aggregate['ParentAggregate']['introduction'] ?></p>
                        </div>
                    </div>
                    <h5>1.2 Worldwide Marketing Approval Status</h5>
                    <div class="row-fluid">
                        <div class="span11">
                            <p><?php echo $aggregate['ParentAggregate']['worldwide_marketing'] ?></p>
                        </div>
                    </div>
                    <h5>1.3 Overview of exposure and safety data</h5>
                    <h5>1.3.1 Action Taken</h5>
                    <div class="row-fluid">
                        <div class="span11">
                            <p><?php echo $aggregate['ParentAggregate']['action_taken'] ?></p>
                        </div>
                    </div>
                    <h5>1.3.2 Changes to Reference Safety Information </h5>
                    <div class="row-fluid">
                        <div class="span11">
                            <p><?php echo $aggregate['ParentAggregate']['reference_changes'] ?></p>
                        </div>
                    </div>
                    <h5>1.3.3 Estimated Exposure and Use Patterns</h5>
                    <div class="row-fluid">
                        <div class="span11">
                            <p><?php echo $aggregate['ParentAggregate']['estimated_exposure'] ?></p>
                        </div>
                    </div>
                    <h5>1.3.4 Findings from clinical trials and other sources</h5>
                    <div class="row-fluid">
                        <div class="span11">
                            <p><?php echo $aggregate['ParentAggregate']['clinical_findings'] ?></p>
                        </div>
                    </div>
                    <h5>1.3.5 Lack of efficacy in controlled clinical trials</h5>
                    <div class="row-fluid">
                        <div class="span11">
                            <p><?php echo $aggregate['ParentAggregate']['efficacy'] ?></p>
                        </div>
                    </div>
                    <h5>1.3.6 Late-breaking information</h5>
                    <div class="row-fluid">
                        <div class="span11">
                            <p><?php echo $aggregate['ParentAggregate']['late_breaking'] ?></p>
                        </div>
                    </div>

                    <hr>
                    <h5>Signal and risk management</h5>
                    <h5>2.1 Summary of safety concerns </h5>
                    <div class="row-fluid">
                        <div class="span11">
                            <p><?php echo $aggregate['ParentAggregate']['safety_concerns'] ?></p>
                        </div>
                    </div>
                    <h5>2.2 Signal evaluation</h5>
                    <!-- Table Here -->
                    <div class="row-fluid">
                        <div class="span11">
                            <table class="table table-bordered  table-condensed table-pvborder">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th colspan="" class="required tooltipper" title="This is the signal term" data-content=""> Signal term </th>
                                        <th style="width: 9%;">Date detected <span style="color:red;">*</span></th>
                                        <th style="width: 7%;">Status<span style="color:red;">*</span></th>
                                        <th style="width: 7%;">Data closed (for closed signals)<span style="color:red;">*</span></th>
                                        <th colspan="1" style="width: 15%;" class="required" title="Dosage" data-content="">
                                            <label class="required">Source or trigger of signal <span style="color:red;">*</span></label>
                                        </th>
                                        <th colspan="" class="required" style="width: 15%;">
                                            <label class="required">Reason summary</label>
                                        </th>
                                        <th style="width: 28%;" colspan="1">
                                            <label class="required pull-left">Method of signal valuation<span style="color:red;">*</span></label>
                                            <span class="pull-right" style="padding-right: 10px;"></span>
                                        </th>
                                        <th style="width: 3%;">
                                            <label>Outcome, if closed </label>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cc = 0;
                                    foreach ($aggregate['AggregateListOfSignal'] as $rep) { ?>
                                        <tr>
                                            <td><?php $cc++;
                                                echo $cc; ?></td>
                                            <td><?php echo $rep['signal_term']; ?></td>
                                            <td><?php echo $rep['date_detected']; ?></td>
                                            <td><?php echo $rep['status']; ?></td>
                                            <td><?php echo $rep['date_closed']; ?></td>
                                            <td><?php echo $rep['source_trigger']; ?></td>
                                            <td><?php echo $rep['reason_summary']; ?></td>
                                            <td><?php echo $rep['evaluation_method']; ?></td>
                                            <td><?php echo $rep['outcome']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <h5>2.3 Evaluation of risks and new information</h5>
                    <div class="row-fluid">
                        <div class="span11">
                            <p><?php echo $aggregate['ParentAggregate']['risks_evaluation'] ?></p>
                        </div>
                    </div>


                    <h5>2.4 Characterisation of risks </h5>
                    <div class="row-fluid">
                        <div class="span11">
                            <p><?php echo $aggregate['ParentAggregate']['risks_characterisation'] ?></p>
                        </div>
                    </div>
                    <hr>

                    <h5>3 Benefit evaluation </h5>
                    <div class="row-fluid">
                        <div class="span11">
                            <p><?php echo $aggregate['ParentAggregate']['benefit_evaluation'] ?></p>
                        </div>
                    </div>

                    <h5>4 Benefit-risk balance</h5>
                    <div class="row-fluid">
                        <div class="span11">
                            <p><?php echo $aggregate['ParentAggregate']['risk_balance'] ?></p>
                        </div>
                    </div>

                </div>
                <hr>

                <?php if (count($aggregate['Attachment']) > 0) { ?>
                    <table class="change_order_items" style="width: 100%;">
                        <tbody>
                            <tr id="attachmentsTableHeader">
                                <th>#</th>
                                <th class="required" style="width : 30%;"><label class="required">FILE</label></th>
                                <th class="required"><label class="required">A DESCRIPTION OF THE CONTENTS</label></th>
                            </tr>
                            <?php
                            $i = 1;
                            foreach ($aggregate['Attachment'] as $attachment) : ?>
                                <tr>
                                    <td style="width: 10%;"><?php echo $i++; ?></td>
                                    <td style="width : 30%;">
                                        <a href="/attachments/download/<?php echo $attachment['id']; ?>"><?php echo __($attachment['basename']); ?></a>
                                    </td>
                                    <td><?php echo $attachment['description']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php }; ?>
                <hr>
                <?php
                if ($this->Session->read('Auth.User.user_type') != 'Public Health Program') {
                ?>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 25%;">NAME OF PERSON REPORTING:</td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['ParentAggregate']['reporter_name'] ?></strong></td>
                            <td style="width: 25%;">E-MAIL ADDRESS: </td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['ParentAggregate']['reporter_email'] ?></strong></td>
                        </tr>
                        <tr>
                            <td style="width: 25%;">DESIGNATION:</td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['Designation']['name'] ?></strong></td>
                            <td style="width: 25%;">PHONE NO.</td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['ParentAggregate']['reporter_phone'] ?></strong></td>
                        </tr>
                        <tr>
                            <td style="width: 25%;">Date:</td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['ParentAggregate']['reporter_date'] ?></strong></td>
                            <td style="width: 25%;"></td>
                            <td style="width: 25%;"></td>
                        </tr>
                    </table>
                    <hr>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 50%;">Is the person submitting different from reporter?</td>
                            <td><strong><?php echo $aggregate['ParentAggregate']['person_submitting'] ?></strong></td>
                        </tr>
                    </table>
                    <hr>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 25%;">NAME OF PERSON REPORTING:</td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['ParentAggregate']['reporter_name_diff'] ?></strong></td>
                            <td style="width: 25%;">E-MAIL ADDRESS: </td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['ParentAggregate']['reporter_email_diff'] ?></strong></td>
                        </tr>
                        <tr>
                            <td style="width: 25%;">DESIGNATION:</td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['ParentAggregate']['reporter_designation_diff'] ?></strong></td>
                            <td style="width: 25%;">PHONE NO.</td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['ParentAggregate']['reporter_phone_diff'] ?></strong></td>
                        </tr>
                        <tr>
                            <td style="width: 25%;">Date:</td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['ParentAggregate']['reporter_date_diff'] ?></strong></td>
                            <td style="width: 25%;"></td>
                            <td style="width: 25%;"></td>
                        </tr>
                    </table>
                    <hr>
                <?php } ?>

            </div> <!-- /art-sheet -->
        </div> <!-- /art-sheet -->
    </div>
</div>