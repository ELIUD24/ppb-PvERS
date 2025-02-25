<?php
$this->extend('/Reports/upgrade/menu/d_aefi');
$this->assign('d-aefi-analysis', 'active');
$this->Html->css('summary', null, array('inline' => false));
?>

<?php $this->start('report'); ?>

<div class="row-fluid">

    <div class="span12">
        <table class="table table-condensed table-bordered">
            <?php
            $totalColumn = 'total'; // Change this to the column you need

            if (!empty($inputData)) {
                $lastItem = end($inputData);
                 // Get the last record
                $total = Hash::get($lastItem, "Disproportionality.$totalColumn", 0); // Extract 'total' from last item
                 
            } else {
                $total ="0";
            }
            ?>
            <thead>
                <tr>
                    <th>Total Cases <?php echo $total ?></th>
                </tr>
                <tr> 
                    <th><?php echo $this->Paginator->sort('Vaccine Name'); ?></th>
                    <th><?php echo $this->Paginator->sort('Drug Reaction'); ?></th>
                    <th><?php echo $this->Paginator->sort('Observed'); ?></th>
                    <th><?php echo $this->Paginator->sort('Expected'); ?></th>
                    <th><?php echo $this->Paginator->sort('IC025>0'); ?></th>
                    <th><?php echo $this->Paginator->sort('IC'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($inputData as $key => $value) {
                     
                   $confidenceInterval= $value['Disproportionality']['confidence_interval'];
                   $log=$value['Disproportionality']['ic_calculated_data'];
                   $color = $confidenceInterval > 0 ? 'red' : 'green';
                    echo "<tr>";
                    echo "<td>" . $value['Disproportionality']['drug_name'] . "</td>";
                    echo "<td> ". $value['Disproportionality']['reaction_name'] . "</td>";
                    echo "<td> ". $value['Disproportionality']['b_reports'] . "</td>";
                    echo "<td> ". (int)$value['Disproportionality']['eab_expected'] . "</td>";
                    echo "<td style='color: $color;'>" . round($confidenceInterval,2) . "</td>"; 
                    echo "<td>" . $log . "</td>";
                    echo "<tr>";
                } ?>


            </tbody>
        </table>
    </div>
</div>

<hr>
<?php $this->end(); ?>