<?php
$this->assign('Dashboard', 'active');
$this->Html->script('dashboard', array('inline' => false));
?>

<section>
  <div class="row-fluid">
    <div class="span8">
      <h4 class="text-success">Reports</h4>

      <?php if ($this->Session->read('Auth.User.health_program') == "Cancer/Oncology program") { ?>
        <div class="row-fluid">

          <div class="span4 formback" style="padding: 4px;">
            <h5>Suspected Adverse Drug Reaction</h5>
            <?php
            echo '<ol>';
            foreach ($sadrs as $sadr) {
              if ($sadr['Sadr']['submitted'] > 1) {
                echo "<li>";
                echo $this->Html->link(
                  $sadr['Sadr']['report_title'] . ' <small class="muted">(' . $sadr['Sadr']['reference_no'] . ')</small>',
                  array('controller' => 'sadrs', 'action' => 'view', $sadr['Sadr']['id']),
                  array('escape' => false, 'class' => 'text-' . ((isset($sadr['Sadr']['serious']) && $sadr['Sadr']['serious'] == 'Yes') ? 'error' : 'success'))
                );
                echo "&nbsp;";
                echo $this->Form->postLink('<span class="label label-inverse tooltipper" data-toggle="tooltip" title="Add follow up report"> <i class="fa fa-facebook" aria-hidden="true"></i> </span>', array('controller' => 'sadrs', 'action' => 'followup', $sadr['Sadr']['id']), array('escape' => false), __('Add a followup report?'));
                echo "</li>";
              } else {
                echo "<li>";
                echo $this->Html->link(
                  $sadr['Sadr']['reference_no'] . ' <small class="muted">(unsubmitted)</small>',
                  array('controller' => 'sadrs', 'action' => 'edit', $sadr['Sadr']['id']),
                  array('escape' => false)
                );
                echo "</li>";
              }
            }
            echo '</ol>';
            echo $this->Html->link('All SADRs >>', array('controller' => 'sadrs', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));
            if ($this->Session->read('Auth.User.user_type') != 'Public Health Program') echo $this->Form->postLink('Report SADR', array('controller' => 'sadrs', 'action' => 'add'), array('class' => 'btn btn-success pull-right btn-mini pull-right'), __('Report New ADR?'));


            ?>
          </div>

          <div class="span4 formbackm" style="padding: 4px;">
            <h5>Medication Errors</h5>
            <?php
            echo '<ol>';
            foreach ($medications as $medication) {
              if ($medication['Medication']['submitted'] > 1) {
                $generic_name_i = (!empty($medication['MedicationProduct'][0]['generic_name_i'])) ? $medication['MedicationProduct'][0]['generic_name_i'] : $medication['Medication']['reference_no'];
                echo "<li>";
                echo $this->Html->link(
                  $generic_name_i . ' <small class="muted">(' . $medication['Medication']['reference_no'] . ')</small>',
                  array('controller' => 'medications', 'action' => 'view', $medication['Medication']['id']),
                  array('escape' => false, 'class' => 'text-success')
                );
                echo "&nbsp;";
                echo $this->Form->postLink('<span class="label label-inverse tooltipper" data-toggle="tooltip" title="Add follow up report"> <i class="fa fa-facebook" aria-hidden="true"></i> </span>', array('controller' => 'medications', 'action' => 'followup', $medication['Medication']['id']), array('escape' => false), __('Add a followup report?'));
                echo "</li>";
              } else {
                echo "<li>";
                echo $this->Html->link(
                  $medication['Medication']['reference_no'] . ' <small class="muted">(unsubmitted)</small>',
                  array('controller' => 'medications', 'action' => 'edit', $medication['Medication']['id']),
                  array('escape' => false)
                );
                echo "</li>";
              }
            }
            echo '</ol>';
            echo $this->Html->link('All Errors >>', array('controller' => 'medications', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));
            if ($this->Session->read('Auth.User.user_type') != 'Public Health Program')   echo $this->Form->postLink('Report Medication Error', array('controller' => 'medications', 'action' => 'add'), array('class' => 'btn btn-success pull-right btn-mini'), __('Report New Medication Error?'));
            ?>
          </div>
        </div>
      <?php } else { ?>
        <div class="row-fluid">

          <div class="span4 formback" style="padding: 4px;">
            <h5>Suspected Adverse Drug Reaction</h5>
            <?php
            echo '<ol>';
            foreach ($sadrs as $sadr) {
              if ($sadr['Sadr']['submitted'] > 1) {
                echo "<li>";
                echo $this->Html->link(
                  $sadr['Sadr']['report_title'] . ' <small class="muted">(' . $sadr['Sadr']['reference_no'] . ')</small>',
                  array('controller' => 'sadrs', 'action' => 'view', $sadr['Sadr']['id']),
                  array('escape' => false, 'class' => 'text-' . ((isset($sadr['Sadr']['serious']) && $sadr['Sadr']['serious'] == 'Yes') ? 'error' : 'success'))
                );
                echo "&nbsp;";
                echo $this->Form->postLink('<span class="label label-inverse tooltipper" data-toggle="tooltip" title="Add follow up report"> <i class="fa fa-facebook" aria-hidden="true"></i> </span>', array('controller' => 'sadrs', 'action' => 'followup', $sadr['Sadr']['id']), array('escape' => false), __('Add a followup report?'));
                echo "</li>";
              } else {
                echo "<li>";
                echo $this->Html->link(
                  $sadr['Sadr']['reference_no'] . ' <small class="muted">(unsubmitted)</small>',
                  array('controller' => 'sadrs', 'action' => 'edit', $sadr['Sadr']['id']),
                  array('escape' => false)
                );
                echo "</li>";
              }
            }
            echo '</ol>';
            echo $this->Html->link('All SADRs >>', array('controller' => 'sadrs', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));
            if ($this->Session->read('Auth.User.user_type') != 'Public Health Program') echo $this->Form->postLink('Report SADR', array('controller' => 'sadrs', 'action' => 'add'), array('class' => 'btn btn-success pull-right btn-mini pull-right'), __('Report New ADR?'));


            ?>
          </div>

          <div class="span4 formbacka" style="padding: 4px;">
            <h5>Adverse Event Following Immunization </h5>
            <?php
            echo '<ol>';
            foreach ($aefis as $aefi) {
              if ($aefi['Aefi']['submitted'] > 1) {
                // debug($aefi['AefiListOfVaccine']);
                echo "<li>";
                $vname = (!empty($aefi['AefiListOfVaccine'][0]['Vaccine']['vaccine_name'])) ? $aefi['AefiListOfVaccine'][0]['Vaccine']['vaccine_name'] : $aefi['Aefi']['reference_no'];
                echo $this->Html->link(
                  $vname . ' <small class="muted">(' . $aefi['Aefi']['reference_no'] . ')</small>',
                  array('controller' => 'aefis', 'action' => 'view', $aefi['Aefi']['id']),
                  array('escape' => false, 'class' => 'text-' . ((isset($aefi['Aefi']['serious']) && $aefi['Aefi']['serious'] == 'Yes') ? 'error' : 'success'))
                );
                echo "&nbsp;";
                if ($this->Session->read('Auth.User.user_type') == 'County Pharmacist' && $aefi['Aefi']['user_id'] != $this->Session->read('Auth.User.id')) {
                  echo $this->Form->postLink('<span class="label label-inverse tooltipper" data-toggle="tooltip" title="Add Investigation up report"> <i class="fa fa-eye" aria-hidden="true"></i></span>', array('controller' => 'aefis', 'action' => 'investigation', $aefi['Aefi']['id']), array('escape' => false), __('Add a Investigation report?'));
                } else {
                  echo $this->Form->postLink('<span class="label label-inverse tooltipper" data-toggle="tooltip" title="Add follow up report"> <i class="fa fa-facebook" aria-hidden="true"></i> </span>', array('controller' => 'aefis', 'action' => 'followup', $aefi['Aefi']['id']), array('escape' => false), __('Add a followup report?'));
                }
                echo "</li>";
              } else {
                echo "<li>";
                echo $this->Html->link(
                  $aefi['Aefi']['created'] . ' <small class="muted">(unsubmitted)</small>',
                  array('controller' => 'aefis', 'action' => 'edit', $aefi['Aefi']['id']),
                  array('escape' => false)
                );
                echo "</li>";
              }
            }
            echo '</ol>';
            echo $this->Html->link('All AEFIs >>', array('controller' => 'aefis', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));
            if ($this->Session->read('Auth.User.user_type') != 'Public Health Program')   echo $this->Form->postLink('Report AEFI', array('controller' => 'aefis', 'action' => 'add'), array('class' => 'btn btn-success pull-right btn-mini'), __('Report  New AEFI?'));
            ?>

          </div>
          <div class="span4 formbackp" style="padding: 4px;">
            <h5>Poor-Quality Health Products and Technologies</h5>
            <?php
            echo '<ol>';
            foreach ($pqmps as $pqmp) {
              if ($pqmp['Pqmp']['submitted'] > 1) {
                echo "<li>";
                echo $this->Html->link(
                  $pqmp['Pqmp']['brand_name'] . ' <small class="muted">(' . $pqmp['Pqmp']['reference_no'] . ')</small>',
                  array('controller' => 'pqmps', 'action' => 'view', $pqmp['Pqmp']['id']),
                  array('escape' => false, 'class' => 'text-' . ((in_array($pqmp['Pqmp']['product_formulation'], ['Injection', 'Powder for Reconstitution of Injection', 'Eye drops', 'Nebuliser solution'])
                    || $pqmp['Pqmp']['therapeutic_ineffectiveness'] || $pqmp['Pqmp']['particulate_matter']) ? 'error' : 'success'))
                );
                echo "</li>";
              } else {
                echo "<li>";
                echo $this->Html->link(
                  $pqmp['Pqmp']['reference_no'] . ' <small class="muted">(unsubmitted)</small>',
                  array('controller' => 'pqmps', 'action' => 'edit', $pqmp['Pqmp']['id']),
                  array('escape' => false)
                );
                echo "</li>";
              }
            }
            echo '</ol>';
            echo $this->Html->link('All PQHPTs >>', array('controller' => 'pqmps', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));
            if ($this->Session->read('Auth.User.user_type') != 'Public Health Program')   echo $this->Form->postLink('Report  PQHPT', array('controller' => 'pqmps', 'action' => 'add'), array('class' => 'btn btn-success pull-right btn-mini'), __('ReportPoor-Quality Health Products and Technologies?'));
            ?>
          </div>
       
        </div>
        <hr>
        <div class="row-fluid">
          <!-- here -->
          <div class="span4 formbackd" style="padding: 4px;">
            <h5>Medical Devices</h5>
            <?php
            if ($this->Session->read('Auth.User.user_type') != 'Public Health Program') {
              echo '<ol>';
              foreach ($devices as $device) {
                if ($device['Device']['submitted'] > 1) {
                  echo "<li>";
                  echo $this->Html->link(
                    $device['Device']['report_title'] . ' <small class="muted">(' . $device['Device']['reference_no'] . ')</small>',
                    array('controller' => 'devices', 'action' => 'view', $device['Device']['id']),
                    array('escape' => false, 'class' => 'text-' . ((isset($device['Device']['serious']) && in_array($device['Device']['serious'], ['Fatal', 'Serious'])) ? 'error' : 'success'))
                  );
                  echo "&nbsp;";
                  echo $this->Form->postLink('<span class="label label-inverse tooltipper" data-toggle="tooltip" title="Add follow up report"> <i class="fa fa-facebook" aria-hidden="true"></i> </span>', array('controller' => 'devices', 'action' => 'followup', $device['Device']['id']), array('escape' => false), __('Add a followup report?'));
                  echo "</li>";
                } else {
                  echo "<li>";
                  echo $this->Html->link(
                    $device['Device']['reference_no'] . ' <small class="muted">(unsubmitted)</small>',
                    array('controller' => 'devices', 'action' => 'edit', $device['Device']['id']),
                    array('escape' => false)
                  );
                  echo "</li>";
                }
              }
              echo '</ol>';
              echo $this->Html->link('All Incidents >>', array('controller' => 'devices', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));
              echo $this->Form->postLink('Report Medical Device', array('controller' => 'devices', 'action' => 'add'), array('class' => 'btn btn-success pull-right btn-mini'), __('Report New Medical Device?'));
            }
            ?>
          </div>
          <div class="span4 formbackm" style="padding: 4px;">
            <h5>Medication Errors</h5>
            <?php
            echo '<ol>';
            foreach ($medications as $medication) {
              if ($medication['Medication']['submitted'] > 1) {
                $generic_name_i = (!empty($medication['MedicationProduct'][0]['generic_name_i'])) ? $medication['MedicationProduct'][0]['generic_name_i'] : $medication['Medication']['reference_no'];
                echo "<li>";
                echo $this->Html->link(
                  $generic_name_i . ' <small class="muted">(' . $medication['Medication']['reference_no'] . ')</small>',
                  array('controller' => 'medications', 'action' => 'view', $medication['Medication']['id']),
                  array('escape' => false, 'class' => 'text-success')
                );
                echo "&nbsp;";
                echo $this->Form->postLink('<span class="label label-inverse tooltipper" data-toggle="tooltip" title="Add follow up report"> <i class="fa fa-facebook" aria-hidden="true"></i> </span>', array('controller' => 'medications', 'action' => 'followup', $medication['Medication']['id']), array('escape' => false), __('Add a followup report?'));
                echo "</li>";
              } else {
                echo "<li>";
                echo $this->Html->link(
                  $medication['Medication']['reference_no'] . ' <small class="muted">(unsubmitted)</small>',
                  array('controller' => 'medications', 'action' => 'edit', $medication['Medication']['id']),
                  array('escape' => false)
                );
                echo "</li>";
              }
            }
            echo '</ol>';
            echo $this->Html->link('All Errors >>', array('controller' => 'medications', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));
            if ($this->Session->read('Auth.User.user_type') != 'Public Health Program')   echo $this->Form->postLink('Report Medication Error', array('controller' => 'medications', 'action' => 'add'), array('class' => 'btn btn-success pull-right btn-mini'), __('Report New Medication Error?'));
            ?>
          </div>
          <!-- Here -->
          <div class="span4 formbackt" style="padding: 4px;">
            <h5>Transfusions Reactions</h5>
            <?php
            if ($this->Session->read('Auth.User.user_type') != 'Public Health Program') {
              echo '<ol>';
              foreach ($transfusions as $transfusion) {
                if ($transfusion['Transfusion']['submitted'] > 1) {
                  echo "<li>";
                  echo $this->Html->link(
                    $transfusion['Transfusion']['diagnosis'] . ' <small class="muted">(' . $transfusion['Transfusion']['reference_no'] . ')</small>',
                    array('controller' => 'transfusions', 'action' => 'view', $transfusion['Transfusion']['id']),
                    array('escape' => false, 'class' => 'text-success')
                  );
                  echo "&nbsp;";
                  echo $this->Form->postLink('<span class="label label-inverse tooltipper" data-toggle="tooltip" title="Add follow up report"> <i class="fa fa-facebook" aria-hidden="true"></i> </span>', array('controller' => 'transfusions', 'action' => 'followup', $transfusion['Transfusion']['id']), array('escape' => false), __('Add a followup report?'));
                  echo "</li>";
                  echo "</li>";
                } else {
                  echo "<li>";
                  echo $this->Html->link(
                    $transfusion['Transfusion']['reference_no'] . ' <small class="muted">(unsubmitted)</small>',
                    array('controller' => 'transfusions', 'action' => 'edit', $transfusion['Transfusion']['id']),
                    array('escape' => false)
                  );
                  echo "</li>";
                }
              }
              echo '</ol>';
              echo $this->Html->link('All BT >>', array('controller' => 'transfusions', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));
              echo $this->Form->postLink('Report Transfusion', array('controller' => 'transfusions', 'action' => 'add'), array('class' => 'btn btn-success pull-right btn-mini'), __('Report New Transfusion Reaction?'));
            }
            ?>

          </div>
        </div>
        <hr>
        <div class="row-fluid">
        <?php
          if ($this->Session->read('Auth.User.user_type') == "Market Authority") { ?>
            <div class="span4 formbacka" style="padding: 4px;">
              <h5>E2Bs </h5>
              <?php
              echo '<ol>';
              foreach ($ce2bs as $ce) {
                if ($ce['Ce2b']['submitted'] > 1) {
                  echo "<li>";
                  echo $this->Html->link(
                    ' <small class="muted">(' . $ce['Ce2b']['reference_no'] . ')</small>',
                    array('controller' => 'ce2bs', 'action' => 'view', $ce['Ce2b']['id']),
                    array('escape' => false, 'class' => 'text-success')
                  );
                  echo "&nbsp;";
                 
                  echo "</li>";
                } else {
                  echo "<li>";
                  echo $this->Html->link(
                    $ce['Ce2b']['created'] . ' <small class="muted">(unsubmitted)</small>',
                    array('controller' => 'ce2bs', 'action' => 'edit', $ce['Ce2b']['id']),
                    array('escape' => false)
                  );
                  echo "</li>";
                }
              }
              echo '</ol>';
              echo $this->Html->link('All E2Bs >>', array('controller' => 'ce2bs', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));
              echo $this->Form->postLink('Report E2B', array('controller' => 'ce2bs', 'action' => 'add'), array('class' => 'btn btn-success pull-right btn-mini'), __('Report E2b Report?'));
              ?>

            </div>
            <div class="span4 formbackd" style="padding: 4px;">
              <h5>Aggregate Reports </h5>
              <?php
              echo '<ol>';
              foreach ($aggregates as $ce) {
                if ($ce['Aggregate']['submitted'] > 1) {
                  echo "<li>";
                  echo $this->Html->link(
                    ' <small class="muted">(' . $ce['Aggregate']['reference_no'] . ')</small>',
                    array('controller' => 'aggregates', 'action' => 'view', $ce['Aggregate']['id']),
                    array('escape' => false, 'class' => 'text-success')
                  );
                  echo "&nbsp;";
                  
                  echo "</li>";
                } else {
                  echo "<li>";
                  echo $this->Html->link(
                    $ce['Aggregate']['created'] . ' <small class="muted">(unsubmitted)</small>',
                    array('controller' => 'aggregates', 'action' => 'edit', $ce['Aggregate']['id']),
                    array('escape' => false)
                  );
                  echo "</li>";
                }
              }
              echo '</ol>';
              echo $this->Html->link('All Aggregates >>', array('controller' => 'aggregates', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));
              echo $this->Form->postLink('Report Aggregate', array('controller' => 'aggregates', 'action' => 'add'), array('class' => 'btn btn-success pull-right btn-mini'), __('Report Aggregate Report?'));
              ?>

            </div>
          <?php } ?>

          <?php if ($this->Session->read('Auth.User.user_type') == 'County Pharmacist') { ?>
            <div class="span4 formbacka" style="padding: 4px;">
              <h5>County Submitted Serious Reports </h5>
              <h6>AEFI </h6>
              <?php
              echo ' <ol>';
              foreach ($serious_aefis as $saefi) {
                if ($saefi['Aefi']['submitted'] > 1) {
                  echo "<li>";
                  $vname = (!empty($saefi['AefiListOfVaccine'][0]['Vaccine']['vaccine_name'])) ? $saefi['AefiListOfVaccine'][0]['Vaccine']['vaccine_name'] : $saefi['Aefi']['reference_no'];
                  echo $this->Html->link(
                    $vname . ' <small class="muted">(' . $saefi['Aefi']['reference_no'] . ')</small>',
                    array('controller' => 'aefis', 'action' => 'view', $saefi['Aefi']['id']),
                    array('escape' => false, 'class' => 'text-' . ((isset($saefi['Aefi']['serious']) && $saefi['Aefi']['serious'] == 'Yes') ? 'error' : 'success'))
                  );
                  echo "&nbsp;";
                  if ($this->Session->read('Auth.User.user_type') == 'County Pharmacist' && $saefi['Aefi']['user_id'] != $this->Session->read('Auth.User.id')) {
                    echo $this->Form->postLink('<span class="label label-inverse tooltipper" data-toggle="tooltip" title="Add Investigation up report"> <i class="fa fa-eye" aria-hidden="true"></i></span>', array('controller' => 'aefis', 'action' => 'investigation', $saefi['Aefi']['id']), array('escape' => false), __('Add a Investigation report?'));
                  }
                  echo "</li>";
                } else {
                  echo "<li>";
                  echo $this->Html->link(
                    $saefi['Aefi']['created'] . ' <small class="muted">(unsubmitted)</small>',
                    array('controller' => 'aefis', 'action' => 'edit', $saefi['Aefi']['id']),
                    array('escape' => false)
                  );
                  echo "</li>";
                }
              }
              echo '</ol>';
              echo $this->Html->link('All AEFIs >>', array('controller' => 'aefis', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));
              ?>
              <h6>SADR </h6>
              <?php
              echo ' <ol>';
              foreach ($serious_sadr as $adr) {
                if ($adr['Sadr']['submitted'] > 1) {
                  echo "<li>";
                  $vname = (!empty($adr['Sadr']['report_title'])) ? $adr['Sadr']['report_title'] : $adr['Sadr']['reference_no'];
                  echo $this->Html->link(
                    $vname . ' <small class="muted">(' . $adr['Sadr']['reference_no'] . ')</small>',
                    array('controller' => 'sadrs', 'action' => 'view', $adr['Sadr']['id']),
                    array('escape' => false, 'class' => 'text-' . ((isset($adr['Sadr']['serious']) && $adr['Sadr']['serious'] == 'Yes') ? 'error' : 'success'))
                  );
                  echo "</li>";
                }
              }
              echo '</ol>';
              echo $this->Html->link('All SADRs >>', array('controller' => 'sadrs', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));

              ?>
              <h6>PQHPT </h6>
              <?php
              echo ' <ol>';
              foreach ($serious_pqmp as $adr) {
                if ($adr['Pqmp']['submitted'] > 1) {
                  echo "<li>";
                  $vname = $adr['Pqmp']['reference_no'];
                  echo $this->Html->link(
                    $vname . ' <small class="muted">(' . $adr['Pqmp']['reference_no'] . ')</small>',
                    array('controller' => 'pqmps', 'action' => 'view', $adr['Pqmp']['id']),
                    array('escape' => false, 'class' => 'text-error')
                  );
                  echo "</li>";
                }
              }
              echo '</ol>';
              echo $this->Html->link('All PQHPT >>', array('controller' => 'pqmps', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));

              ?>
              <h6>Medical Devices </h6>
              <?php
              echo ' <ol>';
              foreach ($serious_dev as $adr) {
                if ($adr['Device']['submitted'] > 1) {
                  echo "<li>";
                  $vname = (!empty($adr['Device']['serious'])) ? $adr['Device']['serious'] : $adr['Device']['reference_no'];
                  echo $this->Html->link(
                    $vname . ' <small class="muted">(' . $adr['Device']['reference_no'] . ')</small>',
                    array('controller' => 'devices', 'action' => 'view', $adr['Device']['id']),
                    array('escape' => false, 'class' => 'text-error')
                  );
                  echo "</li>";
                }
              }
              echo '</ol>';
              echo $this->Html->link('All Incidents >>', array('controller' => 'devices', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));

              ?>
              <h6>Medication Errors </h6>
              <?php
              echo ' <ol>';
              foreach ($serious_med as $adr) {
                if ($adr['Medication']['submitted'] > 1) {
                  echo "<li>";
                  $vname = (!empty($adr['Medication']['report_title'])) ? $adr['Medication']['report_title'] : $adr['Medication']['reference_no'];
                  echo $this->Html->link(
                    $vname . ' <small class="muted">(' . $adr['Medication']['reference_no'] . ')</small>',
                    array('controller' => 'medications', 'action' => 'view', $adr['Medication']['id']),
                    array('escape' => false, 'class' => 'text-' . ((isset($adr['Medication']['outcome']) && $adr['Medication']['outcome'] == 'Death') ? 'error' : 'success'))
                  );
                  echo "</li>";
                }
              }
              echo '</ol>';
              echo $this->Html->link('All Errors >>', array('controller' => 'medications', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));

              ?>
              <h6>Transfusions Reactions </h6>
              <?php
              echo ' <ol>';
              foreach ($serious_trans as $adr) {
                if ($adr['Transfusion']['submitted'] > 1) {
                  echo "<li>";
                  $vname = (!empty($adr['Transfusion']['faint'])) ? $adr['Transfusion']['faint'] : $adr['Transfusion']['reference_no'];
                  echo $this->Html->link(
                    $vname . ' <small class="muted">(' . $adr['Transfusion']['reference_no'] . ')</small>',
                    array('controller' => 'transfusions', 'action' => 'view', $adr['Transfusion']['id']),
                    array('escape' => false, 'class' => 'text-' . ((isset($adr['Transfusion']['faint']) && $adr['Transfusion']['faint'] == 'Severe') ? 'error' : 'success'))
                  );
                  echo "</li>";
                }
              }
              echo '</ol>';
              echo $this->Html->link('All BTs >>', array('controller' => 'transfusions', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));

              ?>
            </div>
          <?php } ?>
          <?php if ($this->Session->read('Auth.User.user_type') == 'County Pharmacist') { ?>
            <div class="span4 formbacka" style="padding: 4px;">
              <h5>Investigation Reports </h5>
              <?php
              echo '<ol>';
              foreach ($saefis as $saefi) {
                if ($saefi['Saefi']['submitted'] > 1) {
                  echo "<li>";
                  $vname = (!empty($saefi['AefiListOfVaccine'][0]['Vaccine']['vaccine_name'])) ? $saefi['AefiListOfVaccine'][0]['Vaccine']['vaccine_name'] : $saefi['Saefi']['reference_no'];
                  echo $this->Html->link(
                    $vname . ' <small class="muted">(' . $saefi['Saefi']['reference_no'] . ')</small>',
                    array('controller' => 'saefis', 'action' => 'view', $saefi['Saefi']['id']),
                    array('escape' => false, 'class' => 'text-' . ((isset($saefi['Saefi']['serious']) && $saefi['Saefi']['serious'] == 'Yes') ? 'error' : 'success'))
                  );
                  echo "&nbsp;";
                  // echo $this->Form->postLink('<span class="label label-inverse tooltipper" data-toggle="tooltip" title="Add follow up report"> <i class="fa fa-facebook" aria-hidden="true"></i> </span>', array('controller' => 'saefis', 'action' => 'followup', $saefi['Saefi']['id']), array('escape' => false), __('Add a followup report?'));
                  echo "</li>";
                } else {
                  echo "<li>";
                  echo $this->Html->link(
                    $saefi['Saefi']['created'] . ' <small class="muted">(unsubmitted)</small>',
                    array('controller' => 'saefis', 'action' => 'edit', $saefi['Saefi']['id']),
                    array('escape' => false)
                  );
                  echo "</li>";
                }
              }
              echo '</ol>';
              echo $this->Html->link('All SAEFIs >>', array('controller' => 'saefis', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-link'));
              // if ($this->Session->read('Auth.User.user_type') != 'Public Health Program')   echo $this->Form->postLink('Report SAEFI', array('controller' => 'saefis', 'action' => 'add'), array('class' => 'btn btn-success pull-right btn-mini'), __('Report  New SAEFI?'));
              ?>

            </div>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
    <div class="span4"><!-- Notifications -->
      <h4 class="text-warning">Notifications!</h4>
      <div class="thumbnail">
        <img alt="" src="/img/preferences_desktop_notification.png">
        <div class="caption">
          <h4><?php echo $this->Html->link(
                'Notifications',
                array('controller' => 'notifications', 'action' => 'index'),
                array('escape' => false, 'class' => 'text-success')
              ); ?> <small>Actions that require your attention.</small></h4>
          <?php
          echo $this->element('alerts/notifications', ['notifications' => $notifications]);
          ?>
        </div>
      </div>
    </div>
  </div>
</section>