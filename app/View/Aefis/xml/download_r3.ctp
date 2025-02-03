<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; echo "\n"; ?>
<MCCI_IN200100UV01 ITSVersion="XML_1.0" xsi:schemaLocation="urn:hl7-org:v3 http://eudravigilance.ema.europa.eu/XSD/multicacheschemas/MCCI_IN200100UV01.xsd" xmlns="urn:hl7-org:v3" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:mif="urn:hl7-org:v3/mif">
  <!--N.1.2: Batch Number-->
  <id extension="11917722_8P1" root="2.16.840.1.113883.3.989.2.1.3.22" />
  <!--N.1.5: Date of Batch Transmission-->
  <creationTime value="<?php echo date('YmdHis');?>" />
  <responseModeCode code="D" />
  <interactionId root="2.16.840.1.113883.1.6" extension="MCCI_IN200100UV01" />
  <!--N.1.1: Type of Messages in Batch-->
  <name code="1" codeSystem="2.16.840.1.113883.3.989.2.1.1.1" codeSystemVersion="2.0" />
  <!--N.2.r: ICH ICSR MESSAGE Header - (1)-->
  <PORR_IN049016UV> 
    <!--N.2.r.1: Message Identifier-->
    <id extension="KE-PPB-<?php  echo date('Y').'-'.$aefi['Aefi']['id'] ?>" root="2.16.840.1.113883.3.989.2.1.3.1" />
    <!--N.2.r.4: Date of Message Creation-->
    <creationTime value="<?php echo date('YmdHis');?>" />
    <interactionId root="2.16.840.1.113883.1.6" extension="PORR_IN049016UV" />
    <processingCode code="P" />
    <processingModeCode code="T" />
    <acceptAckCode code="AL" />
    <!--N.2.r.3: Message Receiver Identifier-->
    <receiver typeCode="RCV">
      <device classCode="DEV" determinerCode="INSTANCE">
        <id extension="PPB" root="2.16.840.1.113883.3.989.2.1.3.12" />
      </device>
    </receiver>
    <!--N.2.r.2: Message Sender Identifier-->
    <sender typeCode="SND">
      <device classCode="DEV" determinerCode="INSTANCE">
        <id extension="Pharmacy and Poisons Board" root="2.16.840.1.113883.3.989.2.1.3.11" />
      </device>
    </sender>
    <controlActProcess classCode="CACT" moodCode="EVN">
      <code code="PORR_TE049016UV" codeSystem="2.16.840.1.113883.1.18" />
      <!--C.1.2: Date of Creation-->
      <effectiveTime value="<?php echo date('YmdHis');?>" />
      <!--C.1: Identification of the Case Safety Report -->
      <subject typeCode="SUBJ">
        <investigationEvent classCode="INVSTG" moodCode="EVN">
          <!--C.1.1: Sender's (case) Safety Report Unique Identifier  -->
          <id root="2.16.840.1.113883.3.989.2.1.3.1" extension="KE-PPB-<?php echo $aefi['Aefi']['reference_no'];?>" />
          <!--C.1.8.1: Worldwide Unique Case Identification Number  -->
          <id root="2.16.840.1.113883.3.989.2.1.3.2" extension="KE-PPB-<?php echo $aefi['Aefi']['reference_no'];?>" />
          <!--H.1: Case Narrative Including Clinical Course, Therapeutic Measures, Outcome and Additional Relevant Information  -->
          <code code="PAT_ADV_EVNT" codeSystem="2.16.840.1.113883.5.4" />
          <text><?php echo $aefi['Aefi']['description_of_reaction']; ?></text>
          <statusCode code="active" />
          <!--C.1.4: Date Report Was First Received from Source  -->
          <effectiveTime>
            <low value="<?php echo $aefi['Aefi']['submitted_date']; ?>" /> 
          </effectiveTime>
          <!--C.1.5: Date of receipt of the most recent information for this report  -->
          <availabilityTime value="<?php echo $aefi['Aefi']['submitted_date']; ?>" />
          <!--D: Patient Characteristics-->
          <component typeCode="COMP">
            <adverseEventAssessment classCode="INVSTG" moodCode="EVN">
              <subject1 typeCode="SBJ">
                <primaryRole classCode="INVSBJ">
                  <player1 classCode="PSN" determinerCode="INSTANCE">
                    <!--D.1: Patient (name or initials)-->
                    <name><?php echo $aefi['Aefi']['patient_name']?></name>
                    <!--D.5: Patient Sex-->
                    <administrativeGenderCode code="<?php if($aefi['Aefi']['gender'] == 'Male') echo 1 ;
                elseif($aefi['Aefi']['gender'] == 'Female') echo 2 ;?>" codeSystem="1.0.5218" />
                  </player1>
                  <!--china extention start-->
                  <!--china extention end-->
                  <subjectOf2 typeCode="SBJ">
                    <organizer classCode="CATEGORY" moodCode="EVN">
                      <code code="1" codeSystem="2.16.840.1.113883.3.989.2.1.1.20" codeSystemVersion="2.0" />
                      <!--D.7.1.r: Relevant Medical History and Concurrent Conditions (not including reaction / event) - (1)-->
                      <!-- <component typeCode="COMP"> -->
                        <!-- <observation moodCode="EVN" classCode="OBS"> -->
                          <!--D.7.1.r.1a: MedDRA Version for Medical History-->
                          <!--D.7.1.r.1b: Medical History (disease / surgical procedure / etc.) (MedDRA code)-->
                          <!-- <code code="10046784" codeSystemVersion="26.1" codeSystem="2.16.840.1.113883.6.163" /> -->
                          <!-- <effectiveTime xsi:type="IVL_TS"> -->
                            <!--D.7.1.r.2: Start Date-->
                            <!-- <low value="2020" /> -->
                          <!-- </effectiveTime> -->
                        <!-- </observation> -->
                      <!-- </component>    -->
                    </organizer>
                  </subjectOf2>
                  <!--E.i: REACTION(S)/EVENT(S) - (1)-->
                  <subjectOf2 typeCode="SBJ">
                    <observation moodCode="EVN" classCode="OBS">
                      <!--Reaction/event [reaction/event reference ID]-->
                      <id root="0eaf867a-6c0b-df9e-e063-b5791e0a2df1" />
                      <code code="29" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
                      <effectiveTime xsi:type="IVL_TS">
                        <!--E.i.4: Date of start of reaction/event-->
                        <low value="<?php echo date('Ymd', strtotime($aefi['Aefi']['date_aefi_started']));?>" />
                      </effectiveTime>
                      <!--E.i.2.1a: MedDRA Version for Reaction / Event-->
                      <!--E.i.2.1b: Reaction / Event (MedDRA code)-->
                      <value xsi:type="CE" codeSystem="2.16.840.1.113883.6.163" code="10046784" codeSystemVersion="26.1" />
                      <!-- E.i.9: Identification of the Country Where the Reaction / Event Occurred -->
                      <location typeCode="LOC">
                        <locatedEntity classCode="LOCE">
                          <locatedPlace classCode="COUNTRY" determinerCode="INSTANCE">
                            <code code="KE" codeSystem="1.0.3166.1.2.2" />
                          </locatedPlace>
                        </locatedEntity>
                      </location>
                      <!-- E.i.1.2: Reaction / Event as Reported by the Primary Source for Translation -->
                      <outboundRelationship2 typeCode="PERT">
                        <observation moodCode="EVN" classCode="OBS">
                          <code code="30" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
                          <value xsi:type="ED">Fibroids growth was found to have doubled</value>
                        </observation>
                      </outboundRelationship2>
                      <!--E.i.3.2a: Results in Death -->
                      <outboundRelationship2 typeCode="PERT">
                        <observation moodCode="EVN" classCode="OBS">
                          <code code="34" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
                          <value xsi:type="BL" nullFlavor="NI" />
                        </observation>
                      </outboundRelationship2>
                      <!--E.i.3.2b: Life Threatening -->
                      <outboundRelationship2 typeCode="PERT">
                        <observation moodCode="EVN" classCode="OBS">
                          <code code="21" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
                          <value xsi:type="BL" nullFlavor="NI" />
                        </observation>
                      </outboundRelationship2>
                      <!--E.i.3.2c: Caused / Prolonged Hospitalisation -->
                      <outboundRelationship2 typeCode="PERT">
                        <observation moodCode="EVN" classCode="OBS">
                          <code code="33" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
                          <value xsi:type="BL" nullFlavor="NI" />
                        </observation>
                      </outboundRelationship2>
                      <!--E.i.3.2d: Disabling / Incapacitating -->
                      <outboundRelationship2 typeCode="PERT">
                        <observation moodCode="EVN" classCode="OBS">
                          <code code="35" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
                          <value xsi:type="BL" nullFlavor="NI" />
                        </observation>
                      </outboundRelationship2>
                      <!--E.i.3.2e: Congenital Anomaly / Birth Defect -->
                      <outboundRelationship2 typeCode="PERT">
                        <observation moodCode="EVN" classCode="OBS">
                          <code code="12" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
                          <value xsi:type="BL" nullFlavor="NI" />
                        </observation>
                      </outboundRelationship2>
                      <!--E.i.3.2f: Other Medically Important Condition  -->
                      <outboundRelationship2 typeCode="PERT">
                        <observation moodCode="EVN" classCode="OBS">
                          <code code="26" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
                          <value xsi:type="BL" nullFlavor="NI" />
                        </observation>
                      </outboundRelationship2>
                      <!--E.i.7: Outcome of Reaction / Event at the Time of Last Observation  -->
                      <outboundRelationship2 typeCode="PERT">
                        <observation moodCode="EVN" classCode="OBS">
                          <code code="27" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
                          <value xsi:type="CE" code="3" codeSystem="2.16.840.1.113883.3.989.2.1.1.11" codeSystemVersion="2.0" />
                        </observation>
                      </outboundRelationship2>
                      <!--E.i.8: Medical Confirmation by Healthcare Professional  -->
                      <outboundRelationship2 typeCode="PERT">
                        <observation moodCode="EVN" classCode="OBS">
                          <code code="24" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
                          <value xsi:type="BL" value="false" />
                        </observation>
                      </outboundRelationship2>
                    </observation>
                  </subjectOf2> 
                  <subjectOf2 typeCode="SBJ">
                    <organizer classCode="CATEGORY" moodCode="EVN">
                      <code code="4" codeSystem="2.16.840.1.113883.3.989.2.1.1.20" codeSystemVersion="2.0" />
                      <!--G.k: DRUG(S) INFORMATION - (1)-->
                      <component typeCode="COMP">
                        <substanceAdministration moodCode="EVN" classCode="SBADM">
                          <!--G.k[GID]: Drug UUID-->
                          <id root="0eaf867a-6c0c-df9e-e063-b5791e0a2df1" />
                          <consumable typeCode="CSM">
                            <instanceOfKind classCode="INST">
                              <kindOfProduct classCode="MMAT" determinerCode="KIND">
                                <!--G.k.2.2: Medicinal Product Name as Reported by the Primary Source-->
                                <name>Visanne</name>
                                <asManufacturedProduct classCode="MANU">
                                  <!--G.k.3: Holder and Authorisation / Application Number of Drug -->
                                  <subjectOf typeCode="SBJ">
                                    <approval classCode="CNTRCT" moodCode="EVN">
                                      <!--G.k.3.1: Authorisation / Application Number-->
                                      <id extension="H2014/CTD1052/019" root="2.16.840.1.113883.3.989.2.1.3.4" />
                                      <holder typeCode="HLD">
                                        <role classCode="HLD">
                                          <!--G.k.3.3: Name of Holder / Applicant-->
                                          <playingOrganization classCode="ORG" determinerCode="INSTANCE">
                                            <name>PPB AG</name>
                                          </playingOrganization>
                                        </role>
                                      </holder>
                                      <author typeCode="AUT">
                                        <territorialAuthority classCode="TERR">
                                          <territory classCode="NAT" determinerCode="INSTANCE">
                                            <!--G.k.3.2: Country of Authorisation / Application-->
                                            <code codeSystem="1.0.3166.1.2.2" code="KE" />
                                          </territory>
                                        </territorialAuthority>
                                      </author>
                                    </approval>
                                  </subjectOf>
                                </asManufacturedProduct>
                                <!--G.k.2.3.r: Substance / Specified Substance Identifier and Strength - (1)-->
                                <ingredient classCode="ACTI">
                                  <quantity>
                                    <!--G.k.2.3.r.3a: Strength (number)-->
                                    <!--G.k.2.3.r.3b: Strength (unit)-->
                                    <numerator value="2" unit="mg" />
                                    <denominator value="1" />
                                  </quantity>
                                  <ingredientSubstance classCode="MMAT" determinerCode="KIND">
                                    <!--G.k.2.3.r.2a: Substance / Specified Substance TermID Version Date / Number -->
                                    <!--G.k.2.3.r.2b: Substance / Specified Substance TermID -->
                                    <!--G.k.2.3.r.1: Substance / Specified Substance Name-->
                                    <name>DIENOGEST</name>
                                  </ingredientSubstance>
                                </ingredient>
                              </kindOfProduct>
                              <!--G.k.2.4: Identification of the Country Where the Drug Was Obtained-->
                              <subjectOf typeCode="SBJ">
                                <productEvent classCode="ACT" moodCode="EVN">
                                  <code code="1" codeSystem="2.16.840.1.113883.3.989.2.1.1.18" codeSystemVersion="2.0" />
                                  <performer typeCode="PRF">
                                    <assignedEntity classCode="ASSIGNED">
                                      <representedOrganization classCode="ORG" determinerCode="INSTANCE">
                                        <addr>
                                          <country>KE</country>
                                        </addr>
                                      </representedOrganization>
                                    </assignedEntity>
                                  </performer>
                                </productEvent>
                              </subjectOf>
                            </instanceOfKind>
                          </consumable>
                          <!--G.k.4.r: Dosage and Relevant Information  - (1)-->
                          <outboundRelationship2 typeCode="COMP">
                            <substanceAdministration classCode="SBADM" moodCode="EVN">
                              <!--G.k.4.r.8: Dosage Text-->
                              <text>UNK, QD</text>
                              <!--G.k.4.r.4: Date and Time of Start of Drug-->
                              <effectiveTime xsi:type="SXPR_TS">
                                <comp xsi:type="PIVL_TS">
                                  <!--G.k.4.r.2: Number of Units in the Interval  -->
                                  <!--G.k.4.r.3: Definition of the Time Interval Unit -->
                                  <period value="1" unit="d" />
                                </comp>
                                <comp xsi:type="IVL_TS" operator="A">
                                  <low value="202307" />
                                </comp>
                              </effectiveTime>
                              <consumable typeCode="CSM">
                                <instanceOfKind classCode="INST">
                                  <productInstanceInstance classCode="MMAT" determinerCode="INSTANCE">
                                    <id nullFlavor="NI" />
                                    <!--G.k.4.r.7: Batch / Lot Number-->
                                    <lotNumberText nullFlavor="UNK" />
                                  </productInstanceInstance>
                                  <kindOfProduct classCode="MMAT" determinerCode="KIND">
                                    <!--G.k.4.r.9.1: Pharmaceutical form (Dosage form)-->
                                    <!--G.k.4.r.9.2a: Pharmaceutical Dose Form TermID Version Date / Number -->
                                    <!--G.k.4.r.9.2b: Pharmaceutical Dose Form TermID -->
                                    <formCode code="PDF-10219000" codeSystem="0.4.0.127.0.16.1.1.2.1" codeSystemVersion="1">
                                      <originalText>Tablet</originalText>
                                    </formCode>
                                  </kindOfProduct>
                                </instanceOfKind>
                              </consumable>
                            </substanceAdministration>
                          </outboundRelationship2>
                          <!--china extention start-->
                          <!--china extention end-->
                          <!--G.k.7.r: Indication for Use in Case - (1)-->
                          <inboundRelationship typeCode="RSON">
                            <observation moodCode="EVN" classCode="OBS">
                              <code code="19" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
                              <!--G.k.7.r.2a: MedDRA Version for Indication-->
                              <!--G.k.7.r.2b: Indication (MedDRA code)-->
                              <!--G.k.7.r.1: Indication as Reported by the Primary Source-->
                              <value xsi:type="CE" code="10046784" codeSystem="2.16.840.1.113883.6.163" codeSystemVersion="26.1">
                                <originalText>Uterine fibroids</originalText>
                              </value>
                              <performer typeCode="PRF">
                                <assignedEntity classCode="ASSIGNED">
                                  <code code="3" codeSystem="2.16.840.1.113883.3.989.2.1.1.21" codeSystemVersion="2.0" displayName="sourceReporter" />
                                </assignedEntity>
                              </performer>
                              <outboundRelationship1 typeCode="REFR">
                                <!--G.k[GID]: Drug UUID-->
                                <actReference classCode="SBADM" moodCode="EVN">
                                  <id root="0eaf867a-6c0c-df9e-e063-b5791e0a2df1" />
                                </actReference>
                              </outboundRelationship1>
                            </observation>
                          </inboundRelationship>
                        </substanceAdministration>
                      </component>
                    </organizer>
                  </subjectOf2>
                </primaryRole>
              </subject1>
              <!--G.k.1: Characterization of Drug Role-->
              <component typeCode="COMP">
                <causalityAssessment classCode="OBS" moodCode="EVN">
                  <code code="20" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
                  <value xsi:type="CE" code="1" codeSystem="2.16.840.1.113883.3.989.2.1.1.13" codeSystemVersion="2.0" />
                  <!--G.k[GID]: Drug UUID-->
                  <subject2 typeCode="SUBJ">
                    <productUseReference classCode="SBADM" moodCode="EVN">
                      <id root="0eaf867a-6c0c-df9e-e063-b5791e0a2df1" />
                    </productUseReference>
                  </subject2>
                </causalityAssessment>
              </component>
              <!--EU Reference instance  - EU Causality assessment - (1)-->
              <component typeCode="COMP">
                <causalityAssessment classCode="OBS" moodCode="EVN">
                  <code code="39" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
                  <!--G.k.9.i.2.r.3.EU.1: Result of Assessment - captured in the code field-->
                  <value xsi:type="CE" code="1" codeSystem="2.16.840.1.113883.3.989.5.1.1.5.3" codeSystemVersion="1.0" />
                  <!--G.k.9.i.2.r.2.EU.1: EU Method of assessment - captured in the code field-->
                  <methodCode code="1" codeSystem="2.16.840.1.113883.3.989.5.1.1.5.2" codeSystemVersion="1.0" />
                  <author typeCode="AUT">
                    <assignedEntity classCode="ASSIGNED">
                      <!--G.k.9.i.2.r.1.EU.1: EU Source of assessment - captured in the code field-->
                      <code code="6" codeSystem="2.16.840.1.113883.3.989.5.1.1.5.4" codeSystemVersion="1.0" />
                    </assignedEntity>
                  </author>
                  <!--G.k.9.i.1: Reaction(s) / Event(s) Assessed-->
                  <subject1 typeCode="SUBJ">
                    <adverseEffectReference classCode="OBS" moodCode="EVN">
                      <id root="0eaf867a-6c0b-df9e-e063-b5791e0a2df1" />
                    </adverseEffectReference>
                  </subject1>
                  <!--Reference to Drug-->
                  <subject2 typeCode="SUBJ">
                    <productUseReference classCode="SBADM" moodCode="EVN">
                      <id root="0eaf867a-6c0c-df9e-e063-b5791e0a2df1" />
                    </productUseReference>
                  </subject2>
                </causalityAssessment>
              </component>    
              <!--G.k.9.i.2.r: Relatedness of Drug to Reaction(s) / Event(s) - (1)-->
              <component typeCode="COMP">
                <causalityAssessment classCode="OBS" moodCode="EVN">
                  <code code="39" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
                  <!--G.k.9.i.2.r.3: Result of Assessment-->
                  <value xsi:type="ST">not reported</value>
                  <!--G.k.9.i.2.r.2: Method of Assessment-->
                  <methodCode>
                    <originalText>Global Introspection</originalText>
                  </methodCode>
                  <author typeCode="AUT">
                    <assignedEntity classCode="ASSIGNED">
                      <code>
                        <!--G.k.9.i.2.r.1: Source of Assessment-->
                        <originalText>PRIMARY SOURCE REPORTER</originalText>
                      </code>
                    </assignedEntity>
                  </author>
                  <!--G.k.9.i.1: Reaction(s) / Event(s) Assessed-->
                  <subject1 typeCode="SUBJ">
                    <adverseEffectReference classCode="OBS" moodCode="EVN">
                      <id root="0eaf867a-6c0b-df9e-e063-b5791e0a2df1" />
                    </adverseEffectReference>
                  </subject1>
                  <!--Reference to Drug-->
                  <subject2 typeCode="SUBJ">
                    <productUseReference classCode="SBADM" moodCode="EVN">
                      <id root="0eaf867a-6c0c-df9e-e063-b5791e0a2df1" />
                    </productUseReference>
                  </subject2>
                </causalityAssessment>
              </component> 
            </adverseEventAssessment>
          </component>
          <!--C.1.6.1: Are Additional Documents Available?  -->
          <component typeCode="COMP">
            <observationEvent classCode="OBS" moodCode="EVN">
              <code code="1" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
              <value xsi:type="BL" value="false" />
            </observationEvent>
          </component>
          <!--C.1.7: Does This Case Fulfil the Local Criteria for an Expedited Report?-->
          <component typeCode="COMP">
            <observationEvent classCode="OBS" moodCode="EVN">
              <code code="23" codeSystem="2.16.840.1.113883.3.989.2.1.1.19" codeSystemVersion="2.0" />
              <value xsi:type="BL" value="true" />
            </observationEvent>
          </component>
          <!--C.1.8.2: First Sender of This Case  -->
          <outboundRelationship typeCode="SPRT">
            <relatedInvestigation classCode="INVSTG" moodCode="EVN">
              <code code="1" codeSystem="2.16.840.1.113883.3.989.2.1.1.22" codeSystemVersion="2.0" />
              <subjectOf2 typeCode="SUBJ">
                <controlActEvent classCode="CACT" moodCode="EVN">
                  <author typeCode="AUT">
                    <assignedEntity classCode="ASSIGNED">
                      <code code="2" codeSystem="2.16.840.1.113883.3.989.2.1.1.3" codeSystemVersion="2.0" />
                    </assignedEntity>
                  </author>
                </controlActEvent>
              </subjectOf2>
            </relatedInvestigation>
          </outboundRelationship>
          <!--C.2.r: Primary Source(s) of Information - (1)-->
          <outboundRelationship typeCode="SPRT">
            <!--C.2.r.5: Primary Source for Regulatory Purposes-->
            <priorityNumber value="1" />
            <relatedInvestigation classCode="INVSTG" moodCode="EVN">
              <code code="2" codeSystem="2.16.840.1.113883.3.989.2.1.1.22" codeSystemVersion="2.0" />
              <subjectOf2 typeCode="SUBJ">
                <controlActEvent classCode="CACT" moodCode="EVN">
                  <author typeCode="AUT">
                    <assignedEntity classCode="ASSIGNED">
                      <addr>
                        <!--C.2.r.2.5: Reporter's State or Province-->
                        <state nullFlavor="MSK" />
                      </addr>
                      <assignedPerson classCode="PSN" determinerCode="INSTANCE">
                        <name>
                          <!--C.2.r.1.2: Reporter's Given Name-->
                          <given nullFlavor="MSK" />
                          <!--C.2.r.1.4: Reporter's Family Name-->
                          <family nullFlavor="MSK" />
                        </name>
                        <!--C.2.r.4: Qualification-->
                        <asQualifiedEntity classCode="QUAL">
                          <code code="5" codeSystem="2.16.840.1.113883.3.989.2.1.1.6" codeSystemVersion="2.0" />
                        </asQualifiedEntity>
                        <!--C.2.r.3: Reporter's Country Code-->
                        <asLocatedEntity classCode="LOCE">
                          <location determinerCode="INSTANCE" classCode="COUNTRY">
                            <code code="KE" codeSystem="1.0.3166.1.2.2" />
                          </location>
                        </asLocatedEntity>
                      </assignedPerson>
                    </assignedEntity>
                  </author>
                </controlActEvent>
              </subjectOf2>
            </relatedInvestigation>
          </outboundRelationship>
          <!--C.3: Information on Sender of Case Safety Report-->
          <subjectOf1 typeCode="SUBJ">
            <controlActEvent classCode="CACT" moodCode="EVN">
              <author typeCode="AUT">
                <assignedEntity classCode="ASSIGNED">
                  <!--C.3.1: Sender Type-->
                  <code code="3" codeSystem="2.16.840.1.113883.3.989.2.1.1.7" codeSystemVersion="2.0" />
                  <addr>
                    <!--C.3.4.1: Sender's Street Address-->
                    <streetAddressLine>P.O. Box:27663-00506</streetAddressLine>
                    <!--C.3.4.2: Sender's City-->
                    <city>Nairobi</city>
                  </addr>
                  <assignedPerson classCode="PSN" determinerCode="INSTANCE">
                    <name />
                    <!--C.3.4.5: Sender's Country Code-->
                    <asLocatedEntity classCode="LOCE">
                      <location classCode="COUNTRY" determinerCode="INSTANCE">
                        <code code="KE" codeSystem="1.0.3166.1.2.2" />
                      </location>
                    </asLocatedEntity>
                  </assignedPerson>
                  <representedOrganization classCode="ORG" determinerCode="INSTANCE">
                    <!--C.3.3.1: Sender's Department-->
                    <name>Department of Pharmacovigilance</name>
                    <assignedEntity classCode="ASSIGNED">
                      <representedOrganization classCode="ORG" determinerCode="INSTANCE">
                        <!--C.3.2: Sender's Organization -->
                        <name>Pharmacy and Poisons Board</name>
                      </representedOrganization>
                    </assignedEntity>
                  </representedOrganization>
                </assignedEntity>
              </author>
            </controlActEvent>
          </subjectOf1>
          <!--C.1.3: Type of Report  -->
          <subjectOf2 typeCode="SUBJ">
            <investigationCharacteristic classCode="OBS" moodCode="EVN">
              <code code="1" codeSystem="2.16.840.1.113883.3.989.2.1.1.23" codeSystemVersion="1.0" />
              <value xsi:type="CE" code="1" codeSystem="2.16.840.1.113883.3.989.2.1.1.2" codeSystemVersion="2.0" />
            </investigationCharacteristic>
          </subjectOf2>
          <!--C.1.9.1: Other Case Identifiers in Previous Transmissions  -->
          <subjectOf2 typeCode="SUBJ">
            <investigationCharacteristic classCode="OBS" moodCode="EVN">
              <code code="2" codeSystem="2.16.840.1.113883.3.989.2.1.1.23" codeSystemVersion="1.0" />
              <value xsi:type="BL" nullFlavor="NI" />
            </investigationCharacteristic>
          </subjectOf2>
        </investigationEvent>
      </subject>
    </controlActProcess>
  </PORR_IN049016UV>
  <!--N.1.4: Batch Receiver Identifier-->
  <receiver typeCode="RCV">
    <device classCode="DEV" determinerCode="INSTANCE">
      <id extension="PPB" root="2.16.840.1.113883.3.989.2.1.3.14" />
    </device>
  </receiver>
  <!--N.1.3: Batch Sender Identifier-->
  <sender typeCode="SND">
    <device classCode="DEV" determinerCode="INSTANCE">
      <id extension="PPB" root="2.16.840.1.113883.3.989.2.1.3.13" />
    </device>
  </sender>
</MCCI_IN200100UV01>