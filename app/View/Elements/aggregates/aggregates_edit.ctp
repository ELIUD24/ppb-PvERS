<?php
$this->assign('AGGREGATE', 'active');
$this->Html->script('jquery/combobox', array('inline' => false));
$this->Html->script('aggregate', array('inline' => false));
$this->Html->script('ckeditor/ckeditor', array('inline' => false));
$this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
?>

<!-- SADR
    ================================================== -->
<section id="sadrsadd">

    <?php
    echo $this->Session->flash();


    echo $this->Form->create('Aggregate', array(
        'type' => 'file',
        'class' => 'form-vertical',
        'inputDefaults' => array(
            'div' => array('class' => 'control-group'),
            'label' => array('class' => 'control-label'),
            'between' => '<div class="controls">',
            'after' => '</div>',
            'class' => '',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('class' => 'controls help-block')),
        ),
    ));
    ?>
    <div class="row-fluid">
        <div class="span10 formbackd">

            <?php
            echo $this->Form->input('Aggregate.id', array());
            echo $this->Form->input('Aggregate.reference_no', array('type' => 'hidden'));
            ?>

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
                        <h5 style="color: red;">Aggregate Report FORM</h5>
                    </div>
                </div>
            </div>
            <hr>

            <div class="row-fluid">
                <div class="span12">
                    <section id="aefisview">
                        <div style="margin: 10px;">
                            <div class="row-fluid">
                                <h5 class=" text-success">Do you have the report summary?&nbsp;</h5>

                                <?php

                                
                                echo $this->Form->input(
                                    'summary_available',
                                    array(
                                        'type' => 'radio',
                                        'label' => false,
                                        'legend' => false,
                                        'div' => false,
                                        'hiddenField' => false,
                                        'error' => false,
                                        'class' => 'summary-available',
                                        'before' => '<div class="form-inline">
												<input type="hidden" value="" id="AggregateSummaryAvailable_" name="data[Aggregate][summary_available]">
												<label class="radio">',
                                        'after' => '</label>&nbsp;&nbsp;',
                                        'options' => array('Yes' => 'Yes'),
                                    )
                                );
                                echo $this->Form->input(
                                    'summary_available',
                                    array(
                                        'type' => 'radio',
                                        'label' => false,
                                        'legend' => false,
                                        'div' => false,
                                        'hiddenField' => false,
                                        'class' => 'summary-available',
                                        'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
                                        'error' => array('attributes' => array('wrap' => 'p', 'class' => 'required error')),
                                        'before' => '<label class="radio"> ',
                                        'after' => '</label> </div>',
                                        'options' => array('No' => 'No'),
                                    )
                                );
                                ?>
                                <br>
                                <br>

                            </div>
                            <div class="" id="basicInformation">
                                <div class="row-fluid">
                                    <div class="span6">
                                        <?php
                                        echo $this->Form->input(
                                            'brand_name',
                                            array(
                                                'class' => 'span8 available',
                                                'label' => array('class' => 'control-label ', 'text' => 'Invented/Brand name of the
                                                medicinal product(s) <span style="color:red;">*</span>'),
                                                'after' => '<p class="help-block"> </p></div>',
                                            )
                                        );

                                        ?>
                                    </div>
                                    <div class="span6">
                                        <?php
                                        echo $this->Form->input(
                                            'inn_name',
                                            array(
                                                'class' => 'span8 available',
                                                'label' => array('class' => 'control-label ', 'text' => 'INN (or common name) of the
                                                active substance(s)<span style="color:red;">*</span>'),
                                                'after' => '<p class="help-block"> </p></div>',
                                            )
                                        );

                                        ?>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span6">
                                        <?php
                                        echo $this->Form->input(
                                            'mah',
                                            array(
                                                'class' => 'span8 available',
                                                'label' => array('class' => 'control-label ', 'text' => '
                                                Marketing authorization holder<span style="color:red;">*</span>'),
                                                'after' => '<p class="help-block"> </p></div>',
                                            )
                                        );

                                        ?>
                                    </div>
                                    <div class="span6">
                                        <?php
                                        echo $this->Form->input(
                                            'local_technical',
                                            array(
                                                'class' => 'span8 available',
                                                'label' => array('class' => 'control-label ', 'text' => 'Local Technical Representative (where applicable)<span style="color:red;"></span>'),
                                                'after' => '<p class="help-block"> </p></div>',
                                            )
                                        );

                                        ?>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span6">
                                        <?php
                                        echo $this->Form->input(
                                            'therapeutic_group',
                                            array(
                                                'class' => 'span8 available',
                                                'label' => array('class' => 'control-label ', 'text' => 'Pharmaco-therapeutic group (ATC
                                                Code)<span style="color:red;">*</span>'),
                                                'after' => '<p class="help-block"> </p></div>',
                                            )
                                        );

                                        ?>
                                    </div>
                                    <div class="span6">
                                        <?php
                                        echo $this->Form->input(
                                            'authorised_indications',
                                            array(
                                                'class' => 'span8 available',
                                                'label' => array('class' => 'control-label ', 'text' => 'Indications authorised in Kenya for
                                                the product (list all the indications)<span style="color:red;">*</span>'),
                                                'after' => '<p class="help-block"> </p></div>',
                                            )
                                        );

                                        ?>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span6">
                                        <?php
                                        echo $this->Form->input(
                                            'form_strength',
                                            array(
                                                'class' => 'span8 available',
                                                'label' => array('class' => 'control-label ', 'text' => 'Pharmaceutical form(s) and
                                                strength(s)<span style="color:red;">*</span>'),
                                                'after' => '<p class="help-block"> </p></div>',
                                            )
                                        );

                                        ?>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>

                        <div class="" id="parentDetails" style="margin: 10px;">
                            <div class="row-fluid">
                                <div class="span10">
                                    <?php


                                    echo $this->Form->input(
                                        'introduction',
                                        array(
                                            'class' => 'span10 available',
                                            'rows' => '3',
                                            'label' => array('class' => 'control-label', 'text' => '1 Introduction <span style="color:red;">*</span>'),
                                            'after' => '<p class="help-block"> </p></div>',
                                        )
                                    );
                                    echo $this->Form->input(
                                        'worldwide_marketing',
                                        array(
                                            'class' => 'span10 available',
                                            'rows' => '3',
                                            'label' => array('class' => 'control-label', 'text' => '1.2 Worldwide Marketing Approval Status <span style="color:red;">*</span>'),
                                            'after' => '<p class="help-block"> </p></div>',
                                        )
                                    );
                                    ?>
                                    <h5>1.3 Overview of exposure and safety data</h5>
                                    <?php

                                    echo $this->Form->input(
                                        'action_taken',
                                        array(
                                            'class' => 'span10',
                                            'rows' => '3',
                                            'label' => array('class' => 'control-label ', 'text' => '1.3.1 Actions Taken in the Reporting Interval for Safety Reasons <span style="color:red;">*</span>'),
                                            'after' => '<p class="help-block"> </p></div>',
                                        )
                                    );
                                    echo $this->Form->input(
                                        'reference_changes',
                                        array(
                                            'class' => 'span10',
                                            'rows' => '3',
                                            'label' => array('class' => 'control-label ', 'text' => '1.3.2 Changes to Reference Safety Information <span style="color:red;">*</span>'),
                                            'after' => '<p class="help-block"> </p></div>',
                                        )
                                    );
                                    echo $this->Form->input(
                                        'estimated_exposure',
                                        array(
                                            'class' => 'span10',
                                            'rows' => '3',
                                            'label' => array('class' => 'control-label ', 'text' => '1.3.3 Estimated Exposure and Use Patterns<span style="color:red;">*</span>'),
                                            'after' => '<p class="help-block"> </p></div>',
                                        )
                                    );

                                    echo $this->Form->input(
                                        'clinical_findings',
                                        array(
                                            'class' => 'span10',
                                            'rows' => '3',
                                            'label' => array('class' => 'control-label ', 'text' => '1.3.4 Findings from clinical trials and other sources<span style="color:red;">*</span>'),
                                            'after' => '<p class="help-block"> </p></div>',
                                        )
                                    );
                                    echo $this->Form->input(
                                        'efficacy',
                                        array(
                                            'class' => 'span10',
                                            'rows' => '3',
                                            'label' => array('class' => 'control-label ', 'text' => '1.3.5 Lack of efficacy in controlled clinical trials<span style="color:red;">*</span>'),
                                            'after' => '<p class="help-block"> </p></div>',
                                        )
                                    );
                                    echo $this->Form->input(
                                        'late_breaking',
                                        array(
                                            'class' => 'span10',
                                            'rows' => '3',
                                            'label' => array('class' => 'control-label ', 'text' => '1.3.6 Late-breaking information<span style="color:red;">*</span>'),
                                            'after' => '<p class="help-block"> </p></div>',
                                        )
                                    );
                                    ?>

                                </div>
                            </div>
                            <hr>
                        </div>
                       
                        <div class="" id="otherDetails" style="margin: 10px;">
                            <div class="row-fluid">
                                <div class="span10">
                                    <?php
                                    echo $this->Form->input(
                                        'safety_concerns',
                                        array(
                                            'class' => 'span10',
                                            'rows' => '3',
                                            'label' => array('class' => 'control-label ', 'text' => '2.1 Summary of safety concerns <span style="color:red;">*</span>'),
                                            'after' => '<p class="help-block"> </p></div>',
                                        )
                                    ); ?>
                                    <h5>2.2 Signal evaluation</h5>
                                    <p>Tabular overview of signals: new, ongoing or closed during the reporting interval dd.mm.yyyy to dd.mm.yyyy</p>

                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <?php
                                    echo $this->element('multi/list_of_signals');
                                    ?>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span10">
                                    <?php
                                    echo $this->Form->input(
                                        'risks_evaluation',
                                        array(
                                            'class' => 'span10',
                                            'rows' => '3',
                                            'label' => array('class' => 'control-label ', 'text' => '2.3 Evaluation of risks and new information <span style="color:red;">*</span>'),
                                            'after' => '<p class="help-block"> </p></div>',
                                        )
                                    );
                                    echo $this->Form->input(
                                        'risks_characterisation',
                                        array(
                                            'class' => 'span10',
                                            'rows' => '3',
                                            'label' => array('class' => 'control-label ', 'text' => '2.4 Characterisation of risks <span style="color:red;">*</span>'),
                                            'after' => '<p class="help-block"> </p></div>',
                                        )
                                    );


                                    ?>
                                </div>

                            </div>
                            <hr>
                            <div class="row-fluid">
                                <div class="span10">
                                    <?php
                                    echo $this->Form->input(
                                        'benefit_evaluation',
                                        array(
                                            'class' => 'span10',
                                            'rows' => '3',
                                            'label' => array('class' => 'control-label ', 'text' => '3 Benefit evaluation <span style="color:red;">*</span>'),
                                            'after' => '<p class="help-block"> </p></div>',
                                        )
                                    );
                                    echo $this->Form->input(
                                        'risk_balance',
                                        array(
                                            'class' => 'span10',
                                            'rows' => '3',
                                            'label' => array('class' => 'control-label ', 'text' => '4 Benefit-risk balance <span style="color:red;">*</span>'),
                                            'after' => '<p class="help-block"> </p></div>',
                                        )
                                    );
                                    ?>



                                </div>
                            </div>
                            <hr>
                        </div>
                      

                        <!-- Start of Attachments -->
                        <div style="margin: 10px;">
                            <div class="row-fluid">
                                <div class="span8">

                                    <?php

                                    echo $this->Form->input(
                                        'sample',
                                        array(
                                            // create a hidden input with the same name as the input
                                            'type' => 'hidden',
                                            'id' => 'sample',
                                            'value' => 'dammy',
                                            'class' => 'autosave-ignore',
                                        )
                                    );
                                    echo $this->element('multi/aggregates', ['model' => 'Aggregate', 'group' => 'attachment', 'examples' => '']); ?>
                                </div>
                            </div>
                        </div>

                        <!-- End of Attachements -->



                        <!-- Reporter Details -->
                        <div style="margin: 10px;">
                            <div class="row-fluid">
                                <div class="span6">
                                    <?php
                                    echo $this->Form->input(
                                        'reporter_name',
                                        array(
                                            'div' => array('class' => 'control-group required'),
                                            'label' => array('class' => 'control-label required', 'text' => 'Name of Person Reporting <span style="color:red;">*</span>'),
                                        )
                                    );
                                    echo $this->Form->input(
                                        'reporter_email',
                                        array(
                                            'type' => 'email',
                                            'div' => array('class' => 'control-group required'),
                                            'label' => array('class' => 'control-label required', 'text' => 'E-MAIL ADDRESS <span style="color:red;">*</span>')
                                        )
                                    );

                                    ?>
                                </div>
                                <!--/span-->
                                <div class="span6">
                                    <?php
                                    echo $this->Form->input(
                                        'designation_id',
                                        array('label' => array('class' => 'control-label required', 'text' => 'DESIGNATION' . ' <span style="color:red;">*</span>'), 'empty' => true)
                                    );
                                    echo $this->Form->input(
                                        'reporter_phone',
                                        array(
                                            'div' => array('class' => 'control-group'),
                                            'label' => array('class' => 'control-label required', 'text' => 'PHONE NO.' . ' <span style="color:red;">*</span>')
                                        )
                                    );

                                    echo $this->Form->input(
                                        'reporter_date',
                                        array(
                                            'type' => 'text',
                                            'class' => 'date-pick-field',
                                            'label' => array('class' => 'control-label required', 'text' => 'Date <span style="color:red;">*</span>'),
                                        )
                                    );
                                    ?>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                            <table class="table table-bordered  table-condensed table-pvborderless">
                                <tbody>
                                    <tr>
                                        <td width="45%">
                                            <h5 class="pull-right text-success">Is the person submitting different from
                                                reporter?&nbsp;</h5>
                                        </td>
                                        <td>
                                            <?php
                                            echo $this->Form->input(
                                                'person_submitting',
                                                array(
                                                    'type' => 'radio',
                                                    'label' => false,
                                                    'legend' => false,
                                                    'div' => false,
                                                    'hiddenField' => false,
                                                    'error' => false,
                                                    'class' => 'person-submit',
                                                    'before' => '<div class="form-inline">
												<input type="hidden" value="" id="AggregatePersonSubmitting_" name="data[Aggregate][person_submitting]">
												<label class="radio">',
                                                    'after' => '</label>&nbsp;&nbsp;',
                                                    'options' => array('Yes' => 'Yes'),
                                                )
                                            );
                                            echo $this->Form->input(
                                                'person_submitting',
                                                array(
                                                    'type' => 'radio',
                                                    'label' => false,
                                                    'legend' => false,
                                                    'div' => false,
                                                    'hiddenField' => false,
                                                    'class' => 'person-submit',
                                                    'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
                                                    'error' => array('attributes' => array('wrap' => 'p', 'class' => 'required error')),
                                                    'before' => '<label class="radio">',
                                                    'after' => '</label> </div>',
                                                    'options' => array('No' => 'No'),
                                                )
                                            );
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row-fluid">
                                <div class="span6">
                                    <?php
                                    echo $this->Form->input(
                                        'reporter_name_diff',
                                        array(
                                            'div' => array('class' => 'control-group required'),
                                            'class' => 'diff',
                                            'label' => array('class' => 'control-label required', 'text' => 'Name <span style="color:red;">*</span>'),
                                        )
                                    );
                                    echo $this->Form->input(
                                        'reporter_email_diff',
                                        array(
                                            'type' => 'email',
                                            'div' => array('class' => 'control-group required'),
                                            'class' => 'diff',
                                            'label' => array('class' => 'control-label required', 'text' => 'E-MAIL ADDRESS <span style="color:red;">*</span>')
                                        )
                                    );
                                    ?>
                                </div>
                                <!--/span-->
                                <div class="span6">
                                    <?php
                                    echo $this->Form->input(
                                        'reporter_designation_diff',
                                        array(
                                            'type' => 'select',
                                            'options' => $designations,
                                            'empty' => true,
                                            'class' => 'diff',
                                            'label' => array('class' => 'control-label required', 'text' => 'Designation' . ' <span style="color:red;">*</span>'),
                                            'empty' => true
                                        )
                                    );
                                    echo $this->Form->input(
                                        'reporter_phone_diff',
                                        array(
                                            'div' => array('class' => 'control-group'),
                                            'class' => 'diff',
                                            'label' => array('class' => 'control-label required', 'text' => 'PHONE NO.' . ' <span style="color:red;">*</span>')
                                        )
                                    );
                                    echo $this->Form->input(
                                        'reporter_date_diff',
                                        array(
                                            'type' => 'text',
                                            'class' => 'date-pick-field diff',
                                            'label' => array('class' => 'control-label required', 'text' => 'Date'),
                                        )
                                    );
                                    ?>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                        </div>


                        <!-- End of Reporter Details -->

                    </section>
                </div>
            </div>

        </div>
        <div class="span2">
            <div class="my-sidebar" data-spy="affix">
                <div class="awell">
                    <?php
                    echo $this->Form->button('<i class="fa fa-floppy-o" aria-hidden="true"></i> Save Changes', array(
                        'name' => 'saveChanges',
                        'class' => 'btn btn-success mapop',
                        'formnovalidate' => 'formnovalidate',
                        'id' => 'SadrSaveChanges', 'title' => 'Save & continue editing',
                        'data-content' => 'Save changes to form without submitting it.
	                                              The form will still be available for further editing.',
                        'div' => false,
                    ));
                    ?>
                    <br>
                    <hr>
                    <?php
                    echo $this->Form->button('<i class="fa fa-paper-plane-o" aria-hidden="true"></i> Submit', array(
                        'name' => 'submitReport',
                        'onclick' => "return confirm('Are you sure you wish to submit the report?');",
                        'class' => 'btn btn-primary btn-block mapop',
                        'id' => 'SiteInspectionSubmitReport', 'title' => 'Save and Submit Report',
                        'data-content' => 'Submit report for peer review and approval.',
                        'div' => false,
                    ));

                    ?>
                    <br>
                    <hr>
                    <?php

                    echo $this->Html->link(
                        '<i class="fa fa-times" aria-hidden="true"></i> Cancel',
                        array('controller' => 'users', 'action' => 'dashboard'),
                        array('escape' => false, 'class' => 'btn btn-danger btn-block')
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</section>



<script type="text/javascript">
    CKEDITOR.replace('data[Aggregate][introduction]');
    CKEDITOR.replace('data[Aggregate][worldwide_marketing]');
    CKEDITOR.replace('data[Aggregate][action_taken]');
    CKEDITOR.replace('data[Aggregate][reference_changes]');
    CKEDITOR.replace('data[Aggregate][estimated_exposure]');
    CKEDITOR.replace('data[Aggregate][clinical_findings]');
    CKEDITOR.replace('data[Aggregate][efficacy]');
    CKEDITOR.replace('data[Aggregate][late_breaking]');
    CKEDITOR.replace('data[Aggregate][safety_concerns]');
    CKEDITOR.replace('data[Aggregate][risks_evaluation]');
    CKEDITOR.replace('data[Aggregate][risks_characterisation]');
    CKEDITOR.replace('data[Aggregate][benefit_evaluation]');
    CKEDITOR.replace('data[Aggregate][risk_balance]');
</script>