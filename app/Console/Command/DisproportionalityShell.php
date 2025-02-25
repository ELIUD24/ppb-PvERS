<?php

App::uses('HttpSocket', 'Network/Http');
App::uses('String', 'Utility');
App::uses('ReportsController', 'Controller');

class DisproportionalityShell extends AppShell
{
    public $uses = array('Disproportionality');

    public function main()
    {
        $this->out('Running Disproportionality Algorithym...');
        $this->out('preparing public reports data');
         // Instantiate the controller
         $dataController = new ReportsController();        
         // Call the controller method directly
         $dataController->d_aefi_analytics(); 
         $dataController->d_sadr_analytics(); 
         $dataController->manager_public_analytics(); 
         $dataController->d_sadr_analytics(); 
         $this->out('Controller method called from shell.');

         $drugs = $this->Disproportionality->find('all', array(
            'contain' => false,
            'order' => array('Disproportionality.id' => 'desc') 
        ));


    }
}