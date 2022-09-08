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
  Scenario: I can see a warning text if I select House robbery
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
    And I wait for the element "#other-aab-field" to be filled
    Then the "#other-aab-field" element should contain "Préciser le type d'infraction"
    And the "#other-aab-field" element should contain "textarea"

  @javascript
  Scenario Outline: I can see 2 radio buttons for FSI visit if I select House robbery
    Given I am on "/declaration/nature-infraction"
    When I select "Cambriolage" from "Nature de l'infraction"
    And I wait for the element "#house-robbery-fields" to be filled
    Then the "#house-robbery-fields" element should contain "Des FSI sont-ils intervenus sur les lieux de l'infraction ?"
    Then I should see 2 "input[type=radio][name='offense_nature[fsiVisit]']" elements
    And I should see "<fsi_visit>" in the "<element>" element

    Examples:
      | element                              | fsi_visit |
      | label[for=offense_nature_fsiVisit_0] | Oui       |
      | label[for=offense_nature_fsiVisit_1] | Non       |

  @javascript
  Scenario Outline: I can see 2 radio buttons for technical police visit if I select House robbery
    Given I am on "/declaration/nature-infraction"
    When I select "Cambriolage" from "Nature de l'infraction"
    And I wait for the element "#house-robbery-fields" to be filled
    Then the "#house-robbery-fields" element should contain "La police technique et scientifique est-elle intervenue sur les lieux de l'infraction ?"
    Then I should see 2 "input[type=radio][name='offense_nature[technicalPoliceVisit]']" elements
    And I should see "<technical_police_visit>" in the "<element>" element

    Examples:
      | element                                          | technical_police_visit |
      | label[for=offense_nature_technicalPoliceVisit_0] | Oui                    |
      | label[for=offense_nature_technicalPoliceVisit_1] | Non                    |
