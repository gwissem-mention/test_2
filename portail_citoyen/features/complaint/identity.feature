Feature:
  In order to fill a complaint
  As a user
  I want to see the identity step page

  @func
  Scenario: I can see the identity breadcrumb
    Given I am on "/declaration/identite"
    Then I should see "Identité" in the ".fr-breadcrumb__list" element

  @func
  Scenario: I can click on the next button
    Given I am on "/declaration/identite"
    When I follow "Suivant"
    Then the response status code should be 200
    And I should be on "/declaration/nature-infraction"

  @func
  Scenario: I can see a the declarant status label
    Given I am on "/declaration/identite"
    Then I should see "Statut du déclarant"

  @func
  Scenario Outline: I can see the declarant status inputs
    Given I am on "/declaration/identite"
    Then I should see 3 "input[type=radio][name='identity[declarantStatus]']" elements
    And I should see "<declarant_status>" in the "<element>" element

    Examples:
      | element                               | declarant_status                           |
      | label[for=identity_declarantStatus_0] | Victime                                    |
      | label[for=identity_declarantStatus_1] | Représentant légal d'une personne morale   |
      | label[for=identity_declarantStatus_2] | Représentant légal d'une personne physique |

  @javascript
  Scenario: I can select the Victim radio button
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_0']" element
    And I wait for the element "#identity_identity" to appear
    Then I should see "Victime" in the "#form-identity" element

  @javascript
  Scenario: I can select the Corporation Legal Representative radio button
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_1']" element
    And I wait for the element "#identity_identity" to appear
    Then I should see "Représentant légal d'une personne morale" in the "#form-identity" element

  @javascript
  Scenario: I can select the Person Legal Representative radio button
    Given I am on "/declaration/identite"
    When I click the "label[for='identity_declarantStatus_2']" element
    And I wait for the element "#identity_identity" to appear
    Then I should see "Représentant légal d'une personne physique" in the "#form-identity" element
