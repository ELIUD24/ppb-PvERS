<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeText', 'Utility');
App::uses('ThemeView', 'View');
App::uses('HtmlHelper', 'View/Helper');
App::uses('Router', 'Routing');
App::uses('Xml', 'Utility');
App::uses('Time', 'Utility');
App::uses('CakeTime', 'Utility');


/**
 * Ce2bs Controller
 *
 * @property Ce2b $Ce2b
 */
class Ce2bsController extends AppController
{

    public $components = array('Search.Prg');
    public $paginate = array();
    public $presetVars = true;
    public $page_options = array('25' => '25', '50' => '50', '100' => '100');

    /**
     * index method
     */
    /*public function index() {
        $this->Aefi->recursive = 0;
        $this->set('aefis', $this->paginate());
    }*/

    // Short Term Goal 
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('finalize');
    }

    private function extractReactions($xml)
    {
        // Register the namespace
        $xml->registerXPathNamespace('ns', 'urn:hl7-org:v3');

        // Initialize an array for reactions
        $reactions = [];

        // Extract reaction information
        foreach ($xml->xpath('//ns:observation[@classCode="OBS" and @moodCode="EVN"]') as $reaction) {
            // Extract reaction details
            $reactionId = (string)$reaction->id['root'];
            $reactionCode = (string)$reaction->code['code'];
            $reactionDisplayName = (string)$reaction->code['displayName'];
            $reactionValue = (string)$reaction->value['code']; // Reaction value code
            $reactionDescription = ''; // Initialize reaction translation or description

            // Extract detailed reaction translation/description
            $translationNode = $reaction->xpath('ns:outboundRelationship2/ns:observation[ns:code[@displayName="reactionForTranslation"]]/ns:value');
            if (!empty($translationNode)) {
                $reactionDescription = trim((string)$translationNode[0]);
            }

            // Add reaction data to the array
            $reactions[] = [
                'reaction_id' => $reactionId,
                'code' => $reactionCode,
                'display_name' => $reactionDisplayName,
                'value' => $reactionValue,
                'description' => $reactionDescription,
            ];
        }

        return $reactions;
    }
    public function finalize($id = null)
    {
        $this->Ce2b->id = $id;
        if (!$this->Ce2b->exists()) {
            return "CE2B Report is invalid";
        }

        $ce2b = $this->Ce2b->find('first', array(
            'conditions' => array('Ce2b.id' => $id),
        ));
        $cc = $ce2b['Ce2b']['e2b_content'];
        if (empty($cc)) {
            return "No XML content found for this CE2B report.";
        }
    }
    public function map_full_reaction_details($reactions)
    {

        $reactions_data = array();
        foreach ($reactions as $re) {
            $reactions_data[] = array(
                'index' => $re['id'],
                'reaction_name' => !empty(Hash::extract($re['reactions'], '{n}[code=30].value'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=30].value')[0]
                    : null,
                'start_date' => '',
                'meddra_code' => $re['value'],
                'meddra_version' => '23',
                'source_country' => $re['location'],
                'criteria_death_code' => '34',
                'criteria_death_null' => !empty(Hash::extract($re['reactions'], '{n}[code=34].value_null'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=34].value_null')[0]
                    : null,
                'criteria_death_value' => !empty(Hash::extract($re['reactions'], '{n}[code=43].value'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=34].value')[0]
                    : null,
                'life_hreatening_code' => '21',
                'life_threatening_null' => !empty(Hash::extract($re['reactions'], '{n}[code=21].value_null'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=21].value_null')[0]
                    : null,
                'life_threatening_value' => !empty(Hash::extract($re['reactions'], '{n}[code=21].value'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=21].value')[0]
                    : null,
                'prolonged_hospitalisation_code' => '33',
                'prolonged_hospitalisation_null' => !empty(Hash::extract($re['reactions'], '{n}[code=33].value_null'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=33].value_null')[0]
                    : null,
                'prolonged_hospitalisation_value' => !empty(Hash::extract($re['reactions'], '{n}[code=33].value'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=33].value')[0]
                    : null,
                'incapacitating_code' => '35',
                'incapacitating_null' => !empty(Hash::extract($re['reactions'], '{n}[code=35].value_null'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=35].value_null')[0]
                    : null,
                'incapacitating_value' => !empty(Hash::extract($re['reactions'], '{n}[code=35].value'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=35].value')[0]
                    : null,
                'birth_defect_code' => '12',
                'birth_defect_null' => !empty(Hash::extract($re['reactions'], '{n}[code=12].value_null'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=12].value_null')[0]
                    : null,
                'birth_defect_value' => !empty(Hash::extract($re['reactions'], '{n}[code=12].value'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=12].value')[0]
                    : null,
                'other_medical_code' => '26',
                'other_medical_null' => !empty(Hash::extract($re['reactions'], '{n}[code=26].value_null'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=26].value_null')[0]
                    : null,
                'other_medical_value' => !empty(Hash::extract($re['reactions'], '{n}[code=26].value'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=26].value')[0]
                    : null,
                'reaction_outcome_code' => '27',
                'reaction_outcome_null' => !empty(Hash::extract($re['reactions'], '{n}[code=27].value_null'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=27].value_null')[0]
                    : null,
                'reaction_outcome_value' => !empty(Hash::extract($re['reactions'], '{n}[code=27].value'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=27].value')[0]
                    : null,
                'medical_confirmation_code' => '24',
                'medical_confirmation_null' => !empty(Hash::extract($re['reactions'], '{n}[code=24].value_null'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=24].value_null')[0]
                    : null,
                'medical_confirmation_value' => !empty(Hash::extract($re['reactions'], '{n}[code=24].value'))
                    ? (string) Hash::extract($re['reactions'], '{n}[code=24].value')[0]
                    : null,
                'serious' => ''
            );
        }

        return $reactions_data;
    }
    public function manipulate_reaction_information($cc)
    {
        $reactions = [];
        try {
            $xml = new SimpleXMLElement($cc);

            $xml->registerXPathNamespace('ns', 'urn:hl7-org:v3');

            foreach ($xml->xpath('//ns:subjectOf2/ns:observation[@classCode="OBS" and @moodCode="EVN" and ns:code[@code="29"]]') as $reaction) {


                $details = [
                    'id' => (string) $reaction->id['root'],
                    'code' => (string) $reaction->code['code'],
                    'value' => (string) $reaction->value['code'],
                ];
                if (isset($reaction->location->locatedEntity->locatedPlace->code)) {
                    $codeElement = $reaction->location->locatedEntity->locatedPlace->code;
                    $locationCode = (string) $codeElement['code'];
                    $details['location'] = $locationCode;
                }
                if (isset($reaction->outboundRelationship2)) {
                    $dt = array();
                    foreach ($reaction->outboundRelationship2  as $kk) {
                        $obsc = (string) $kk->observation->code['code'];
                        $re = (string) $kk->observation->value;
                        $vnull = null;
                        if (empty($re)) {
                            $vnull = "NI";
                        }
                        $dt[] = array(
                            'code' => $obsc,
                            'value' => trim($re),
                            'value_null' => $vnull,
                        );
                    }
                    $details['reactions'] = $dt;
                }
                // Add the reaction details to the results array
                $reactions[] = $details;
            }
        } catch (Exception $e) {
        }
        return $reactions;
    }

    public function manipulate_drug_information($cc)
    {
        $drugs = [];
        try {
            $xml = new SimpleXMLElement($cc);

            // Register namespaces for parsing
            $xml->registerXPathNamespace('ns', 'urn:hl7-org:v3');
            $brand_name = null;
            $dose = null;
            // Extract drug information
            foreach ($xml->xpath('//ns:substanceAdministration/ns:consumable/ns:instanceOfKind/ns:kindOfProduct') as $product) {
                // Extract drug name
                if (isset($product->ingredient)) {
                    if (isset($product->ingredient->ingredientSubstance)) {
                        $brand_name = (string) $product->ingredient->ingredientSubstance->name;
                    }
                    if (isset($product->ingredient->quantity)) {
                        $value = (string) $product->ingredient->quantity->numerator['value'];
                        $unit = (string) $product->ingredient->quantity->numerator['unit'];
                        $dose = "{$value} {$unit}";
                    }
                    // return $product->ingredient->quantity;
                }
                $drugName = trim((string)$product->name);
                if (!empty($drugName)) {
                    if (!in_array($drugName, $drugs)) {
                        $drugs[] = [
                            'drug_name' => $drugName,  // Combine ingredients into a single string
                            'brand_name' => $brand_name,
                            'dose' => $dose
                        ];
                    }
                }
            }
        } catch (Exception $e) {
        }

        return $drugs;
    }
    public function finalize_drugs($id = null)
    {
        $this->Ce2b->id = $id;
        if (!$this->Ce2b->exists()) {
            return "CE2B Report is invalid";
        }

        $ce2b = $this->Ce2b->find('first', array(
            'conditions' => array('Ce2b.id' => $id),
        ));
        $cc = $ce2b['Ce2b']['e2b_content'];
        if (empty($cc)) {
            return "No XML content found for this CE2B report.";
        }

        // Parse the XML string
        try {
            $xml = new SimpleXMLElement($cc);

            // Register namespaces for parsing
            $xml->registerXPathNamespace('ns', 'urn:hl7-org:v3');

            // Initialize arrays for drugs and reactions
            $drugs = [];
            $reactions = [];

            // Extract drug information
            foreach ($xml->xpath('//ns:substanceAdministration/ns:consumable/ns:instanceOfKind/ns:kindOfProduct') as $product) {
                // Extract drug name
                $drugName = trim((string)$product->name);
                $ingredients = [];

                // // Extract ingredient substances
                // foreach ($product->xpath('ns:ingredient/ns:ingredientSubstance') as $ingredientNode) {
                //     $ingredients[] = trim((string)$ingredientNode->name);
                // }

                // Add the drug with its ingredients
                $drugs[] = [
                    'name' => $drugName,
                    'ingredients' => implode(', ', $ingredients), // Combine ingredients into a single string
                ];
            }




            // Prepare data for saving
            $data = ['Drug' => $drugs, 'Reaction' => $reactions];
            debug($data);
            exit;
            // Save to database using models
            // $this->loadModel('Drug');
            // $this->loadModel('Reaction');
            // if ($this->Drug->saveAll($data['Drug']) && $this->Reaction->saveAll($data['Reaction'])) {
            //     return "Data has been successfully saved.";
            // } else {
            //     return "Failed to save data.";
            // }
        } catch (Exception $e) {
            return "Error parsing XML: " . $e->getMessage();
        }
        debug($cc);
        exit;
    }
    public function vigiflow($id = null)
    {
        # code...
        $this->Ce2b->id = $id;
        if (!$this->Ce2b->exists()) {
            $this->Session->setFlash(__('Could not verify the E2b report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        $ce2b = $this->Ce2b->find('first', array(
            'conditions' => array('Ce2b.id' => $id),
        ));

        if (empty($ce2b['Ce2b']['e2b_content'])) {
            $this->Session->setFlash(__('Could not verify the E2b report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect($this->referer());
        }


        $version = $ce2b['Ce2b']['e2b_type'];

        if ($version == "R2") {
            $ce2b['Ce2b']['e2b_content'] = $this->manipulated_content($ce2b['Ce2b']['e2b_content']);
        }
        if ($version == "R3") {
            $ce2b['Ce2b']['e2b_content'] = $this->manipulated_r3content($ce2b['Ce2b']['e2b_content']);
        }
        // debug($ce2b['Ce2b']['e2b_content']);
        // exit;
        $view = new View($this, false);
        $view->viewPath = 'Ce2bs/xml';  // Directory inside view directory to search for .ctp files
        $view->layout = false; // if you want to disable layout
        $view->set('ce2b', $ce2b); // set your variables for view here
        $html = $view->render('download');



        // debug($html);
        // exit;
        $HttpSocket = new HttpSocket();
        // string data
        $results = $HttpSocket->post(
            Configure::read('vigiflow_api'),
            $html,
            array('header' => array('umc-vigiflow-web-radr-access-key' => Configure::read('vigiflow_key')))
        );
        // debug($results);
        // exit;
        if ($results->isOk()) {
            $body = $results->body;
            $this->Ce2b->saveField('vigiflow_message', $body);
            $this->Ce2b->saveField('vigiflow_date', date('Y-m-d H:i:s'));
            $resp = json_decode($body, true);
            if (json_last_error() == JSON_ERROR_NONE) {
                $this->Ce2b->saveField('vigiflow_ref', $resp);
            }
            $this->Flash->success('Vigiflow integration success!!');
            $this->Flash->success($body);
            $this->redirect($this->referer());
        } else {
            $body = $results->body;
            $this->Ce2b->saveField('vigiflow_message', $body);
            $this->Flash->error('Error sending report to vigiflow:');
            $this->Flash->error($body);
            $this->redirect($this->referer());
        }
        $this->autoRender = false;
    }
    public function reporter_followup($id = null)
    {
        $this->followup($id);
    }
    public function manager_followup($id = null)
    {
        $this->followup($id);
    }

    public function followup($id = null)
    {

        if ($this->request->is('post')) {
            $this->Ce2b->id = $id;
            if (!$this->Ce2b->exists()) {
                throw new NotFoundException(__('Invalid SADR'));
            }
            $ce2b = Hash::remove($this->Ce2b->find(
                'first',
                array(
                    'conditions' => array('Ce2b.id' => $id)
                )
            ), 'Ce2b.id');

            $data_save = $ce2b['Ce2b'];
            $data_save['ce2b_id'] = $id;
            $data_save['reference_no'] = $ce2b['Ce2b']['reference_no'];
            $data_save['report_type'] = 'Followup';
            $data_save['submitted'] = 0;

            if ($this->Ce2b->saveAssociated($data_save, array('deep' => true, 'validate' => false))) {
                $this->Session->setFlash(__('Follow up ' . $data_save['reference_no'] . ' has been created'), 'alerts/flash_info');
                $this->redirect(array('action' => 'edit', $this->Ce2b->id));
            } else {
                $this->Session->setFlash(__('The followup could not be saved. Please, try again.'), 'alerts/flash_error');
                $this->redirect($this->referer());
            }
        }
    }
    public function reporter_index()
    {
        # code...
        $this->Prg->commonProcess();
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($this->page_options);
        //Health program fiasco
        if ($this->Session->read('Auth.User.user_type') == 'Public Health Program') {
            $this->passedArgs['health_program'] = $this->Session->read('Auth.User.health_program');
        }

        $criteria = $this->Ce2b->parseCriteria($this->passedArgs);
        if ($this->Session->read('Auth.User.user_type') != 'Public Health Program') $criteria['Ce2b.user_id'] = $this->Auth->User('id');
        if ($this->Session->read('Auth.User.user_type') == 'Public Health Program') {
            $criteria['Ce2b.submitted'] = array(2);
        } else {
            if (isset($this->request->query['submitted'])) {
                if ($this->request->query['submitted'] == 1) {
                    $criteria['Ce2b.submitted'] = array(0, 1);
                } else {
                    $criteria['Ce2b.submitted'] = array(2, 3);
                }
            } else {
                $criteria['Ce2b.submitted'] = array(0, 1, 2, 3);
            }
        }
        // add deleted condition to criteria
        $criteria['Ce2b.deleted'] = false;
        $criteria['Ce2b.archived'] = false;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Ce2b.created' => 'desc');
        $this->set('ce2bs', Sanitize::clean($this->paginate(), array('encode' => false)));
        $this->set('page_options', $this->page_options);
    }


    public function manager_index()
    {
        # code...
        $this->Prg->commonProcess();
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($this->page_options);


        $criteria = $this->Ce2b->parseCriteria($this->passedArgs);
        $criteria['Ce2b.copied !='] = '1';
        if (isset($this->request->query['submitted']) && $this->request->query['submitted'] == 1) {
            $criteria['Ce2b.submitted'] = array(0, 1);
        } else {
            $criteria['Ce2b.submitted'] = array(2, 3);
        }
        $criteria['Ce2b.deleted'] = false;
        if (!empty($this->passedArgs['archived'])) {
            $criteria['Ce2b.archived'] = true;
        } else {
            $criteria['Ce2b.archived'] = false;
        }
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Ce2b.created' => 'desc');
        $this->set('ce2bs', Sanitize::clean($this->paginate(), array('encode' => false)));
        $this->set('page_options', $this->page_options);
    }

    public function manipulated_r3content($xmlString)
    {

        if (empty($xmlString)) {
            throw new BadRequestException(__('No XML data provided.'));
        }

        // Load XML
        $xml = simplexml_load_string($xmlString);
        if ($xml === false) {
            throw new BadRequestException(__('Invalid XML format.'));
        }

        // Register namespaces if necessary
        $namespaces = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('ns', $namespaces['']);


        // Replacement XML for `<receiver>` and `<sender>`
        $newReceiver = '
            <receiver typeCode="RCV">
                <device classCode="DEV" determinerCode="INSTANCE">
                     <id extension="PPB"  root="2.16.840.1.113883.3.989.2.1.3.14" />
                </device>
            </receiver>';
        //  $newReceiver = '';
        $newSender = '
            <sender typeCode="SND">
                <device classCode="DEV" determinerCode="INSTANCE">
                    <id extension="Pharmacy and Poisons Board" root="2.16.840.1.113883.3.989.2.1.3.13" />
                </device>
            </sender>';

        // Replace all `<receiver>` nodes
        $receivers = $xml->xpath('//ns:receiver');
        foreach ($receivers as $receiverNode) {
            $newReceiverXml = new SimpleXMLElement($newReceiver);
            $domReceiver = dom_import_simplexml($receiverNode);
            $domNewReceiver = dom_import_simplexml($newReceiverXml);
            $domReceiver->parentNode->replaceChild(
                $domReceiver->ownerDocument->importNode($domNewReceiver, true),
                $domReceiver
            );
        }
        // foreach ($receivers as $receiverNode) {
        //     $domReceiver = dom_import_simplexml($receiverNode);
        //     if ($domReceiver !== false && $domReceiver->parentNode !== null) {
        //         $domReceiver->parentNode->removeChild($domReceiver);
        //     }
        // }

        // Replace all `<sender>` nodes
        $senders = $xml->xpath('//ns:sender');
        foreach ($senders as $senderNode) {
            $newSenderXml = new SimpleXMLElement($newSender);
            $domSender = dom_import_simplexml($senderNode);
            $domNewSender = dom_import_simplexml($newSenderXml);
            $domSender->parentNode->replaceChild(
                $domSender->ownerDocument->importNode($domNewSender, true),
                $domSender
            );
        }


        return $xml->asXML();
    }
    public function manipulated_content($file)
    {

        // Load the XML from the string content
        $xml = Xml::build($file); // Parse the XML from the string content
        $xmlArray = Xml::toArray($xml);  // Convert XML to array for easy manipulation

        // Check if <sender> exists and remove it
        if (!empty($xmlArray['ichicsr']['safetyreport']['sender'])) {
            unset($xmlArray['ichicsr']['safetyreport']['sender']); // Remove existing <sender> content
        }

        // Add the new <sender> content
        $xmlArray['ichicsr']['safetyreport']['sender'] = [
            'sendertype' => '3',
            'senderorganization' => 'Pharmacy and Poisons Board',
            'senderdepartment' => 'Department of Pharmacovigilance',
            'sendertitle' => 'Dr.',
            'sendergivename' => 'Christabel',
            'sendermiddlename' => 'N.',
            'senderfamilyname' => 'Khaemba',
            'senderstreetaddress' => 'P.O. Box:27663-00506',
            'sendercity' => 'Nairobi',
            'senderstate' => '', // Empty field
            'senderpostcode' => '00506',
            'sendercountrycode' => 'KE',
            'sendertel' => '720608811',
            'sendertelextension' => '', // Empty field
            'sendertelcountrycode' => '254',
            'senderfax' => '2713409',
            'senderfaxextension' => '20',
            'senderfaxcountrycode' => '254',
            'senderemailaddress' => 'pv@pharmacyboardkenya.org'
        ];



        //Remove the receiver details

        // Check if <receiver> exists and replace it
        if (!empty($xmlArray['ichicsr']['safetyreport']['receiver'])) {
            unset($xmlArray['ichicsr']['safetyreport']['receiver']); // Remove existing <receiver> content
        }

        // Add new <receiver> content
        $xmlArray['ichicsr']['safetyreport']['receiver'] = [
            'receivertype' => '',
            'receiverorganization' => '',
            'receiverdepartment' => '',
            'receivertitle' => '',
            'receivergivename' => '',
            'receivermiddlename' => '',
            'receiverfamilyname' => '',
            'receiverstreetaddress' => '',
            'receivercity' => '',
            'receiverstate' => '',
            'receiverpostcode' => '',
            'receivercountrycode' => '',
            'receivertel' => '',
            'receivertelextension' => '',
            'receivertelcountrycode' => '',
            'receiverfax' => '',
            'receiverfaxextension' => '',
            'receiverfaxcountrycode' => '',
            'receiveremailaddress' => ''
        ];



        // Extract specific sections if they exist
        $primarysource = $xmlArray['ichicsr']['safetyreport']['patient'];
        $sender = $xmlArray['ichicsr']['safetyreport']['sender'];
        $receiver = $xmlArray['ichicsr']['safetyreport']['receiver'];
        $patient = $xmlArray['ichicsr']['safetyreport']['patient'];

        // Remove the original tags so we can reassign in the correct order
        unset($xmlArray['ichicsr']['safetyreport']['patient']);
        unset($xmlArray['ichicsr']['safetyreport']['sender']);
        unset($xmlArray['ichicsr']['safetyreport']['receiver']);

        // Reassign with desired order and keep remaining details
        $orderedSafetyReport = $xmlArray['ichicsr']['safetyreport'] + [
            'primarysource' => $primarysource, // Replace <patient> with <primarysource>
            'sender' => $sender,
            'receiver' => $receiver,
            'patient' => $patient,
        ]; // Append remaining sections

        // Set ordered report back in the main array
        $xmlArray['ichicsr']['safetyreport'] = $orderedSafetyReport;
        $xmlArray['ichicsr']['ichicsrmessageheader']['messagesenderidentifier'] = 'PPB';
        $xmlArray['ichicsr']['ichicsrmessageheader']['messagereceiveridentifier'] = 'KE';

        $xml = Xml::fromArray($xmlArray, ['format' => 'tags']);
        $xmlString = $xml->asXML();

        // Format the XML string with indentation and line breaks
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xmlString);
        $formattedXmlContent = $dom->saveXML();

        return $formattedXmlContent;
    }
    public function download($id = null)
    {
        $this->Ce2b->id = $id;
        if (!$this->Ce2b->exists()) {
            $this->Session->setFlash(__('Could not verify the E2b report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }

        $ce2b = $this->Ce2b->find('first', array(
            'conditions' => array('Ce2b.id' => $id),
        ));
        $e2b_content = $ce2b['Ce2b']['e2b_content'];
        $filename = 'CE2B_' . $ce2b['Ce2b']['id'] . ".xml";
        // // Set the HTTP headers for file download
        $version = $ce2b['Ce2b']['e2b_type'];

        if ($version == "R2") {
            $ce2b['Ce2b']['e2b_content'] = $this->manipulated_content($ce2b['Ce2b']['e2b_content']);
        }
        if ($version == "R3") {
            $ce2b['Ce2b']['e2b_content'] = $this->manipulated_r3content($ce2b['Ce2b']['e2b_content']);
        }
        // debug($ce2b['Ce2b']['e2b_content']);
        // exit;
        header('Content-Type: application/xml');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $this->set('ce2b', $ce2b);
        // // Output the XML content
        // echo $e2b_content;
        // $ce2b = Sanitize::clean($ce2b, array('escape' => true));
        // $this->set('ce2b', $ce2b);
        // $this->response->download('CE2B_' . $ce2b['Ce2b']['id'] . '.xml');
    }
    public function manager_copy($id = null)
    {
        if ($this->request->is('post')) {
            $this->Ce2b->id = $id;
            if (!$this->Ce2b->exists()) {
                throw new NotFoundException(__('Invalid E2b'));
            }
            $this->generate_copy($id);
        }
    }

    public function reviewer_copy($id = null)
    {
        if ($this->request->is('post')) {
            $this->Ce2b->id = $id;
            if (!$this->Ce2b->exists()) {
                throw new NotFoundException(__('Invalid E2b'));
            }
            $this->generate_copy($id);
        }
    }

    public function generate_copy($id)
    {
        # code...
        $ce2b = Hash::remove($this->Ce2b->find(
            'first',
            array(
                'contain' => array('Ce2bReaction', 'Ce2bListOfDrug'),
                'conditions' => array('Ce2b.id' => $id)
            )
        ), 'Ce2b.id');

        if ($ce2b['Ce2b']['copied']) {
            $this->Session->setFlash(__('A clean copy already exists. Click on edit to update changes.'), 'alerts/flash_error');
            return $this->redirect(array('action' => 'index'));
        }
        $ce2b = Hash::remove($ce2b, 'Ce2bReaction.{n}.id');
        $ce2b = Hash::remove($ce2b, 'Ce2bListOfDrug.{n}.id');
        $data_save = $ce2b['Ce2b'];
        $data_save['ce2b_id'] = $id;
        $data_save['Ce2bReaction'] = $ce2b['Ce2bReaction'];
        $data_save['Ce2bListOfDrug'] = $ce2b['Ce2bListOfDrug'];
        $data_save['user_id'] = $this->Auth->User('id');
        $this->Ce2b->saveField('copied', 1);
        $data_save['copied'] = 2;
        $data_save['submitted'] = 2;

        if ($this->Ce2b->saveAssociated($data_save, array('deep' => true, 'validate' => false))) {
            $this->Session->setFlash(__('Clean copy of ' . $data_save['reference_no'] . ' has been created'), 'alerts/flash_info');
            $this->redirect(array('action' => 'edit', $this->Ce2b->id));
        } else {
            $this->Session->setFlash(__('The clean copy could not be created. Please, try again.'), 'alerts/flash_error');
            $this->redirect($this->referer());
        }
    }
    public function manager_edit($id = null)
    {
        $this->Ce2b->id = $id;
        if (!$this->Ce2b->exists()) {
            throw new NotFoundException(__('Invalid E2b'));
        }
        $this->general_editor($id);
    }
    public function reviewer_edit($id = null)
    {
        # code...
        $this->Ce2b->id = $id;
        if (!$this->Ce2b->exists()) {
            throw new NotFoundException(__('Invalid E2b'));
        }
        $this->general_editor($id);
    }

    public function extract_drug_details($xmlString)
    {
        if (empty($xmlString)) {
            throw new BadRequestException(__('No XML data provided.'));
        }

        // Load XML
        $xml = simplexml_load_string($xmlString);
        if ($xml === false) {
            throw new BadRequestException(__('Invalid XML format.'));
        }

        // Register namespace
        $namespaces = $xml->getNamespaces(true);
        if (isset($namespaces[''])) {
            $xml->registerXPathNamespace('ns', $namespaces['']);
        } else {
            throw new BadRequestException(__('No default namespace found in the XML.'));
        }

        $drugDetails = [];

        // Extract all `<component>` nodes under `<subjectOf2>/<organizer>`
        $components = $xml->xpath('//ns:subjectOf2/ns:organizer/ns:component');
        foreach ($components as $component) {
            $substance = $component->xpath('.//ns:substanceAdministration');
            if (empty($substance)) {
                continue; // Skip if no <substanceAdministration> found
            }
            $substance = $substance[0];

            // Extract drug name
            $drugNameNodes = $substance->xpath('.//ns:kindOfProduct/ns:name');
            $drugName = isset($drugNameNodes[0]) ? trim((string)$drugNameNodes[0]) : 'Unknown';

            // Extract ingredients
            $ingredients = [];
            $ingredientNodes = $substance->xpath('.//ns:ingredientSubstance/ns:name');
            foreach ($ingredientNodes as $ingredient) {
                $ingredients[] = trim((string)$ingredient);
            }
            if (empty($ingredients)) {
                $ingredients[] = 'Unknown';
            }

            // Extract indications
            $indications = [];
            $indicationNodes = $substance->xpath('.//ns:inboundRelationship/ns:observation');
            foreach ($indicationNodes as $indicationNode) {
                $code = $indicationNode->xpath('./ns:code/@code');
                $displayName = $indicationNode->xpath('./ns:code/@displayName');
                $originalTextNode = $indicationNode->xpath('./ns:value/ns:originalText');

                $indications[] = [
                    'code' => isset($code[0]) ? (string)$code[0] : 'Unknown',
                    'displayName' => isset($displayName[0]) ? (string)$displayName[0] : 'Unknown',
                    'originalText' => isset($originalTextNode[0]) ? trim((string)$originalTextNode[0]) : 'Unknown',
                ];
            }

            // Extract outbound relationships
            $outboundRelationships = [];
            $outboundNodes = $substance->xpath('.//ns:outboundRelationship2/ns:substanceAdministration');
            foreach ($outboundNodes as $outbound) {
                $routeCode = $outbound->xpath('./ns:routeCode/@code');
                $routeTextNode = $outbound->xpath('./ns:routeCode/ns:originalText');
                $doseValue = $outbound->xpath('./ns:doseQuantity/@value');
                $doseUnit = $outbound->xpath('./ns:doseQuantity/@unit');
                $lotNumberTextNode = $outbound->xpath('.//ns:lotNumberText');

                $outboundRelationships[] = [
                    'route_code' => isset($routeCode[0]) ? (string)$routeCode[0] : 'Unknown',
                    'route_text' => isset($routeTextNode[0]) ? trim((string)$routeTextNode[0]) : 'Unknown',
                    'dose' => [
                        'value' => isset($doseValue[0]) ? (string)$doseValue[0] : 'Unknown',
                        'unit' => isset($doseUnit[0]) ? (string)$doseUnit[0] : 'Unknown',
                    ],
                    'lot_number' => isset($lotNumberTextNode[0]) ? trim((string)$lotNumberTextNode[0]) : 'Unknown',
                ];
            }

            // Compile details for this drug
            $drugDetails[] = [
                'drug_name' => $drugName,
                'ingredients' => $ingredients,
                'indications' => $indications,
                'outbound_relationships' => $outboundRelationships,
            ];
        }

        return $drugDetails;
    }



    public function extract_related_reactions($xmlString)
    {
        if (empty($xmlString)) {
            throw new BadRequestException(__('No XML data provided.'));
        }

        // Load XML
        $xml = simplexml_load_string($xmlString);
        if ($xml === false) {
            throw new BadRequestException(__('Invalid XML format.'));
        }

        // Register namespaces if necessary
        $namespaces = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('ns', $namespaces['']);


        $reactions = [];
        // Extract reaction details
        $reactionNodes = $xml->xpath('//ns:observation[contains(@classCode, "OBS") and contains(@code/@displayName, "reaction")]');
        foreach ($reactionNodes as $reactionNode) {
            $code = (string)$reactionNode->xpath('./ns:code/@code')[0];
            $description = (string)$reactionNode->xpath('./ns:code/@displayName')[0];
            $location = (string)$reactionNode->xpath('./ns:location/ns:locatedEntity/ns:locatedPlace/ns:code/@code')[0];
            // Extract all outboundRelationship2 details
            $details = [];
            $outboundRelationships = $reactionNode->xpath('./ns:outboundRelationship2/ns:observation');
            foreach ($outboundRelationships as $relationship) {
                $relationshipCode = (string)$relationship->xpath('./ns:code/@code')[0];
                $relationshipDescription = (string)$relationship->xpath('./ns:code/@displayName')[0];
                $relationshipValue = trim((string)$relationship->xpath('./ns:value')[0]);
                $details[] = [
                    'code' => $relationshipCode,
                    'description' => $relationshipDescription,
                    'value' => $relationshipValue,
                ];
            }
            $reactions[] = [
                'meddra_code' => $code,
                'reaction_name' => $description,
                'source_country' => $location,
                'details' => $details,
            ];
        }
        return $reactions;
    }

    public function extract_reactions_and_drugs($file)
    {
        $xmlContent = file_get_contents($file['tmp_name']);

        // Parse the XML
        $xml = new SimpleXMLElement($xmlContent);

        // Register namespaces for parsing
        $xml->registerXPathNamespace('ns', 'urn:hl7-org:v3');

        // Initialize arrays for drugs and reactions
        $drugs = [];
        $reactions = [];

        // Extract drug information
        // foreach ($xml->xpath('//ns:substanceAdministration/ns:consumable/ns:instanceOfKind/ns:kindOfProduct/ns:name') as $drug) {
        //     $drugName = trim((string)$drug);
        //     $drugs[] = ['name' => $drugName];
        // }

        // // Extract reaction information
        // foreach ($xml->xpath('//ns:observation[@classCode="OBS" and @moodCode="EVN"]') as $reaction) {
        //     $reactionCode = (string)$reaction->code['code'];
        //     $reactionDesc = trim((string)$reaction->xpath('ns:outboundRelationship2/ns:observation/ns:value')[0]);
        //     $reactions[] = [
        //         'reaction_id' => $reactionCode,
        //         'description' => $reactionDesc,
        //     ];
        // }

        // Prepare data for saving
        $data = ['Drug' => $drugs, 'Reaction' => $reactions];
        return $data;
    }


    public function handle_r2_flattened($xmlArray)
    {

        // Flattened data structure
        $flattenedData = [];

        // Extract Message Header
        if (isset($xmlArray['ichicsr']['ichicsrmessageheader'])) {
            $messageHeader = $xmlArray['ichicsr']['ichicsrmessageheader'];
            $flattenedData['MessageHeader'] = [
                'MessageType' => isset($messageHeader['messagetype']) ? $messageHeader['messagetype'] : '',
                'MessageNumber' => isset($messageHeader['messagenumb']) ? $messageHeader['messagenumb'] : '',
                'MessageSender' => isset($messageHeader['messagesenderidentifier']) ? $messageHeader['messagesenderidentifier'] : '',
                'MessageReceiver' => isset($messageHeader['messagereceiveridentifier']) ? $messageHeader['messagereceiveridentifier'] : '',
                'MessageDate' => isset($messageHeader['messagedate']) ? $messageHeader['messagedate'] : '',
            ];
        }

        // Extract Safety Report
        if (isset($xmlArray['ichicsr']['safetyreport'])) {
            $safetyReport = $xmlArray['ichicsr']['safetyreport'];
            $flattenedData['SafetyReport'] = [
                'SafetyReportID' => isset($safetyReport['safetyreportid']) ? $safetyReport['safetyreportid'] : '',
                'PrimarySourceCountry' => isset($safetyReport['primarysourcecountry']) ? $safetyReport['primarysourcecountry'] : '',
                'Seriousness' => isset($safetyReport['serious']) ? $safetyReport['serious'] : '',
                'ReportType' => isset($safetyReport['reporttype']) ? $safetyReport['reporttype'] : '',
                'ReceivedDate' => isset($safetyReport['receivedate']) ? $safetyReport['receivedate'] : '',
            ];
        }

        // Extract Patient Information
        if (isset($safetyReport['patient'])) {
            $patient = $safetyReport['patient'];
            $flattenedData['Patient'] = [
                'Initial' => isset($patient['patientinitial']) ? $patient['patientinitial'] : '',
                'MedicalHistory' => [],
                'Reactions' => [],
                'Drugs' => []
            ];

            // Extract Medical History
            if (isset($patient['medicalhistoryepisode'])) {
                $medicalHistoryEpisodes = is_array($patient['medicalhistoryepisode']) ? $patient['medicalhistoryepisode'] : [$patient['medicalhistoryepisode']];
                foreach ($medicalHistoryEpisodes as $history) {
                    $flattenedData['Patient']['MedicalHistory'][] = [
                        'EpisodeName' => isset($history['patientepisodename']) ? $history['patientepisodename'] : '',
                        'StartDate' => isset($history['patientmedicalstartdate']) ? $history['patientmedicalstartdate'] : '',
                        'Comment' => isset($history['patientmedicalcomment']) ? $history['patientmedicalcomment'] : ''
                    ];
                }
            }

            // Check if 'reaction' exists and handle single/multiple cases
            if (isset($patient['reaction'])) {
                $reactions = is_array($patient['reaction']) && isset($patient['reaction'][0])
                    ? $patient['reaction']
                    : [$patient['reaction']]; // Wrap single item in an array

                foreach ($reactions as $reaction) {
                    $flattenedData['Patient']['Reactions'][] = array(
                        'primarysourcereaction' => isset($reaction['primarysourcereaction']) ? $reaction['primarysourcereaction'] : '',
                        'reactionmeddraversionllt' => isset($reaction['reactionmeddraversionllt']) ? $reaction['reactionmeddraversionllt'] : '',
                        'reactionmeddrallt' => isset($reaction['reactionmeddrallt']) ? $reaction['reactionmeddrallt'] : '',
                        'reactionmeddraversionpt' => isset($reaction['reactionmeddraversionpt']) ? $reaction['reactionmeddraversionpt'] : '',
                        'reactionmeddrapt' => isset($reaction['reactionmeddrapt']) ? $reaction['reactionmeddrapt'] : '',
                        'termhighlighted' => isset($reaction['termhighlighted']) ? $reaction['termhighlighted'] : '',
                        'reactionstartdateformat' => isset($reaction['reactionstartdateformat']) ? $reaction['reactionstartdateformat'] : '',
                        'reactionstartdate' => isset($reaction['reactionstartdate']) ? $reaction['reactionstartdate'] : '',
                        'reactionoutcome' => isset($reaction['reactionoutcome']) ? $reaction['reactionoutcome'] : ''
                    );
                }
            }
            // Extract Drug Information
            if (isset($patient['drug'])) {
                $drugs = is_array($patient['drug']) ? $patient['drug'] : [$patient['drug']];
                foreach ($drugs as $drug) {
                    $flattenedData['Patient']['Drugs'][] = [
                        'MedicinalProduct' => isset($drug['medicinalproduct']) ? $drug['medicinalproduct'] : '',
                        'ActiveSubstance' => isset($drug['activesubstance']['activesubstancename']) ? $drug['activesubstance']['activesubstancename'] : '',
                        'AdministrationRoute' => isset($drug['drugadministrationroute']) ? $drug['drugadministrationroute'] : '',
                        'StartDate' => isset($drug['drugstartdate']) ? $drug['drugstartdate'] : '',
                        'Characterization' => isset($drug['drugcharacterization']) ? $drug['drugcharacterization'] : ''
                    ];
                }
            }
        }

        return $flattenedData;
    }

    public function manipulate_r2_drugs($data)
    {
        $drugs = [];
        if (isset($data)) {
            foreach ($data as $dt) {
                //         'MedicinalProduct' => 'Malaria falciparum RTS,S vaccine + AS01E Solution for injection',
                // 'ActiveSubstance' => 'MALARIA FALCIPARUM VACCINE',
                // 'AdministrationRoute' => '030',
                // 'StartDate' => '20240815',
                // 'Characterization' => '1'
                $MedicinalProduct = $dt['MedicinalProduct'];
                $ActiveSubstance = $dt['ActiveSubstance'];
                $StartDate = $dt['StartDate'];
                $route_id = $dt['AdministrationRoute'];
                $drugs[] = [
                    'drug_name' => $MedicinalProduct,  // Combine ingredients into a single string
                    'brand_name' => $ActiveSubstance,
                    // 'dose' => $dose,
                    'route_id' => $route_id,
                    'start_date' => $StartDate
                ];
            }
        }


        return $drugs;
    }

    public function manipulate_r2_reactions($data)
    {
        $reactions = [];
        if (isset($data)) {
            foreach ($data as $dt) {
                $reactions[] = [
                    'reaction_name' => $dt['primarysourcereaction'],
                    'meddra_code' => $dt['reactionmeddrallt'],
                    'start_date' => $dt['reactionstartdate'],
                    'reaction_outcome_value' => $dt['reactionoutcome']
                ];
            }
        }
        return $reactions;
    }
    public function general_editor($id = null)
    {

        $this->Ce2b->id = $id;
        if (!$this->Ce2b->exists()) {
            throw new NotFoundException(__('Invalid E2b'));
        }
        $ce2b = $this->Ce2b->read(null, $id);
        if ($ce2b['Ce2b']['submitted'] > 1) {
            $this->Session->setFlash(__('The E2b has been submitted'), 'alerts/flash_info');
            $this->redirect(array('action' => 'view', $this->Ce2b->id));
        }
        if ($ce2b['Ce2b']['user_id'] !== $this->Auth->user('id')) {
            $this->Session->setFlash(__('You don\'t have permission to edit this E2b!!'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }

        // Start of Post 
        if ($this->request->is('post') || $this->request->is('put')) {

            $flattenedData = null;
            $xmlString = null;
            $validate = false;
            if (isset($this->request->data['submitReport'])) {

                // debug($this->request->data['Ce2b']['e2b_file_data']);
                // exit;

                $validate = 'first';
                try {

                    // Manipulate R3
                    $file = $this->request->data['Ce2b']['e2b_file_data'];
                    $xmlString = file_get_contents($file['tmp_name']);
                    $xml = Xml::build($xmlString);
                    $xmlString = $xml->asXML();
                    // debug($xmlString);
                    // exit;

                    $filePath = WWW_ROOT . 'files' . DS . 'ce2bs' . DS . $file['name'];
                    move_uploaded_file($file['tmp_name'], $filePath);

                    $xmlArray = Xml::toArray(Xml::build($filePath));

                    $declaration1 = '<?xml version="1.0" encoding="utf-8"?>';
                    $rootElement1 = '<MCCI_IN200100UV01 ITSVersion="XML_1.0" xsi:schemaLocation="urn:hl7-org:v3 http://eudravigilance.ema.europa.eu/XSD/multicacheschemas/MCCI_IN200100UV01.xsd" xmlns="urn:hl7-org:v3" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:mif="urn:hl7-org:v3/mif">';
                    $declaration2 = '<?xml version="1.0" encoding="ISO-8859-1"?>';
                    $doctype = '<!DOCTYPE ichicsr SYSTEM "http://eudravigilance.ema.europa.eu/dtd/icsr21xml.dtd">';
                    $rootElement2 = '<ichicsr lang="en">';



                    if (strpos($xmlString, 'MCCI_IN200100UV01') !== false) {
                        $this->request->data['Ce2b']['e2b_type'] = "R3";
                        $flattenedData = $this->flattenXml($xmlArray);
                        $reactions = $this->manipulate_reaction_information($xmlString);
                        $reactions = $this->map_full_reaction_details($reactions);

                        $this->request->data['Ce2bReaction'] = $reactions;
                        $this->request->data['Ce2bListOfDrug'] = $this->manipulate_drug_information($xmlString);
                    } else {
                        $this->request->data['Ce2b']['e2b_type'] = "R2";
                        $flattenedData = $this->handle_r2_flattened($xmlArray);
                        // debug($xmlArray);
                        // debug($flattened);
                        // debug($flattenedData);
                        $drugs = $this->manipulate_r2_drugs($flattenedData['Patient']['Drugs']);
                        $reactions = $this->manipulate_r2_reactions($flattenedData['Patient']['Reactions']);
                        // debug($drugs);
                        // debug($reactions);
                        $this->request->data['Ce2bReaction'] = $reactions;
                        $this->request->data['Ce2bListOfDrug'] = $drugs;
                        // exit;
                    }

                    $this->Ce2b->saveField('submitted', 2);
                    $this->Ce2b->saveField('e2b_content', $xmlString, false);
                } catch (Exception $e) {

                    $this->request->data['Ce2b']['e2b_type'] = "R2";
                }
            }

            if ($this->Ce2b->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                if (isset($this->request->data['submitReport'])) {

                    try {
                        //populate other parts::::                       

                        $newReportData = $this->extractReportData($flattenedData);

                        foreach ($newReportData as $key => $value) {
                            $this->Ce2b->saveField($key, $value, false);
                        }
                    } catch (Exception $rr) {
                        //
                    }

                    $this->Ce2b->saveField('submitted', 2);
                    $this->Ce2b->saveField('submitted_date', date("Y-m-d H:i:s"));
                    if (!empty($ce2b['Ce2b']['reference_no']) && $ce2b['Ce2b']['reference_no'] == 'new') {
                        $count = $this->Ce2b->find('count',  array(
                            'fields' => 'Ce2b.reference_no',
                            'conditions' => array(
                                'Ce2b.submitted_date BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s")),
                                'Ce2b.reference_no !=' => 'new'
                            )
                        ));
                        $count++;
                        $count = ($count < 10) ? "0$count" : $count;
                        $reference = 'E2B/' . date('Y') . '/' . $count;
                        $this->Ce2b->saveField('reference_no', $reference);
                    }

                    $ce2b = $this->Ce2b->read(null, $id);

                    //******************       Send Email and Notifications to Reporter and Managers          *****************************
                    $this->loadModel('Message');
                    $html = new HtmlHelper(new ThemeView());
                    $message = $this->Message->find('first', array('conditions' => array('name' => 'reporter_ce2b_submit')));
                    $variables = array(
                        'name' => $this->Auth->User('name'),
                        'reference_no' => $ce2b['Ce2b']['reference_no'],
                        'reference_link' => $html->link(
                            $ce2b['Ce2b']['reference_no'],
                            array('controller' => 'ce2bs', 'action' => 'view', $ce2b['Ce2b']['id'], 'reporter' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'modified' => $ce2b['Ce2b']['modified']
                    );
                    $datum = array(
                        'email' => $ce2b['Ce2b']['reporter_email'],
                        'id' => $id,
                        'user_id' => $this->Auth->User('id'),
                        'type' => 'reporter_ce2b_submit',
                        'model' => 'Ce2b',
                        'subject' => CakeText::insert($message['Message']['subject'], $variables),
                        'message' => CakeText::insert($message['Message']['content'], $variables)
                    );

                    $this->loadModel('Queue.QueuedTask');
                    $this->QueuedTask->createJob('GenericEmail', $datum);
                    $this->QueuedTask->createJob('GenericNotification', $datum);


                    //Send SMS
                    if (!empty($ce2b['Ce2b']['reporter_phone']) && strlen(substr($ce2b['Ce2b']['reporter_phone'], -9)) == 9 && is_numeric(substr($ce2b['Ce2b']['reporter_phone'], -9))) {
                        $datum['phone'] = '254' . substr($ce2b['Ce2b']['reporter_phone'], -9);
                        $variables['reference_url'] = Router::url(['controller' => 'ce2bs', 'action' => 'view', $ce2b['Ce2b']['id'], 'reporter' => true, 'full_base' => true]);
                        $datum['sms'] = CakeText::insert($message['Message']['sms'], $variables);
                        $this->QueuedTask->createJob('GenericSms', $datum);
                    }

                    //Notify managers
                    $users = $this->Ce2b->User->find('all', array(
                        'contain' => array(),
                        'conditions' => array(
                            'User.group_id' => 2,
                            'User.is_active' => '1'
                        )
                    ));
                    foreach ($users as $user) {
                        $variables = array(
                            'name' => $user['User']['name'],
                            'reference_no' => $ce2b['Ce2b']['reference_no'],
                            'reference_link' => $html->link(
                                $ce2b['Ce2b']['reference_no'],
                                array('controller' => 'Ce2bs', 'action' => 'view', $ce2b['Ce2b']['id'], 'manager' => true, 'full_base' => true),
                                array('escape' => false)
                            ),
                            'modified' => $ce2b['Ce2b']['modified']
                        );
                        $datum = array(
                            'email' => $user['User']['email'],
                            'id' => $id,
                            'user_id' => $user['User']['id'],
                            'type' => 'reporter_ce2b_submit',
                            'model' => 'Ce2b',
                            'subject' => CakeText::insert($message['Message']['subject'], $variables),
                            'message' => CakeText::insert($message['Message']['content'], $variables)
                        );

                        // $this->QueuedTask->createJob('GenericEmail', $datum);
                        // $this->QueuedTask->createJob('GenericNotification', $datum);
                    }
                    // **********************************    END   *********************************

                    $this->Session->setFlash(__('The E2b has been submitted to PPB'), 'alerts/flash_success');
                    $this->redirect(array('action' => 'view', $this->Ce2b->id));
                }
                $this->Session->setFlash(__('The E2b has been saved'), 'alerts/flash_success');
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('The E2b could not be saved. Please review the error(s) and resubmit and try again.'), 'alerts/flash_error');
            }
        } else {
            $this->request->data = $this->Ce2b->read(null, $id);
        }
        // End of Post
        $counties = $this->Ce2b->County->find('list', array('order' => array('County.county_name' => 'ASC')));
        $this->set(compact('counties'));
        $sub_counties = $this->Ce2b->SubCounty->find('list', array('order' => array('SubCounty.sub_county_name' => 'ASC')));
        $this->set(compact('sub_counties'));
        $designations = $this->Ce2b->Designation->find('list', array('order' => array('Designation.name' => 'ASC')));
        $this->set(compact('designations'));
    }


    private function extractDrugs($flattenedData, $reaction)

    {
        $observations = [];
        $index = $reaction + 1;

        while (true) {
            // Construct the dynamic key for the observation
            $observationKey = "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.component.0.adverseEventAssessment.subject1.primaryRole.subjectOf2.{$index}.organizer";

            // Check if the key exists in the flattened data
            if (isset($flattenedData[$observationKey . ".@classCode"])) {

                $brand_name_key = $observationKey .  ".component.substanceAdministration.consumable.instanceOfKind.kindOfProduct.ingredient.ingredientSubstance.name";
                $dose_key = $observationKey . ".component.substanceAdministration.outboundRelationship2.substanceAdministration.doseQuantity";
                $drug_name_key = $observationKey .  ".component.substanceAdministration.consumable.instanceOfKind.kindOfProduct.name";
                $route_key = $observationKey . ".component.substanceAdministration.outboundRelationship2.substanceAdministration.routeCode.originalText";
                $drug_name = null;
                $brand_name = null;
                $dose = null;
                $route = null;
                if (isset($flattenedData[$drug_name_key])) {
                    $drug_name = $flattenedData[$drug_name_key];
                }

                if (isset($flattenedData[$dose_key])) {
                    $dose = $flattenedData[$dose_key];
                }
                if (isset($flattenedData[$brand_name_key])) {
                    $brand_name = $flattenedData[$brand_name_key];
                }
                if (isset($flattenedData[$route_key])) {
                    $route = $flattenedData[$route_key];
                }
                $observations[] = [
                    'index' => $index,
                    'drug_name' => $drug_name,
                    'brand_name' => $brand_name,
                    'dose' => $dose,
                    'route' => $route

                ];
                $index++;
            } else {
                // Break the loop if the key does not exist
                break;
            }
        }

        return $observations;
    }
    function getNestedValueDynamic($array, $keyPath)
    {
        $keys = explode('.', $keyPath);
        return $this->getNestedValueRecursive($array, $keys);
    }

    function getNestedValueRecursive($array, $keys)
    {
        $currentValue = $array;

        foreach ($keys as $index => $key) {
            if ($key === '*') {
                // This indicates we need to look at all sub-keys from a specific index onwards
                $nextKeys = array_slice($keys, $index + 1);
                $found = false;
                foreach ($currentValue as $subKey => $subValue) {
                    if (is_array($subValue)) {
                        $result = $this->getNestedValueRecursive($subValue, $nextKeys);
                        if ($result !== null) {
                            return $result;
                        }
                    }
                }
                if (!$found) {
                    return null;
                }
            } else {
                // Normal key handling
                if (is_array($currentValue) && array_key_exists($key, $currentValue)) {
                    $currentValue = $currentValue[$key];
                } else {
                    // Return null if any key in the path does not exist
                    return null;
                }
            }
        }

        return $currentValue;
    }
    public function extractCriteria($data)
    {



        // Using wildcards to represent dynamic keys
        $keyPath = 'MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.component.0.adverseEventAssessment.subject1.primaryRole.subjectOf2.{n}.observation.outboundRelationship2.{n}.observation.value.@nullFlavor';

        $values = Hash::extract($data, $keyPath);
        $observations = [
            'value' => $values,

        ];


        return $observations;
    }

    private function extractObservations($flattenedData)
    {
        $observations = [];
        $index = 1;

        while (true) {
            // Construct the dynamic key for the observation
            $observationKey = "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.component.0.adverseEventAssessment.subject1.primaryRole.subjectOf2.{$index}.observation";

            // Check if the key exists in the flattened data
            if (isset($flattenedData[$observationKey . ".@classCode"])) {
                // Construct the dynamic key for the locatedPlace code within the observation
                $locatedPlaceKey = $observationKey . ".location.locatedEntity.locatedPlace.code.@code";
                $start_date = $observationKey . ".effectiveTime.low";

                $meddra_code_key = $observationKey .  ".value.@code";
                $meddra_version_key = $observationKey . ".value.@codeSystemVersion";
                $reaction_name_key = $observationKey . ".outboundRelationship2.0.observation.value.@";

                // Results in Death
                $criteria_death_code_key = $observationKey . ".outboundRelationship2.1.observation.code.@code";
                $criteria_death_null_key = $observationKey . ".outboundRelationship2.1.observation.value.@nullFlavor";
                $criteria_death_value_key = $observationKey . ".outboundRelationship2.1.observation.value";

                // Life Threatening 
                $life_hreatening_code_key = $observationKey . ".outboundRelationship2.2.observation.code.@code";
                $life_hreatening_null_key = $observationKey . ".outboundRelationship2.2.observation.value.@nullFlavor";
                $life_hreatening_value_key = $observationKey . ".outboundRelationship2.2.observation.value";

                //    Caused / Prolonged Hospitalisation 
                $prolonged_hospitalisation_code_key = $observationKey . ".outboundRelationship2.3.observation.code.@code";
                $prolonged_hospitalisation_null_key = $observationKey . ".outboundRelationship2.3.observation.value.@nullFlavor";
                $prolonged_hospitalisation_value_key = $observationKey . ".outboundRelationship2.3.observation.value";

                // Disabling / Incapacitating 
                $incapacitating_code_key = $observationKey . ".outboundRelationship2.4.observation.code.@code";
                $incapacitating_null_key = $observationKey . ".outboundRelationship2.4.observation.value.@nullFlavor";
                $incapacitating_value_key = $observationKey . ".outboundRelationship2.4.observation.value";


                // Congenital Anomaly / Birth Defect  birth_defect
                $birth_defect_code_key = $observationKey . ".outboundRelationship2.5.observation.code.@code";
                $birth_defect_null_key = $observationKey . ".outboundRelationship2.5.observation.value.@nullFlavor";
                $birth_defect_value_key = $observationKey . ".outboundRelationship2.5.observation.value";


                // Other Medically Important Condition 
                $other_medical_code_key = $observationKey . ".outboundRelationship2.6.observation.code.@code";
                $other_medical_null_key = $observationKey . ".outboundRelationship2.6.observation.value.@nullFlavor";
                $other_medical_value_key = $observationKey . ".outboundRelationship2.6.observation.value";


                // Outcome of Reaction
                $reaction_outcome_code_key = $observationKey . ".outboundRelationship2.7.observation.code.@code";
                $reaction_outcome_null_key = $observationKey . ".outboundRelationship2.7.observation.value.@nullFlavor";
                $reaction_outcome_value_key = $observationKey . ".outboundRelationship2.7.observation.value.@code";


                // Medical Confirmation medical_confirmation
                $medical_confirmation_code_key = $observationKey . ".outboundRelationship2.8.observation.code.@code";
                $medical_confirmation_null_key = $observationKey . ".outboundRelationship2.8.observation.value.@nullFlavor";
                $medical_confirmation_value_key = $observationKey . ".outboundRelationship2.8.observation.value";


                $start_of_reaction = null;
                $country_of_source = null;
                $meddra_code = null;
                $meddra_version = null;
                $reaction_name = null;

                $criteria_death_code = $medical_confirmation_code = $reaction_outcome_code = $other_medical_code = $life_hreatening_code = $prolonged_hospitalisation_code = $incapacitating_code = $birth_defect_code = null;
                $criteria_death_null = $medical_confirmation_null = $reaction_outcome_null = $other_medical_null = $life_threatening_null = $prolonged_hospitalisation_null = $incapacitating_null = $birth_defect_null = null;
                $criteria_death_value = $medical_confirmation_value = $reaction_outcome_value = $other_medical_value = $life_threatening_value = $prolonged_hospitalisation_value = $incapacitating_value = $birth_defect_value = null;

                // medical_confirmation
                if (isset($flattenedData[$medical_confirmation_code_key])) {
                    $medical_confirmation_code = $flattenedData[$medical_confirmation_code_key];
                }
                if (isset($flattenedData[$medical_confirmation_null_key])) {
                    $medical_confirmation_null = $flattenedData[$medical_confirmation_null_key];
                }
                if (isset($flattenedData[$medical_confirmation_value_key])) {
                    $medical_confirmation_value = $flattenedData[$medical_confirmation_value_key];
                }
                // reaction_outcome
                if (isset($flattenedData[$reaction_outcome_code_key])) {
                    $reaction_outcome_code = $flattenedData[$reaction_outcome_code_key];
                }
                if (isset($flattenedData[$reaction_outcome_null_key])) {
                    $reaction_outcome_null = $flattenedData[$reaction_outcome_null_key];
                }
                if (isset($flattenedData[$reaction_outcome_value_key])) {
                    $reaction_outcome_value = $flattenedData[$reaction_outcome_value_key];
                }
                // other_medical
                if (isset($flattenedData[$other_medical_code_key])) {
                    $other_medical_code = $flattenedData[$other_medical_code_key];
                }
                if (isset($flattenedData[$other_medical_null_key])) {
                    $other_medical_null = $flattenedData[$other_medical_null_key];
                }
                if (isset($flattenedData[$other_medical_value_key])) {
                    $other_medical_value = $flattenedData[$other_medical_value_key];
                }
                // birth_defect
                if (isset($flattenedData[$birth_defect_code_key])) {
                    $birth_defect_code = $flattenedData[$birth_defect_code_key];
                }
                if (isset($flattenedData[$birth_defect_null_key])) {
                    $birth_defect_null = $flattenedData[$birth_defect_null_key];
                }
                if (isset($flattenedData[$birth_defect_value_key])) {
                    $birth_defect_value = $flattenedData[$birth_defect_value_key];
                }


                if (isset($flattenedData[$incapacitating_code_key])) {
                    $incapacitating_code = $flattenedData[$incapacitating_code_key];
                }
                if (isset($flattenedData[$incapacitating_null_key])) {
                    $incapacitating_null = $flattenedData[$incapacitating_null_key];
                }
                if (isset($flattenedData[$incapacitating_value_key])) {
                    $incapacitating_value = $flattenedData[$incapacitating_value_key];
                }

                //   Life Threatening  
                if (isset($flattenedData[$prolonged_hospitalisation_code_key])) {
                    $prolonged_hospitalisation_code = $flattenedData[$prolonged_hospitalisation_code_key];
                }
                if (isset($flattenedData[$prolonged_hospitalisation_null_key])) {
                    $prolonged_hospitalisation_null = $flattenedData[$prolonged_hospitalisation_null_key];
                }
                if (isset($flattenedData[$prolonged_hospitalisation_value_key])) {
                    $prolonged_hospitalisation_value = $flattenedData[$prolonged_hospitalisation_value_key];
                }


                //   Life Threatening  
                if (isset($flattenedData[$life_hreatening_code_key])) {
                    $life_hreatening_code = $flattenedData[$life_hreatening_code_key];
                }
                if (isset($flattenedData[$life_hreatening_null_key])) {
                    $life_threatening_null = $flattenedData[$life_hreatening_null_key];
                }
                if (isset($flattenedData[$life_hreatening_value_key])) {
                    $life_threatening_value = $flattenedData[$life_hreatening_value_key];
                }



                if (isset($flattenedData[$criteria_death_null_key])) {
                    $criteria_death_null = $flattenedData[$criteria_death_null_key];
                }
                if (isset($flattenedData[$criteria_death_code_key])) {
                    $criteria_death_code = $flattenedData[$criteria_death_code_key];
                }
                if (isset($flattenedData[$criteria_death_value_key])) {
                    $criteria_death_value = $flattenedData[$criteria_death_value_key];
                }

                if (isset($flattenedData[$reaction_name_key])) {
                    $reaction_name = $flattenedData[$reaction_name_key];
                }
                if (isset($flattenedData[$meddra_version_key])) {
                    $meddra_version = $flattenedData[$meddra_version_key];
                }
                if (isset($flattenedData[$meddra_code_key])) {
                    $meddra_code = $flattenedData[$meddra_code_key];
                }
                if (isset($flattenedData[$start_date])) {
                    $start_of_reaction = $flattenedData[$start_date];
                }
                if (isset($flattenedData[$locatedPlaceKey])) {
                    $country_of_source = $flattenedData[$locatedPlaceKey];
                }
                if (isset($flattenedData[$criteria_death_code_key])) {
                    $criteria_death_code = $flattenedData[$criteria_death_code_key];
                }

                if (!empty($reaction_name)) {

                    if (
                        $criteria_death_value === 'true' ||
                        $life_threatening_value === 'true' ||
                        $prolonged_hospitalisation_value === 'true' ||
                        $incapacitating_value === 'true' ||
                        $birth_defect_value === 'true'
                    ) {
                        // Add a new field 'serious' with value 'true'
                        $serious = 'true';
                    } else {
                        // Add a new field 'serious' with value 'false'
                        $serious = 'false';
                    }

                    if (!empty($start_of_reaction)) {
                        $start_of_reaction = $this->generateDesiredDate($start_of_reaction);
                    }
                    $observations[] = [
                        'index' => $index,
                        'reaction_name' => $reaction_name,
                        'start_date' => $start_of_reaction,
                        'meddra_code' => $meddra_code,
                        'meddra_version' => $meddra_version,
                        'source_country' => $country_of_source,
                        'criteria_death_code' => $criteria_death_code,
                        'criteria_death_null' => $criteria_death_null,
                        'criteria_death_value' => $criteria_death_value,
                        'life_hreatening_code' => $life_hreatening_code,
                        'life_threatening_null' => $life_threatening_null,
                        'life_threatening_value' => $life_threatening_value,
                        'prolonged_hospitalisation_code' => $prolonged_hospitalisation_code,
                        'prolonged_hospitalisation_null' => $prolonged_hospitalisation_null,
                        'prolonged_hospitalisation_value' => $prolonged_hospitalisation_value,
                        'incapacitating_code' => $incapacitating_code,
                        'incapacitating_null' => $incapacitating_null,
                        'incapacitating_value' => $incapacitating_value,
                        'birth_defect_code' => $birth_defect_code,
                        'birth_defect_null' => $birth_defect_null,
                        'birth_defect_value' => $birth_defect_value,
                        'other_medical_code' => $other_medical_code,
                        'other_medical_null' => $other_medical_null,
                        'other_medical_value' => $other_medical_value,
                        'reaction_outcome_code' => $reaction_outcome_code,
                        'reaction_outcome_null' => $reaction_outcome_null,
                        'reaction_outcome_value' => $reaction_outcome_value,
                        'medical_confirmation_code' => $medical_confirmation_code,
                        'medical_confirmation_null' => $medical_confirmation_null,
                        'medical_confirmation_value' => $medical_confirmation_value,
                        'serious' => $serious

                    ];
                }
                $index++;
            } else {
                // Break the loop if the key does not exist
                break;
            }
        }

        return $observations;
    }

    public function extractReportData($flattenedData)

    {
        $mappings = [
            'creation_time' => "MCCI_IN200100UV01.creationTime",
            'sender_reference' => 'MCCI_IN200100UV01.PORR_IN049016UV.id.@extension',
            'receiver_id' => "MCCI_IN200100UV01.PORR_IN049016UV.receiver.device.id.@extension",
            'sender_id' => "MCCI_IN200100UV01.PORR_IN049016UV.sender.device.id.@extension",
            'sender_unique_identifier' => "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.id.0.@extension",
            'worldwide_identifier' => "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.id.1.@extension",
            'case_narrative' => "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.text",
            'date_first_received' => "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.effectiveTime.low",
            'date_most_recent_info' => "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.availabilityTime",
            'patient_name' => "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.component.0.adverseEventAssessment.subject1.primaryRole.player1.name",
            'patient_sex' => "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.component.0.adverseEventAssessment.subject1.primaryRole.player1.administrativeGenderCode.@code",
            'patient_dob' => "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.component.0.adverseEventAssessment.subject1.primaryRole.player1.birthTime",
            'patient_number' => "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.component.0.adverseEventAssessment.subject1.primaryRole.player1.asIdentifiedEntity.id.@extension",
            'past_medical' => "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.component.0.adverseEventAssessment.subject1.primaryRole.subjectOf2.0.organizer.component.0.observation.outboundRelationship2.observation.value.@",
            'sender_address' => "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.subjectOf1.controlActEvent.author.assignedEntity.addr.streetAddressLine",
            'sender_city' => "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.subjectOf1.controlActEvent.author.assignedEntity.addr.city",
            'sender_state' => "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.subjectOf1.controlActEvent.author.assignedEntity.addr.state",
            'sender_department' => "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.subjectOf1.controlActEvent.author.assignedEntity.representedOrganization.name",
            'sender_organization' => "MCCI_IN200100UV01.PORR_IN049016UV.controlActProcess.subject.investigationEvent.subjectOf1.controlActEvent.author.assignedEntity.representedOrganization.assignedEntity.representedOrganization.name"
        ];

        $save_data = [];
        foreach ($mappings as $key => $path) {
            // Check if the path exists in the flattened data and is not null
            $save_data[$key] = isset($flattenedData[$path]) ? $flattenedData[$path] : null;
        }
        return $save_data;
    }


    private function flattenXml($xmlArray)
    {
        $data = [];
        $this->recursiveFlatten($xmlArray, $data);
        return $data;
    }

    private function recursiveFlatten($element, &$data, $parentKey = '')
    {
        foreach ($element as $key => $value) {
            $newKey = $parentKey ? $parentKey . '.' . $key : $key;
            if (is_array($value)) {
                if (isset($value['@attributes'])) {
                    foreach ($value['@attributes'] as $attrKey => $attrValue) {
                        $data[$newKey . '.' . $attrKey] = $attrValue;
                    }
                }
                if (isset($value['@value'])) {
                    $data[$newKey] = $value['@value'];
                } else {

                    foreach ($value as $subKey => $subValue) {
                        if (is_array($subValue)) {
                            $this->recursiveFlatten([$subKey => $subValue], $data, $newKey);
                        } else {
                            $data[$newKey . '.' . $subKey] = $subValue;
                        }
                    }
                    // $this->recursiveFlatten($value, $data, $newKey);
                }
            } else {
                $data[$newKey] = $value;
            }
        }
    }

    public function general_add()
    {
        $user = $this->Auth->User();
        // debug($user);
        // exit;
        $this->Ce2b->create();
        $this->Ce2b->save(['Ce2b' => [
            'user_id' => $this->Auth->User('id'),
            'reference_no' => 'new',
            'reporter_email' => $this->Auth->User('email'),
            'designation_id' => $this->Auth->User('designation_id'),
            'reporter_designation' => $this->Auth->User('designation_id'),
            'county_id' => $this->Auth->User('county_id'),
            'reporter_name' => $this->Auth->User('name'),
            'reporter_email' => $this->Auth->User('email'),
            'reporter_phone' => $this->Auth->User('phone_no'),
            'company_name' => $user['name_of_institution'],
            'company_code' => $this->Auth->User('institution_code'),

            // 
        ]], false);
        $this->Session->setFlash(__('The E2b has been created'), 'alerts/flash_success');
        $this->redirect(array('action' => 'edit', $this->Ce2b->id));
    }
    public function manager_add()
    {
        $this->general_add();
    }
    public function reporter_add()
    {

        $this->general_add();
    }
    public function generateReferenceNumber()
    {
        $count = $this->Ce2b->find('count',  array(
            'fields' => 'Ce2b.reference_no',
            'conditions' => array(
                'Ce2b.submitted_date BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s")),
                'Ce2b.reference_no !=' => 'new'
            )
        ));
        $count++;
        $count = ($count < 10) ? "0$count" : $count;
        $reference = 'E2B/' . date('Y') . '/' . $count;
        //ensure that the reference number is unique
        $exists = $this->Ce2b->find('count',  array(
            'fields' => 'Ce2b.reference_no',
            'conditions' => array('Ce2b.reference_no' => $reference)
        ));
        if ($exists > 0) {
            $reference = $this->generateReferenceNumber();
        }

        return $reference;
    }

    public function reporter_edit($id = null)
    {
        $this->Ce2b->id = $id;
        if (!$this->Ce2b->exists()) {
            throw new NotFoundException(__('Invalid Ce2b'));
        }
        $this->general_editor($id);
    }

    public function manager_view($id = null)
    {
        # code...
        $this->Ce2b->id = $id;
        if (!$this->Ce2b->exists()) {
            $this->Session->setFlash(__('Could not verify the E2b report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }
        $this->general_view($id);
    }

    public function reviewer_view($id = null)
    {
        # code...
        $this->Ce2b->id = $id;
        if (!$this->Ce2b->exists()) {
            $this->Session->setFlash(__('Could not verify the E2b report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }
        $this->general_view($id);
    }

    public function generateDesiredDate($input)
    {


        try {
            // Create a DateTime object from the input
            $date = new DateTime($input);

            // Format the DateTime object as YYYY-MM-DD HH:MM:SS
            $formattedDate = $date->format('Y-m-d H:i:s');

            return $formattedDate;
        } catch (Exception $e) {
            // Handle exceptions if the input format is invalid
            return '';
        }
    }
    public function general_view($id = null)
    {
        # code...

        $ce2b = $this->Ce2b->find('first', array(
            'conditions' => array('Ce2b.id' => $id),
            'contain' => array('Designation', 'Ce2bListOfDrug' => array('Route'), 'Ce2bReaction', 'Attachment', 'ExternalComment', 'ExternalComment.Attachment', 'ReviewComment', 'ReviewComment.Attachment')
        ));


        // debug($ce2b);
        // exit;

        if (empty($ce2b['Ce2b']['e2b_content'])) {
            $this->Session->setFlash(__('Invalid XML File, please reupload and try again'), 'flash_error');
            // $this->redirect($this->referer());

            //unsubmit and allow editing:

            $this->Ce2b->saveField('submitted', 1);
            $this->redirect(array('action' => 'edit', $this->Ce2b->id));
        }

        if ($ce2b['Ce2b']['e2b_type'] === "R2") {

            $data = [];
            try {
                $xml = $ce2b['Ce2b']['e2b_content'];
                $xml = Xml::build($xml);
                $elements = $xml->xpath('//*');

                // $this->Ce2b->saveField('submitted', 1);

                foreach ($elements as $element) {
                    $key = $element->getName();
                    $value = (string) $element;
                    if ($key == 'ichicsr' || $key == 'ichicsrmessageheader') {
                        continue;
                    } else {

                        $keyMapping = [
                            'narrativeincludeclinical' => 'case_narrative',
                            'senderdepartment' => 'sender_department',
                            'senderorganization' => 'sender_organization',
                            'receivedate' => 'date_first_received',
                            'seriousnessdeath' => 'results_in_death',
                            'seriousnesslifethreatening' => 'life_threatening',
                            'seriousnesshospitalization' => 'prolonged_hospitalization',
                            'seriousnessdisabling' => 'incapacitating',
                            'messagedate' => 'creation_time',
                            'safetyreportid' => 'sender_unique_identifier',
                            'messagenumb' => 'worldwide_identifier',
                            'reporterfamilyname' => 'patient_name',

                        ];

                        $formattedKeys = ['narrativeincludeclinical'];
                        // Rename key if it exists in the mapping
                        if (isset($keyMapping[$key])) {
                            $key = $keyMapping[$key];
                        }
                        if (in_array($key, $formattedKeys)) {
                            // Convert newlines to HTML <br> and escape special characters
                            $value = nl2br(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
                        } else {
                            // Trim whitespace for other fields
                            $value = trim($value);
                        }


                        $this->Ce2b->saveField($key, $value, false);


                        $data[] = [
                            'key' => $key,
                            'value' => $value
                        ];
                    }
                }
            } catch (Exception $e) {
            }

            // $this->set(['ce2b' => $ce2b, 'data' => $data]);
            // debug($data);
            // exit;

            $ce2b = $this->Ce2b->find('first', array(
                'conditions' => array('Ce2b.id' => $id),
                'contain' => array('Designation', 'Ce2bListOfDrug' => array('Route'), 'Ce2bReaction', 'Attachment', 'ExternalComment', 'ExternalComment.Attachment', 'ReviewComment', 'ReviewComment.Attachment')
            ));


// Extract values
$resultsInDeath = Hash::extract($data, '{n}[key=results_in_death].value');
$lifeThreateningValue = Hash::extract($data, '{n}[key=life_threatening].value');
$prolongedHospitalizationValue = Hash::extract($data, '{n}[key=prolonged_hospitalization].value');
$incapacitatingValue = Hash::extract($data, '{n}[key=incapacitating].value');
$birthDefectValue = Hash::extract($data, '{n}[key=seriousnesscongenitalanomali].value');
$sourceCountry = Hash::extract($data, '{n}[key=occurcountry].value');

// Get first value or null if not found
$resultsInDeath = !empty($resultsInDeath) ? $resultsInDeath[0] : null;
$lifeThreateningValue = !empty($lifeThreateningValue) ? $lifeThreateningValue[0] : null;
$prolongedHospitalizationValue = !empty($prolongedHospitalizationValue) ? $prolongedHospitalizationValue[0] : null;
$incapacitatingValue = !empty($incapacitatingValue) ? $incapacitatingValue[0] : null;
$birthDefectValue = !empty($birthDefectValue) ? $birthDefectValue[0] : null;
$sourceCountry = !empty($sourceCountry) ? $sourceCountry[0] : null;

// Debugging output
// debug([
//     'results_in_death' => $resultsInDeath,
//     'life_threatening' => $lifeThreateningValue,
//     'prolonged_hospitalization' => $prolongedHospitalizationValue,
//     'incapacitating' => $incapacitatingValue,
//     'birth_defect' => $birthDefectValue,
//     'source_country' => $sourceCountry
// ]);
// exit;
            // ----------------- UPDATE REACTIONS -----------------
           
            if (!empty($resultsInDeath) && isset($ce2b['Ce2bReaction'])) {
                foreach ($ce2b['Ce2bReaction'] as $reaction) {
                    $reactionId = $reaction['id']; // Get reaction ID

                    // Update only the `criteria_death_value` field for the specific reaction
                    $this->Ce2b->Ce2bReaction->updateAll(
                        [
                            'Ce2bReaction.criteria_death_value' => "'" . $resultsInDeath . "'",
                            'Ce2bReaction.life_threatening_value' => "'" . $lifeThreateningValue . "'",
                            'Ce2bReaction.prolonged_hospitalisation_value' => "'" . $prolongedHospitalizationValue . "'",
                            'Ce2bReaction.incapacitating_value' => "'" . $incapacitatingValue . "'",
                            'Ce2bReaction.birth_defect_value' => "'" . $birthDefectValue . "'",
                            'Ce2bReaction.source_country' => "'" . $sourceCountry . "'"
                        ],
                        ['Ce2bReaction.id' => $reactionId] // Where condition: update all reactions by ID
                    );
                    
                }
            }

            $e2b = Xml::toArray(Xml::build($ce2b['Ce2b']['e2b_content']));

            $ce2b['Ce2b']['creation_time'] = $this->generateDesiredDate($ce2b['Ce2b']['creation_time']);
            $ce2b['Ce2b']['date_first_received'] = $this->generateDesiredDate($ce2b['Ce2b']['date_first_received']);
            $this->set(['ce2b' => $ce2b]);
            $this->set(['e2b' => $e2b]);
        } else {

            // Manipulate data retrived:

            // Convert to Unix timestamp (optional step)

            $ce2b['Ce2b']['creation_time'] = $this->generateDesiredDate($ce2b['Ce2b']['creation_time']);
            $ce2b['Ce2b']['date_first_received'] = $this->generateDesiredDate($ce2b['Ce2b']['date_first_received']);
            $this->set(['ce2b' => $ce2b]);
            // $this->set(['e2b' => $e2b]);
            $this->render('ce2b_r3');
        }

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'E2b' . $id . '.pdf',  'orientation' => 'portrait');
            $this->response->download('Ce2b_' . $ce2b['Ce2b']['id'] . '.pdf');
        }
    }
    public function reporter_view($id = null)
    {
        # code...
        $this->Ce2b->id = $id;
        if (!$this->Ce2b->exists()) {
            $this->Session->setFlash(__('Could not verify the E2b report ID. Please ensure the ID is correct.'), 'flash_error');
            $this->redirect('/');
        }
        $this->general_view($id);
    }

    public function reporter_delete($id = null)
    {
        # code...
        $this->common_delete($id);
    }

    public function common_delete($id = null)
    {
        # code...
        $this->Ce2b->id = $id;
        if (!$this->Ce2b->exists()) {
            throw new NotFoundException(__('Invalid Ce2b'));
        }
        $ce2b = $this->Ce2b->read(null, $id);
        if ($ce2b['Ce2b']['submitted'] == 2) {
            $this->Session->setFlash(__('You cannot delete a submitted E2b Report'), 'alerts/flash_error');
            $this->redirect($this->referer());
        }
        //update the field deleted to true and deleted_date to current date without validation 
        $ce2b['Ce2b']['deleted'] = true;
        $ce2b['Ce2b']['deleted_date'] = date("Y-m-d H:i:s");
        if ($this->Ce2b->save($ce2b, array('validate' => false))) {
            //displat message with reference number 
            $this->Session->setFlash(__('Ce2b Report ' . $ce2b['Ce2b']['reference_no'] . ' has been deleted'), 'alerts/flash_info');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('E2b was not deleted'), 'alerts/flash_error');
        $this->redirect($this->referer());
    }
    public function manager_archive($id = null)
    {

        $this->Ce2b->id = $id;
        if (!$this->Ce2b->exists()) {
            throw new NotFoundException(__('Invalid E2B'));
        }
        $report = $this->Ce2b->read(null, $id);
        $report['Ce2b']['archived'] = true;
        $report['Ce2b']['archived_date'] = date("Y-m-d H:i:s");
        if ($this->Ce2b->save($report, array('validate' => false))) {
            $this->Session->setFlash(__('E2B Archived successfully'), 'alerts/flash_success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('E2B was not archied'), 'alerts/flash_error');
        $this->redirect($this->referer());
    }
    public function manager_restore_archive($id = null)
    {

        $this->Ce2b->id = $id;
        if (!$this->Ce2b->exists()) {
            throw new NotFoundException(__('Invalid E2B'));
        }
        $report = $this->Ce2b->read(null, $id);
        $report['Ce2b']['archived'] = 0;
        // $report['Ce2b']['archived_date'] = date("Y-m-d H:i:s");
        if ($this->Ce2b->save($report, array('validate' => false))) {
            $this->Session->setFlash(__('E2B Archive Restored successfully'), 'alerts/flash_success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('E2B was not restored'), 'alerts/flash_error');
        $this->redirect($this->referer());
    }
}
