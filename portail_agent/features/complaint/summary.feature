Feature:
    In order to show a complaint's summary page
    As a user
    I want to see the summary informations

    @func
    Scenario: I can navigate to the complaint page
        Given I am on "/plainte/recapitulatif/1"
        Then the response status code should be 200
        And I should see a "nav" element
        And I should see a "aside" element
        And I should see a "main" element
        And I should see 3 "button[data-bs-toggle='modal']" element
        And I should see 19 "button" element
        And I should see the key "pel.assign.declaration.to" translated
        And I should see the key "pel.send.to.lrp" translated
        And I should see the key "pel.reject" translated
        And I should see the key "pel.reasign" translated
        And I should see the key "pel.comment" translated
        And I should see the key "pel.summary" translated
        And I should see the key "pel.declarant.identity" translated
        And I should see the key "pel.description.of.facts" translated
        And I should see the key "pel.objects.concerned" translated
        And I should see the key "pel.additional.informations" translated
        And I should see the key "pel.home" translated
        And I should see the key "pel.agent.complaint.online" translated
        And I should see the key "pel.header.baseline" translated
        And I should see the key "pel.search" translated
        And I should see the key "pel.complaint.online.portal" translated
        And I should see the key "pel.fixtures.fsi.type" translated
        And I should see the key "pel.fixtures.fsi.town" translated
        And I should see the key "pel.reporting" translated
        And I should see the key "pel.faq" translated
        And I should see the key "pel.home" translated
        And I should see the key "pel.identification" translated
        And I should see the key "pel.civility" translated
        And I should see the key "pel.born.on" translated
        And I should see the key "pel.at.in" translated
        And I should see the key "pel.department" translated
        And I should see the key "pel.resides.at" translated
        And I should see the key "pel.phone" translated
        And I should see the key "pel.email" translated
        And I should see the key "pel.want.to.receive.sms.notifications" translated
        And I should see the key "pel.is.designated.as" translated
        And I should see the key "pel.sir" translated
        And I should see the key "pel.job" translated
        And I should see the key "pel.nationality" translated
        And I should see "Jean"
        And I should see "DUPONT"
        And I should see "07/03/1967"
        And I should see "France"
        And I should see "Française"
        And I should see "Boulanger"
        And I should see "75"
        And I should see "15 rue PAIRA, Meudon, 92190"
        And I should see "92190"
        And I should see "Meudon"
        And I should see "06 12 34 45 57"
        And I should see "jean.dupont@gmail.com"
        And I should see the key "pel.victim" translated
        And I should see the key "pel.yes" translated
        And I should see the key "pel.description.of.facts" translated
        And I should see the key "pel.nature.of.the.facts" translated
        And I should see the key "pel.nature.place" translated
        And I should see the key "pel.address.start.or.exact" translated
        And I should see the key "pel.address.end" translated
        And I should see the key "pel.facts.date" translated
        And I should see the key "pel.facts.hour" translated
        And I should see the key "pel.between" translated
        And I should see the key "pel.and" translated
        And I should see the key "pel.offense.nature.robbery" translated
        And I should see the key "pel.the" translated
        And I should see the key "pel.between" translated
        And I should see the key "pel.and" translated
        And I should see "Restaurant"
        And I should see "25 Avenue Georges Pompidou, Lyon, 69003"
        And I should see "Place Charles Hernu, Villeurbanne, 69100"
        And I should see "01/12/2022"
        And I should see "10h00"
        And I should see "11h00"
        And I should see the key "pel.objects.description" translated
        And I should see the key "pel.objects.stolen" translated
        And I should see the key "pel.number" translated
        And I should see the key "pel.total" translated
        And I should see the key "pel.total.message.one" translated
        And I should see the key "pel.total.message.amount" translated
        And I should see "Téléphone mobile"
        And I should see "Iphone 13"
        And I should see "Apple"
        And I should see "999 €"
        And I should see "Orange"
        And I should see "Vous avez ajouté 1 objet pour un montant total de 999 €"
        And I should see the key "pel.additional.informations" translated
        And I should see the key "pel.facts.witnesses" translated
        And I should see the key "pel.facts.witnesses.information.text" translated
        And I should see the key "pel.do.you.have.informations.on.potential.suspects" translated
        And I should see the key "pel.facts.suspects.informations.text" translated
        And I should see the key "pel.cctv.present" translated
        And I should see the key "pel.cctv.available" translated
        And I should see the key "pel.fsi.visit" translated
        And I should see the key "pel.observation.made" translated
        And I should see the key "pel.victim.of.violence" translated
        And I should see the key "pel.description.of.facts" translated

    @func
    Scenario: I can click on the menu's summary button
        Given I am on "/plainte/recapitulatif/1"
        When I follow "Récapitulatif"
        And I should be on "/plainte/recapitulatif/1"
        And the response status code should be 200

    @func
    Scenario: I can click on the menu's identity button
        Given I am on "/plainte/recapitulatif/1"
        When I follow "Identité du déclarant"
        Then I should be on "/plainte/identite/1"
        And the response status code should be 200
        And I should see the key "pel.declarant.civil.status" translated
        And the "identity_civility_0" checkbox should not be checked
        And the "identity_civility_1" checkbox should be checked
        And I should see the key "pel.lastname" translated
        And the "identity_lastname" field should contain "DUPONT"
        And I should see the key "pel.firstname" translated
        And the "identity_firstname" field should contain "Jean"
        And I should see the key "pel.birth.date" translated
        And the "identity_birthday" field should contain "1967-03-07"
        And I should see the key "pel.birth.country" translated
        And the "identity_birthCountry" field should contain "France"
        And I should see the key "pel.birth.city" translated
        And the "identity_birthCity" field should contain "Paris"
        And I should see the key "pel.declarant.contact.information" translated
        And I should see the key "pel.phone.number" translated
        And the "identity_phone" field should contain "06 12 34 45 57"
        And I should see the key "pel.email" translated
        And the "identity_email" field should contain "jean.dupont@gmail.com"
        And I should see the key "pel.sms.notification" translated
        And I should see the key "pel.want.to.receive.sms.notifications" translated
        And the "identity_smsNotifications" checkbox should be checked
        And I should see the key "pel.declarant.address" translated
        And the "identity_address" field should contain "15 Rue PAIRA, Meudon, 92190"
        And I should see the key "pel.address.france.label" translated
        And I should see the key "pel.declarant.situation" translated
        And I should see the key "pel.declarant.complain.as" translated
        And the "identity_declarantStatus" field should contain "1"

    @javascript
    Scenario: I can toggle the reject modal
        Given I am on "/plainte/recapitulatif/1"
        When I press "Rejeter"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see the key "pel.you.will.reject.the.declaration" translated
        And I should see the key "pel.refusal.reason" translated
        And I should see the key "pel.appointment.needed" translated
        And I should see the key "pel.reorientation.other.solution" translated
        And I should see the key "pel.absence.of.penal.offense" translated
        And I should see the key "pel.insufisant.quality.to.act" translated
        And I should see the key "pel.victime.carence" translated
        And I should see the key "pel.other" translated
        And I should see the key "pel.free.text" translated
        And I should see the key "pel.back" translated
        And I should see the key "pel.validate.the.refusal" translated
        When I press "complaint-reject-button-back"
        Then I should not see a ".modal[aria-modal=true]" element

    @javascript
    Scenario: I can submit the reject form successfully
        Given I am on "/plainte/recapitulatif/1"
        When I press "Rejeter"
        And I select "1" from "reject_refusalReason"
        And I fill in "reject_refusalText" with "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus scelerisque ante id dui lacinia eu."
        And I press "Valider le refus"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should not see a "complaint-assign-button" element
        And I should not see a "complaint-reject-button" element
        And I should not see a "complaint-send-lrp-button" element
        And I should not see a "complaint-reassign-button" element
        And I should see a ".toast" element
        And I should see the key "pel.the.declaration.has.been.refused" translated
        And I am on "/"
        And I should not see "PEL-2022-00000001"

    @javascript
    Scenario: I can see form errors the reject form when reject_refusalText is too short
        Given I am on "/plainte/recapitulatif/2"
        When I press "Rejeter"
        And I select "1" from "reject_refusalReason"
        And I fill in "reject_refusalText" with "Lorem ipsum dolor sit amet"
        And I press "Valider le refus"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see a ".invalid-feedback" element

    @javascript
    Scenario: I can toggle the send to LRP modal
        Given I am on "/plainte/recapitulatif/3"
        When I press "Envoi à LRP"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see the key "pel.validation.sending.complain.to.lrp" translated
        And I should see the key "pel.complaint.assignation" translated
        And I should see the key "pel.complaint.validation.sending.to.lrp" translated
        And I should see the key "pel.receipt.report" translated
        And I should see the key "pel.complaint.validation.course.to.lrp" translated
        And I should see the key "pel.send.compatible.data" translated
        And I should see the key "pel.data.integration" translated
        And I should see the key "pel.data.modification.by.agent" translated
        And I should see the key "pel.report.generation.in.lrp" translated
        And I should see the key "pel.back" translated
        And I should see the key "pel.validate.the.sending.to.the.lrp" translated
        When I press "complaint-send-to-lrp-button-back"
        Then I should not see a ".modal[aria-modal=true]" element

    @javascript
    Scenario: I can validate the send to LRP action successfully
        Given I am on "/plainte/recapitulatif/3"
        When I press "Envoi à LRP"
        Then I should see a ".modal[aria-modal=true]" element
        When I press "Valider l'envoi vers le LRP"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see a ".toast" element
        And I should see the key "pel.file.generated" translated

    @javascript
    Scenario: I can toggle the assign modal
        Given I am on "/plainte/recapitulatif/3"
        When I press "Attribuer la déclaration à..."
        Then I should see a ".modal[aria-modal=true]" element
        And I should see the key "pel.select.the.agent.to.assign" translated
        And I should see the key "pel.back" translated
        And I should see the key "pel.validate.the.assignment" translated
        When I press "complaint-assign-button-back"
        Then I should not see a ".modal[aria-modal=true]" element

    @javascript
    Scenario: I can submit the assign form successfully
        Given I am on "/plainte/recapitulatif/3"
        When I press "Attribuer la déclaration à..."
        And I fill in the autocomplete "assign_assignedTo-ts-control" with "Thomas" and click "2"
        And I press "Valider l'attribution"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see a ".toast" element
        And I should see the key "pel.the.declaration.has.been.assigned.to" translated
