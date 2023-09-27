Feature:
    As a user, I should be able to see notifications

    Background:
        Given I am authenticated with ZSBVHOAY from PN

    @javascript
    Scenario: As a supervisor if a complaint has violence, I can see the relative notification
        Given I am on the homepage
        When I click the "#notifications-dropdown" element
        And I should see "La déclaration PEL-2023-00000001 est une urgence violence"

    @javascript
    Scenario: As a supervisor if a complaint has robbery and degradation, I can see the relative notification
        Given I am on the homepage
        When I click the "#notifications-dropdown" element
        And I should see "La déclaration PEL-2023-00000001 est une urgence PTS"

    @javascript
    Scenario: As a supervisor if a complaint has a stolen registered vehicle, I can see the relative notification
        Given I am on the homepage
        When I click the "#notifications-dropdown" element
        And I should see "La déclaration PEL-2023-00000001 est une urgence vol de véhicule"

