<?php
$this->extend('/Reports/upgrade/menu/d_sadr');
$this->assign('medications_analytics', 'active');
$this->Html->css('summary', null, array('inline' => false));
?>

<?php $this->start('report'); ?>

<div class="row-fluid">

    <div class="span12"> 
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th>Total Cases <?php echo $total ?></th>
                </tr>
                <tr>
                    <th>Drug Name</th> 
                    <th colspan="2">Disproportionality Analysis Data</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($inputData as $key => $value) {
                    echo "<tr>";
                    echo "<th>" . $value['current_drug_name'] . "</th>"; 
                    echo "<td>"; ?>
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr> 
                                <th>Drug Reaction</th>
                                <th>Observed</th> 
                                <!-- <th>Drug vs Reaction</th> -->
                                <th>Expected</th>
                                <th>IC025>0</th> 
                                <th>IC</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($value['reactionDetails'] as $key => $dt) { ?>
                                <?php 

                                // Assuming $dt is an array with your data
                                $confidenceInterval = $dt['95%_Confidence_Interval']; //round($dt['95%_Confidence_Interval']);

                                // Determine the color based on the value
                                $color = $confidenceInterval > 0 ? 'red' : 'green';

                                $log = $dt['IC_raw_calculated_log_data']; //round($dt['IC_raw_calculated_log_data']);


                                echo "<tr>";
                                echo "<td>" . $dt['reaction_at_hand'] . "</td>";
                                echo "<td>" . $dt['B_reports_with_reaction'] . "</td>"; 
                                // echo "<td>" . $dt['AB_reports_with_drug_and_reaction'] . "</td>"; 
                                echo "<td>" . (int)$dt['E_(AB)_expected_count'] . "</td>";
                                echo "<td style='color: $color;'>" . round($confidenceInterval,2) . "</td>";  
                                echo "<td>" . $log . "</td>"; 
                                echo "</tr>";
                                ?>

                            <?php } ?>
                        </tbody>
                    </table><?php

                            echo "</td>";
                            echo "</tr>";
                        }
                            ?>

            </tbody>
        </table>
    </div>
</div>

<hr>
<?php $this->end(); ?>