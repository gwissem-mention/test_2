Feature:
    In order to show complaint continuation page
    As a user
    I need to see an page with 2 radio buttons blocks

    @func
    Scenario Outline: I can see the material damage radio buttons
        Given I am on "/poursuivre"
        Then I should see 2 "input[type=radio][name='continuation[materialDamage]']" elements
        And I should see "<material_damage>" in the "<element>" element
        And I should see the key "pel.complaint.continuation.material.damage" translated

        Examples:
            | element                                  | material_damage |
            | label[for=continuation_materialDamage_0] | Oui             |
            | label[for=continuation_materialDamage_1] | Non             |

    @javascript
    Scenario Outline: I can see the offense author known radio buttons when I select "Yes" to the material damage radio buttons
        Given I am on "/poursuivre"
        When I click the "label[for=continuation_materialDamage_0]" element
        And I wait for the element "#form-offense-author-known" to appear
        #Then I should see 2 "input[type=radio][name='continuation[offenseAuthorKnown]']" elements
        And I should see "<offense_author_known>" in the "<element>" element
        And I should see the key "pel.complaint.continuation.offense.author.known" translated

        Examples:
            | element                                      | offense_author_known |
            | label[for=continuation_offenseAuthorKnown_0] | Oui                  |
            | label[for=continuation_offenseAuthorKnown_1] | Non                  |

    @javascript
    Scenario: I am redirected to / when I select "No" to the material damage radio buttons
        Given I am on "/poursuivre"
        When I click the "label[for=continuation_materialDamage_1]" element
        Then I am redirected on "/"

    @javascript
    Scenario: I am redirected to / when I select "Yes" to the offense author known radio buttons
        Given I am on "/poursuivre"
        And I click the "label[for=continuation_materialDamage_0]" element
        And I wait for the element "#continuation_offenseAuthorKnown_0" to appear
        When I click the "label[for=continuation_offenseAuthorKnown_0]" element
        Then I am redirected on "/"

    @javascript
    Scenario: I am redirected to /accueil-deroule when I select "No" to the offense author known radio buttons
        Given I am on "/poursuivre"
        And I click the "label[for=continuation_materialDamage_0]" element
        And I wait for the element "#continuation_offenseAuthorKnown_1" to appear
        When I click the "label[for=continuation_offenseAuthorKnown_1]" element
        And I wait for the element "#continuation_continue" to appear
        And I press "Continuer"
        Then I am redirected on "/accueil-deroule"
