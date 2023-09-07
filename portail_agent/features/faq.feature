Feature:
    In order to show the faq
    As a user
    I want to see the faq page

    Background:
        Given I am authenticated with PR5KTZ9R from GN

    @func
    Scenario: I can see a sidebar with current user information
        Given I am on "/faq"
        Then I should see the key "pel.profile" translated
        And I should see the key "pel.close" translated
        And I should see the key "pel.display.settings" translated
        And I should see the key "pel.settings" translated
        And I should see the key "pel.rights.delegation" translated
        And I should see the key "pel.select.the.delegation.period" translated
        And I should see the key "pel.selected.delegation.period" translated
        And I should see the key "pel.from" translated
        And I should see the key "pel.to" translated
        And I should see the key "pel.select.one.or.more.delegated.agents" translated
        And I should see the key "pel.logout" translated

    @func
    Scenario: I want to show the faq page
        When I am on "/faq"
        Then the response status code should be 200
        And I should see a "nav" element
        And I should see the key "pel.faq" translated
        And I should see a ".fr-search-bar" element
        And I should see 4 ".col-lg-3" elements
        And I should see the key "pel.video.tutorial" translated
        And I should see the key "pel.questions.answers" translated
        And I should see the key "pel.regulatory.information" translated
        And I should see the key "pel.useful.numbers" translated
        And I should see a ".fr-accordions-group" element
        And I should see "Qu’est-ce qu’un témoin ?"
        And I should see "Qu’est-ce qu’un suspect ?"
        And I should see "Qu'est-ce qu'une plainte ?"

    @javascript
    Scenario: I can click the first FAQ question
        When I am on "/faq"
        When I click the "#heading-1" element
        Then I should see "Il s’agit de toute personne qui aurait assisté aux faits dont vous avez été victime et qui accepterait de témoigner pour vous."

    @javascript
    Scenario: I can click the second FAQ question
        When I am on "/faq"
        When I click the "#heading-2" element
        Then I should see "Il s’agit de la personne qui a potentiellement commis les faits dont vous avez été victime."

    @javascript
    Scenario: I can click the third FAQ question
        When I am on "/faq"
        When I click the "#heading-3" element
        Then I should see "Le dépôt de plainte permet à une victime d’infraction pénale d'informer le procureur de la République."
