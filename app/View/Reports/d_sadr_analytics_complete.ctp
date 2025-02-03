<?php
$this->extend('/Reports/upgrade/menu/d_sadr');
$this->assign('d-sadr-analysis', 'active');
$this->Html->css('summary', null, array('inline' => false));
?>

<?php $this->start('report'); ?>

<div class="row-fluid">

    <div class="span12">
        <!-- N- Total Reports <br>
        A- Reports with the drug of interface_exists <br>
        B- Reports with reaction of interest <br>
        AB- Reports with both the drug and reaction of interest <br>
        E_AB- Observed vs. Expected calculated as -> E(AB)= (A * B)/N<br>
        IC- Raw Data Observed vs. Expected calculated as IC=(E(AB)+0.5)/(AB+0.5)<br> 
        Loc IC -Log of the above  log2(IC)<br>
        Variance of IC- Calculated <br>
        Standard Error <br>
        95% Confidence -> Calculated -->
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th>Total Cases <?php echo $total ?></th>
                </tr>
                <tr>
                    <th>Drug Name</th>
                    <th>Total Cases</th>
                    <th>A-Drug</th>
                    <th colspan="2">Disproportionality Analysis Data</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($inputData as $key => $value) {
                    echo "<tr>";
                    echo "<th>" . $value['current_drug_name'] . "</th>";
                    echo "<th>" . $value['N_total_reports'] . "</th>";
                    echo "<th>" . $value['A_reports_with_drug'] . "</th>";
                    echo "<td>"; ?>
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Drug Reaction</th>
                                <th>B-Observed</th>
                                <th>AB - (Both Drug and Reaction)</th>
                                <th>E_AB (Expected)</th>
                                <th>IC</th>
                                <th>Log IC</th>
                                <th>Var(IC)</th>
                                <th>Standard Error (SE) of IC</th>
                                <th>95% Confidence Interval</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($value['reactionDetails'] as $key => $dt) { ?>
                                <?php
                                  echo "<tr>"; 
                                  echo "<td>" . $dt['reaction_at_hand'] . "</td>";
                                  echo "<td>" . $dt['B_reports_with_reaction'] . "</td>";
                                  echo "<td>" . $dt['AB_reports_with_drug_and_reaction'] . "</td>";
                                  echo "<td>" . $dt['E_(AB)_expected_count'] . "</td>";
                                  echo "<td>" . $dt['IC_raw_calculated_data'] . "</td>";
                                  echo "<td>" . $dt['IC_raw_calculated_log_data'] . "</td>"; 
                                  echo "<td>" . $dt['Var(IC)_Variance_of_IC'] . "</td>";
                                  echo "<td>" . $dt['Standard_Error_(SE)_of_IC'] . "</td>";
                                  echo "<td>" . $dt['95%_Confidence_Interval'] . "</td>";
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