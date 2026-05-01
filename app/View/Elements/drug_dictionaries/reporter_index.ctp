<?php
$this->assign('Registry', 'active');
?>

<div class="row-fluid">
  <div class="span12">

    <?php
    echo $this->Session->flash();
    ?>
    <div class="row-fluid">
      <div class="span12">
        <?php
        if ($this->Session->read('Auth.User.user_type') != 'Public Health Program')  echo $this->Html->link(
          '<i class="fa fa-refresh" aria-hidden="true"></i>  Sync Data',
          array('controller' => 'drug_dictionaries', 'action' => 'sync'),
          array('escape' => false, 'class' => 'btn btn-success')
        );
        ?>
      </div>
    </div>

    <div class="marketing">
      <div class="row-fluid">
        <div class="span12">
          <h3>Drug Dictionary:<small> <i class="icon-glass"></i> Filter, <i class="icon-search"></i> Search, and <i class="icon-eye-open"></i> view reports</small></h3>
          <hr class="soften" style="margin: 7px 0px;">
        </div>
      </div>
    </div>

    <?php
    $page_options = [10 => '10', 25 => '25', 50 => '50', 100 => '100'];
    echo $this->Form->create('DrugDictionary', [
      'url' => array_merge(['action' => 'index'], $this->params['pass']),
      'class' => 'ctr-groups', 'style' => ['padding:9px;', 'background-color: #F5F5F5'],
    ]);
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
                'class' => 'span12', 'label' => array('class' => 'required', 'text' => 'Drug Name')
              )
            );
            ?>
          </td>
          <td>
            <?php
            echo $this->Form->input(
              'trade_name',
              array(
                'div' => false,
                'class' => 'span12', 'label' => array('class' => 'required', 'text' => 'Trade Name')
              )
            );
            ?>
          </td>
          <td>
            <?php
            echo $this->Form->input(
              'id',
              array(
                'div' => false,
                'class' => 'span12', 'label' => array('class' => 'required', 'text' => 'ID')
              )
            );
            ?>
          </td>
          <td>
          </td>
          <td>
          </td>
        </tr>

        <tr>
          <td><label for="DrugDictionaryPages" class="required">Pages</label></td>
          <td>
            <?php
            echo $this->Form->input('pages', [
              'type' => 'select', 'div' => false, 'class' => 'input-small', 'selected' => $paging['DrugDictionary']['limit'],
              'empty' => true,
              'options' => $page_options,
              'label' => false,
            ]);
            ?>
          </td>
          <td>
          </td>
          <td>
            <?php
            echo $this->Form->button('<i class="icon-search icon-white"></i> Search', array(
              'class' => 'btn btn-primary', 'div' => false,
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
        </tr>
      </tbody>
    </table>
    <p>
      <?php
      echo $this->Paginator->counter(array(
        'format' => __('Page <span class="badge">{:page}</span> of <span class="badge">{:pages}</span>,
                showing <span class="badge">{:current}</span> Drug Dictionaries out of
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
          <th><?php echo $this->Paginator->sort('id'); ?></th>
          <th><?php echo $this->Paginator->sort('drug_name'); ?></th>
          <th><?php echo $this->Paginator->sort('trade_name'); ?></th>
          <th><?php echo $this->Paginator->sort('created'); ?></th>
          <th><?php echo $this->Paginator->sort('modified'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($drugDictionaries as $drugDictionary) : ?>
        <tr>
          <td><?php echo h($drugDictionary['DrugDictionary']['id']); ?>&nbsp;</td>
          <td><?php echo h($drugDictionary['DrugDictionary']['drug_name']); ?>&nbsp;</td>
          <td><?php echo h($drugDictionary['DrugDictionary']['trade_name']); ?>&nbsp;</td>
          <td><?php echo h($drugDictionary['DrugDictionary']['created']); ?>&nbsp;</td>
          <td><?php echo h($drugDictionary['DrugDictionary']['modified']); ?>&nbsp;</td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>