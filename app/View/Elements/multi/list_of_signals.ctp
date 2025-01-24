<?php

/**
 * @var \App\View\AppView $this
 */
$this->Html->script('list_of_signals', array('inline' => false));
$this->Html->css('sadr', false, array('inline' => false));
?>
<style>
    .small-label {
        font-size: 12px;
    }
</style>
<div class="row-fluid">
    <div class="span12">
        <h5 style="text-align: center;"> <button type="button" class="btn btn-small" id="addListOfSignals" title="click to add row"><i class="icon-plus"></i></button>
        </h5>
    </div>
</div>

<div class="row-fluid srollable">
    <div class="span12">
        <table id="buildyourform" class="table table-bordered  table-condensed table-pvborder">
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
                <tr>
                    <td>1</td>
                    <td data-title="Signal term *">
                        <?php
                        echo $this->Form->input('AggregateListOfSignal.0.id', array('type' => 'hidden'));

                        echo $this->Form->input(
                            'AggregateListOfSignal.0.signal_term',
                            array(
                                'label' => false,
                                'between' => false,
                                'after' => false,
                                'class' => 'span11 available autosave-ignore',
                                'error' => array('attributes' => array('class' => 'help-block')),
                                'data-items' => '4',
                                'autocomplete' => 'off',
                            )
                        );
                        ?>
                    </td>
                    <td data-title="">
                        <?php

                        echo $this->Form->input(
                            'AggregateListOfSignal.0.date_detected',
                            array(
                                'type' => 'text',
                                'label' => false,
                                'between' => false,
                                'after' => false,
                                'class' => 'span12 available autosave-ignore',
                                'error' => array('attributes' => array('class' => 'help-block')),
                            )
                        );

                        ?>
                    </td>
                    <td data-title="Status">
                        <?php
                        echo $this->Form->input(
                            'AggregateListOfSignal.0.status',
                            array(
                                'empty' => true,
                                'label' => false,
                                'between' => false,
                                'after' => false,
                                'class' => 'span12 available autosave-ignore',
                                'type' => 'select',
                                'error' => array('attributes' => array('class' => 'help-block')),
                                'options' => array(
                                    'new' => 'New',
                                    'ongoing' => 'Ongoing',
                                    'closed' => 'Closed'

                                ),
                            )
                        );
                        ?>
                    </td>
                    <td data-title="Data Closed">
                        <?php

                        echo $this->Form->input(
                            'AggregateListOfSignal.0.date_closed',
                            array(
                                'type' => 'text',
                                'label' => false,
                                'between' => false,
                                'after' => false,
                                'class' => 'span12 available autosave-ignore',
                                'error' => array('attributes' => array('class' => 'help-block')),
                            )
                        );
                        ?>
                    </td>
                    <td data-title="Source of Trigger">
                        <?php
                        echo $this->Form->input(
                            'AggregateListOfSignal.0.source_trigger',
                            array(
                                'label' => false,
                                'between' => false,
                                'error' => array('attributes' => array('class' => 'help-block')),
                                'class' => 'span12 available autosave-ignore',
                            )
                        );
                        ?>
                    </td>
                    <td colspan="1" data-title="Reason Summary">
                        <?php
                        echo $this->Form->input(
                            'AggregateListOfSignal.0.reason_summary',
                            array(
                                'empty' => true,
                                'label' => false,
                                'between' => false,
                                'after' => false,
                                'class' => 'span12 available autosave-ignore',
                                'type' => 'text',
                                'error' => array('attributes' => array('class' => 'help-block')),

                            )
                        );
                        ?>
                    </td>
                    <td data-title="Method of signal evaluation *">
                        <?php
                        echo $this->Form->input(
                            'AggregateListOfSignal.0.evaluation_method',
                            array(
                                'empty' => true,
                                'label' => false,
                                'between' => false,
                                'after' => false,
                                'class' => 'span12 available autosave-ignore showHidden',
                                'type' => 'text',
                                'error' => array('attributes' => array('class' => 'help-block')),
                            )
                        );
                        ?>
                    </td>
                    <td data-title="Outcome  *">
                        <?php
                        echo $this->Form->input(
                            'AggregateListOfSignal.0.outcome',
                            array(
                                'label' => false,
                                'between' => false,
                                'after' => false,
                                'class' => 'span11 available autosave-ignore',
                            )
                        );
                        ?>
                    </td>
                    <td data-title="Add a Drug - "> </td>
                </tr>
                <?php
                if (!empty($this->request->data['AggregateListOfSignal'])) {
                    for ($i = 1; $i <= count($this->request->data['AggregateListOfSignal']) - 1; $i++) { ?>
                        <tr>
                            <td>
                                <?php echo $i + 1; ?>
                            </td>
                            <td> <?php
                                    echo $this->Form->input('AggregateListOfSignal.' . $i . '.id', array('type' => 'hidden'));

                                    echo $this->Form->input(
                                        'AggregateListOfSignal.' . $i . '.signal_term',
                                        array(
                                            'label' => false,
                                            'between' => false,
                                            'after' => false,
                                            'class' => 'span11 autosave-ignore',
                                            'error' => array('attributes' => array('class' => 'help-block')),
                                            'data-items' => '4',
                                            'autocomplete' => 'off',
                                        )
                                    );
                                    ?>
                            </td>
                            <td data-title="Date detected (dd-mm-yyyy)">
                                <?php

                                echo $this->Form->input(
                                    'AggregateListOfSignal.' . $i . '.date_detected',
                                    array(
                                        'type' => 'text',
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span12 autosave-ignore',
                                        'error' => array('attributes' => array('class' => 'help-block')),
                                    )
                                );
                                ?>
                            </td>
                            <td data-title="Status">
                                <?php
                                echo $this->Form->input(
                                    'AggregateListOfSignal.' . $i . '.status',
                                    array(
                                        'empty' => true,
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span12 autosave-ignore',
                                        'type' => 'select',
                                        'error' => array('attributes' => array('class' => 'help-block')),
                                        'options' => array(
                                            'new' => 'New',
                                            'ongoing' => 'Ongoing',
                                            'closed' => 'Closed'

                                        ),
                                    )
                                );
                                ?>
                            </td>
                            <td data-title="Data Closed">
                                <?php

                                echo $this->Form->input(
                                    'AggregateListOfSignal.' . $i . '.date_closed',
                                    array(
                                        'type' => 'text',
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span12 autosave-ignore',
                                        'error' => array('attributes' => array('class' => 'help-block')),
                                    )
                                );
                                ?>
                            </td>
                            <td data-title="Source of Trigger">
                                <?php
                                echo $this->Form->input(
                                    'AggregateListOfSignal.' . $i . '.source_trigger',
                                    array(
                                        'label' => false,
                                        'between' => false,
                                        'error' => array('attributes' => array('class' => 'help-block')),
                                        'class' => 'span12 autosave-ignore',
                                    )
                                );
                                ?>
                            </td>
                            <td colspan="1" data-title="Reason Summary">
                                <?php
                                echo $this->Form->input(
                                    'AggregateListOfSignal.' . $i . '.reason_summary',
                                    array(
                                        'empty' => true,
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span12 autosave-ignore',
                                        'type' => 'text',
                                        'error' => array('attributes' => array('class' => 'help-block')),

                                    )
                                );
                                ?>
                            </td>
                            <td data-title="Method of signal evaluation *">
                                <?php
                                echo $this->Form->input(
                                    'AggregateListOfSignal.' . $i . '.evaluation_method',
                                    array(
                                        'empty' => true,
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span12 autosave-ignore',
                                        'type' => 'text',
                                        'error' => array('attributes' => array('class' => 'help-block')),
                                    )
                                );
                                ?>
                            </td>
                            <td data-title="Outcome  *">
                                <?php
                                echo $this->Form->input(
                                    'AggregateListOfSignal.' . $i . '.outcome',
                                    array(
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span12 autosave-ignore',
                                    )
                                );
                                ?>
                            </td>
                            <td data-title="Remove Drug - ">
                                <button type="button" class="btn-mini removeTr" title="click here to delete row" id="<?php if (isset($this->request->data['AggregateListOfSignal'][$i]['id'])) {
                                                                                                                            echo $this->request->data['AggregateListOfSignal'][$i]['id'];
                                                                                                                        } ?>">
                                    <i class="icon-minus"></i>
                                </button>
                            </td>
                        </tr>
                <?php }
                }; ?>

            </tbody>
            <tbody>
                <?php
                // $i = 0;
                if (!empty($this->request->data['SadrListOfMedicine'])) {
                    for ($i = 0; $i <= count($this->request->data['SadrListOfMedicine']) - 1; $i++) {
                ?>
                        <tr>
                            <td>
                                <?= $i + 1; ?>
                            </td>
                            <td>
                                <?php
                                echo $this->Form->input('SadrListOfMedicine.' . $i . '.id');
                                echo $this->Form->input(
                                    'SadrListOfMedicine.' . $i . '.drug_name',
                                    array(
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span11',
                                    )
                                );
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $this->Form->input(
                                    'SadrListOfMedicine.' . $i . '.brand_name',
                                    array(
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span11',
                                    )
                                );
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $this->Form->input(
                                    'SadrListOfMedicine.' . $i . '.batch_no',
                                    array(
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span11',
                                    )
                                );
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $this->Form->input(
                                    'SadrListOfMedicine.' . $i . '.manufacturer',
                                    array(
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span11',
                                    )
                                );
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $this->Form->input(
                                    'SadrListOfMedicine.' . $i . '.dose',
                                    array(
                                        'type' => 'text',
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span11',
                                    )
                                );
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $this->Form->input(
                                    'SadrListOfMedicine.' . $i . '.dose_id',
                                    array(
                                        'empty' => true,
                                        'label' => false,
                                        'between' => false,
                                        'class' => 'span12',
                                        'type' => 'select',
                                        'error' => array('attributes' => array('class' => 'help-block')),
                                        'options' => $dose,
                                    )
                                );
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $this->Form->input(
                                    'SadrListOfMedicine.' . $i . '.route_id',
                                    array(
                                        'empty' => true,
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span12',
                                        'type' => 'select',
                                        'error' => array('attributes' => array('class' => 'help-block')),
                                        'options' => $routes,
                                    )
                                );
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $this->Form->input(
                                    'SadrListOfMedicine.' . $i . '.frequency_id',
                                    array(
                                        'empty' => true,
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span12',
                                        'type' => 'select',
                                        'id' => 'selectOther',
                                        'options' => $frequency,
                                        'error' => array('attributes' => array('class' => 'help-block')),
                                    )
                                );
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $this->Form->input(
                                    'SadrListOfMedicine.' . $i . '.start_date',
                                    array(
                                        'type' => 'text',
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span12 date-pick-from',
                                    )
                                );
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $this->Form->input(
                                    'SadrListOfMedicine.' . $i . '.stop_date',
                                    array(
                                        'type' => 'text',
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span12 date-pick-to',
                                    )
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo $this->Form->input(
                                    'SadrListOfMedicine.' . $i . '.indication',
                                    array(
                                        'type' => 'text',
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'class' => 'span12',
                                    )
                                );
                                ?>
                            </td>

                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-medicine" value="<?php if (isset($this->request->data['SadrListOfMedicine'][$i]['id'])) {
                                                                                                                echo $this->request->data['SadrListOfMedicine'][$i]['id'];
                                                                                                            } ?>">
                                    <i class="icon-minus"></i>
                                </button>
                            </td>
                        </tr>

                <?php }
                } ?>

            </tbody>
        </table>
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
        ?>
    </div><!--/span-->
</div><!--/row-->
<hr>