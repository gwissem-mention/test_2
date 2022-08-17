Feature:
    In order to show place nature page
    As a user
    I need to see a select box with nature places

    @func
    Scenario Outline: Show place nature page and select a nature place
        Given I am on "/place/nature"
        Then the response status code should be 200
        And I should see 1 "body" element
        When I select "<nature_place>" from "place_nature_place"
        And I should see "<nature_place>" in the ".fr-select" element

        Examples:
            | nature_place           |
            | Domicile/Logement      |
            | Parking / garage       |
            | Voie publique / Rue    |
            | Commerce               |
            | Transports en commun   |
            | Autres natures de lieu |
