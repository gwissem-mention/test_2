Feature:
  In order to fill a complaint
  As a user
  I want to see the offense nature step page

  @func
  Scenario: I can click on the identity breadcrumb
    Given I am on "/declaration/nature-infraction"
    When I follow "Identité"
    Then the response status code should be 200
    And I should be on "/declaration/identite"

  @func
  Scenario: I can see the offense nature breadcrumb
    Given I am on "/declaration/nature-infraction"
    Then I should see "Nature de l'infraction" in the ".fr-breadcrumb__list" element


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
    When I select "<offense_nature>" from "Nature de l'infraction"
    And I should see "<offense_nature>" in the ".fr-select" element

    Examples:
      | offense_nature                 |
      | Vol dans/sur véhicule motorisé |
      | Vol de véhicule motorisé       |
      | Autres vols                    |
      | Arnaque                        |
      | Dégradation                    |
      | Autre situation d’AAB          |

  @javascript
  Scenario: I can see a warning text if I select Motorized Vehicle Robbery
    Given I am on "/declaration/nature-infraction"
    When I select "Vol dans/sur véhicule motorisé" from "Nature de l'infraction"
    And I wait for the element "#warning-text" to appear
    Then I should see the key "complaint.offense.nature.warning.text" translated

  @javascript
  Scenario: I can see a warning text if I select House robbing
    Given I am on "/declaration/nature-infraction"
    When I select "Cambriolage" from "Nature de l'infraction"
    And I wait for the element "#warning-text" to appear
    Then I should see the key "complaint.offense.nature.warning.text" translated

  @javascript
  Scenario: I can see a warning text if I select Motorized Vehicle Theft
    Given I am on "/declaration/nature-infraction"
    When I select "Vol de véhicule motorisé" from "Nature de l'infraction"
    And I wait for the element "#warning-text" to appear
    Then I should see the key "complaint.offense.nature.warning.text" translated

  @javascript
  Scenario: I can see a warning text if I select Scam
    Given I am on "/declaration/nature-infraction"
    When I select "Arnaque" from "Nature de l'infraction"
    And I wait for the element "#warning-text" to appear
    Then I should see the key "complaint.offense.nature.warning.text" translated

  @javascript
  Scenario: I can see a textarea field if I select Other AAB
    Given I am on "/declaration/nature-infraction"
    When I select "Autre situation d’AAB" from "Nature de l'infraction"
    And I wait for the element "#aab-text" to be filled
    Then the "#aab-text" element should contain "textarea"
