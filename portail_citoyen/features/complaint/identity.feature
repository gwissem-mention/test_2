@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the complaint identity page

    Background:
        Given I am on "/porter-plainte/statut-declarant"
        And I click the "label[for=declarant_status_declarantStatus_0]" element
        And I press "declarant_status_submit"
        And I follow "Continuer sans m'authentifier"
        And I follow "Je confirme"
        Then I should be on "/porter-plainte/identite"

    Scenario: I can click on the back button
        When I follow "Précédent"
        Then I should be on "/porter-plainte/statut-declarant"

    Scenario: I can see the complaint identity page
        And I should see "Statut du déclarant" in the ".fr-breadcrumb__list" element
        And I should see "Identité" in the ".fr-breadcrumb__list" element
        And I should see "Faits" in the ".fr-breadcrumb__list" element
        And I should see "Objets concernés" in the ".fr-breadcrumb__list" element
        And I should see "Informations complémentaires" in the ".fr-breadcrumb__list" element
        And I should see "Récapitulatif de déclaration de plainte initiale" in the ".fr-breadcrumb__list" element
        And I should see the key "pel.ministry" translated
        And I should see the key "pel.inside" translated
        And I should see the key "pel.and.overseas" translated
        And I should see the key "pel.header.baseline" translated

    Scenario: I can switch to afghanistan phone country and submit a valid phone number
        When I click the ".iti__flag-container" element
        And I click the "li[data-country-code=af]" element
        And I fill in "identity_contactInformation_mobile_number" with "70 123 4567"
        Then I should not see a "#form-errors-identity_contactInformation_mobile_number" element

    @flaky
    Scenario: I cannot enter invalid chars for phone input
        When I fill in "identity_contactInformation_phone_number" with "abcd6 01 02 03 04$."
        Then the "identity_contactInformation_phone_number" field should contain "6 01 02 03 04"

    Scenario: I should see a error message when number is wrong
        When I fill in "identity_contactInformation_phone_number" with "00010203040506"
        Then I should see the key "pel.phone.is.invalid" translated

    Scenario: I cannot enter non mobile number in mobile field
        When I fill in "identity_contactInformation_mobile_number" with "0102030405"
        Then I should see a "#form-errors-identity_contactInformation_mobile_number" element
        And I should see the key "pel.phone.mobile.error" translated

    Scenario: I cannot enter non fixe number in fixe field
        When I fill in "identity_contactInformation_phone_number" with "0602030405"
        Then I should see a "#form-errors-identity_contactInformation_phone_number" element
        And I should see the key "pel.phone.fixe.error" translated

    Scenario: I can enter a mobile number in mobile field
        When I fill in "identity_contactInformation_mobile_number" with "0602030405"
        Then I should not see a "#form-errors-identity_contactInformation_mobile_number" element
        And I should not see the key "pel.phone.mobile.error" translated

    Scenario: I can enter a fixe number in fixe field
        When I fill in "identity_contactInformation_phone_number" with "0102030405"
        Then I should not see a "#form-errors-identity_contactInformation_phone_number" element
        And I should not see the key "pel.phone.fixe.error" translated
