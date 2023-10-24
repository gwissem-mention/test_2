Feature:
    In order to fill a complaint
    As a user
    I want to authenticate with France Connect, fill in identity step, facts step and see the recap

    @javascript
    Scenario: Submit the facts form as a victim logged in with France Connect
        Given I am on "/authentification"
        When I press "france_connect_auth_button"
        Then I should be on "/porter-plainte/rappel-a-la-loi"
        And I click the "label[for=law_refresher_lawRefresherAccepted]" element
        And I press "law_refresher_submit"
        Then I should be on "/porter-plainte/identite"
        And I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_familySituation"
        When I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I fill in "contact-information-address" with "avenue de la république bordeaux"
        And I wait for "contact-information-address-33063_8132" to appear
        And I click the "#contact-information-address-33063_8132" element
        And I fill in "identity_contactInformation_phone_number" with "0101020304"
        And I fill in "identity_contactInformation_mobile_number" with "0601020304"
        And I click the "label[for=identity_consentContactEmail]" element
        And I click the "label[for=identity_consentContactSMS]" element
        And I click the "label[for=identity_consentContactPortal]" element
        When I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        When I fill in "facts_description" with "description informations lorem ipsum dol or sit amet lorem ipsum dol or sit amet"
        And I select "1" from "facts_placeNature"
        And I select "10" from "facts_subPlaceNature"
        And I click the "label[for=facts_victimOfViolence]" element
        And I fill in "facts_victimOfViolenceText" with "Violence informations"
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
        And I fill in "facts_offenseDate_hour" with "15:00"
        And I fill in "facts-startAddress-address" with "Avenue de la République Bordeaux"
        And I click the "#facts-startAddress-address-33063_8132" element
        And I press "facts_submit"
        Then I should be on "/porter-plainte/objets"
        When I select "5" from "objects_objects_0_category"
        And I select "1" from "objects_objects_0_status"
        And I fill in "objects_objects_0_label" with "Object 1"
        And I fill in "objects_objects_0_amount" with "100"
        And I attach the file "blank.pdf" to "object-files-0" field
        And I press "objects_objects_add"
        And I click the "#objects_objects_1_label" element
        And I select "3" from "objects_objects_1_category"
        And I select "2" from "objects_objects_1_status"
        And I fill in "objects_objects_1_amount" with "100"
        And I fill in "objects_objects_1_brand" with "Apple"
        And I fill in "objects_objects_1_phoneNumberLine_number" with "0601020304"
        And I press "objects_objects_add"
        And I select "1" from "objects_objects_2_category"
        And I select "1" from "objects_objects_2_documentType_documentType"
        And I fill in "objects_objects_2_documentType_documentNumber" with "123"
        And I fill in "objects_objects_2_documentType_documentIssuedBy" with "Préfecture de Paris"
        And I fill in "objects_objects_2_documentType_documentIssuedOn" with "01/01/2010"
        And I fill in "objects_objects_2_documentType_documentValidityEndDate" with "01/01/2030"
        And I select "2" from "objects_objects_2_status"
        And I press "objects_objects_add"
        And I select "3" from "objects_objects_3_category"
        And I select "2" from "objects_objects_3_status"
        And I fill in "objects_objects_3_brand" with "OnePlus"
        And I fill in "objects_objects_3_model" with "Nord 2"
        And I fill in "objects_objects_3_phoneNumberLine_number" with "612345678"
        And I fill in "objects_objects_3_operator" with "SFR"
        And I fill in "objects_objects_3_serialNumber" with "123456783"
        And I fill in "objects_objects_3_imei" with "ABCD"
        And I fill in "objects_objects_3_description" with "Ceci est une description test pour mon smartphone."
        And I press "objects_objects_add"
        And I select "7" from "objects_objects_4_category"
        And I select "2" from "objects_objects_4_status"
        And I select "9" from "objects_objects_4_multimediaNature"
        And I fill in "objects_objects_4_brand" with "Sony"
        And I fill in "objects_objects_4_model" with "Playstation 4"
        And I fill in "objects_objects_4_serialNumber" with "12345678"
        And I fill in "objects_objects_4_description" with "Ceci est une description test pour ma console."
        And I press "objects_objects_add"
        And I select "6" from "objects_objects_5_category"
        And I select "1" from "objects_objects_5_status"
        And I fill in "objects_objects_5_label" with "Lingot d'or"
        And I fill in "objects_objects_5_serialNumber" with "1234"
        And I fill in "objects_objects_5_description" with "Lingot d'or"
        And I fill in "objects_objects_5_quantity" with "1"
        And I press "objects_objects_add"
        And I click the "#objects_objects_6_label" element
        And I select "2" from "objects_objects_6_category"
        And I select "1" from "objects_objects_6_status"
        And I select "00004" from "objects_objects_6_paymentCategory"
        And I fill in "objects_objects_6_label" with "CB"
        And I fill in "objects_objects_6_bank" with "BNP"
        And I fill in "objects_objects_6_bankAccountNumber" with "767888"
        And I fill in "objects_objects_6_creditCardNumber" with "4624 7482 3324 9080"
        And I press "objects_objects_add"
        And I select "2" from "objects_objects_7_category"
        And I select "1" from "objects_objects_7_status"
        And I select "00001" from "objects_objects_7_paymentCategory"
        And I fill in "objects_objects_7_label" with "Chéquier"
        And I fill in "objects_objects_7_bank" with "BNP"
        And I fill in "objects_objects_7_bankAccountNumber" with "767888"
        And I fill in "objects_objects_7_checkNumber" with "ABCD1234"
        And I fill in "objects_objects_7_checkFirstNumber" with "AAX"
        And I fill in "objects_objects_7_checkLastNumber" with "XXX7895"
        And I press "objects_submit"
        Then I should be on "/porter-plainte/informations-complementaires"
        When I click the "label[for=additional_information_suspectsChoice_0]" element
        And I fill in "additional_information_suspectsText" with "suspects informations"
        And I should see the key "pel.facts.suspects.informations.text" translated
        And I click the "label[for=additional_information_witnessesPresent_0]" element
        And I fill in "additional_information_witnesses_0_description" with "Jean DUPONT"
        And I fill in "additional_information_witnesses_0_email" with "jean.dupont@example.com"
        And I fill in "additional_information_witnesses_0_phone_number" with "0602030405"
        And I click the "label[for=additional_information_fsiVisit_0]" element
        And I click the "label[for=additional_information_observationMade_0]" element
        And I click the "label[for=additional_information_cctvPresent_0]" element
        And I click the "label[for=additional_information_cctvAvailable_0]" element
        And I press "additional_information_submit"
        Then I should be on "/porter-plainte/recapitulatif"
        And I should see the key "pel.sir" translated
        And I should see "DUPONT"
        And I should see "Michel"
        And I should not see the key "pel.usage.name" translated
        And I should see the key "pel.family.situation" translated
        And I should see "Célibataire"
        And I should see the key "pel.at" translated
        And I should see "Né(e) le 2 mars 1967 à Paris 7e arrondissement 75007 (France)"
        And I should see the key "pel.nationality" translated
        And I should see "francaise"
        And I should see the key "pel.resides.at" translated
        And I should see "Avenue de la République 33000 Bordeaux (France)"
        And I should see the key "pel.your.job" translated
        And I should see "Abatteur de bestiaux"
        And I should see the key "pel.phone" translated
        And I should see "+33 1 01 02 03 04"
        And I should see the key "pel.mobile" translated
        And I should see "+33 6 01 02 03 04"
        And I should see the key "pel.email" translated
        And I should see "michel.dupont@example.com"
        And I should see the key "pel.is.designated.as" translated
        And I should see the key "pel.complaint.summary.identity.victim" translated
        And I should see the key "pel.of.infraction" translated
        And I should see the key "pel.facts.description" translated
        And I should see the key "pel.nature.place" translated
        And I should see "Nature du lieu : Domicile, logement et dépendances"
        And I should see "Précision sur la nature du lieu : Maison"
        And I should see the key "pel.victim.at.time.of.facts" translated
        And I should see the key "pel.address" translated
        And I should see "Adresse : Avenue de la République 33000 Bordeaux"
        And I should see the key "pel.consent.confirmation" translated
        And I should see the key "pel.by.email" translated
        And I should see the key "pel.by.sms" translated
        And I should see the key "pel.on.the.judicial.portal" translated
        And I should see the key "pel.complaint.exact.date.is.known" translated
        And I should see the key "pel.facts.offence.occurred" translated
        And I should see the key "pel.the" translated
        And I should see "samedi 1 janvier 2022"
        And I should see the key "pel.complaint.exact.hour.is.known" translated
        And I should see the key "pel.facts.offence.occurred" translated
        And I should see "15h00"
        And I should see the key "pel.additional.factual.information" translated
        And I should see "La victime affirme avoir identifié 1 témoin de l’infraction"
        And I should see "Jean DUPONT"
        And I should see "jean.dupont@example.com"
        And I should see "+33 6 02 03 04 05"
        And I should see the key "pel.do.you.have.informations.on.potential.suspects.yes" translated
        And I should see "suspects informations"
        And I should see the key "pel.cctv.present.yes" translated
        And I should see the key "pel.cctv.available.yes" translated
        And I should see the key "pel.fsi.visit.yes" translated
        And I should see the key "pel.observation.made.yes" translated
        And I should see "description informations lorem ipsum dol or sit amet lorem ipsum dol or sit amet"
        And I should see the key "pel.summary.is.not.legal.proof" translated
        And I should see the key "pel.summary.official.documents.will.be.sent" translated
        And I should see the key "pel.declaration.confirmation" translated
        And I should see the key "pel.i.confirm.declaration.is.complete" translated
        And I should see the key "pel.i.am.inform.of.article.434.26" translated
        And I should see the key "pel.goods.concerned.by.the.offence" translated
        And I should see the key "pel.goods.number" translated
        And I should see the key "pel.object.category.unregistered.vehicle" translated
        And I should see the key "pel.object.category.documents" translated
        And I should see the key "pel.objects.stolen" translated
        And I should see the key "pel.objects.gradient" translated
        And I should see the key "pel.i.am.the.owner.of.this.document" translated
        And I should not see the key "pel.document.owner.lastname.firstname" translated
        And I should not see the key "pel.document.owner.company" translated
        And I should not see the key "pel.document.owner.email" translated
        And I should not see the key "pel.document.owner.address" translated
        And I should see the key "pel.document.number" translated
        And I should see the key "pel.document.issued.by" translated
        And I should see the key "pel.document.issuing.country" translated
        And I should see the key "pel.document.issued.on" translated
        And I should see the key "pel.document.validity.end.date" translated
        And I should see "Véhicules non immatriculés"
        And I should see "Object 1"
        And I should see the key "pel.attachment" translated
        And I should see "blank.pdf"
        When I follow "blank.pdf"
        Then I should see "Numéro de téléphone portable : +33 6 01 02 03 04"
        And I should see "Document officiel"
        And I should see "CARTE NATIONALE D'IDENTITE"
        And I should see "Oui"
        And I should see "123"
        And I should see "Préfecture de Paris"
        And I should see "01/01/2010"
        And I should see "01/01/2030"
        And I should see "France"
        And I should see the key "pel.brand" translated
        And I should see the key "pel.model" translated
        And I should see the key "pel.mobile" translated
        And I should see the key "pel.operator" translated
        And I should see the key "pel.serial.number" translated
        And I should see the key "pel.imei" translated
        And I should see the key "pel.description" translated
        And I should see "Téléphone portable"
        And I should see "OnePlus"
        And I should see "Nord 2"
        And I should see "+33 6 12 34 56 78"
        And I should see "SFR"
        And I should see "123456783"
        And I should see "N° de série : 1234"
        And I should see "Ceci est une description test pour mon smartphone."
        And I should not see the key "pel.owner.lastname.firstname" translated
        And I should see "Sony"
        And I should see "Playstation 4"
        And I should see "N° de série : 1234"
        And I should not see "DUPONT Michel"
        And I should see "12345678"
        And I should see "Ceci est une description test pour ma console."
        And I should see "Bien volé de type Autres"
        And I should see "Description : Lingot d'or"
        And I should see "Quantité : 1"
        And I should see "CB"
        And I should see "Bien volé de type Moyens de paiement"
        And I should see "Catégorie de paiement : carte bancaire"
        And I should see "Organisme / Banque : BNP"
        And I should see "IBAN : 767888"
        And I should see "Numéro de carte : 4624 7482 3324 9080"
        And I should see "chequier"
        And I should see "Catégorie de paiement : CHEQUIER"
        And I should see "Organisme / Banque : BNP"
        And I should see "IBAN : 767888"
        And I should see "N° de chèque / chéquier : ABCD1234"
        And I should see "Premier N° de chèque / chéquier : AAX"
        And I should see "Dernier N° de chèque / chéquier : XXX7895"
        And I should see the key "pel.goods.number" translated
        And I should see the key "pel.total" translated
        And I should see the key "pel.total.message.amount" translated
        And I should see "8 biens déclarés pour un montant total de 200,00 €"
        When I press "summary_submit"
        Then I should be on "/porter-plainte/rendez-vous"
        When I fill in "appointment_appointmentContactText" with "Between 10am and 12am"
        And I press "Continuer"
        Then I should be on "/porter-plainte/fin"
        And I should see the key "pel.end.thanks" translated
        And I should see "Commissariat de police de Bordeaux"
        And I should see the key "pel.place.complaint.handling" translated
        And I should see the key "pel.download.my.declaration" translated
