Feature:
  In order to show the orienteer homepage
  As a user
  I need to see a page with a 2 texts and 2 links

  @func
  Scenario: Show homepage lvl2 on /accueil-orienteur route with 200 status code
    Given I am on "/accueil-orienteur"
    Then the response status code should be 200
    And I should see 1 "body" element
    And I should see the key "home.orienteer.information.message.1" translated
    And I should see the key "home.orienteer.information.message.2" translated
    And I should see the key "home.orienteer.information.message.3" translated
    And I should see the key "home.orienteer.legal.message.1" translated
    And I should see the key "home.orienteer.legal.message.2" translated
    And I should see the key "article.10.2" translated
    And I should see the key "article.d.8.2.2" translated

  @func
  Scenario: Click on "Article 10-2" link
    Given I am on "/accueil-orienteur"
    Then I follow "Article 10-2"
    And I should be on "https://www.legifrance.gouv.fr/codes/article_lc/LEGIARTI000044569870"

  @func
  Scenario: Click on "Article D8-2-2" link
    Given I am on "/accueil-orienteur"
    Then I follow "Article D8-2-2"
    And I should be on "https://www.legifrance.gouv.fr/codes/article_lc/LEGIARTI000038552381"
