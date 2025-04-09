<?php
$this->Html->css('comments', null, array('inline' => false));
$this->Html->script('comments/comments', array('inline' => false));
$this->Html->script('ckeditor/ckeditor', array('inline' => false));
$this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
?>
<div class="bs-example">
  <?php
  echo $this->Form->create('Comment', array(
    'url' => array('controller' => 'comments', 'action' => $model['url'], (isset($model['param'])) ? $model['param'] : ''),
    'type' => 'file',
    'class' => false,
    'inputDefaults' => array(
      'div' => array('class' => 'control-group'),
      'label' => array('class' => 'control-label'),
      'between' => '<div class="controls">',
      'after' => '</div>',
      'class' => '',
      'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
      'error' => array('attributes' => array('class' => 'controls help-block')),
    ),
  ));
  ?>
  <?php
  echo $this->Form->input('model_id', ['type' => 'hidden', 'value' => $model['model_id'], 'escape' => false]);
  echo $this->Form->input('foreign_key', ['type' => 'hidden', 'value' => $model['foreign_key']]);
  echo $this->Form->input('model', ['type' => 'hidden', 'value' => $model['model']]);
  echo $this->Form->input('category', ['type' => 'hidden', 'value' => $model['category']]);
  echo $this->Form->input('user_id', ['type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')]);
  if (strpos($model['url'], 'committee') !== false) {
    echo $this->Form->input('sender', ['label' => array('class' => 'required'), 'escape' => false]);
  } else {
    echo $this->Form->input('sender', ['type' => 'hidden', 'value' => $this->Session->read('Auth.User.name')]);
  }
  if ($model['review'] === true) {
 
    echo $this->Form->input('review', array(
      'label' => array(
        'class' => 'control-label required', 
        'text' => 'Content <span class="sterix">*</span>'
      ),
      'between' => '<div class="controls">',
      'placeholder' => '',
      'class' => 'input-large span12' ,
    ));
  } else {
    echo $this->Form->input('subject', ['label' => array('class' => 'required span12')]);
    echo $this->Form->input('content', array(
      'label' => array(
        'class' => 'control-label required', 
        'text' => 'Content <span class="sterix">*</span>'
      ),
      'between' => '<div class="controls">',
      'placeholder' => '',
      'class' => 'input-large span12' ,
    ));
  }
  ?>
  <div class="row-fluid">
    <div class="span11">
      <div class="uploadsTable">
        <h6 class="muted"><b>Attach File(s) </b>
          <button type="button" class="btn btn-primary btn-small addUpload">&nbsp;<i class="icon-plus"></i>&nbsp;</button>
        </h6>
        <hr>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="span12">
      <button type="submit" class="btn btn-success active"><i class="fa fa-save" aria-hidden="true"></i> Submit</button>
    </div>
  </div>
  <?php echo $this->Form->end() ?>

  <script type="text/javascript">
    CKEDITOR.replace('data[Comment][content]');
    CKEDITOR.replace('data[Comment][review]');
  </script>
</div>