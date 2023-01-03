Feature:
    In order to show authentication page
    As a user
    I need to a button and a link

    @func
    Scenario: Show authentication page with a button and a link and click on the link
        Given I am on "/authentification"
        Then the response status code should be 200
        And I should see 1 "body" element
        And I should see the key "pel.to.log.in" translated
        And I should see the key "pel.identify.with" translated
        And I should see the key "pel.france.connect" translated
        And I should see the key "pel.what.is.france.connect" translated
        And I should see the key "pel.new.window" translated in the response
        And I should see the key "pel.continue.pel.without.log.in" translated
        And I should see the key "pel.continue.pel.without.log.in.explanation" translated
        And I follow "Continuer sans m'authentifier"
        And I am on "/porter-plainte"

    @javascript
    Scenario: I can click on the FranceConnect Button
        Given I am on "/authentification"
        When I press "france_connect_auth_button"
        Then I should be on "/porter-plainte"
        When I click the "label[for=identity_declarantStatus_0]" element
        Then the "identity_civilState_birthName" field should contain "DUPONT"
        And the "identity_civilState_firstnames" field should contain "Michel"
        And the "identity_civilState_birthDate" field should contain "1967-03-02"
        And the "identity_civilState_civility" field should contain "1"
        And the "identity_civilState_birthLocation_country" field should contain "99100"
        And the "identity_civilState_birthLocation_frenchTown" field should contain "75107"
        And the "identity_contactInformation_email" field should contain "michel.dupont@example.com"

    @javascript
    Scenario: I can click on the unconnected button
        Given I am on "/authentification"
        When I follow "Continuer sans m'authentifier"
        Then I should be on "/porter-plainte"
        When I click the "label[for=identity_declarantStatus_0]" element
        Then the "identity_civilState_birthName" field should not contain "DUPONT"
        And the "identity_civilState_firstnames" field should not contain "Michel"
        And the "identity_civilState_birthDate" field should not contain "1967-03-02"
        And the "identity_civilState_civility" field should not contain "1"
        And the "identity_civilState_birthLocation_frenchTown" field should not contain "75107"
        And the "identity_contactInformation_email" field should not contain "michel.dupont@example.com"
