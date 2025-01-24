<?php
$this->assign('AGGREGATE', 'active');
?>
<section id="sadrsview">
  <ul id="reviewer_tab" class="nav nav-tabs">
    <?php if (!empty($aggregate['Aggregate']['aggregate_id'])) { ?>
      <li class=""><a href="#original" data-toggle="tab"><?php echo "Original Report" ?></a></li>
    <?php } ?>
    <li class="active"><a href="#formview" data-toggle="tab"><?php echo $aggregate['Aggregate']['reference_no']; ?></a></li>
    <li><a href="#external_report_comments" data-toggle="tab">Feedback (<?php echo count($aggregate['ExternalComment']); ?>)</a></li>
    <!-- <li><a href="#reviewer_comments" data-toggle="tab">Reviewer’s comments (<?php echo count($aggregate['ReviewerComment']); ?>)</a></li> -->
    <!-- <li><a href="#recommendation_comments" data-toggle="tab">Recommendations (<?php echo count($aggregate['Recommendation']); ?>)</a></li> -->

  </ul>
  <div class="tab-content">


    <!-- Start of original  -->
    <div class="tab-pane" id="original">
      <div class="row-fluid">
        <div class="span10">
          <?php echo $this->element('aggregates/original'); ?>
        </div>
        <div class="span2">
          <?php
          echo $this->Html->link('Download PDF', array('controller' => 'aggregates', 'action' => 'view', 'ext' => 'pdf', $aggregate['Aggregate']['id']),                            array('class' => 'btn btn-primary btn-block mapop', 'title' => 'Download PDF',                            'data-content' => 'Download the pdf version of the report',));


          if ($aggregate['Aggregate']['summary_available'] == "No") {
            echo $this->Html->link('Edit', array('controller' => 'aggregates', 'action' => 'edit', $aggregate['Aggregate']['id']),                            array('class' => 'btn btn-success btn-block mapop', 'title' => 'Download PDF',                            'data-content' => 'Edit Report',));
          }
          ?>
          <hr>
        </div>
      </div>
    </div>

    <!-- End of original -->

    <div class="tab-pane active" id="formview">
      <div class="row-fluid">
        <div class="span10">
          <?php echo $this->element('aggregates/aggregate_view'); ?>
        </div>
        <div class="span2">
          <?php
          echo $this->Html->link('Download PDF', array('controller' => 'aggregates', 'action' => 'view', 'ext' => 'pdf', $aggregate['Aggregate']['id']),                            array('class' => 'btn btn-primary btn-block mapop', 'title' => 'Download PDF',                            'data-content' => 'Download the pdf version of the report',));


          if ($aggregate['Aggregate']['summary_available'] == "No") {
            echo $this->Html->link('Edit', array('controller' => 'aggregates', 'action' => 'edit', $aggregate['Aggregate']['id']),                            array('class' => 'btn btn-success btn-block mapop', 'title' => 'Download PDF',                            'data-content' => 'Edit Report',));
          }
          echo "&nbsp;";
          echo "Summary Report File(s)";
          echo "&nbsp;";
          $i = 1;
          foreach ($aggregate['Attachment'] as $attachment) : ?>
            <tr>

              <td style="width : 30%;">
                <a href="/attachments/download/<?php echo $attachment['id']; ?>"><?php echo __($attachment['basename']); ?></a>
              </td>

            </tr>
          <?php endforeach; ?>

          <hr>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="external_report_comments">
      <!-- 12600 Letters debat -->
      <div class="amend-form">
        <h5 class="text-info"><u>FEEDBACK</u></h5>
        <div class="row-fluid">
          <div class="span8">
            <?php
            echo $this->element('comments/list', ['comments' => $aggregate['ExternalComment']]);
            ?>
          </div>
          <div class="span4 lefty">
            <?php
            echo $this->element('comments/add', [
              'model' => [
                'model_id' => $aggregate['Aggregate']['id'], 'foreign_key' => $aggregate['Aggregate']['id'],
                'model' => 'Aggregate', 'review' => false, 'category' => 'external', 'url' => 'report_feedback'
              ]
            ])
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="reviewer_comments">
      <!-- 12600 Letters debat -->
      <div class="amend-form">
        <h5 class="text-info"><u>Reviewer’s comments</u></h5>
        <div class="row-fluid">
          <div class="span8">
            <?php
            echo $this->element('comments/list', ['comments' => $aggregate['ReviewerComment']]);
            ?>
          </div>
          <div class="span4 lefty">
            <?php
            echo $this->element('comments/add', [
              'model' => [
                'model_id' => $aggregate['Aggregate']['id'], 'foreign_key' => $aggregate['Aggregate']['id'],
                'model' => 'Aggregate', 'review' => false, 'category' => 'review', 'url' => 'report_feedback'
              ]
            ])
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="recommendation_comments">
      <!-- 12600 Letters debat -->
      <div class="amend-form">
        <h5 class="text-info"><u>Recommendations</u></h5>
        <div class="row-fluid">
          <div class="span8">
            <?php
            echo $this->element('comments/list', ['comments' => $aggregate['Recommendation']]);
            ?>
          </div>
          <div class="span4 lefty">
            <?php
            echo $this->element('comments/add', [
              'model' => [
                'model_id' => $aggregate['Aggregate']['id'], 'foreign_key' => $aggregate['Aggregate']['id'],
                'model' => 'Aggregate', 'review' => false, 'category' => 'recommendation', 'url' => 'report_feedback'
              ]
            ])
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>


</section>