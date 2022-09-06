Feature:
  In order to show the orienteer homepage
  As a user
  I need to see an empty page

  @func
  Scenario: Show homepage on /accueil-orienteur route with 200 status code
    Given I am on "/accueil-orienteur"
    Then the response status code should be 200
    And I should see 1 "body" element
