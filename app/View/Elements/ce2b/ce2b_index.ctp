<?php
$this->assign('E2B', 'active');
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
                '<i class="fa fa-file-o" aria-hidden="true"></i> New E2b',
                array('controller' => 'ce2bs', 'action' => 'add'),
                array('escape' => false, 'class' => 'btn btn-success')
            );
        }
        ?>
        <h3>E2b Reports:<small> <i class="icon-glass"></i> Filter, <i class="icon-search"></i> Search, and <i class="icon-eye-open"></i> view reports</small>

        </h3>
        <hr class="soften" style="margin: 7px 0px;">
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <?php
        echo $this->Form->create('Ce2b', array(
            'url' => array_merge(array('action' => 'index'), $this->params['pass']),
            'class' => 'ctr-groups',
            'style' => array('padding:9px;', 'background-color: #F5F5F5'),
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
                                'placeholder' => 'e2b/2023',
                                'class' => 'span12',
                                'label' => array('class' => 'required', 'text' => 'Reference No.')
                            )
                        );
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->input(
                            'company_name',
                            array(
                                'div' => false,
                                'placeholder' => '',
                                'class' => 'span12 unauthorized_index',
                                'label' => array('class' => 'required', 'text' => 'Company Name ')
                            )
                        );
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->input(
                            'reporter_email',
                            array(
                                'div' => false,
                                'placeholder' => '',
                                'class' => 'span12 unauthorized_index',
                                'label' => array('class' => 'required', 'text' => 'Reporter Email ')
                            )
                        );
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->input(
                            'start_date',
                            array(
                                'div' => false,
                                'type' => 'text',
                                'class' => 'input-small unauthorized_index',
                                'after' => '-to-',
                                'label' => array('class' => 'required', 'text' => 'Report Dates'),
                                'placeHolder' => 'Start Date'
                            )
                        );
                        echo $this->Form->input(
                            'end_date',
                            array(
                                'div' => false,
                                'type' => 'text',
                                'class' => 'input-small unauthorized_index',
                                'after' => '<a style="font-weight:normal" onclick="$(\'.unauthorized_index\').val(\'\');" >
                              <em class="accordion-toggle">clear!</em></a>',
                                'label' => false,
                                'placeHolder' => 'End Date'
                            )
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo $this->Form->input(
                            'drug_name',
                            array(
                                'div' => false,
                                'placeholder' => 'drug name',
                                'class' => 'span12 unauthorized_index',
                                'label' => array('class' => 'required', 'text' => 'Drug Name')
                            )
                        );
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        <h5>Serious?</h5>
                        <?php
                        echo $this->Form->input('serious', array(
                            'options' => array('1' => 'Yes', '0' => 'No'),
                            'legend' => false,
                            'type' => 'radio'
                        ));
                        ?>

                        <h5>Report Status</h5>
                        <?php

                        echo $this->Form->input('submitted', array(
                            'options' => array('1' => 'UnSubmitted', '2' => 'Submitted'),
                            'legend' => false,
                            'type' => 'radio'
                        ));
                        ?>
                    </td>

                    <td>
                        <?php
                        echo $this->Form->input(
                            'inn',
                            array('div' => false, 'placeholder' => 'Brand Name', 'class' => 'span12 unauthorized_index', 'label' => array('class' => 'required', 'text' => 'Brand Name'))
                        );
                        ?>
                        <h5>Vigiflow status:</h5>
                        <?php
                        echo $this->Form->input('vigiflow', array(
                            'type' => 'select',
                            'options' => [
                                '0' => 'Uploaded',
                                '1' => 'Pending',
                            ],
                            'empty' => true,
                            'label' => array('class' => 'control-label', 'text' => ''),
                            'class' => 'input-xlarge'
                        ));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->input(
                            'sender_unique_identifier',
                            array('div' => false, 'placeholder' => 'eg KE-xx', 'class' => 'span12 unauthorized_index', 'label' => array('class' => 'required', 'text' => 'Sender Identifier'))
                        );
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->input(
                            'sender_organization',
                            array('div' => false, 'placeholder' => 'Sender\'s Org', 'class' => 'span12 unauthorized_index', 'label' => array('class' => 'required', 'text' => 'Sender\'s Organization'))
                        );
                        ?>


                    </td>
                    <td>
                        <?php
                        if ($redir == 'manager') { ?>
                            <h6>Feedback Status</h6>

                        <?php
                            echo $this->Form->input('has_review', [
                                'type' => 'checkbox',
                                'hiddenField' => false,
                                'label' => 'Show',
                            ]);
                        } ?>
                    </td>
                    <td>
                        <?php
                        if ($redir == 'manager') { ?>
                            <h6>Archived Status</h6>
                        <?php
                            echo $this->Form->input('archived', [
                                'type' => 'checkbox',
                                'hiddenField' => false,
                                'label' => 'Show',
                            ]);
                        } ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="PadrPages" class="required">Pages</label></td>
                    <td>
                        <?php
                        echo $this->Form->input('pages', array(
                            'type' => 'select',
                            'div' => false,
                            'class' => 'input-small',
                            'selected' => $this->request->params['paging']['Ce2b']['limit'],
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
                            'class' => 'btn btn-primary',
                            'div' => 'control-group',
                            'div' => false,
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
                        echo $this->Html->link('<i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel', array('action' => 'index', 'ext' => 'csv', '?' => $this->request->query), array('class' => 'btn btn-success', 'escape' => false));
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <p>
            <?php
            echo $this->Paginator->counter(array(
                'format' => __('Page <span class="badge">{:page}</span> of <span class="badge">{:pages}</span>,
                showing <span class="badge">{:current}</span> Ce2bs out of
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
                    <th><?php echo $this->Paginator->sort('company_name'); ?></th>
                    <th><?php echo $this->Paginator->sort('reporter_email'); ?></th>
                    <th><?php echo $this->Paginator->sort('e2b_file_type'); ?></th>
                    <?php if ($redir == 'manager' || $redir == 'reviewer') { ?><th><?php echo $this->Paginator->sort('vigiflow_ref'); ?></th> <?php } ?>
                    <th><?php echo $this->Paginator->sort('created'); ?></th>
                    <th><?php echo $this->Paginator->sort('submitted_date', 'Date Submitted'); ?></th>
                    <th class="actions"><?php echo __('Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($ce2bs as $ce2b) : ?>
                    <tr class="">
                        <td><?php echo h($ce2b['Ce2b']['id']); ?>&nbsp;</td>
                        <td>
                            <?php
                            if ($ce2b['Ce2b']['submitted'] > 1) {
                                echo $this->Html->link($ce2b['Ce2b']['reference_no'], array('action' => 'view', $ce2b['Ce2b']['id']), array('escape' => false, 'class' => 'text-' . ((isset($ce2b['Ce2b']['serious']) && $ce2b['Ce2b']['serious'] == '1') ? 'error' : 'success')));
                            } else {
                                echo $this->Html->link($ce2b['Ce2b']['reference_no'], array('action' => (($redir == 'reporter') ? 'edit' : 'view'), $ce2b['Ce2b']['id']), array('escape' => false, 'class' => 'text-' . ((isset($ce2b['Ce2b']['serious']) && $ce2b['Ce2b']['serious'] == '1') ? 'error' : 'success')));
                            }
                            ?>&nbsp;
                        </td>
                        <td><?php echo h($ce2b['Ce2b']['company_name']);
                            if ($ce2b['Ce2b']['report_type'] == 'Followup') {
                                echo "<br> <b>Follow Up:</b> ";
                                echo $this->Html->link(
                                    '<label class="label label-info">' . $ce2b['Ce2b']['reference_no'] . '</label>',
                                    array('action' => 'view', $ce2b['Ce2b']['ce2b_id']),
                                    array('escape' => false)
                                );
                            }


                            ?>&nbsp;</td>
                        <td><?php echo h($ce2b['Ce2b']['reporter_email']); ?>&nbsp;</td>
                        <td><?php echo h($ce2b['Ce2b']['e2b_type']); ?>&nbsp;</td>
                        <?php if ($redir == 'manager' || $redir == 'reviewer') { ?>
                            <td><?php echo h($ce2b['Ce2b']['vigiflow_ref']);
                                echo "\n" . $ce2b['Ce2b']['vigiflow_date']; ?>&nbsp;</td>
                        <?php } ?>
                        <td><?php echo h($ce2b['Ce2b']['created']); ?>&nbsp;</td>
                        <td><?php echo h($ce2b['Ce2b']['submitted_date']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php
                            if ($ce2b['Ce2b']['submitted'] > 1) {
                                echo $this->Html->link(
                                    '<span class="label label-info tooltipper" title="View"><i class="fa fa-eye" aria-hidden="true"></i> View </span>',
                                    array('controller' => 'ce2bs', 'action' => 'view', $ce2b['Ce2b']['id']),
                                    array('escape' => false)
                                );
                                echo "&nbsp;";
                                if ($redir == 'reporter'  and $this->Session->read('Auth.User.user_type') != 'Public Health Program') echo $this->Form->postLink('<span class="label label-inverse tooltipper" data-toggle="tooltip" title="Add follow up report"> <i class="fa fa-facebook" aria-hidden="true"></i> Followup </span>', array('controller' => 'ce2bs', 'action' => 'followup', $ce2b['Ce2b']['id']), array('escape' => false), __('Add a followup report?'));
                                echo "&nbsp;";
                                if (($redir == 'manager' || $redir == 'reviewer')) echo $this->Form->postLink('<span class="label label-inverse tooltipper" data-toggle="tooltip" title="Download E2B file"> <i class="fa fa-etsy" aria-hidden="true"></i> 2 <i class="fa fa-bold" aria-hidden="true"></i> </span>', array('controller' => 'ce2bs', 'action' => 'download', $ce2b['Ce2b']['id'], 'ext' => 'xml', 'manager' => false), array('escape' => false), __('Download E2B?'));
                                echo "&nbsp;";

                                // if ($redir == 'manager') echo $this->Html->link(
                                //     '<span class="label label-warning tooltipper" title="Send to vigiflow"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Vigiflow </span>',
                                //     array('controller' => 'ce2bs', 'action' => 'vigiflow', $ce2b['Ce2b']['id'], 'manager' => false),
                                //     array('escape' => false)
                                // );
                                if (($redir == 'manager' || $redir == 'reviewer') && empty($ce2b['Ce2b']['vigiflow_ref']) && $ce2b['Ce2b']['copied'] == 2) echo $this->Html->link(
                                    '<span class="label label-warning tooltipper" title="Send to vigiflow"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Vigiflow </span>',
                                    array('controller' => 'ce2bs', 'action' => 'vigiflow', $ce2b['Ce2b']['id'], 'manager' => false),
                                    array('escape' => false)
                                );

                                //Create a follow up as a manager
                                echo "&nbsp;";
                                if (($redir == 'manager' || $redir == 'reviewer') && $ce2b['Ce2b']['user_id'] == $this->Session->read('Auth.User.id'))  echo $this->Form->postLink('<span class="label label-inverse tooltipper" data-toggle="tooltip" title="Add follow up report"> <i class="fa fa-facebook" aria-hidden="true"></i> Followup </span>', array('controller' => 'ce2bs', 'action' => 'followup', $ce2b['Ce2b']['id']), array('escape' => false), __('Add a followup report?'));
                                echo "&nbsp;";
                                if (($redir == 'manager' || $redir == 'reviewer') && $ce2b['Ce2b']['copied'] == 2) echo $this->Html->link(
                                    '<span class="label label-success tooltipper" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit </span>',
                                    array('controller' => 'ce2bs', 'action' => 'edit', $ce2b['Ce2b']['id']),
                                    array('escape' => false)
                                );
                                echo "&nbsp;";
                                if (($redir == 'manager' || $redir == 'reviewer') && $ce2b['Ce2b']['copied'] == 0) echo $this->Form->postLink('<span class="badge badge-success tooltipper" data-toggle="tooltip" title="Copy & Edit"> <i class="fa fa-copy" aria-hidden="true"></i> Copy </span>', array('controller' => 'ce2bs', 'action' => 'copy', $ce2b['Ce2b']['id']), array('escape' => false), __('Create a clean copy to edit?'));

                                echo "&nbsp;";
                                echo $this->Html->link(
                                    '<span class="label label-default tooltipper" title="View"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF </span>',
                                    array('controller' => 'ce2bs', 'action' => 'view', 'ext' => 'pdf', $ce2b['Ce2b']['id']),
                                    array('escape' => false)
                                );
                                if ($redir == 'manager' && $ce2b['Ce2b']['archived'] == 0)  echo $this->Html->link(
                                    '<span class="label label-warning tooltipper" title="Archive"><i class="fa fa-refresh" aria-hidden="true"></i> Archive </span>',
                                    array('controller' => 'ce2bs', 'action' => 'archive', $ce2b['Ce2b']['id']),
                                    array('escape' => false),
                                    __('Are you sure you want to archive the report?')
                                );
                                if ($redir == 'manager' && $ce2b['Ce2b']['archived'] == 1) echo $this->Html->link(
                                    '<span class="label label-warning tooltipper" title="Restore"><i class="fa fa-refresh" aria-hidden="true"></i> Restore </span>',
                                    array('controller' => 'ce2bs', 'action' => 'restore_archive', $ce2b['Ce2b']['id']),
                                    array('escape' => false),
                                    __('Are you sure you want to restore archive the report?')
                                );

                                // PDF Download

                            } else {
                                if ($redir == 'reporter') echo $this->Html->link(
                                    '<span class="label label-success tooltipper" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit </span>',
                                    array('controller' => 'ce2bs', 'action' => 'edit', $ce2b['Ce2b']['id']),
                                    array('escape' => false)
                                );
                                echo "&nbsp;";
                                if (($redir == 'reporter' || $redir == 'manager') && $ce2b['Ce2b']['submitted'] == 0) {
                                    echo "&nbsp;";
                                    echo $this->Form->postLink('<span class="label label-warning tooltipper" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete </span>', array('controller' => 'ce2bs', 'action' => 'delete', $ce2b['Ce2b']['id']), array('escape' => false), __('Are you sure you want to delete this report?
                                    Note: This action cannot be undone.'));
                                }
                                echo "&nbsp;";
                                if ($redir == 'manager' || $redir == 'reviewer') echo $this->Html->link(
                                    '<span class="label label-info tooltipper" title="View"><i class="fa fa-eye" aria-hidden="true"></i> View </span>',
                                    array('controller' => 'ce2bs', 'action' => 'view', $ce2b['Ce2b']['id']),
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
        var adates = $('#Ce2bStartDate, #Ce2bEndDate').datepicker({
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
                var option = this.id == "PadrStartDate" ? "minDate" : "maxDate",
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