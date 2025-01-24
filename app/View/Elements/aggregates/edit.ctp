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

                            <div class="">
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
                                    <div class="span6">
                                        <div class="row-fluid">
                                            <div class="span6">

                                                <?php
                                                echo $this->Form->input(
                                                    'interval_code',
                                                    array(
                                                        'class' => 'span12 available',
                                                        'label' => array('class' => 'control-label ', 'text' => 'Submission Frequency<span style="color:red;">*</span>'),
                                                        'after' => '<p class="help-block"> </p></div>',
                                                        'type' => 'number', // Ensure the input is of type 'number'
                                                        'min' => 1,         // Set minimum value to 1
                                                        'max' => 11
                                                    )
                                                );

                                                ?>

                                            </div>
                                            <div class="span6">
                                                <?php
                                              

                                                echo $this->Form->input(
                                                    'submission_frequency',
                                                    array(
                                                        'class' => 'span4 available',
                                                        'type' => 'select',
                                                        'options' => $units,
                                                        'empty' => true, 
                                                        'label' => array('class' => 'control-label required', 'text' => 'Unit' . ' <span style="color:red;">*</span>'),
                                                        'empty' => true
                                                    )
                                                );

                                                ?>
                                            </div>

                                        </div>
                                    </div>
                                    <hr>
                                </div>
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
                                        echo $this->element('multi/aggregates', ['model' => 'Aggregate', 'group' => 'attachment', 'examples' => 'This should be a text PDF file of maximum size of 10MB']); ?>
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