Feature:
    In order to show a complaint's summary page
    As a user
    I want to see the summary informations

    Background:
        Given I am authenticated with PR5KTZ9R from GN

    @func
    Scenario: I can access the complaint's summary page if the complaint is assigned to my unit, but I can't access a complaint assigned to another unit
        Given I am on "/plainte/recapitulatif/91"
        And the response status code should be 200
        Then I am on "/plainte/recapitulatif/1"
        And the response status code should be 403

    @func
    Scenario: I can access the complaint's summary page if the complaint is assigned to me, but I can't access other complaints
        Given I am authenticated with PR5KTQSD from GN
        And I am on "/plainte/recapitulatif/101"
        And the response status code should be 200
        Then I am on "/plainte/recapitulatif/91"
        And the response status code should be 403

    @func
    Scenario: I can navigate to the complaint page
        Given I am on "/plainte/recapitulatif/91"
        Then the response status code should be 200
        And I should see a "nav" element
        And I should see a "aside" element
        And I should see a "main" element
        And I should see 4 "button[data-bs-toggle='modal']" element
        And I should see 20 "button" element
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
        And I should see the key "pel.complaint.online.portal" translated
        And I should see "Brigade de proximité de Voiron"
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
        And I should see "Voiture"
        And I should see "Citroën"
        And I should see "C3"
        And I should see "17 000 €"
        And I should see "Carte Bancaire VISA"
        And I should see "Visa principale"
        And I should see "LCL"
        And I should see "Permis de conduire"
        And I should see "Blouson"
        And I should see "Blouson Adidas de couleur bleu"
        And I should see "100 €"
        And I should see "Vous avez ajouté 5 objets pour un montant total de 18 099 €"
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
        Given I am on "/plainte/recapitulatif/91"
        When I follow "Récapitulatif"
        And I should be on "/plainte/recapitulatif/91"
        And the response status code should be 200

    @func
    Scenario: I can click on the Go to identity button
        Given I am on "/plainte/recapitulatif/91"
        When I follow "Accéder à l'onglet : Identité du déclarant"
        And I should be on "/plainte/identite/91"
        And the response status code should be 200

    @javascript
    Scenario: I can toggle the reject modal
        Given I am on "/plainte/recapitulatif/91"
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
        Given I am on "/plainte/recapitulatif/91"
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
        And I should see 1 ".btn-danger" element

    @javascript
    Scenario: I can see form errors the reject form when reject_refusalText is too short
        Given I am on "/plainte/recapitulatif/92"
        When I press "Rejeter"
        And I select "1" from "reject_refusalReason"
        And I fill in "reject_refusalText" with "Lorem ipsum dolor sit amet"
        And I press "Valider le refus"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see a ".invalid-feedback" element

    @javascript
    Scenario: I can toggle the send to LRP modal
        Given I am on "/plainte/recapitulatif/93"
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
        Given I am on "/plainte/recapitulatif/93"
        When I press "Envoi à LRP"
        Then I should see a ".modal[aria-modal=true]" element
        When I press "Valider l'envoi vers le LRP"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see a ".toast" element
        And I should see the key "pel.file.generated" translated
        Given I am on the homepage
        Then I should see 6 ".btn-warning" element

    @javascript
    Scenario: I can toggle the assign modal
        Given I am on "/plainte/recapitulatif/93"
        When I press "Attribuer la déclaration à..."
        Then I should see a ".modal[aria-modal=true]" element
        And I should see the key "pel.select.the.agent.to.assign" translated
        And I should see the key "pel.back" translated
        And I should see the key "pel.validate.the.assignment" translated
        When I press "complaint-assign-button-back"
        Then I should not see a ".modal[aria-modal=true]" element

    @javascript
    Scenario: I can submit the assign form successfully and Jean DUPONT should have a notif
        Given I am on "/plainte/recapitulatif/93"
        When I press "Attribuer la déclaration à..."
        And I fill in the autocomplete "assign_assignedTo-ts-control" with "Julie" and click "4"
        And I press "Valider l'attribution"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see a ".toast" element
        And I should see the key "pel.the.declaration.has.been.assigned.to" translated
        And I should see "Déclaration attribuée à : Julie RICHARD"
        And I should see the key "pel.declaration.assigned.to" translated
        Given I am authenticated with PR5KTZ9C from GN
        And I am on the homepage
        When I click the "#notifications-dropdown" element
        Then I should see "La déclaration PEL-2023-00000093 vient de vous être attribuée"

    @javascript
    Scenario: I can submit the reassign form successfully
        Given I am on "/plainte/recapitulatif/93"
        When I press "Attribuer la déclaration à..."
        And I fill in the autocomplete "assign_assignedTo-ts-control" with "Julie" and click "4"
        And I press "Valider l'attribution"
        When I press "complaint-reassign-button"
        And I click the "#modal-complaint-assign .clear-button" element
        And I fill in the autocomplete "assign_assignedTo-ts-control" with "Philippe" and click "5"
        And I press "Valider la réattribution"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see a ".toast" element
        And I should see the key "pel.the.declaration.has.been.assigned.to" translated
        And I should see "Déclaration attribuée à : Philippe RIVIERE"
        And I should see the key "pel.declaration.assigned.to" translated

    @func
    Scenario: I can see the comments space on the summary page
        Given I am on "/plainte/recapitulatif/93"
        Then I should see a "#comment_content" element
        And I should see a ".comment-box" element
        And I should see a "#comments-feed-title" element
        And I should see the key "pel.comments.feed" translated
        And the "#comments-feed-title" element should contain "Espace commentaires (5)"
        And I should see 3 ".comment-left" element
        And I should see 2 ".comment-right" element
        And I should see 5 "#comment-author" element
        And I should see 5 "#comment-published-at" element
        And I should see "Jean Dupont" in the ".comment-right" element
        And I should see "André Durant" in the ".comment-left" element

    @javascript
    Scenario: I can click the "Comment" button, and it focus the comment field
        Given I am on "/plainte/recapitulatif/93"
        And I should not focus the "comment_content" element
        Then I press "complaint-comment-button"
        And I should focus the "comment_content" element

    @javascript
    Scenario: I can add a comment from the summary page
        Given I am on "/plainte/recapitulatif/97"
        And the "#comments-feed-title" element should contain "Espace commentaires (5)"
        Then I fill in "comment_content" with "Ceci est un commentaire test."
        When I press "comment-button"
        And I should see 3 ".comment-right" element
        And the "#comments-feed-title" element should contain "Espace commentaires (6)"
