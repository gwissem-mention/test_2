Feature:
    In order to fill a complaint
    As a user
    I want to see the complaint identity page

    Background:
        Given I am on "/authentification"
        And I follow "Continuer sans m'authentifier"

    @func
    Scenario: I can see the complaint identity page
        Then the response status code should be 200
        And I should see "Identité" in the ".fr-breadcrumb__list" element
        And I should see "Faits" in the ".fr-breadcrumb__list" element
        And I should see "Objets concernés" in the ".fr-breadcrumb__list" element
        And I should see "Informations complémentaires" in the ".fr-breadcrumb__list" element
        And I should see "Récapitulatif de déclaration de plainte initiale" in the ".fr-breadcrumb__list" element
        And I should see the key "pel.ministry" translated
        And I should see the key "pel.inside" translated
        And I should see the key "pel.and.overseas" translated
        And I should see the key "pel.header.baseline" translated

    @func
    Scenario: I can see the declarant status inputs
        Then I should see 3 "input[type=radio][name='identity[declarantStatus]']" elements
        And I should see "Victime" in the "label[for=identity_declarantStatus_0]" element
        And I should see "Représentant légal d'une personne physique" in the "label[for=identity_declarantStatus_1]" element
        And I should see "Représentant légal d'une personne morale" in the "label[for=identity_declarantStatus_2]" element

    @javascript
    Scenario: I can switch to afghanistan phone country and submit a valid phone number
        And I click the ".iti__flag-container" element
        And I click the "li[data-country-code=af]" element
        And I fill in "identity_contactInformation_phone_number" with "70 123 4567"
        Then I should not see a "#form-errors-identity_contactInformation_phone_number" element

    @javascript
    Scenario: I cannot enter invalid chars for phone input
        When I fill in "identity_contactInformation_phone_number" with "abcd6 01 02 03 04$."
        Then the "identity_contactInformation_phone_number" field should contain "6 01 02 03 04"

    @javascript
    Scenario: I should see a error message when number is wrong
        When I fill in "identity_contactInformation_phone_number" with "00010203040506"
        Then I should see the key "pel.phone.is.invalid" translated
