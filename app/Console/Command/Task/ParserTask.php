<?php
App::uses('AppShell', 'Console/Command');

class ParserTask extends AppShell {

    public function run($data, $id = null) {
        $ce2bId = isset($data['ce2b_id']) ? $data['ce2b_id'] : null;

        if (!$ce2bId) {
            $this->out("Missing ce2b_id");
            return false;
        }

        $this->out("Queue: Running ParserShell for ce2b_id: $ce2bId");

        // Call ParserShell from within queue
        $cmd = 'php ' . APP . 'Console' . DS . 'cake.php parser ' . $ce2bId;

        exec($cmd . ' > /dev/null 2>&1', $output, $exitCode);

        if ($exitCode !== 0) {
            $this->err("ParserShell failed with exit code $exitCode");
            return false;
        }

        return true;
    }
}
// 		$this->out('Now adding the Job into the Queue...');

?>