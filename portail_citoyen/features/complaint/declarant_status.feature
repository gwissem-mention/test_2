@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the declarant status step form

    Scenario: I can see the place natures list
        Given I am on "/porter-plainte/rappel-a-la-loi"
        And I click the "label[for=law_refresher_lawRefresherAccepted]" element
        And I press "law_refresher_submit"
        Then I should be on "/porter-plainte/identite"
        And I should see "Étape 2 sur 7"
        And I should see "Votre identité"
        And I should see "Description des faits"
        And I should see the key "pel.all.fields.marked.with.an.asterisk.are.mandatory" translated
        And I should see the key "pel.complaint.your.identity" translated
        And I should see the key "pel.complaint.identity.declarant.status" translated
        And I should see a "button[type=submit]#identity_submit" element

    Scenario: I can see the declarant status inputs
        Given I am on "/porter-plainte/rappel-a-la-loi"
        And I click the "label[for=law_refresher_lawRefresherAccepted]" element
        And I press "law_refresher_submit"
        Then I should be on "/porter-plainte/identite"
        # Change these values when the Person Legal Representative is reenabled
        Then I should see 2 "input[type=radio][name='identity[declarantStatus]']" elements
        And I should see "Vous êtes victime ou vous représentez votre enfant mineur" in the "label[for=identity_declarantStatus_0]" element
#        And I should see "Représentant légal d’une personne physique" in the "label[for=identity_declarantStatus_1]" element
        And I should see "Vous êtes dirigeant, ou mandataire d'une personne morale" in the "label[for=identity_declarantStatus_1]" element
