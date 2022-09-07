Feature:
  In order to show the orienteer homepage
  As a user
  I need to see a page with a text

  @func
  Scenario: Show homepage lvl2 on /accueil-orienteur route with 200 status code
    Given I am on "/accueil-orienteur"
    Then the response status code should be 200
    And I should see 1 "body" element
    And I should see the key "home.orienteer.information.message.1" translated
    And I should see the key "home.orienteer.information.message.2" translated
    And I should see the key "home.orienteer.information.message.3" translated

