<?php
$this->assign('Aggregate', 'active');
$ichecked = "&#x2611;";
$nchecked = "&#x2610;";
?>

<!-- Aggregate
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
                            <p><strong><?php echo $aggregate['Aggregate']['company_name'] ?></strong></p>
                        </td>
                        <td style="width: 10%;">REPORT ID: </td>
                        <td style="width: 20%;">
                            <p><strong><?php echo $aggregate['Aggregate']['reference_no'] ?></strong></p>
                        </td>
                    </tr>
                </table>
                <hr>
                <div class="row-fluid" style="margin:10px ;">
                    <div class="row-fluid">
                        <div class="span3">
                            <p> <strong>Brand Name</strong></p>
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
                            <td style="width: 25%;"><strong><?php echo $aggregate['Aggregate']['reporter_name'] ?></strong></td>
                            <td style="width: 25%;">E-MAIL ADDRESS: </td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['Aggregate']['reporter_email'] ?></strong></td>
                        </tr>
                        <tr>
                            <td style="width: 25%;">DESIGNATION:</td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['Designation']['name'] ?></strong></td>
                            <td style="width: 25%;">PHONE NO.</td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['Aggregate']['reporter_phone'] ?></strong></td>
                        </tr>
                        <tr>
                            <td style="width: 25%;">Date:</td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['Aggregate']['reporter_date'] ?></strong></td>
                            <td style="width: 25%;"></td>
                            <td style="width: 25%;"></td>
                        </tr>
                    </table>
                    <hr>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 50%;">Is the person submitting different from reporter?</td>
                            <td><strong><?php echo $aggregate['Aggregate']['person_submitting'] ?></strong></td>
                        </tr>
                    </table>
                    <hr>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 25%;">NAME OF PERSON REPORTING:</td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['Aggregate']['reporter_name_diff'] ?></strong></td>
                            <td style="width: 25%;">E-MAIL ADDRESS: </td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['Aggregate']['reporter_email_diff'] ?></strong></td>
                        </tr>
                        <tr>
                            <td style="width: 25%;">DESIGNATION:</td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['Aggregate']['reporter_designation_diff'] ?></strong></td>
                            <td style="width: 25%;">PHONE NO.</td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['Aggregate']['reporter_phone_diff'] ?></strong></td>
                        </tr>
                        <tr>
                            <td style="width: 25%;">Date:</td>
                            <td style="width: 25%;"><strong><?php echo $aggregate['Aggregate']['reporter_date_diff'] ?></strong></td>
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