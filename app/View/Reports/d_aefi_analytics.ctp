<?php
$this->extend('/Reports/upgrade/menu/d_aefi');
$this->assign('d-aefi-analysis', 'active');
$this->Html->css('summary', null, array('inline' => false));
?>

<?php $this->start('report'); ?>

<div class="row-fluid">

    <div class="span12">
        <!-- N- Total Reports <br>
        A- Reports with the drug of interest_exists <br>
        B- Reports with reaction of interest -::::: Observed<br>
        AB- Reports with both the drug and reaction of interest <br>
        E_AB- Observed vs. Expected calculated as -> E(AB)= ((A + AB)(B+AB))/N<br>
        IC- Raw Data Observed vs. Expected calculated as IC=(AB+0.5)/(E(AB)+0.5)<br>
        Log IC -Log of the above log2(IC)<br>
        Variance of IC- Calculated <br>
        Standard Error <br>
        95% Confidence -> Calculated -->
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th>Total Cases <?php echo $total ?></th>
                </tr>
                <tr>
                    <th>Vaccine Name</th>
                    <!-- <th>Total Cases</th> -->
                    <!-- <th>A-Drug</th> -->
                    <th colspan="2">Disproportionality Analysis Data</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($inputData as $key => $value) {
                    echo "<tr>";
                    echo "<th>" . $value['current_drug_name'] . "</th>";
                    // echo "<th>" . $value['N_total_reports'] . "</th>";
                    // echo "<th>" . $value['A_reports_with_drug'] . "</th>";
                    echo "<td>"; ?>
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Drug Reaction</th>
                                <th>Observed</th>
                                <!-- <th>AB </th> -->
                                <th>Expected</th>
                                <th>IC025>0</th>
                                <!-- <th>IC Raw Data</th> -->
                                <th>IC</th>
                                <!-- <th>Variance of IC</th> -->
                                <!-- <th>Standard Error (SE (IC))</th> -->
                               
                                <!--  <th>IC025 > 0</th>
                                <th>Var(IC)</th> -->
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
                                // echo "<td>" . $dt['IC_raw_calculated_data'] . "</td>";
                                echo "<td>" . $log . "</td>";
                                // echo "<td>" . $dt['Var(IC)_Variance_of_IC'] . "</td>";
                                // echo "<td>" . $dt['Standard_Error_(SE)_of_IC'] . "</td>";
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