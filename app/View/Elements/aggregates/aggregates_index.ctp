<?php
$this->assign('AGGREGATE', 'active');
?>


<?php
echo $this->Session->flash();
// debug($this->request->query);
?>
<div class="row-fluid">
    <div class="span12">
        <?php
        if ($redir == 'reporter' || $redir == 'manager') {
            echo $this->Html->link(
                '<i class="fa fa-file-o" aria-hidden="true"></i> New Report',
                array('controller' => 'aggregates', 'action' => 'add'),
                array('escape' => false, 'class' => 'btn btn-success')
            );
        }
        ?>
        <h3>Aggregate Reports:<small> <i class="icon-glass"></i> Filter, <i class="icon-search"></i> Search, and <i class="icon-eye-open"></i> view reports</small>

        </h3>
        <hr class="soften" style="margin: 7px 0px;">
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <?php
        echo $this->Form->create('Aggregate', array(
            'url' => array_merge(array('action' => 'index'), $this->params['pass']),
            'class' => 'ctr-groups', 'style' => array('padding:9px;', 'background-color: #F5F5F5'),
        ));
        ?>
        <table class="table table-condensed" style="margin-bottom: 2px;">
            <tbody>
                <tr>
                    <td>
                        <?php
                        echo $this->Form->input(
                            'reference_no',
                            array(
                                'div' => false,
                                'placeholder' => 'aggregates/2023',
                                'class' => 'span12', 'label' => array('class' => 'required', 'text' => 'Reference No.')
                            )
                        );
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->input(
                            'company_name',
                            array(
                                'div' => false, 'placeholder' => '',
                                'class' => 'span12 unauthorized_index', 'label' => array('class' => 'required', 'text' => 'Company Name ')
                            )
                        );
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->input(
                            'reporter_email',
                            array(
                                'div' => false, 'placeholder' => '',
                                'class' => 'span12 unauthorized_index', 'label' => array('class' => 'required', 'text' => 'Reporter Email ')
                            )
                        );
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->input(
                            'start_date',
                            array(
                                'div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index', 'after' => '-to-',
                                'label' => array('class' => 'required', 'text' => 'Report Dates'), 'placeHolder' => 'Start Date'
                            )
                        );
                        echo $this->Form->input(
                            'end_date',
                            array(
                                'div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index',
                                'after' => '<a style="font-weight:normal" onclick="$(\'.unauthorized_index\').val(\'\');" >
                              <em class="accordion-toggle">clear!</em></a>',
                                'label' => false, 'placeHolder' => 'End Date'
                            )
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                        echo $this->Form->input(
                            'brand_name',
                            array(
                                'div' => false, 'placeholder' => '',
                                'class' => 'span12 unauthorized_index', 'label' => array('class' => 'required', 'text' => 'Brand Name ')
                            )
                        );
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->input(
                            'inn_name',
                            array(
                                'div' => false, 'placeholder' => '',
                                'class' => 'span12 unauthorized_index', 'label' => array('class' => 'required', 'text' => 'Inn Name ')
                            )
                        );
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->input(
                            'submission_frequency',
                            array(
                                'div' => false, 
                                'class' => 'span12', 'label' => array('class' => 'required submission_frequency', 'text' => 'Submission Frequency '),
                                'type'=>'select',
                                'empty'=>true, 
                                'options'=>array(
                                    '0'=>'Monthly',
                                    '1'=>'Yearly'
                                )
                            )
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="PadrPages" class="required">Pages</label></td>
                    <td>
                        <?php
                        echo $this->Form->input('pages', array(
                            'type' => 'select', 'div' => false, 'class' => 'input-small', 'selected' => $this->request->params['paging']['Aggregate']['limit'],
                            'empty' => true,
                            'options' => $page_options,
                            'label' => false,
                        ));
                        ?>
                    </td>
                    <td>
                        <?php

                        ?>
                    </td>
                    <td></td>
                    <td>
                        <?php
                        echo $this->Form->button('<i class="icon-search icon-white"></i> Search', array(
                            'class' => 'btn btn-primary', 'div' => 'control-group', 'div' => false,
                            'formnovalidate' => 'formnovalidate',
                            'style' => array('margin-bottom: 5px')
                        ));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Html->link('<i class="icon-remove"></i> Clear', array('action' => 'index'), array('class' => 'btn', 'escape' => false, 'style' => array('margin-bottom: 5px')));
                        ?>
                    </td>
                    <td>
                        <?php
                        // echo $this->Html->link('<i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel', array('action' => 'index', 'ext' => 'csv', '?' => $this->request->query), array('class' => 'btn btn-success', 'escape' => false));
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <p>
            <?php
            echo $this->Paginator->counter(array(
                'format' => __('Page <span class="badge">{:page}</span> of <span class="badge">{:pages}</span>,
                showing <span class="badge">{:current}</span> Aggregates out of
                <span class="badge badge-inverse">{:count}</span> total, starting on record <span class="badge">{:start}</span>,
                ending on <span class="badge">{:end}</span>')
            ));
            ?>
        </p>
        <?php echo $this->Form->end(); ?>

        <div class="pagination">
            <ul>
                <?php
                echo $this->Paginator->prev('&laquo;', array('tag' => 'li', 'disabledTag' => 'a', 'escape' => false), null, array('class' => 'disabled', 'tag' => 'li', 'currentTag' => 'a', 'escape' => false));
                echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentTag' => 'a', 'currentClass' => 'active'));
                echo $this->Paginator->next('&raquo;', array('tag' => 'li', 'disabledTag' => 'a', 'escape' => false), null, array('class' => 'disabled', 'tag' => 'li', 'escape' => false));
                ?>
            </ul>
        </div>

        <table class="table  table-bordered table-striped">
            <thead>
                <tr>
                    <th><?php echo $this->Paginator->sort('id'); ?></th>
                    <th><?php echo $this->Paginator->sort('reference_no'); ?></th>
                    <th><?php echo $this->Paginator->sort('brand_name'); ?></th>
                    <th><?php echo $this->Paginator->sort('inn_name'); ?></th>
                    <th><?php echo $this->Paginator->sort('reporter_email'); ?></th>
                    <th><?php echo $this->Paginator->sort('created'); ?></th>
                    <th><?php echo $this->Paginator->sort('date_submitted'); ?></th>
                    <th class="actions"><?php echo __('Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 0;
                foreach ($aggregates as $aggregate) : ?>
                    <tr class="">
                        <td><?php
                            $counter++;
                            echo $counter; ?>&nbsp;</td>
                        <td>
                            <?php
                            echo $this->Html->link($aggregate['Aggregate']['reference_no'], array('action' => 'view', $aggregate['Aggregate']['id']), array('escape' => false));
                            ?>&nbsp;</td>
                        <td>
                            <?php
                            echo $this->Text->truncate($aggregate['Aggregate']['brand_name'], 42);
                            if ($aggregate['Aggregate']['report_type'] == 'Followup') {
                                echo "<br> Initial: ";
                                echo $this->Html->link(
                                    '<label class="label label-info">' . $aggregate['Aggregate']['reference_no'] . '</label>',
                                    array('action' => 'view', $aggregate['Aggregate']['aggregate_id']),
                                    array('escape' => false)
                                );
                            }
                            ?>&nbsp;
                        </td>
                        <td><?php echo h($aggregate['Aggregate']['inn_name']); ?>&nbsp;</td>
                        <td><?php echo h($aggregate['Aggregate']['reporter_email']); ?>&nbsp;</td>
                        <td><?php echo h($aggregate['Aggregate']['created']); ?>&nbsp;</td>
                        <td><?php echo h($aggregate['Aggregate']['submitted_date']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php
                            if ($aggregate['Aggregate']['submitted'] > 1) {
                                echo $this->Html->link(
                                    '<span class="label label-info tooltipper" title="View"><i class="fa fa-eye" aria-hidden="true"></i> View </span>',
                                    array('controller' => 'aggregates', 'action' => 'view', $aggregate['Aggregate']['id']),
                                    array('escape' => false)
                                );
                                echo "&nbsp;";
                                if ($redir == 'reporter'  and $this->Session->read('Auth.User.user_type') != 'Public Health Program') echo $this->Form->postLink('<span class="label label-inverse tooltipper" data-toggle="tooltip" title="Add follow up report"> <i class="fa fa-facebook" aria-hidden="true"></i> Followup </span>', array('controller' => 'aggregates', 'action' => 'followup', $aggregate['Aggregate']['id']), array('escape' => false), __('Add a followup report?'));


                                echo "&nbsp;";
                                if (($redir == 'manager' || $redir == 'reviewer') && $aggregate['Aggregate']['user_id'] == $this->Session->read('Auth.User.id'))  echo $this->Form->postLink('<span class="label label-inverse tooltipper" data-toggle="tooltip" title="Add follow up report"> <i class="fa fa-facebook" aria-hidden="true"></i> Followup </span>', array('controller' => 'aggregates', 'action' => 'followup', $aggregate['Aggregate']['id']), array('escape' => false), __('Add a followup report?'));
                                echo "&nbsp;";
                                if (($redir == 'manager' || $redir == 'reviewer') && $aggregate['Aggregate']['copied'] == 2) echo $this->Html->link(
                                    '<span class="label label-success tooltipper" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit </span>',
                                    array('controller' => 'aggregates', 'action' => 'edit', $aggregate['Aggregate']['id']),
                                    array('escape' => false)
                                );
                                echo "&nbsp;";
                                // if (($redir == 'manager' || $redir == 'reviewer') && $aggregate['Aggregate']['copied'] == 0) echo $this->Form->postLink('<span class="badge badge-success tooltipper" data-toggle="tooltip" title="Copy & Edit"> <i class="fa fa-copy" aria-hidden="true"></i> Copy </span>', array('controller' => 'aggregates', 'action' => 'copy', $aggregate['Aggregate']['id']), array('escape' => false), __('Create a clean copy to edit?'));
                                if (($redir == 'manager' || $redir == 'reviewer')) echo $this->Html->link(
                                    '<span class="label label-warning tooltipper" title="View"><i class="fa fa-refresh" aria-hidden="true"></i> Archive </span>',
                                    array('controller' => 'aggregates', 'action' => 'archive', $aggregate['Aggregate']['id']),
                                    array('escape' => false),
                                    __('Are you sure you want to archive the report?')
                                );
                            } else {
                                if ($redir == 'reporter') echo $this->Html->link(
                                    '<span class="label label-success tooltipper" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit </span>',
                                    array('controller' => 'aggregates', 'action' => 'edit', $aggregate['Aggregate']['id']),
                                    array('escape' => false)
                                );
                                echo "&nbsp;";
                                if (($redir == 'reporter' || $redir == 'manager') && $aggregate['Aggregate']['submitted'] == 0) {
                                    echo "&nbsp;";
                                    echo $this->Form->postLink('<span class="label label-warning tooltipper" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete </span>', array('controller' => 'aggregates', 'action' => 'delete', $aggregate['Aggregate']['id']), array('escape' => false), __('Are you sure you want to delete this report?
                                    Note: This action cannot be undone.'));
                                }
                                echo "&nbsp;";
                                if ($redir == 'manager' || $redir == 'reviewer') echo $this->Html->link(
                                    '<span class="label label-info tooltipper" title="View"><i class="fa fa-eye" aria-hidden="true"></i> View </span>',
                                    array('controller' => 'aggregates', 'action' => 'view', $aggregate['Aggregate']['id']),
                                    array('escape' => false)
                                );
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<script type="text/javascript">
    $(function() {
        var adates = $('#AggregateStartDate, #AggregateEndDate').datepicker({
            minDate: "-100Y",
            maxDate: "-0D",
            dateFormat: 'dd-mm-yy',
            format: 'dd-mm-yy',
            endDate: '-0d',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            showAnim: 'show',
            onSelect: function(selectedDate) {
                var option = this.id == "AggregateStartDate" ? "minDate" : "maxDate",
                    instance = $(this).data("datepicker"),
                    date = $.datepicker.parseDate(
                        instance.settings.dateFormat ||
                        $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings);
                adates.not(this).datepicker("option", option, date);
            }
        });

    });
</script>