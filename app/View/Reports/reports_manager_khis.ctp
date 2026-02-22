<?php
$this->assign('KHIS', 'active');
echo $this->Session->flash();
$this->Html->script('reports', array('inline' => false));
$this->Html->script('highcharts/highcharts', array('inline' => false));
$this->Html->script('highcharts/modules/data', array('inline' => false));
if ($this->Session->read('Auth.User.group_id') === '2') {
    $this->Html->script('highcharts/modules/exporting', array('inline' => false));
}
if ($this->Session->read('Auth.User.group_id') === '2') {
    $this->Html->script('highcharts/modules/export-data', array('inline' => false));
}

$activeReportFilters = isset($reportFilters) && is_array($reportFilters) ? $reportFilters : array();
$hasDefaultRange = !empty($activeReportFilters['is_default_range']);
$filterSummary = $hasDefaultRange
    ? 'Showing previous month by default. Apply filters to change the reporting range.'
    : 'Showing data for your current filter selection.';
?>

<div class="container">
    <div class="row-fluid">
        <div class="span12">
            <div class="formbacka" style="padding: 12px; border-width: 1px;">
                <div class="row-fluid">
                    <div class="span8">
                        <h4 class="text-success">KHIS Summary Dashboard</h4>
                        <p class="muted"><?php echo h($filterSummary); ?></p>
                    </div>
                </div>

                <?php
                echo $this->Form->create('Report', array(
                    'class' => 'ctr-groups'
                ));
                ?>
                <div class="row-fluid">
                    <div class="span3">
                        <?php
                        echo $this->Form->input('start_date', array(
                            'div' => false,
                            'type' => 'select',
                            'class' => 'span12 unauthorized_index',
                            'label' => array('class' => 'required', 'text' => 'Month'),
                            'options' => array(
                                '01' => 'January',
                                '02' => 'February',
                                '03' => 'March',
                                '04' => 'April',
                                '05' => 'May',
                                '06' => 'June',
                                '07' => 'July',
                                '08' => 'August',
                                '09' => 'September',
                                '10' => 'October',
                                '11' => 'November',
                                '12' => 'December'
                            ),
                            'empty' => 'Month',
                            'default' => isset($activeReportFilters['month']) ? $activeReportFilters['month'] : null
                        ));
                        ?>
                    </div>
                    <div class="span3">
                        <?php
                        $currentYear = date('Y');
                        $years = range($currentYear, 1960);
                        echo $this->Form->input('end_date', array(
                            'div' => false,
                            'type' => 'select',
                            'class' => 'span12 unauthorized_index',
                            'label' => array('class' => 'required', 'text' => 'Year'),
                            'options' => array_combine($years, $years),
                            'empty' => 'Year',
                            'default' => isset($activeReportFilters['year']) ? $activeReportFilters['year'] : null
                        ));
                        ?>
                    </div>
                    <div class="span6">
                        <?php
                        echo $this->Form->input('county_id', array(
                            'div' => false,
                            'type' => 'select',
                            'class' => 'span12 unauthorized_index',
                            'label' => array('class' => 'required', 'text' => 'County'),
                            'empty' => 'All Counties',
                            'options' => $counties,
                            'default' => isset($activeReportFilters['county_id']) && $activeReportFilters['county_id'] !== ''
                                ? $activeReportFilters['county_id']
                                : $this->Session->read('Auth.User.county_id')
                        ));
                        ?>
                    </div>
                </div>

                <div style="margin-top: 10px;">
                    <?php
                    echo $this->Form->button('<i class="icon-search icon-white"></i> Search', array(
                        'name' => 'searchReport',
                        'class' => 'btn btn-primary',
                        'div' => false,
                        'formnovalidate' => 'formnovalidate',
                        'escape' => false
                    ));
                    echo ' ';
                    echo $this->Html->link(
                        '<i class="icon-remove"></i> Clear',
                        array('controller' => 'khis', 'action' => 'index', 'manager' => true),
                        array('class' => 'btn', 'escape' => false)
                    );
                    echo ' ';
                    echo $this->Form->button('<i class="fa fa-paper-plane-o icon-white"></i> Upload', array(
                        'name' => 'uploadReport',
                        'onclick' => "return confirm('Are you sure you wish to upload the data?');",
                        'class' => 'btn btn-success',
                        'div' => false,
                        'formnovalidate' => 'formnovalidate',
                        'escape' => false
                    ));
                    ?>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>

            <?php echo $this->fetch('report'); ?>
        </div>
    </div>

    <?php if ($is_mobile) { ?>
        <div class="row-fluid">
            <div class="span12">
                <?php
                if ($this->Session->read('Auth.User.user_type') == 'County Pharmacist') {
                    echo $this->element('menus/report_county_sidebar');
                } elseif ($this->Session->read('Auth.User.user_type') == 'Public Health Program') {
                    echo $this->element('menus/report_program_sidebar');
                } elseif ($this->Session->read('Auth.User.group_id') == 3) {
                    echo $this->element('menus/report_public_sidebar');
                } elseif (!$this->Session->read('Auth.User')) {
                    echo $this->element('menus/report_public_sidebar');
                } else {
                    echo $this->element('menus/report_sidebar');
                }
                ?>
            </div>
        </div>
    <?php } ?>
</div>
