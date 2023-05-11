Feature:
    In order to fill a complaint
    As a user
    I want to authenticate with France Connect, fill in identity step, facts step and see the recap

    @javascript
    Scenario: Submit the facts form as a victim logged in with France Connect
        Given I am on "/authentification"
        When I press "france_connect_auth_button"
        Then I should be on "/porter-plainte/statut-declarant"
        And I click the "label[for=declarant_status_declarantStatus_0]" element
        And I press "declarant_status_submit"
        Then I should be on "/porter-plainte/identite"
        When I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "2"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_phone_number" with "0101020304"
        And I fill in "identity_contactInformation_mobile_number" with "0601020304"
        When I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        When I fill in "facts_description" with "description informations lorem ipsum dol or sit amet lorem ipsum dol or sit amet"
        And I select "1" from "facts_placeNature"
        And I click the "label[for=facts_victimOfViolence]" element
        And I fill in "facts_victimOfViolenceText" with "Violence informations"
        And I click the "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        And I fill in "facts-endAddress-address" with "2 test street"
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
        And I fill in "facts_offenseDate_hour" with "15:00"
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
        And I fill in "objects_objects_1_label" with "Object 2"
        And I fill in "objects_objects_1_amount" with "100"
        And I fill in "objects_objects_1_brand" with "Apple"
        And I fill in "objects_objects_1_phoneNumberLine_number" with "0601020304"
        And I press "objects_submit"
        Then I should be on "/porter-plainte/informations-complementaires"
        When I click the "label[for=additional_information_suspectsChoice_0]" element
        And I fill in "additional_information_suspectsText" with "suspects informations"
        And I should see the key "pel.facts.suspects.informations.text" translated
        And I click the "label[for=additional_information_witnesses_0]" element
        And I fill in "additional_information_witnessesText" with "witnesses informations"
        And I click the "label[for=additional_information_fsiVisit_0]" element
        And I click the "label[for=additional_information_observationMade_0]" element
        And I click the "label[for=additional_information_cctvPresent_0]" element
        And I click the "label[for=additional_information_cctvAvailable_0]" element
        And I press "additional_information_submit"
        Then I should be on "/porter-plainte/recapitulatif"
        And I should see the key "pel.civility" translated
        And I should see the key "pel.sir" translated
        And I should see "DUPONT"
        And I should see "Michel"
        And I should not see the key "pel.usage.name" translated
        And I should see the key "pel.born.on" translated
        And I should see the key "pel.at" translated
        And I should see "Né(e) le : 02/03/1967 à Paris 7e arrondissement (75)"
        And I should see the key "pel.nationality" translated
        And I should see "Française"
        And I should see the key "pel.resides.at" translated
        And I should see "Avenue de la République 75011 Paris, France"
        And I should see the key "pel.your.job" translated
        And I should see "Abatteur de bestiaux"
        And I should see the key "pel.phone" translated
        And I should see "+33 1 01 02 03 04"
        And I should see the key "pel.mobile" translated
        And I should see "+33 6 01 02 03 04"
        And I should see the key "pel.email" translated
        And I should see "michel.dupont@example.com"
        And I should see the key "pel.want.to.receive.sms.notifications" translated
        And I should see the key "pel.is.designated.as" translated
        And I should see the key "pel.complaint.identity.victim" translated
        And I should see the key "pel.of.infraction" translated
        And I should see the key "pel.facts.description" translated
        And I should see the key "pel.nature.place" translated
        And I should see the key "pel.nature.place.home" translated
        And I should see the key "pel.complaint.identity.victim" translated
        And I should see the key "pel.victim.at.time.of.facts" translated
        And I should see the key "pel.victim.at.time.of.facts.precisions" translated
        And I should see the key "pel.address.or.route.facts" translated
        And I should see the key "pel.address" translated
        And I should see the key "pel.address.start.or.exact" translated
        And I should see "Avenue de la République 75011 Paris, France"
        And I should see the key "pel.address.end" translated
        And I should see "2 test street"
        And I should see the key "pel.complaint.exact.date.known" translated
        And I should see the key "pel.facts.date" translated
        And I should see the key "pel.the" translated
        And I should see "01/01/2022"
        And I should see the key "pel.do.you.know.hour.facts" translated
        And I should see the key "pel.exact.hour" translated
        And I should see "15:00"
        And I should see the key "pel.additional.factual.information" translated
        And I should see the key "pel.facts.witnesses" translated
        And I should see the key "pel.facts.witnesses.information.text" translated
        And I should see "witnesses informations"
        And I should see the key "pel.do.you.have.informations.on.potential.suspects" translated
        And I should see the key "pel.facts.suspects.informations.text" translated
        And I should see "suspects informations"
        And I should see the key "pel.cctv.present" translated
        And I should see the key "pel.cctv.available" translated
        And I should see the key "pel.fsi.visit" translated
        And I should see the key "pel.observation.made" translated
        And I should see the key "pel.facts.description.precise" translated
        And I should see "description informations lorem ipsum dol or sit amet lorem ipsum dol or sit amet"
        And I should see the key "pel.note" translated
        And I should see the key "pel.summary.is.not.legal.proof" translated
        When I press "Suivant"
        Then I should see 1 "#fr-modal-complaint-confirm[open=true]" element
        And I should see the key "pel.declaration.confirmation" translated
        And I should see the key "pel.i.confirm.declaration.is.complete" translated
        And I should see the key "pel.i.am.inform.of.article.434.26" translated
        And I should see the key "pel.i.consent.use.of.data.for.fsi" translated
        When I follow "Annuler"
        Then I should be on "/porter-plainte/recapitulatif"
        And I should see the key "pel.objects.description" translated
        And I should see the key "pel.objects" translated
        And I should see the key "pel.object.category" translated
        And I should see the key "pel.objects.stolen" translated
        And I should see the key "pel.objects.gradient" translated
        And I should see "Véhicules non immatriculés"
        And I should see the key "pel.object" translated
        And I should see "Object 1"
        And I should see "Object 2"
        And I should see the key "pel.attachment" translated
        And I should see "blank.pdf"
        When I follow "blank.pdf"
        Then I should see "N° de la ligne de téléphone : +33 6 01 02 03 04"
        And I should see the key "pel.objects" translated
        And I should see the key "pel.total" translated
        And I should see the key "pel.total.message.one" translated
        And I should see the key "pel.total.message.amount" translated
        And I should see "Vous avez ajouté 2 objets pour un montant total de 200,00 €"
        And I should see the key "pel.place.complaint.handling" translated
        And I should see "Commissariat de police de Paris 11ème arrondissement - 12 PASSAGE CHARLES DALLERY 75011 PARIS 11"
        When I press "Suivant"
        And I follow "Je confirme"
        Then I should be on "/porter-plainte/rendez-vous"
        When I fill in "appointment_appointmentContactText" with "Between 10am and 12am"
        And I press "Suivant"
        Then I should be on "/porter-plainte/fin"
