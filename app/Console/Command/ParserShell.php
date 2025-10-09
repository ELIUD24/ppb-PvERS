<?php
App::uses('Sanitize', 'Utility');
App::uses('Router', 'Routing');
config('routes');
App::uses('Shell', 'Console');
App::uses('AppModel', 'Model');
App::uses('CakeEmail', 'Network/Email');

class ParserShell extends AppShell
{

    public $uses = ['Ce2b', 'Ce2bListOfDrug', 'Ce2bReaction'];
 

    public function main()
    {
        $ce2bId = isset($this->args[0]) ? $this->args[0] : null;
        $fallbackFilePath = isset($this->args[1]) ? $this->args[1] : null;

        if (!$ce2bId) {
            $this->out("Usage: cake ParseCe2b <ce2b_id> [fallback_file_path]");
            return;
        }

        $this->out("Processing Ce2b ID: $ce2bId");

        $ce2b = $this->Ce2b->find('first', array(
            'conditions' => array('Ce2b.id' => $ce2bId),
            'fields' => array('Ce2b.id', 'Ce2b.e2b_content')
        ));

        if (empty($ce2b)) {
            $this->out("Ce2b record not found.");
            return;
        }

        $xmlRaw = trim($ce2b['Ce2b']['e2b_content']);
        if (empty($xmlRaw)) {
            if (!empty($fallbackFilePath) && file_exists($fallbackFilePath)) {
                $this->out("e2b_content is empty — reading from file: $fallbackFilePath");
                $xmlRaw = file_get_contents($fallbackFilePath);
            } else {
                $this->out("No e2b_content in DB and no valid fallback file provided.");
                return;
            }
        }

        $xmlRaw = $this->removeUtf8Bom($xmlRaw);
        $xml = simplexml_load_string($xmlRaw, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!$xml) {
            $this->out("Failed to parse XML.");
            return;
        }

        $xml->registerXPathNamespace('hl7', 'urn:hl7-org:v3');

        $data = $this->extractData($xml);
        $this->out("Extracted Data: " . print_r($data, true));
        // Save patient + narrative
        $this->Ce2b->id = $ce2bId;
        $this->Ce2b->save(array(
            'patient_name' => isset($data['patient']['name']) ? $data['patient']['name'] : null,
            'patient_sex' => isset($data['patient']['gender']) ? $data['patient']['gender'] : null,
            'patient_dob' => isset($data['patient']['birthDate']) ? $data['patient']['birthDate'] : null,
            'medical_record_number' => isset($data['patient']['medical_record_number']) ? $data['patient']['medical_record_number'] : null,
            'case_narrative' => isset($data['narrative']) ? $data['narrative'] : null,
            'worldwide_identifier' => isset($data['wuci']) ? $data['wuci'] : null,
            'sender_unique_identifier' => isset($data['sender_unique_identifier']) ? $data['sender_unique_identifier'] : null,
            'sender_identifier' => isset($data['sender_identifier']) ? $data['sender_identifier'] : null,
            'sender_oid' => isset($data['sender_oid']) ? $data['sender_oid'] : null,
            'sender_software' => isset($data['sender_software']) ? $data['sender_software'] : null,
            'sender_organization' => isset($data['sender_organization']) ? $data['sender_organization'] : null,
            'sender_org_id' => isset($data['sender_org_id']) ? $data['sender_org_id'] : null,

            'parsed' => 1,
            'status' => 'PROCESSED',
        ), array('validate' => false, 'deep' => true));

        // Save drugs
        // if (!empty($data['drugs'])) {
        //     foreach ($data['drugs'] as &$drug) {
        //         $drug['ce2b_id'] = $ce2bId;
        //     }
        //     $this->Ce2bListOfDrug->deleteAll(array('Ce2bListOfDrug.ce2b_id' => $ce2bId), false);
        //     $this->Ce2bListOfDrug->saveAll($data['drugs']);
        //     $this->out(count($data['drugs']) . " drugs saved.");
        // }

        // Save reactions
        // if (!empty($data['reactions'])) {
        //     foreach ($data['reactions'] as &$reaction) {
        //         $reaction['ce2b_id'] = $ce2bId;
        //     }
        //     $this->Ce2bReaction->deleteAll(array('Ce2bReaction.ce2b_id' => $ce2bId), false);
        //     $this->Ce2bReaction->saveAll($data['reactions']);
        //     $this->out(count($data['reactions']) . " reactions saved.");
        // }

        $this->out("Ce2b #$ce2bId fully parsed and saved.");
    }

    private function removeUtf8Bom($text)
    {
        $bom = pack('H*', 'EFBBBF');
        if (strncmp($text, $bom, 3) === 0) {
            $text = substr($text, 3);
        }
        return preg_replace('/^\xC3\xAF\xC2\xBB\xC2\xBF/', '', $text);
    }

    private function extractData($xml)
    {
        $data = array('patient' => array(), 'drugs' => array(), 'reactions' => array(), 'narrative' => '');

        // 🔹 Patient
        $playerNode = $xml->xpath('//hl7:subject/hl7:investigationEvent/hl7:component/hl7:adverseEventAssessment/hl7:subject1/hl7:primaryRole/hl7:player1');
        if (!empty($playerNode[0])) {
            $data['patient']['name'] = (string)$playerNode[0]->name;
            $data['patient']['gender'] = (string)$playerNode[0]->administrativeGenderCode['code'];
            $data['patient']['birthDate'] = (string)$playerNode[0]->birthTime['value'];
        }

        // 🔹 Sender Information
        $senderNode = $xml->xpath('//hl7:sender/hl7:device');
        if (!empty($senderNode[0])) {
            $device = $senderNode[0];

            $senderId = $device->xpath('hl7:id');
            $senderSoftware = $device->xpath('hl7:softwareName');
            $orgName = $device->xpath('hl7:asAgent/hl7:representedOrganization/hl7:name');
            $orgId = $device->xpath('hl7:asAgent/hl7:representedOrganization/hl7:id');

            $data['sender_identifier'] = isset($senderId[0]['extension']) ? (string)$senderId[0]['extension'] : null;
            $data['sender_oid'] = isset($senderId[0]['root']) ? (string)$senderId[0]['root'] : null;
            $data['sender_software'] = isset($senderSoftware[0]) ? (string)$senderSoftware[0] : null;
            $data['sender_organization'] = isset($orgName[0]) ? (string)$orgName[0] : null;
            $data['sender_org_id'] = isset($orgId[0]['extension']) ? (string)$orgId[0]['extension'] : null;
        }


        // 🔹 Worldwide Unique Case Identification
        $caseIdNode = $xml->xpath('//hl7:subject/hl7:investigationEvent/hl7:id');
        if (!empty($caseIdNode[0])) {
            $data['wuci'] = isset($caseIdNode[0]['extension']) ? (string)$caseIdNode[0]['extension'] : null;
        }
        // 🔹 Sender Identifier
        $senderIdNode = $xml->xpath('//hl7:sender/hl7:device/hl7:id');
        if (!empty($senderIdNode[0])) {
            $data['sender_unique_identifier'] = isset($senderIdNode[0]['extension']) ? (string)$senderIdNode[0]['extension'] : null;
        }

        // 🔹 Narrative
        $narrativeNode = $xml->xpath('//hl7:investigationEvent/hl7:text');
        if (!empty($narrativeNode[0])) {
            $data['narrative'] = (string)$narrativeNode[0];
        }

        // 🔹 Drugs
        $xml->registerXPathNamespace('hl7', 'urn:hl7-org:v3');

        $drugNodes = $xml->xpath('//hl7:substanceAdministration');
        
        foreach ($drugNodes as $adminNode) {
            $adminNode->registerXPathNamespace('hl7', 'urn:hl7-org:v3');
        
            // Set default values
            $drug = array(
                'drug_name' => null,
                'brand_name' => null,
                'batch' => null,
                'dose' => null,
                'route_code' => null,
                'route_name' => null
            );
        
            // 🔹 dose
            if (isset($adminNode->doseQuantity['value'])) {
                $drug['dose'] = (string)$adminNode->doseQuantity['value'];
            }
        
            // 🔹 route
            if (isset($adminNode->routeCode['code'])) {
                $drug['route_code'] = (string)$adminNode->routeCode['code'];
            }
            if (isset($adminNode->routeCode['displayName'])) {
                $drug['route_name'] = (string)$adminNode->routeCode['displayName'];
            }
        
            // 🔹 instanceOfKind (get drug details)
            $instanceOfKind = $adminNode->xpath('hl7:consumable/hl7:instanceOfKind');
            if (!empty($instanceOfKind) && isset($instanceOfKind[0])) {
                $instance = $instanceOfKind[0];
        
                if (isset($instance->kindOfProduct->name)) {
                    $drug['drug_name'] = (string)$instance->kindOfProduct->name;
                }
        
                if (isset($instance->kindOfProduct->ingredient->ingredientSubstance->name)) {
                    $drug['brand_name'] = (string)$instance->kindOfProduct->ingredient->ingredientSubstance->name;
                }
        
                if (isset($instance->productInstanceInstance->lotNumberText)) {
                    $drug['batch'] = (string)$instance->productInstanceInstance->lotNumberText;
                }
            }
        
            // Only save if there's at least a drug name or brand
            if (!empty($drug['drug_name']) || !empty($drug['brand_name'])) {
                $data['drugs'][] = $drug;
            }
        }
        
         

        // 🔹 Reactions
        $reactionNodes = $xml->xpath('//hl7:observation[hl7:code[@code="29"]]');
        foreach ($reactionNodes as $node) {
            $term = $node->xpath('hl7:outboundRelationship2/hl7:observation/hl7:value');

            $start = $node->xpath('hl7:effectiveTime/hl7:low');
            $end = $node->xpath('hl7:effectiveTime/hl7:high');
            $outcome = $node->xpath('hl7:interpretationCode');
            $outcome = $node->xpath('hl7:interpretationCode');
            $outcomeCode = isset($outcome[0]['code']) ? (string)$outcome[0]['code'] : null;


            $data['reactions'][] = array(
                'meddra_code' => (string)$node->value['code'],
                'reported_term' => isset($term[0]) ? (string)$term[0] : '',
                'start_date' => isset($start[0]['value']) ? (string)$start[0]['value'] : null,
                'end_date' => isset($end[0]['value']) ? (string)$end[0]['value'] : null,
                'reaction_outcome_value' => $outcomeCode, 
            );
        }


        return $data;
    }
}
