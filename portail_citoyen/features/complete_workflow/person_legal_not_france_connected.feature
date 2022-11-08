Feature:
    In order to fill a complaint
    As a user
    I don't want to authenticate with France Connect, fill in identity step, facts step and see the recap

    @javascript
    Scenario: Submit the facts form as a person legal not logged in with France Connect
        Given I am on "/authentification"
        When I follow "Continuer sans m'authentifier"
        Then I am on "/identite?france_connected=0"
        When I click the "label[for=identity_declarantStatus_1]" element
        And I wait for the element "#form-identity" to appear
        Then the "identity_civilState_birthName" field should not contain "DUPONT"
        And the "identity_civilState_firstnames" field should not contain "Michel"
        And the "identity_civilState_birthDate" field should not contain "1967-03-02"
        And the "identity_civilState_civility" field should not contain "1"
        And the "identity_civilState_birthLocation_frenchTown" field should not contain "Paris (75)"
        And the "identity_contactInformation_email" field should not contain "michel.dupont@example.com"
        And I select "1" from "identity_civilState_civility"
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I select "Paris (75)" from "identity_civilState_birthLocation_frenchTown"
        And I wait for the "#identity_civilState_birthLocation_department" field to contain "75"
        And I select "1" from "identity_civilState_nationality"
        And I select "1" from "identity_civilState_job"
        And I fill in "identity_contactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_mobile" with "0601020304"
        And I select "2" from "identity_representedPersonCivilState_civility"
        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
        And I fill in "identity_representedPersonCivilState_firstnames" with "Julie"
        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2010"
        And I select "Paris (75)" from "identity_representedPersonCivilState_birthLocation_frenchTown"
        And I wait for the "#identity_representedPersonCivilState_birthLocation_department" field to contain "75"
        And I select "1" from "identity_representedPersonCivilState_nationality"
        And I select "3" from "identity_representedPersonCivilState_job"
        And I fill in "identity_representedPersonContactInformation_frenchAddress" with "Av. de la République 75011 Paris France"
        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
        And I fill in "identity_representedPersonContactInformation_mobile" with "0602030405"
        And I press "Suivant"
        Then I am on "/faits"
        And I follow "Précédent"
        Then I am on "/identite"
        And the "identity_civilState_civility" field should contain "1"
        And the "identity_civilState_birthName" field should contain "DUPONT"
        And the "identity_civilState_firstnames" field should contain "Jean Pierre Marie"
        And the "identity_civilState_birthDate" field should contain "2000-01-01"
        And the "identity_civilState_birthLocation_frenchTown" field should contain "Paris (75)"
        And the "identity_civilState_birthLocation_department" field should contain "75"
        And the "identity_civilState_nationality" field should contain "1"
        And the "identity_civilState_job" field should contain "1"
        And the "identity_contactInformation_frenchAddress" field should contain "Av. de la République 75011 Paris France"
        And the "identity_contactInformation_email" field should contain "jean@test.com"
        And the "identity_contactInformation_mobile" field should contain "0601020304"
        And the "identity_representedPersonCivilState_birthName" field should contain "DUPONT"
        And the "identity_representedPersonCivilState_firstnames" field should contain "Julie"
        And the "identity_representedPersonCivilState_birthDate" field should contain "2010-01-01"
        And the "identity_representedPersonCivilState_birthLocation_frenchTown" field should contain "Paris (75)"
        And the "identity_representedPersonCivilState_birthLocation_department" field should contain "75"
        And the "identity_representedPersonCivilState_nationality" field should contain "1"
        And the "identity_representedPersonCivilState_job" field should contain "3"
        And the "identity_representedPersonContactInformation_frenchAddress" field should contain "Av. de la République 75011 Paris France"
        And the "identity_representedPersonContactInformation_email" field should contain "jean@test.com"
        And the "identity_representedPersonContactInformation_mobile" field should contain "0602030405"
        And I press "Suivant"
        Then I am on "/faits"
        When I select "1" from "facts_offenseNature_offenseNature"
        And I click the "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        And I wait for the element "#facts_address_startAddress" to appear
        And I fill in "facts_address_startAddress" with "1 test street"
        And I fill in "facts_address_endAddress" with "2 test street"
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I wait for the element "#facts_offenseDate_startDate" to appear
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
        And I wait for the element "#facts_offenseDate_hour" to appear
        And I fill in "facts_offenseDate_hour" with "15:00"
        And I select "1" from "facts_objects_0_category"
        And I fill in "facts_objects_0_label" with "Object 1"
        And I press "facts_objects_add"
        And I wait for the element "#facts_objects_1_label" to appear
        And I select "1" from "facts_objects_1_category"
        And I fill in "facts_objects_1_label" with "Object 2"
        And I click the "label[for=facts_amountKnown_0]" element
        And I wait for the element "#facts_amount" to appear
        And I fill in "facts_amount" with "700"
        And I wait for the element "#facts_additionalInformation_suspectsChoice_0" to appear
        And I click the "label[for=facts_additionalInformation_suspectsChoice_0]" element
        And I wait for the element "#facts_additionalInformation_suspectsText" to appear
        And I fill in "facts_additionalInformation_suspectsText" with "suspects informations"
        And I should see the key "pel.facts.suspects.informations.text" translated
        And I click the "label[for=facts_additionalInformation_witnesses_0]" element
        And I wait for the element "#facts_additionalInformation_witnessesText" to appear
        And I fill in "facts_additionalInformation_witnessesText" with "witnesses informations"
        And I click the "label[for=facts_additionalInformation_fsiVisit_0]" element
        And I click the "label[for=facts_additionalInformation_observationMade_0]" element
        And I click the "label[for=facts_additionalInformation_cctvPresent_0]" element
        And I wait for the element "#facts_additionalInformation_cctvAvailable_0" to appear
        And I click the "label[for=facts_additionalInformation_cctvAvailable_0]" element
        And I fill in "facts_description" with "description informations"
        And I press "Suivant"
        Then I am on "/recapitulatif"
        And I should see the key "pel.civility" translated
        And I should see the key "pel.sir" translated
        And I should see "DUPONT"
        And I should see "Jean Pierre Marie"
        And I should not see the key "pel.usage.name" translated
        And I should see the key "pel.born.on" translated
        And I should see "01/01/2000"
        And I should see the key "pel.at.in" translated
        And I should see "France"
        And I should see the key "pel.department" translated
        And I should see the key "pel.nationality" translated
        And I should see "Française"
        And I should see the key "pel.resides.at" translated
        And I should see "Av. de la République 75011 Paris France"
        And I should see the key "pel.your.job" translated
        And I should see the key "pel.job.policeman" translated
        And I should see the key "pel.phone" translated
        And I should see "0601020304"
        And I should see the key "pel.email" translated
        And I should see "jean@test.com"
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
        And I should see "Av. de la République 75011 Paris France"
        And I should see the key "pel.your.job" translated
        And I should see the key "pel.job.none" translated
        And I should see the key "pel.phone" translated
        And I should see "0602030405"
        And I should see the key "pel.email" translated
        And I should see "jean@test.com"
