<?php
$this->extend('/Reports/upgrade/menu/general');
$this->assign('general', 'active');
$this->Html->css('summary', null, array('inline' => false));
?>

<?php $this->start('report'); ?>

<div class="row-fluid">
    <div class="span12">
        <p>
            <?php
            echo $this->Paginator->counter(array(
                'format' => __('Page <span class="badge">{:page}</span> of <span class="badge">{:pages}</span>,
                showing <span class="badge">{:current}</span> Reports out of
                <span class="badge badge-inverse">{:count}</span> total, starting on record <span class="badge">{:start}</span>,
                ending on <span class="badge">{:end}</span>')
            ));
            ?>
        </p>
    </div>
</div>

<div class="row-fluid">

    <div class="span12">
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th>Total Cases <?php echo $total ?></th>
                </tr>
                <tr>
                    <th colspan="2">Disproportionality Analysis Data</th>
                </tr>
                <tr>
                    <th>Drug/Vaccine</th>
                    <th>Drug Reaction</th>
                    <th>Observed</th>
                    <th>Expected</th>
                    <th>IC025>0</th>
                    <th>IC</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($disproportionalities as $key => $value) {
                    foreach ($value['reactionDetails'] as $key => $dt) { ?>
                        <?php

                        // Assuming $dt is an array with your data
                        $confidenceInterval = $dt['95%_Confidence_Interval']; //round($dt['95%_Confidence_Interval']);

                        // Determine the color based on the value
                        $color = $confidenceInterval > 0 ? 'red' : 'green';

                        $log = $dt['IC_raw_calculated_log_data']; //round($dt['IC_raw_calculated_log_data']);


                        echo "<tr>";
                        echo "<td>" . ucwords($value['current_drug_name']) . "</td>";
                        echo "<td>" . $dt['reaction_at_hand'] . "</td>";
                        echo "<td>" . $dt['B_reports_with_reaction'] . "</td>";
                        echo "<td>" . (int)$dt['E_(AB)_expected_count'] . "</td>";
                        echo "<td style='color: $color;'>" . round($confidenceInterval, 2) . "</td>";
                        echo "<td>" . $log . "</td>";
                        echo "</tr>";
                        ?>

                <?php }
                } ?>
            </tbody>
        </table>
    </div>
</div>

<hr>
<?php $this->end(); ?>