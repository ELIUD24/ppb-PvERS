<?php
App::uses('AppModel', 'Model');
/**
 * Disproportionality Model
 * @property Disproportionality $Disproportionality
 */
class Disproportionality extends AppModel {
	public $actsAs = array('Search.Searchable', 'Containable');
	public $filterArgs = array(
        'drug_name' => array('type' => 'like', 'encode' => true),
		'reaction_name' => array('type' => 'like', 'encode' => true),
		'start_date' => array('type' => 'query', 'method' => 'dummy'),
        'end_date' => array('type' => 'query', 'method' => 'dummy'),
	);
	public function dummy($data = array())
    {
        return array('1' => '1');
    }

}
