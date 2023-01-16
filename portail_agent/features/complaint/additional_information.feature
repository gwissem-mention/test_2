Feature:
    In order to the complaint additional information page
    As a user
    I want to see the complaint additional information page

    @func
    Scenario: I want to show complaint additional information page
        Given I am on "/plainte/informations-complementaires/1"
        Then the response status code should be 200
        And I should see a "body" element
        And I should see a "nav" element
        And I should see a "aside" element
        And I should see a "main" element
        And I should see 4 "button[data-bs-toggle='modal']" element
        And I should see 20 "button" element
        And I should see the key "pel.send.to.lrp" translated
        And I should see the key "pel.reject" translated
        And I should see the key "pel.reasign" translated
        And I should see the key "pel.comment" translated
        And I should see the key "pel.summary" translated
        And I should see the key "pel.photos.and.videos" translated
        And I should see the key "pel.cctv.present" translated
        And the "additional_information_cctvPresent_0" checkbox should be checked
        And I should see the key "pel.cctv.available" translated
        And the "additional_information_cctvAvailable_1" checkbox should be checked
        And I should see the key "pel.suspects" translated
        And I should see the key "pel.do.you.have.informations.on.potential.suspects" translated
        And the "additional_information_suspectsKnown_0" checkbox should be checked
        And I should see the key "pel.facts.suspects.informations.text" translated
        And the "additional_information_suspectsKnownText" field should contain "2 hommes"
        And I should see the key "pel.witnesses" translated
        And I should see the key "pel.facts.witnesses" translated
        And the "additional_information_witnessesPresent_0" checkbox should be checked
        And I should see the key "pel.facts.witnesses.information.text" translated
        And the "additional_information_witnessesPresentText" field should contain "Paul DUPONT"
        And I should see the key "pel.fsi.intervention" translated
        And I should see the key "pel.fsi.visit" translated
        And the "additional_information_fsiVisit_0" checkbox should be checked
        And I should see the key "pel.observation.made" translated
        And the "additional_information_observationMade_0" checkbox should be checked
        And I should see the key "pel.physical.violences.and.threats" translated
        And I should see the key "pel.victim.of.violence" translated
        And the "additional_information_victimOfViolence_1" checkbox should be checked
        And I should see the key "pel.description.of.facts" translated
        And the "additional_information_description" field should contain "Vol d'un Iphone 13"
