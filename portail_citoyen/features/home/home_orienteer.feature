Feature:
    In order to show the orienteer homepage
    As a user
    I need to see a page with a 2 texts and 2 links

    @func
    Scenario: Show homepage lvl2 on /accueil-orienteur route with 200 status code
        Given I am on "/accueil-orienteur"
        Then the response status code should be 200
        And I should see 1 "body" element
        And I should see the key "pel.home.orienteer.information.message.1" translated
        And I should see the key "pel.home.orienteer.information.message.2" translated
        And I should see the key "pel.home.orienteer.information.message.3" translated
        And I should see the key "pel.home.orienteer.legal.message.1" translated
        And I should see the key "pel.home.orienteer.legal.message.2" translated
        And I should see the key "pel.ministry" translated
        And I should see the key "pel.inside" translated
        And I should see the key "pel.and.overseas" translated
        And I should see the key "pel.article.10.2" translated
        And I should see the key "pel.article.d.8.2.2" translated
        And I should see 1 "input[type=checkbox]" element
        And the checkbox "consent_agree" should be unchecked
        And I should see 1 "label" element
        And I should see the key "pel.visitor.agree" translated
        And I should see 2 "p" element
        And I should see the key "pel.law.informations" translated
        And I should see 1 "button" element
        And I should see the key "pel.start.redacting.my.online.complaint" translated
        And I should see the key "pel.file.a.complaint" translated
        And I should see the key "pel.faq" translated

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

    @func
    Scenario: Click on the start complaint button with consent_agree checkbox checked
        Given I am on "/accueil-orienteur"
        When I check "consent_agree"
        And I press "start_redacting_my_online_complaint"
        Then I should be on "/authentification"

    @func
    Scenario: Click on the start complaint button with consent_agree checkbox not checked
        Given I am on "/accueil-orienteur"
        When I press "start_redacting_my_online_complaint"
        Then I should be on "/accueil-orienteur"
