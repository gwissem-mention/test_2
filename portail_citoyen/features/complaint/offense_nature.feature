Feature:
    In order to fill a complaint
    As a user
    I want to see the offense nature step page

    @func
    Scenario: I can click on the identity breadcrumb
        Given I am on "/declaration/nature-infraction"
        When I follow "Identité"
        Then the response status code should be 200
        And I should see the key "ministry" translated
        And I should see the key "inside" translated
        And I should see the key "and.overseas" translated
        And I should see the key "file.a.complaint" translated
        And I should see the key "faq" translated
        And I should be on "/declaration/identite"

    @func
    Scenario: I can see the offense nature breadcrumb
        Given I am on "/declaration/nature-infraction"
        Then I should see "Nature des faits" in the ".fr-breadcrumb__list" element


    @func
    Scenario: I can click on the previous button
        Given I am on "/declaration/nature-infraction"
        When I follow "Précédent"
        Then the response status code should be 200
        And I should be on "/declaration/identite"

    @func
    Scenario: I can click on the next button
        Given I am on "/declaration/nature-infraction"
        When I follow "Suivant"
        Then the response status code should be 200
        And I should be on "/declaration/lieu"

    @func
    Scenario Outline: I can see the offense nature list
        Given I am on "/declaration/nature-infraction"
        When I select "<offense_nature>" from "Nature des faits"
        And I should see "<offense_nature>" in the ".fr-select" element

        Examples:
            | offense_nature           |
            | Vol                      |
            | Dégradation              |
            | Autre atteinte aux biens |

    @javascript
    Scenario: I can see a warning text if I select Motorized Vehicle Robbery
        Given I am on "/declaration/nature-infraction"
        When I select "Vol" from "Nature des faits"
        And I wait for the element "#complaint_offense_nature_warning_text" to appear
        Then I should see the key "complaint.offense.nature.warning.text" translated

    @javascript
    Scenario: I can see a warning text if I select Scam
        Given I am on "/declaration/nature-infraction"
        And I select "Dégradation" from "Nature des faits"

    @javascript
    Scenario: I can see a textarea field if I select Other AAB
        Given I am on "/declaration/nature-infraction"
        When I select "Autre atteinte aux biens" from "Nature des faits"
        And I wait for the element "#offense_nature_aabText" to appear
        And I should see the key "complaint.offense.nature.other.aab.text" translated
