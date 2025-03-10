<?php
$this->extend('/Reports/upgrade/menu/d_sadr');
$this->assign('medications_analytics', 'active');
$this->Html->css('summary', null, array('inline' => false));
?>

<?php $this->start('report'); ?>


<div class="row-fluid">

    <div class="span12">
        <table id="data-table" class="table table-condensed table-bordered">

            <thead>
                <tr>
                    <th id="totalCases">Total Cases: </th>
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
        let table = $("#data-table").DataTable({
            "paging": true, // Enable pagination
            "searching": true, // Enable search
            "ordering": true, // Enable column sorting
            "info": true, // Show info (e.g., "Showing 1 to 10 of 50 entries")
            "lengthMenu": [5, 10, 25, 50, 100], // Control number of rows per page
            "pageLength": 10 // Default number of rows per page
        });
        $.blockUI({
            message: '<h3>Please wait...</h3>',
            css: {
                border: '3px solid #aaa',
                padding: '10px'
            }
        });

        fetch('/reports/load_data/Medication') // Adjust endpoint as necessary
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    let total = data[0].Disproportionality.total; // Pick total from the first row
                    document.getElementById("totalCases").textContent = `Total Cases: ${total}`;
                }

                // Clear existing DataTable data
                table.clear();

                // Process each data row
                let rowDataArray = data.map(item => {
                    let rowData = item.Disproportionality;
                    let confidenceInterval = parseFloat(rowData.confidence_interval);
                    let expected = parseInt(rowData.eab_expected);

                    // Ensure values are not NaN, default to 0
                    confidenceInterval = isNaN(confidenceInterval) ? 0 : confidenceInterval;
                    expected = isNaN(expected) ? 0 : expected;

                    let color = confidenceInterval > 0 ? 'red' : 'green';

                    return [
                        rowData.drug_name || "N/A", // Default to "N/A" if missing
                        rowData.reaction_name || "N/A",
                        rowData.b_reports || 0, // Default to 0 if missing
                        expected,
                        `<span style="color: ${color};">${confidenceInterval.toFixed(2)}</span>`,
                        rowData.ic_calculated_data || "N/A"
                    ];
                });

                // Add new data to DataTable
                table.rows.add(rowDataArray).draw();
                $.unblockUI();
            })
            .catch(error => console.error("Error fetching data:", error));
    });
</script>


<hr>
<?php $this->end(); ?>