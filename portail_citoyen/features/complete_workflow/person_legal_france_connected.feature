Feature:
    In order to fill a complaint
    As a user
    I want to authenticate with France Connect, fill in identity step, facts step and see the recap

    @javascript
    Scenario: Submit the facts form as a person legal logged in with France Connect
        Given I am on "/authentification"
        And I press "france_connect_auth_button"
        And I am on "/porter-plainte"
        When I click the "#identity_accordion_title" element
        And I click the "label[for=identity_declarantStatus_1]" element
        And I select "1" from "identity_civilState_civility"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress_address" with "Av. de la République 75011 Paris"
        And I fill in "identity_contactInformation_phone_number" with "0601020304"
        And I select "2" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Julie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2010"
        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I select "1" from "identity_representedPersonCivilState_nationality"
        And I select "3" from "identity_representedPersonCivilState_job"
        And I fill in "identity_representedPersonContactInformation_frenchAddress_address" with "Av. de la République 75011 Paris France"
        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_phone_number" with "0602030405"
        And I press "identity_submit"
        And I wait 2000 ms
        And I press "facts_submit"
        And I click the "label[for=facts_offenseNature_offenseNatures_0]" element
        And I click the "label[for=facts_victimOfViolence]" element
        And I fill in "facts_victimOfViolenceText" with "Violence informations"
        And I click the "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        And I fill in "facts_address_startAddress" with "1 test street"
        And I fill in "facts_address_endAddress" with "2 test street"
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
        And I fill in "facts_offenseDate_hour" with "15:00"
        And I press "facts_submit"
        And I select "1" from "objects_objects_0_category"
        And I fill in "objects_objects_0_label" with "Object 1"
        And I fill in "objects_objects_0_amount" with "100"
        And I press "objects_objects_add"
        And I click the "#objects_objects_1_label" element
        And I select "1" from "objects_objects_1_category"
        And I fill in "objects_objects_1_label" with "Object 2"
        And I fill in "objects_objects_1_amount" with "100"
        And I press "objects_submit"
        And I wait 2000 ms
        And I click the "label[for=additional_information_suspectsChoice_0]" element
        And I fill in "additional_information_suspectsText" with "suspects informations"
        And I should see the key "pel.facts.suspects.informations.text" translated
        And I click the "label[for=additional_information_witnesses_0]" element
        And I fill in "additional_information_witnessesText" with "witnesses informations"
        And I click the "label[for=additional_information_fsiVisit_0]" element
        And I click the "label[for=additional_information_observationMade_0]" element
        And I click the "label[for=additional_information_cctvPresent_0]" element
        And I click the "label[for=additional_information_cctvAvailable_0]" element
        And I fill in "additional_information_description" with "description informations"
        And I press "additional_information_submit"
        And I wait 2000 ms
        Given I am on "/recapitulatif"
        Then I should see the key "pel.civility" translated
        And I should see the key "pel.sir" translated
        And I should see "DUPONT"
        And I should see "Michel"
        And I should not see the key "pel.usage.name" translated
        And I should see the key "pel.born.on" translated
        And I should see "02/03/1967"
        And I should see the key "pel.at.in" translated
        And I should see "France"
        And I should see the key "pel.department" translated
        And I should see the key "pel.nationality" translated
        And I should see "Française"
        And I should see the key "pel.resides.at" translated
        And I should see "Av. de la République 75011 Paris, France"
        And I should see the key "pel.your.job" translated
        And I should see the key "pel.job.policeman" translated
        And I should see the key "pel.phone" translated
        And I should see "+33 6 01 02 03 04"
        And I should see the key "pel.email" translated
        And I should see "michel.dupont@example.com"
        And I should see the key "pel.want.to.receive.sms.notifications" translated
        And I should see the key "pel.is.designated.as" translated
        And I should see the key "pel.complaint.identity.person.legal.representative" translated
        And I should see the key "pel.of.infraction" translated
        And I should see the key "pel.civility" translated
        And I should see the key "pel.madam" translated
        And I should see "DUPONT"
        And I should see "Julie"
        And I should not see the key "pel.usage.name" translated
        And I should see the key "pel.born.on" translated
        And I should see "01/01/2010"
        And I should see the key "pel.at.in" translated
        And I should see "France"
        And I should see the key "pel.department" translated
        And I should see the key "pel.nationality" translated
        And I should see "Française"
        And I should see the key "pel.resides.at" translated
        And I should see "Av. de la République 75011 Paris, France"
        And I should see the key "pel.your.job" translated
        And I should see the key "pel.job.none" translated
        And I should see the key "pel.phone" translated
        And I should see "+33 6 02 03 04 05"
        And I should see the key "pel.email" translated
        And I should see "jean@test.com"
        And I should see the key "pel.facts.description" translated
        And I should see the key "pel.complaint.nature.of.the.facts" translated
        And I should see the key "pel.complaint.identity.person.legal.representative" translated
        And I should see the key "pel.victim.at.time.of.facts" translated
        And I should see the key "pel.address.or.route.facts" translated
        And I should see the key "pel.address" translated
        And I should see the key "pel.address.start.or.exact" translated
        And I should see "1 test street"
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
        And I should see the key "pel.victim.of.violence" translated
        And I should see the key "pel.victim.of.violence.text" translated
        And I should see the key "pel.facts.description.precise" translated
        And I should see "description informations"
        And I should see the key "pel.note" translated
        And I should see the key "pel.summary.is.not.legal.proof" translated
        When I press "Suivant"
        Then I should see 1 "#fr-modal-complaint-confirm[open=true]" element
        And I should see the key "pel.declaration.confirmation" translated
        And I should see the key "pel.i.confirm.declaration.is.complete" translated
        And I should see the key "pel.i.am.inform.of.article.434.26" translated
        And I should see the key "pel.i.consent.use.of.data.for.fsi" translated
        When I follow "Annuler"
        Then I should be on "/recapitulatif"
        And I should see the key "pel.objects.description" translated
        And I should see the key "pel.objects" translated
        And I should see the key "pel.object.category" translated
        And I should see "Documents"
        And I should see the key "pel.object" translated
        And I should see "Object 1"
        And I should see "Object 2"
        And I should see the key "pel.total" translated
        And I should see the key "pel.total.message.one" translated
        And I should see the key "pel.total.message.amount" translated
        And I should see "Vous avez ajouté 2 objets pour un montant total de 200,00 €"
        When I press "Suivant"
        And I follow "Je confirme"
        Then I should be on "/fin"
