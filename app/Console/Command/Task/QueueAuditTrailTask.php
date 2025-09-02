<?php
App::uses('QueueTask', 'Queue.Console/Command/Task');

class QueueAuditTrailTask extends QueueTask {

    public $uses = array('AuditTrail');
    public $timeout = 60;
    public $retries = 1;

    public function run($data,$id=null) {
        $this->out('Running QueueAuditTrailTask...');

        $required = array('model', 'foreign_key', 'message');
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->err("Missing required field: {$field}");
                return false;
            }
        }

        $audit = array(
            'AuditTrail' => array(
                'foreign_key'    => $data['foreign_key'],
                'model'          => $data['model'],
                'message'        => $data['message'],
                'ip'             => isset($data['ip']) ? $data['ip'] : '',
                'hostname'       => isset($data['hostname']) ? $data['hostname'] : gethostname(),
                'uri'            => isset($data['uri']) ? $data['uri'] : '',
                'refer'          => isset($data['refer']) ? $data['refer'] : '',
                'user_agent'     => isset($data['user_agent']) ? $data['user_agent'] : '',
                'created'        => date('Y-m-d H:i:s')
            )
        );

        $this->AuditTrail->create();
        if ($this->AuditTrail->save($audit)) {
            $this->log('Audit trail saved', 'audit_success');
            return true;
        } else {
            $this->log('Failed to save audit trail', 'audit_error');
            $this->log($audit, 'audit_error');
            return false;
        }
    }
}
