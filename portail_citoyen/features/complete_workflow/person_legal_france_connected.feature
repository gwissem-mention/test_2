# Person Legal Representative must be hidden for the experimentation

#Feature:
#    In order to fill a complaint
#    As a user
#    I want to authenticate with France Connect, fill in identity step, facts step and see the recap
#
#    @javascript
#    Scenario: Submit the facts form as a person legal logged in with France Connect
#        Given I am on "/authentification"
#        When I press "france_connect_auth_button"
#        Then I should be on "/porter-plainte/rappel-a-la-loi"
#        And I click the "label[for=law_refresher_lawRefresherAccepted]" element
#        And I press "law_refresher_submit"
#        Then I should be on "/porter-plainte/identite"
#        And I should see the key "pel.these.informations.has.been.transmitted.by.france.connect" translated
#        And I click the "label[for=identity_declarantStatus_0]" element
#        When I click the "label[for=identity_civilState_civility_0]" element
#        And I select "1" from "identity_civilState_familySituation"
#        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "contact-information-address" with "avenue de la république bordeaux"
#        And I click the "#contact-information-address-33063_8132" element
#        And I fill in "identity_contactInformation_phone_number" with "0101020304"
#        And I fill in "identity_contactInformation_mobile_number" with "0601020304"
#        # Person Legal Representative must be hidden for the experimentation
##        And I click the "label[for=identity_representedPersonCivilState_civility_1]" element
##        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
##        And I fill in "identity_representedPersonCivilState_firstnames" with "Julie"
##        And I select "1" from "identity_representedPersonCivilState_familySituation"
##        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2010"
##        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
##        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
##        And I fill in "represented-person-address" with "avenue de la république bordeaux"
##        And I click the "#represented-person-address-33063_8132" element
##        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
##        And I fill in "identity_representedPersonContactInformation_phone_number" with "0101020304"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/faits"
#        When I fill in "facts_description" with "description informations lorem ipsum dol or sit amet lorem ipsum dol or sit amet"
#        And I click the "label[for=facts_victimOfViolence]" element
#        And I fill in "facts_victimOfViolenceText" with "Violence informations"
#        And I select "2" from "facts_placeNature"
#        And I fill in "facts-startAddress-address" with "1 test street"
#        And I fill in "facts-endAddress-address" with "2 test street"
#        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
#        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
#        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
#        And I fill in "facts_offenseDate_hour" with "15:00"#
#        And I press "facts_submit"
#        Then I should be on "/porter-plainte/objets"
#        When I select "5" from "objects_objects_0_category"
#        And I select "1" from "objects_objects_0_status"
#        And I fill in "objects_objects_0_label" with "Object 1"
#        And I fill in "objects_objects_0_amount" with "100"
#        And I press "objects_objects_add"
#        And I click the "#objects_objects_1_label" element
#        And I select "5" from "objects_objects_1_category"
#        And I select "2" from "objects_objects_1_status"
#        And I fill in "objects_objects_1_label" with "Object 2"
#        And I fill in "objects_objects_1_amount" with "100"
#        And I press "objects_objects_add"
#        And I select "1" from "objects_objects_2_category"
#        And I select "1" from "objects_objects_2_documentType_documentType"
#        And I fill in "objects_objects_2_documentType_documentNumber" with "123"
#        And I fill in "objects_objects_2_documentType_documentIssuedBy" with "Préfecture de Paris"
#        And I fill in "objects_objects_2_documentType_documentIssuedOn" with "01/01/2010"
#        And I fill in "objects_objects_2_documentType_documentValidityEndDate" with "01/01/2030"
#        And I select "2" from "objects_objects_2_status"
#        And I press "objects_objects_add"
#        And I select "3" from "objects_objects_3_category"
#        And I fill in "objects_objects_3_label" with "Smartphone"
#        And I select "2" from "objects_objects_3_status"
#        And I fill in "objects_objects_3_brand" with "OnePlus"
#        And I fill in "objects_objects_3_model" with "Nord 2"
#        And I fill in "objects_objects_3_phoneNumberLine_number" with "6123456783"
#        And I fill in "objects_objects_3_operator" with "SFR"
#        And I fill in "objects_objects_3_serialNumber" with "12345678"
#        And I fill in "objects_objects_3_description" with "Ceci est une description test pour mon smartphone."
#        And I press "objects_objects_add"
#        And I select "7" from "objects_objects_4_category"
#        And I fill in "objects_objects_4_label" with "Console"
#        And I select "2" from "objects_objects_4_status"
#        And I fill in "objects_objects_4_brand" with "Sony"
#        And I fill in "objects_objects_4_model" with "Playstation 4"
#        And I fill in "objects_objects_4_serialNumber" with "12345678"
#        And I fill in "objects_objects_4_description" with "Ceci est une description test pour ma console."
#        And I press "objects_submit"
#        Then I should be on "/porter-plainte/informations-complementaires"
#        When I click the "label[for=additional_information_suspectsChoice_0]" element
#        And I fill in "additional_information_suspectsText" with "suspects informations"
#        And I should see the key "pel.facts.suspects.informations.text" translated
#        And I click the "label[for=additional_information_witnessesPresent_0]" element
#        And I fill in "additional_information_witnesses_0_description" with "Jean DUPONT"
#        And I fill in "additional_information_witnesses_0_email" with "jean.dupont@example.com"
#        And I fill in "additional_information_witnesses_0_phone_number" with "0602030405"
#        And I click the "label[for=additional_information_fsiVisit_0]" element
#        And I click the "label[for=additional_information_observationMade_0]" element
#        And I click the "label[for=additional_information_cctvPresent_0]" element
#        And I click the "label[for=additional_information_cctvAvailable_0]" element
#        And I press "additional_information_submit"
#        Then I should be on "/porter-plainte/recapitulatif"
#        And I should see the key "pel.sir" translated
#        And I should see "DUPONT"
#        And I should see "Michel"
#        And I should not see the key "pel.usage.name" translated
#        And I should see the key "pel.family.situation" translated
#        And I should see "Célibataire"
#        And I should see the key "pel.born.on" translated
#        And I should see "Né(e) le 2 mars 1967 à Paris 7e arrondissement 75007 (France)"
#        And I should see the key "pel.nationality" translated
#        And I should see "francaise"
#        And I should see the key "pel.resides.at" translated
#        And I should see "Avenue de la République 33000 Bordeaux (France)"
#        And I should see the key "pel.your.job" translated
#        And I should see "Abatteur de bestiaux"
#        And I should see the key "pel.phone" translated
#        And I should see "+33 1 01 02 03 04"
#        And I should see the key "pel.mobile" translated
#        And I should see "+33 6 01 02 03 04"
#        And I should see the key "pel.email" translated
#        And I should see "michel.dupont@example.com"
#        And I should see the key "pel.is.designated.as" translated
#        And I should see the key "pel.complaint.identity.person.legal.representative" translated
#        And I should see the key "pel.of.infraction" translated
#        And I should see the key "pel.civility" translated
#        And I should see the key "pel.madam" translated
#        And I should see "DUPONT"
#        And I should see "Julie"
#        And I should not see the key "pel.usage.name" translated
#        And I should see the key "pel.family.situation" translated
#        And I should see "Célibataire"
#        And I should see the key "pel.born.on" translated
#        And I should see the key "pel.at" translated
#        And I should see "Né(e) le 1 janvier 2010 à Paris 75000 (France)"
#        And I should see the key "pel.nationality" translated
#        And I should see "francaise"
#        And I should see the key "pel.resides.at" translated
#        And I should see "Avenue de la République 33000 Bordeaux (France)"
#        And I should see the key "pel.your.job" translated
#        And I should see the key "pel.phone" translated
#        And I should see the key "pel.email.address" translated
#        And I should see "jean@test.com"
#        And I should see the key "pel.facts.description" translated
#        And I should see the key "pel.complaint.identity.person.legal.representative" translated
#        And I should see the key "pel.victim.at.time.of.facts" translated
#        And I should see the key "pel.address" translated
#        And I should see "1 test street"
#        And I should see the key "pel.address.end" translated
#        And I should see "2 test street"
#        And I should see the key "pel.complaint.exact.date.is.known" translated
#        And I should see the key "pel.facts.offence.occurred" translated
#        And I should see the key "pel.the" translated
#        And I should see "samedi 1 janvier 2022"
#        And I should see the key "pel.complaint.exact.hour.is.known" translated
#        And I should see the key "pel.facts.offence.occurred" translated
#        And I should see "15h00"
#        And I should see the key "pel.additional.factual.information" translated
#        And I should see "La victime affirme avoir identifié 1 témoin de l’infraction"
#        And I should see "Jean DUPONT"
#        And I should see "jean.dupont@example.com"
#        And I should see "+33 6 02 03 04 05"
#        And I should see the key "pel.do.you.have.informations.on.potential.suspects.yes" translated
#        And I should see "suspects informations"
#        And I should see the key "pel.cctv.present.yes" translated
#        And I should see the key "pel.cctv.available.yes" translated
#        And I should see the key "pel.fsi.visit.yes" translated
#        And I should see the key "pel.observation.made.yes" translated
#        And I should see "description informations lorem ipsum dol or sit amet lorem ipsum dol or sit amet"
#        And I should see the key "pel.summary.is.not.legal.proof" translated
#        And I should see the key "pel.summary.official.documents.will.be.sent" translated
#        And I should see the key "pel.declaration.confirmation" translated
#        And I should see the key "pel.i.confirm.declaration.is.complete" translated
#        And I should see the key "pel.i.am.inform.of.article.434.26" translated
#        And I should see the key "pel.i.consent.use.of.data.for.fsi" translated
#        And I should see the key "pel.goods.concerned.by.the.offence" translated
#        And I should see the key "pel.goods.number" translated
#        And I should see the key "pel.object.category.unregistered.vehicle" translated
#        And I should see the key "pel.object.category.documents" translated
#        And I should see the key "pel.objects.stolen" translated
#        And I should see the key "pel.objects.gradient" translated
#        And I should see the key "pel.i.am.the.owner.of.this.document" translated
#        And I should not see the key "pel.document.owner.lastname.firstname" translated
#        And I should not see the key "pel.document.owner.company" translated
#        And I should not see the key "pel.document.owner.email" translated
#        And I should not see the key "pel.document.owner.address" translated
#        And I should see the key "pel.document.number" translated
#        And I should see the key "pel.document.issued.by" translated
#        And I should see the key "pel.document.issued.on" translated
#        And I should see the key "pel.document.validity.end.date" translated
#        And I should see the key "pel.document.issuing.country" translated
#        And I should see "Véhicules non immatriculés"
#        And I should see "Object 1"
#        And I should see "Object 2"
#        And I should see "Document officiel"
#        And I should see "CARTE NATIONALE D'IDENTITE"
#        And I should see "Oui"
#        And I should see "123"
#        And I should see "Préfecture de Paris"
#        And I should see "01/01/2010"
#        And I should see "01/01/2030"
#        And I should see "France"
#        And I should see the key "pel.brand" translated
#        And I should see the key "pel.model" translated
#        And I should see the key "pel.mobile" translated
#        And I should see the key "pel.operator" translated
#        And I should see the key "pel.serial.number" translated
#        And I should see the key "pel.description" translated
#        And I should see "Téléphone portable"
#        And I should see "Smartphone"
#        And I should see "OnePlus"
#        And I should see "Nord 2"
#        And I should see "+33 6 12 34 56 78"
#        And I should see "SFR"
#        And I should see "123456783"
#        And I should see "Ceci est une description test pour mon smartphone."
#        And I should not see the key "pel.owner.lastname.firstname" translated
#        And I should see "Console"
#        And I should see "Sony"
#        And I should see "Playstation 4"
#        And I should not see "DUPONT Michel"
#        And I should see "12345678"
#        And I should see "Ceci est une description test pour ma console."
#        And I should see the key "pel.total" translated
#        And I should see the key "pel.total.message.amount" translated
#        And I should see "5 biens déclarés pour un montant total de 200,00 €"
#        When I press "summary_submit"
#        Then I should be on "/porter-plainte/rendez-vous"
#        When I fill in "appointment_appointmentContactText" with "Between 10am and 12am"
#        And I press "Continuer"
#        Then I should be on "/porter-plainte/fin"
