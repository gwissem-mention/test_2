Feature:
    In order to fill a complaint
    As a user
    I want to see the declarant status step form

    @func
    Scenario: I can see the place natures list
        Given I am on "/porter-plainte/statut-declarant"
        Then the response status code should be 200
        And I should see "Statut du déclarant" in the ".fr-breadcrumb__list" element
        And I should see "Identité" in the ".fr-breadcrumb__list" element
        And I should see "Faits" in the ".fr-breadcrumb__list" element
        And I should see "Objets concernés" in the ".fr-breadcrumb__list" element
        And I should see "Informations complémentaires" in the ".fr-breadcrumb__list" element
        And I should see "Récapitulatif de déclaration de plainte initiale" in the ".fr-breadcrumb__list" element
        And I should see the key "pel.ministry" translated
        And I should see the key "pel.inside" translated
        And I should see the key "pel.and.overseas" translated
        And I should see the key "pel.header.baseline" translated
        And I should see the key "pel.complaint.my.declaration" translated
        And I should see the key "pel.complaint.all.required.fields.are.required" translated
        And I should see the key "pel.complaint.your.identity" translated
        And I should see the key "pel.complaint.identity.declarant.status" translated
        And I should see a "button[type=submit]#declarant_status_submit" element

    @func
    Scenario: I can see the declarant status inputs
        Given I am on "/porter-plainte/statut-declarant"
        Then I should see 3 "input[type=radio][name='declarant_status[declarantStatus]']" elements
        And I should see "Victime" in the "label[for=declarant_status_declarantStatus_0]" element
        And I should see "Représentant légal d'une personne physique" in the "label[for=declarant_status_declarantStatus_1]" element
        And I should see "Représentant légal d'une personne morale" in the "label[for=declarant_status_declarantStatus_2]" element

    @javascript
    Scenario: I can submit the form
        Given I am on "/porter-plainte/statut-declarant"
        When I click the "label[for=declarant_status_declarantStatus_0]" element
        And I press "declarant_status_submit"
        Then I should be on "/authentification"
