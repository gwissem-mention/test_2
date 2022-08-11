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
