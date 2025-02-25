<?php
$this->extend('/Reports/upgrade/menu/d_aefi');
$this->assign('d-aefi-analysis', 'active');
$this->Html->css('summary', null, array('inline' => false));
?>

<?php $this->start('report'); ?>

<div class="row-fluid">

    <div class="span12">
        <table id="data-table" class="table table-condensed table-bordered">
            <?php
            $total = 0
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
            </tbody>
        </table>
    </div>
</div>

<hr>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        fetch('/reports/load_data/Aefi') // Adjust the endpoint as necessary
            .then(response => response.json())
            .then(data => {
                let tbody = document.querySelector("#data-table tbody");
                tbody.innerHTML = ""; // Clear existing rows

                data.forEach(item => {
                    let rowData = item.Disproportionality; // Extract Disproportionality object
                    let confidenceInterval = parseFloat(rowData.confidence_interval);
                    let log = rowData.ic_calculated_data;
                    let color = confidenceInterval > 0 ? 'red' : 'green';

                    let tr = document.createElement("tr");
                    tr.innerHTML = `
                    <td>${rowData.drug_name}</td>
                    <td>${rowData.reaction_name}</td>
                    <td>${rowData.b_reports}</td>
                    <td>${parseInt(rowData.eab_expected)}</td>
                    <td style="color: ${color};">${confidenceInterval.toFixed(2)}</td>
                    <td>${log}</td>
                `;
                    tbody.appendChild(tr);
                });
            })
            .catch(error => console.error("Error fetching data:", error));
    });
</script>
<?php $this->end(); ?>