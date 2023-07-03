@javascript
Feature:
    In order to show all my complaints reports available on Oodrive
    As a user
    I need to be able to connect to FranceConnect and see my complaints reports

    Scenario: I can connect to FranceConnect and see my complaints reports
        Given I am on "/mes-pv-de-plaintes"
        Then I should see the key "pel.my.complaints.reports" translated
        When I press "france_connect_auth_button"
        Then I should be on "/mes-pv-de-plaintes"
        And I should see "Plainte du 05/01/2023"
        And I should see "Plainte du 01/01/2023"
        And I should see "Télécharger test1.pdf"
        And I should see "Télécharger test2.pdf"
        And I should see "Télécharger test3.pdf"
        And I should see "Télécharger ZIP"


