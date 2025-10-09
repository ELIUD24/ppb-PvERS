<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeText', 'Utility');
App::uses('ThemeView', 'View');
App::uses('HtmlHelper', 'View/Helper');
App::uses('HttpSocket', 'Network/Http');
/**
 * Comments Controller
 */
class CommentsController extends AppController
{

  /**
   * Scaffold
   *
   * @var mixed
   */

  public function submit_surveilance_feedback($data)
  {
    $payload = array(
      "reportId" => $data['Comment']['model_id'],
      "subject" => $data['Comment']['subject'],
      "content" => $data['Comment']['content']
    );

    $options = array(
      'ssl_verify_peer' => false
    );
    $header_options = array(
      'header' => array(
        'Content-Type' => 'application/json'
      )
    );
    $formData = json_encode($payload);
    // debug($formData);
    // exit;
    $HttpSocket = new HttpSocket($options); 
    $baseUrl = Configure::read('pms_api');
    $complaint = Configure::read('pms_feedback_api');
    $auth = Configure::read('pms_api_auth');
    $url = "{$baseUrl}{$complaint}{$auth}";
    //Request Access Token
    $initiate = $HttpSocket->post($url, $formData, $header_options);
  }

  public function report_feedback()
  {
    if ($this->request->is('post') || $this->request->is('put')) {
      $this->Comment->create();
      $model = $this->request->data['Comment']['model'];
      $category = $this->request->data['Comment']['category'];
      $data = $this->request->data;
      if (isset($data['Attachment']) && is_array($data['Attachment'])) {
        // Loop through each Attachment
        foreach ($data['Attachment'] as &$attachment) {
          // Update the 'category' for each Attachment
          $attachment['category'] = $category;
        }
        // Unset the reference to avoid potential issues
        unset($attachment);
      }
      // debug($data);
      // exit;
      if ($model) {
        if ($this->Comment->saveAssociated($this->request->data, array('deep' => true))) {

          //Check if the comment has attachments : If so, the function above has saved them, SO PLEASE GET THE LINKS FOR EACH FILES AND APPEND THEM AS LIST SEPARATED NEXT LINIE
          $comment = $this->Comment->find('first', array(
            'conditions' => array('Comment.id' => $this->Comment->id),
            'contain' => array('Attachment')
          ));
          // debug($data);
          // exit;

          // let's get the basename for all attachments
          $attachment_links = '';
          if (isset($comment['Attachment'])) {
            //  $attachment_links .= '<p> See files below for reference:</p>';
            // check is the attachment is not empty: If empty, it means no files were uploaded else add the message above:

              // Count the number of attachments if more than zero add the message above
              if (count($comment['Attachment']) > 0) {
                $attachment_links .= '<p> See files below for reference:</p>';
              }
              
             


            foreach ($comment['Attachment'] as $key => $value) {
                // Generate full URL to the download link
                $url = Router::url(array(
                    'controller' => 'comments',
                    'action' => 'comment_file_download',
                    $value['id'],
                    'admin' => false
                ), true);
        
                // Append to $attachment_links
                $attachment_links .= '<p>';
                $attachment_links .= '<a href="' . h($url) . '" class="btn btn-link" target="_blank">' . h($value['basename']) . '</a>';
                $attachment_links .= '</p>';
            }
        }

          // debug($attachment_links);
          // exit;
          // CHECK IF PQHPT
          if ($data['Comment']['model'] == "Pqmp") {
            $this->submit_surveilance_feedback($data);
          }

          //******************       Send Email and Notifications to Reporter and Managers          *****************************
          $this->loadModel('Message');
          $html = new HtmlHelper(new ThemeView());
          $message = $this->Message->find('first', array('conditions' => array('name' => 'report_feedback')));
          $entity = ClassRegistry::init($model)->find('first', array(
            'contain' => array(),
            'conditions' => array($model . '.id' => $this->request->data['Comment']['foreign_key'])
          ));

          $users = ClassRegistry::init($model)->User->find('all', array(
            'contain' => array(),
            'conditions' => array(
              'OR' => array(
                array(
                  'User.id' => $entity[$model]['user_id'],
                  'User.is_active' => '1'
                ),
                array(
                  'User.group_id' => 2,
                  'User.is_active' => '1'
                )
              )
            )
          ));
          foreach ($users as $user) {

            if ($this->request->data['Comment']['category'] === 'review') {
            } else {
              $actioner = ($user['User']['group_id'] == 2) ? 'manager' : 'reporter';
              $variables = array(
                'name' => $user['User']['name'], 'reference_no' => $entity[$model]['reference_no'],
                'comment_subject' => $this->request->data['Comment']['subject'],
                'comment_content' => $this->request->data['Comment']['content'],
                'files' => $attachment_links,
                'reference_link' => $html->link(
                  $entity[$model]['reference_no'],
                  array('controller' => 'sadrs', 'action' => 'view', $entity[$model]['id'], $actioner => true, 'full_base' => true),
                  array('escape' => false)
                ),
              );
              $datum = array(
                'email' => $user['User']['email'],
                'id' => $this->request->data['Comment']['foreign_key'], 'user_id' => $user['User']['id'], 'type' => 'report_feedback', 'model' => $model,
                'subject' => CakeText::insert($message['Message']['subject'], $variables),
                'message' => CakeText::insert($message['Message']['content'], $variables)
              );
              $this->loadModel('Queue.QueuedTask');
              $this->QueuedTask->createJob('GenericEmail', $datum);
              $this->QueuedTask->createJob('GenericNotification', $datum);
            }
          }
          //**********************************    END   *********************************

          $this->Session->setFlash(__('The comment has been sent to the user'), 'alerts/flash_success');
          $this->redirect($this->referer());
        } else {
          $errors = $this->Comment->validationErrors;
          $this->Session->setFlash(__('The comment could not be saved. Errors: ') . json_encode($errors), 'alerts/flash_error');
          $this->Session->setFlash(__('The comment could not be saved. Please, try again.'), 'alerts/flash_error');
          $this->redirect($this->referer());
        }
      } else {
        $this->Session->setFlash(__('The model is missing for the class. Please contact provider.'), 'alerts/flash_error');
        $this->redirect($this->referer());
      }
    } else {
      throw new MethodNotAllowedException();
    }
  }

  public function manager_report_feedback()
  {
    $this->report_feedback();
  }
  public function reviewer_report_feedback()
  {
    $this->report_feedback();
  }
  public function reporter_report_feedback()
  {
    $this->report_feedback();
  }

  public function api_report_feedback()
  {
    if ($this->request->is('post') || $this->request->is('put')) {
      $this->Comment->create();
      $model = $this->request->data['Comment']['model'];
      if ($model) {
        $this->request->data['Comment']['user_id'] = $this->Auth->User('id');
        $this->request->data['Comment']['sender'] = $this->Auth->User('name');
        if ($this->Comment->saveAssociated($this->request->data, array('deep' => true))) {

          //******************       Send Email and Notifications to Reporter and Managers          *****************************
          $this->loadModel('Message');
          $html = new HtmlHelper(new ThemeView());
          $message = $this->Message->find('first', array('conditions' => array('name' => 'report_feedback')));
          // $this->loadModel($Model);
          // $entity = $this->Sadr->find('first', array(
          $entity = ClassRegistry::init($model)->find('first', array(
            'contain' => array(),
            'conditions' => array($model . '.id' => $this->request->data['Comment']['foreign_key'])
          ));

          $users = ClassRegistry::init($model)->User->find('all', array(
            'contain' => array(),
            'conditions' => array(
              'OR' => array(
                array(
                  'User.id' => $entity[$model]['user_id'],
                  'User.is_active' => '1'
                ),
                array(
                  'User.group_id' => 2,
                  'User.is_active' => '1'
                )
              )
            )
          ));
          foreach ($users as $user) {
            $actioner = ($user['User']['group_id'] == 2) ? 'manager' : 'reporter';
            $variables = array(
              'name' => $user['User']['name'], 'reference_no' => $entity[$model]['reference_no'],
              'comment_subject' => $this->request->data['Comment']['subject'],
              'comment_content' => $this->request->data['Comment']['content'],
              'reference_link' => $html->link(
                $entity[$model]['reference_no'],
                array('controller' => 'sadrs', 'action' => 'view', $entity[$model]['id'], $actioner => true, 'full_base' => true),
                array('escape' => false)
              ),
            );
            $datum = array(
              'email' => $user['User']['email'],
              'id' => $this->request->data['Comment']['foreign_key'], 'user_id' => $user['User']['id'], 'type' => 'report_feedback', 'model' => $model,
              'subject' => CakeText::insert($message['Message']['subject'], $variables),
              'message' => CakeText::insert($message['Message']['content'], $variables)
            );
            $this->loadModel('Queue.QueuedTask');
            $this->QueuedTask->createJob('GenericEmail', $datum);
            $this->QueuedTask->createJob('GenericNotification', $datum);
          }
          //**********************************    END   *********************************

          $this->set([
            'status' => 'success',
            'message' => 'The comment has been submitted!',
            'data' => $this->request->data,
            '_serialize' => ['status', 'message', 'data']
          ]);
        } else {
          $this->set([
            'status' => 'failed',
            'message' => 'The comment could not be saved. Please, try again.',
            'validation' => $this->Comment->validationErrors,
            'data' => $this->request->data,
            '_serialize' => ['status', 'message', 'validation', 'data']
          ]);
        }
      } else {
        $this->set([
          'status' => 'failed',
          'message' => 'The model is missing for the class. Please contact provider.',
          'data' => $this->request->data,
          '_serialize' => ['status', 'message', 'validation', 'data']
        ]);
      }
    } else {
      throw new MethodNotAllowedException();
    }
  }

  public function comment_file_download($id = null)
  {
    # code... 
    // debug('Response Here');
    // exit;
    $this->loadModel('Attachment');

    $attachment = $this->Attachment->find('first', array(
      'conditions' => array('Attachment.id' => $id),

    ));

    $filename = $attachment['Attachment']['basename'];
    $dirname = $attachment['Attachment']['dirname'];
    $file = WWW_ROOT . 'media/transfer' . DS . $dirname . DS . $filename; // Assuming your files are in the "files" folder under the webroot directory.

    $this->response->file($file, array(
      'download' => true,
      'name' => $filename,
    ));

    return $this->response;
  }
}
