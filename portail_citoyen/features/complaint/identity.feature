@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the complaint identity page

    Background:
        Given I am on "/authentification"
        And I press "no_france_connect_auth_button"
        And I follow "no_france_connect_auth_button_confirm"
        Then I should be on "/porter-plainte/rappel-a-la-loi"
        And I click the "label[for=law_refresher_lawRefresherAccepted]" element
        And I press "law_refresher_submit"
        Then I should be on "/porter-plainte/identite"

    Scenario: I can click on the back button
        When I follow "Étape précédente"
        Then I should be on "/porter-plainte/rappel-a-la-loi"

    Scenario: I can see the family situation choice list
        And I should see "Célibataire" in the "#identity_civilState_familySituation" element
        And I should see "Concubinage" in the "#identity_civilState_familySituation" element
        And I should see "Marié(e)" in the "#identity_civilState_familySituation" element
        And I should see "Divorcé(e)" in the "#identity_civilState_familySituation" element
        And I should see "Pacsé(e)" in the "#identity_civilState_familySituation" element
        And I should see "Veuf(ve)" in the "#identity_civilState_familySituation" element

    Scenario: I can switch to afghanistan phone country and submit a valid phone number
        When I click the ".iti__flag-container" element
        And I click the "li[data-country-code=af]" element
        And I fill in "identity_contactInformation_mobile_number" with "70 123 4567"
        Then I should not see a "#form-errors-identity_contactInformation_mobile_number" element

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

    Scenario: When I follow the link "Foire aux questions", I should see the FAQ page on a new tab
        Given I am on "/porter-plainte/identite"
        When I follow "Foire aux questions"
        Then The page should open in a new tab and I switch to it
        And I should be on "/faq"
        And I close the current window
