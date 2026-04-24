<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package     app.Controller
 * @link        https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    public $components = array(
        'Session',
        'Acl' => array('authorize' => array('Actions' => array('actionPath' => 'controllers'))),
        'Auth' => array(
            'authenticate' => array('Form' => array('scope' => array('User.is_active' => 1))),
            'authorize' => array('Actions'),
            'loginAction' => array('controller' => 'users', 'action' => 'login'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
            'loginRedirect' => array('controller' => 'users', 'action' => 'dashboard')
        ),
        'RequestHandler' => array('viewClassMap' => array('csv' => 'CsvView.Csv')),
        'Flash',
        'DebugKit.Toolbar'
    );

    public $helpers = array('Html', 'Form', 'Session');

    public function isAuthorized($user = null)
    {
        if (empty($user)) {
            return false;
        }
        $groupId = $user['group_id'];
        return in_array($groupId, array(1, 2, 3, 4, 5));
    }

    protected function _getRedirectPrefix($groupId)
    {
        $prefixes = array(
            1 => 'admin',
            2 => 'manager',
            3 => 'reporter',
            4 => 'partner',
            5 => 'reviewer'
        );
        return isset($prefixes[$groupId]) ? $prefixes[$groupId] : 'reporter';
    }

    public function beforeFilter()
    {
        $this->Auth->authError = __('<div class="alert alert-error">
            <button data-dismiss="alert" class="close">&times;</button>
            <h4><strong>Sorry!</strong> You don\'t have sufficient permissions to access the location.</h4>
        </div>', true);
        
        $this->Auth->loginError = __('<div class="alert alert-error">
            <button data-dismiss="alert" class="close">&times;</button>
            <h4>Invalid e-mail / password combination. Please try again.</h4>
        </div>', true);

        if (isset($this->request->prefix) && $this->request->prefix == 'api') {
            $this->Auth = $this->Components->load('Auth');
            $this->Auth->authenticate = array('Jwtoken');
            $this->Auth->authorize = array('Controller');
            AuthComponent::$sessionKey = false;
            $this->Auth->initialize($this);
            $this->Auth->authError = 'Not allowed!!';
            $this->set('redir', 'api');
            $this->set('root', '/');
        } else {
            $redir = $this->_getRedirectPrefix($this->Auth->User('group_id'));
            $this->Auth->initialize($this);
            $this->Auth->allow('display', 'login', 'logout', 'register', 'activate_account', 'forgotPassword', 'resetPassword');
            $this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'dashboard', $redir => true);
            $this->set('redir', $redir);
            $this->set('root', '/');
        }
    }

    protected function _attachments($model = null)
    {
        if (empty($this->request->data['Attachment'])) {
            return;
        }

        $allowedTypes = array('image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $maxFileSize = 10 * 1024 * 1024;

        $attachmentDir = WWW_ROOT . 'files' . DS . 'Attachments' . DS . 'file' . DS;
        if (!is_dir($attachmentDir)) {
            mkdir($attachmentDir, 0755, true);
        }

        $count = count($this->request->data['Attachment']);
        for ($i = 0; $i < $count; $i++) {
            $this->request->data['Attachment'][$i]['model'] = $model;
            $fileData = $this->request->data['Attachment'][$i]['file'];
            
            if (is_string($fileData) && strpos($fileData, 'data:') !== false) {
                $file = explode(',', $fileData);
                $meta = $file[0];
                $data = base64_decode($file[1]);
                
                preg_match('/data:([^;]+)/', $meta, $matches);
                $fileType = isset($matches[1]) ? $matches[1] : 'application/octet-stream';
                
                $mimeToExt = array(
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'application/pdf' => 'pdf',
                    'application/msword' => 'doc',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx'
                );
                $fileExt = isset($mimeToExt[$fileType]) ? $mimeToExt[$fileType] : 'bin';
            } else {
                $fileType = $fileData['type'];
                $data = file_get_contents($fileData['tmp_name']);
                $fileExt = pathinfo($fileData['name'], PATHINFO_EXTENSION);
            }

            if (strlen($data) > $maxFileSize) {
                $this->Flash->error(__('File size exceeds 10MB limit.'));
                continue;
            }

            if (!in_array($fileType, $allowedTypes)) {
                $this->Flash->error(__('File type not allowed.'));
                continue;
            }

            $filename = bin2hex(random_bytes(16)) . '.' . $fileExt;
            $fileDir = $attachmentDir . $filename;

            if (file_put_contents($fileDir, $data, LOCK_EX) === false) {
                $this->Flash->error(__('Failed to save file.'));
                continue;
            }
            chmod($fileDir, 0644);

            $this->request->data['Attachment'][$i]['file'] = null;
            $this->request->data['Attachment'][$i]['file'] = array(
                'name' => $filename,
                'type' => $fileType,
                'tmp_name' => $fileDir,
                'error' => 0,
                'size' => strlen($data),
                'group' => 'attachment'
            );
        }
    }
}