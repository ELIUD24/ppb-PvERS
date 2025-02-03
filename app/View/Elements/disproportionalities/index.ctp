<?php
$this->assign('Reports', 'active');
?>


<?php
echo $this->Session->flash();
// debug($this->request->query);
?>
<div class="row-fluid">
  <div class="span12">

    <h3>Disproportionality:<small> <i class="icon-glass"></i> Filter, <i class="icon-search"></i> Search, and <i class="icon-eye-open"></i> view reports</small></h3>
    <hr class="soften" style="margin: 7px 0px;">
  </div>
</div>

<div class="row-fluid">
  <div class="span12">
    <?php
    echo $this->Form->create('Disproportionality', array(
      'url' => array_merge(array('action' => 'index'), $this->params['pass']),
      'class' => 'ctr-groups', 'style' => array('padding:9px;', 'background-color: #F5F5F5'),
    ));
    ?>
    <table class="table table-condensed" style="margin-bottom: 2px;">
      <tbody>
        <tr>
          <td>
            <?php
            echo $this->Form->input(
              'drug_name',
              array(
                'div' => false,
                'placeholder' => '',
                'class' => 'span12', 'label' => array('class' => 'required', 'text' => 'Drug/Vaccine Name.')
              )
            );
            ?>
          </td>
          <td>
            <?php
            echo $this->Form->input(
              'reaction_name',
              array(
                'div' => false,
                'placeholder' => 'rash',
                'class' => 'unauthorized_index span10', 'label' => array('class' => 'required', 'text' => 'Reaction Name')
              )
            );
            echo $this->Form->input(
              'inn',
              array(
                'div' => false,
                'placeholder' => 'rash',
                'class' => 'unauthorized_index span10',
                'type' => 'hidden',
                'label' => array('class' => 'required', 'text' => 'Reaction Name')
              )
            );
            ?>
          </td>
          <td colspan="2">
            <?php
            echo $this->Form->input(
              'start_date',
              array(
                'div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index', 'after' => '-to-',
                'label' => array('class' => 'required', 'text' => 'Report Dates'), 'placeHolder' => 'Start Date'
              )
            );
            echo $this->Form->input(
              'end_date',
              array(
                'div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index',
                'after' => '<a style="font-weight:normal" onclick="$(\'.unauthorized_index\').val(\'\');" >
                              <em class="accordion-toggle">clear!</em></a>',
                'label' => false, 'placeHolder' => 'End Date'
              )
            );
            ?>
          </td>

        </tr>

        <tr>
          <td><label for="DisproportionalityPages" class="required">Pages</label></td>
          <td>
            <?php
            echo $this->Form->input('pages', array(
              'type' => 'select', 'div' => false, 'class' => 'input-small', 'selected' => $this->request->params['paging']['Disproportionality']['limit'],
              'empty' => true,
              'options' => $page_options,
              'label' => false,
            ));
            ?>
          </td>
          <td>


          </td>
          <td></td>
          <td>
            <?php
            echo $this->Form->button('<i class="icon-search icon-white"></i> Search', array(
              'class' => 'btn btn-primary', 'div' => 'control-group', 'div' => false,
              'formnovalidate' => 'formnovalidate',
              'style' => array('margin-bottom: 5px')
            ));
            ?>
          </td>
          <td>
            <?php
            echo $this->Html->link('<i class="icon-remove"></i> Clear', array('action' => 'index'), array('class' => 'btn', 'escape' => false, 'style' => array('margin-bottom: 5px')));
            ?>
          </td>
          <td>

          </td>
        </tr>
      </tbody>
    </table>
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
    <?php echo $this->Form->end(); ?>

    <div class="pagination">
      <ul>
        <?php
        echo $this->Paginator->prev('&laquo;', array('tag' => 'li', 'disabledTag' => 'a', 'escape' => false), null, array('class' => 'disabled', 'tag' => 'li', 'currentTag' => 'a', 'escape' => false));
        echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentTag' => 'a', 'currentClass' => 'active'));
        echo $this->Paginator->next('&raquo;', array('tag' => 'li', 'disabledTag' => 'a', 'escape' => false), null, array('class' => 'disabled', 'tag' => 'li', 'escape' => false));
        ?>
      </ul>
    </div>

    <table class="table  table-bordered table-striped">
      <thead>
        <tr>
          <th></th>
          <th>Total Reports: </th>
          <th><?php echo $total_reports; ?></th>
        </tr>
        <tr>
          <th><?php echo $this->Paginator->sort('id'); ?></th>
          <th><?php echo $this->Paginator->sort('drug_/_vaccine_name'); ?></th>
          <th><?php echo $this->Paginator->sort('drug_reaction'); ?></th>
          <th><?php echo $this->Paginator->sort('observed'); ?></th>
          <th><?php echo $this->Paginator->sort('expected'); ?></th>
          <th><?php echo $this->Paginator->sort('IC025>0'); ?></th>
          <th><?php echo $this->Paginator->sort('IC'); ?></th>

        </tr>
      </thead>
      <tbody>
        <?php
        $cc = 0;
        foreach ($data as $dt) :
          $cc++;
          $confidenceInterval = $dt['Disproportionality']['95%_Confidence_Interval'];
          $color = $confidenceInterval > 0 ? 'red' : 'green';
          $log = $dt['Disproportionality']['IC_raw_calculated_log_data'];
        ?>
          <tr class="">
            <td><?php echo $cc; ?>&nbsp;</td>
            <td><?php echo h($dt['Disproportionality']['drug_name']); ?>&nbsp;</td>
            <td><?php echo h($dt['Disproportionality']['reaction_name']); ?>&nbsp;</td>
            <td><?php echo h($dt['Disproportionality']['B_reports_with_reaction']); ?>&nbsp;</td>
            <td><?php echo h((int)$dt['Disproportionality']['E_(AB)_expected_count']); ?> &nbsp;</td>
            <?php echo "<td style='color: $color;'>" . round($confidenceInterval, 2) . "&nbsp;</td>"; ?>
            <td><?php echo $log; ?>&nbsp;</td>

          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>


<script type="text/javascript">
  $(function() {
    var adates = $('#DisproportionalityStartDate, #DisproportionalityEndDate').datepicker({
      minDate: "-100Y",
      maxDate: "-0D",
      dateFormat: 'dd-mm-yy',
      format: 'dd-mm-yy',
      endDate: '-0d',
      showButtonPanel: true,
      changeMonth: true,
      changeYear: true,
      showAnim: 'show',
      onSelect: function(selectedDate) {
        var option = this.id == "DisproportionalityStartDate" ? "minDate" : "maxDate",
          instance = $(this).data("datepicker"),
          date = $.datepicker.parseDate(
            instance.settings.dateFormat ||
            $.datepicker._defaults.dateFormat,
            selectedDate, instance.settings);
        adates.not(this).datepicker("option", option, date);
      }
    });

  });
</script>