<?php
$this->extend('/Reports/reports_manager_khis');
$this->assign('khis-summary', 'active');

$sumCounts = function ($rows) {
    $total = 0;
    if (!is_array($rows)) {
        return $total;
    }
    foreach ($rows as $row) {
        if (isset($row[0]['cnt'])) {
            $total += (int) $row[0]['cnt'];
        }
    }
    return $total;
};

$safeRows = function ($rows) {
    return is_array($rows) ? $rows : array();
};

$sex = $safeRows(isset($sex) ? $sex : array());
$age = $safeRows(isset($age) ? $age : array());
$vaccine = $safeRows(isset($vaccine) ? $vaccine : array());
$sadr_gender = $safeRows(isset($sadr_gender) ? $sadr_gender : array());
$sadr_age = $safeRows(isset($sadr_age) ? $sadr_age : array());
$device_gender = $safeRows(isset($device_gender) ? $device_gender : array());
$device_age = $safeRows(isset($device_age) ? $device_age : array());
$medication_gender = $safeRows(isset($medication_gender) ? $medication_gender : array());
$medication_age = $safeRows(isset($medication_age) ? $medication_age : array());
$transfusion_gender = $safeRows(isset($transfusion_gender) ? $transfusion_gender : array());
$transfusion_age = $safeRows(isset($transfusion_age) ? $transfusion_age : array());

$aefiTotal = $sumCounts($sex);
$sadrTotal = $sumCounts($sadr_gender);
$deviceTotal = $sumCounts($device_gender);
$medicalErrorTotal = $sumCounts($medication_gender);
$transfusionTotal = $sumCounts($transfusion_gender);
$vaccineTotal = $sumCounts($vaccine);
?>

<?php $this->start('report'); ?>

<div class="row-fluid">
    <div class="span12">
        <div class="pformback" style="padding: 12px; margin-bottom: 12px; border-width: 1px;">
            <div class="row-fluid">
                <div class="span4">
                    <h5>Total AEFIs</h5>
                    <h3><?php echo (int) $aefiTotal; ?></h3>
                </div>
                <div class="span4">
                    <h5>Total SADRs</h5>
                    <h3><?php echo (int) $sadrTotal; ?></h3>
                </div>
                <div class="span4">
                    <h5>Total Devices</h5>
                    <h3><?php echo (int) $deviceTotal; ?></h3>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span4">
                    <h5>Total Medical Errors</h5>
                    <h3><?php echo (int) $medicalErrorTotal; ?></h3>
                </div>
                <div class="span4">
                    <h5>Total Blood Transfusions</h5>
                    <h3><?php echo (int) $transfusionTotal; ?></h3>
                </div>
                <div class="span4">
                    <h5>AEFI Vaccine Entries</h5>
                    <h3><?php echo (int) $vaccineTotal; ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <div class="formbacka" style="padding: 12px; margin-bottom: 12px; border-width: 1px;">
            <h4>AEFIs by Gender</h4>
            <table class="table table-striped table-bordered table-condensed" id="aefi-gender-table">
                <thead>
                    <tr>
                        <th>Sex</th>
                        <th class="text-right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sex)) { ?>
                        <tr>
                            <td colspan="2" class="muted">No data available</td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($sex as $value) { ?>
                        <tr>
                            <td><?php echo h($value['Aefi']['gender']); ?></td>
                            <td class="text-right"><?php echo (int) $value[0]['cnt']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-right"><?php echo (int) $aefiTotal; ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="span6">
        <div class="formbacka" style="padding: 12px; margin-bottom: 12px; border-width: 1px;">
            <h4>AEFIs by Age Group</h4>
            <table class="table table-striped table-bordered table-condensed" id="aefi-age-table">
                <thead>
                    <tr>
                        <th>Age Group</th>
                        <th class="text-right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $aefiAgeTotal = 0; ?>
                    <?php if (empty($age)) { ?>
                        <tr>
                            <td colspan="2" class="muted">No data available</td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($age as $value) { ?>
                        <?php $aefiAgeTotal += (int) $value[0]['cnt']; ?>
                        <tr>
                            <td><?php echo h($value[0]['ager']); ?></td>
                            <td class="text-right"><?php echo (int) $value[0]['cnt']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-right"><?php echo (int) $aefiAgeTotal; ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="formbacka" style="padding: 12px; margin-bottom: 12px; border-width: 1px;">
            <h4>AEFIs by Vaccine</h4>
            <table class="table table-striped table-bordered table-condensed" id="aefi-vaccine-table">
                <thead>
                    <tr>
                        <th>Vaccine</th>
                        <th class="text-right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($vaccine)) { ?>
                        <tr>
                            <td colspan="2" class="muted">No data available</td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($vaccine as $value) { ?>
                        <tr>
                            <td><?php echo h($value['Vaccine']['vaccine_name']); ?></td>
                            <td class="text-right"><?php echo (int) $value[0]['cnt']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-right"><?php echo (int) $vaccineTotal; ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <div class="formback" style="padding: 12px; margin-bottom: 12px; border-width: 1px;">
            <h4>SADRs by Gender</h4>
            <table class="table table-striped table-bordered table-condensed" id="sadr-gender-table">
                <thead>
                    <tr>
                        <th>Sex</th>
                        <th class="text-right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sadr_gender)) { ?>
                        <tr>
                            <td colspan="2" class="muted">No data available</td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($sadr_gender as $value) { ?>
                        <tr>
                            <td><?php echo h($value['Sadr']['gender']); ?></td>
                            <td class="text-right"><?php echo (int) $value[0]['cnt']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-right"><?php echo (int) $sadrTotal; ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="span6">
        <div class="formback" style="padding: 12px; margin-bottom: 12px; border-width: 1px;">
            <h4>SADRs by Age Group</h4>
            <table class="table table-striped table-bordered table-condensed" id="sadr-age-table">
                <thead>
                    <tr>
                        <th>Age Group</th>
                        <th class="text-right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sadrAgeTotal = 0; ?>
                    <?php if (empty($sadr_age)) { ?>
                        <tr>
                            <td colspan="2" class="muted">No data available</td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($sadr_age as $value) { ?>
                        <?php $sadrAgeTotal += (int) $value[0]['cnt']; ?>
                        <tr>
                            <td><?php echo h($value[0]['ager']); ?></td>
                            <td class="text-right"><?php echo (int) $value[0]['cnt']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-right"><?php echo (int) $sadrAgeTotal; ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <div class="formbackd" style="padding: 12px; margin-bottom: 12px; border-width: 1px;">
            <h4>Devices by Gender</h4>
            <table class="table table-striped table-bordered table-condensed" id="device-gender-table">
                <thead>
                    <tr>
                        <th>Sex</th>
                        <th class="text-right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($device_gender)) { ?>
                        <tr>
                            <td colspan="2" class="muted">No data available</td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($device_gender as $value) { ?>
                        <tr>
                            <td><?php echo h($value['Device']['gender']); ?></td>
                            <td class="text-right"><?php echo (int) $value[0]['cnt']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-right"><?php echo (int) $sumCounts($device_gender); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="span6">
        <div class="formbackd" style="padding: 12px; margin-bottom: 12px; border-width: 1px;">
            <h4>Devices by Age Group</h4>
            <table class="table table-striped table-bordered table-condensed" id="device-age-table">
                <thead>
                    <tr>
                        <th>Age Group</th>
                        <th class="text-right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $deviceAgeTotal = 0; ?>
                    <?php if (empty($device_age)) { ?>
                        <tr>
                            <td colspan="2" class="muted">No data available</td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($device_age as $value) { ?>
                        <?php $deviceAgeTotal += (int) $value[0]['cnt']; ?>
                        <tr>
                            <td><?php echo h($value[0]['ager']); ?></td>
                            <td class="text-right"><?php echo (int) $value[0]['cnt']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-right"><?php echo (int) $deviceAgeTotal; ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <div class="formbackm" style="padding: 12px; margin-bottom: 12px; border-width: 1px;">
            <h4>Medical Errors by Gender</h4>
            <table class="table table-striped table-bordered table-condensed" id="medication-gender-table">
                <thead>
                    <tr>
                        <th>Sex</th>
                        <th class="text-right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($medication_gender)) { ?>
                        <tr>
                            <td colspan="2" class="muted">No data available</td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($medication_gender as $value) { ?>
                        <tr>
                            <td><?php echo h($value['Medication']['gender']); ?></td>
                            <td class="text-right"><?php echo (int) $value[0]['cnt']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-right"><?php echo (int) $sumCounts($medication_gender); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="span6">
        <div class="formbackm" style="padding: 12px; margin-bottom: 12px; border-width: 1px;">
            <h4>Medical Errors by Age Group</h4>
            <table class="table table-striped table-bordered table-condensed" id="medication-age-table">
                <thead>
                    <tr>
                        <th>Age Group</th>
                        <th class="text-right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $medicationAgeTotal = 0; ?>
                    <?php if (empty($medication_age)) { ?>
                        <tr>
                            <td colspan="2" class="muted">No data available</td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($medication_age as $value) { ?>
                        <?php $medicationAgeTotal += (int) $value[0]['cnt']; ?>
                        <tr>
                            <td><?php echo h($value[0]['ager']); ?></td>
                            <td class="text-right"><?php echo (int) $value[0]['cnt']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-right"><?php echo (int) $medicationAgeTotal; ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <div class="formbackt" style="padding: 12px; margin-bottom: 12px; border-width: 1px;">
            <h4>Blood Transfusion by Gender</h4>
            <table class="table table-striped table-bordered table-condensed" id="transfusion-gender-table">
                <thead>
                    <tr>
                        <th>Sex</th>
                        <th class="text-right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transfusion_gender)) { ?>
                        <tr>
                            <td colspan="2" class="muted">No data available</td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($transfusion_gender as $value) { ?>
                        <tr>
                            <td><?php echo h($value['Transfusion']['gender']); ?></td>
                            <td class="text-right"><?php echo (int) $value[0]['cnt']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-right"><?php echo (int) $sumCounts($transfusion_gender); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="span6">
        <div class="formbackt" style="padding: 12px; margin-bottom: 12px; border-width: 1px;">
            <h4>Blood Transfusion by Age Group</h4>
            <table class="table table-striped table-bordered table-condensed" id="transfusion-age-table">
                <thead>
                    <tr>
                        <th>Age Group</th>
                        <th class="text-right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $transfusionAgeTotal = 0; ?>
                    <?php if (empty($transfusion_age)) { ?>
                        <tr>
                            <td colspan="2" class="muted">No data available</td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($transfusion_age as $value) { ?>
                        <?php $transfusionAgeTotal += (int) $value[0]['cnt']; ?>
                        <tr>
                            <td><?php echo h($value[0]['ager']); ?></td>
                            <td class="text-right"><?php echo (int) $value[0]['cnt']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-right"><?php echo (int) $transfusionAgeTotal; ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php $this->end(); ?>
