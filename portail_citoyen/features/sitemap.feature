Feature:
    In order to show the sitemap page
    As a user
    I need to be able to see the sitemap page

    @func
    Scenario: I am on the sitemap page, I can see the sitemap list
        Given I am on "/infos/plan-du-site"
        Then the response status code should be 200
        And I should see "Plan du site - Plainte en ligne" in the "title" element
        When I follow "Accueil"
        Then I should be on "/"
        Given I am on "/infos/plan-du-site"
        When I follow "Centre d’aide"
        Then I should be on "/faq"
        Given I am on "/infos/plan-du-site"
        When I follow "Je trouve la démarche adaptée à ma situation"
        Then I should be on "/cgu"
        Given I am on "/infos/plan-du-site"
        When I follow "Complétez votre déclaration en ligne"
        Then I should be on "/accueil-confirmation"
        Given I am on "/infos/plan-du-site"
        When I follow "Je suis dans une autre situation"
        Then I should be on "/cgu"
        Given I am on "/infos/plan-du-site"
        When I follow "Conditions générales d'utilisation"
        Then I should be on "/conditions-generales-dutilisation"
        Given I am on "/infos/plan-du-site"
        When I follow "Accessibilité"
        Then I should be on "/infos/accessibilite"
        Given I am on "/infos/plan-du-site"
        When I follow "Mentions légales"
        Then I should be on "/infos/mentions-legales"
        Given I am on "/infos/plan-du-site"
        When I follow "Données personnelles"
        Then I should be on "/infos/donnees-personnelles"
        Given I am on "/infos/plan-du-site"
        When I follow "Gestion des cookies"
        Then I should be on "/infos/gestion-des-cookies"
