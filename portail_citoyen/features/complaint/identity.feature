Feature:
    In order to fill a complaint
    As a user
    I want to see the identity step page

    @func
    Scenario: I can see the identity breadcrumb
        Given I am on "/declaration/identite"
        Then I should see the key "ministry" translated
        And I should see the key "inside" translated
        And I should see the key "and.overseas" translated
        And I should see "Identité" in the ".fr-breadcrumb__list" element

    @func
    Scenario: I can see a the declarant status label
        Given I am on "/declaration/identite"
        Then I should see the key "complaint.identity.declarant.status" translated

    @func
    Scenario Outline: I can see the declarant status inputs
        Given I am on "/declaration/identite"
        Then I should see 3 "input[type=radio][name='identity[declarantStatus]']" elements
        And I should see "<declarant_status>" in the "<element>" element

        Examples:
            | element                               | declarant_status                           |
            | label[for=identity_declarantStatus_0] | Victime                                    |
            | label[for=identity_declarantStatus_1] | Représentant légal d'une personne physique |
            | label[for=identity_declarantStatus_2] | Représentant légal d'une personne morale   |
