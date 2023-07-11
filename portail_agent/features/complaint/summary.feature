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
        And I should see the key "pel.number.of.the.declaration" translated
        And I should see "PEL-2023-00000091"
        And I should see the key "pel.declaration.status" translated
        And I should see "A attribuer"
        And I should see the key "pel.declaration.alert" translated
        And I should see "Alert de test trop longue"
        And I should see 2 "button[data-bs-toggle='modal']" element
        And I should see 14 "button" element
        And I should see the key "pel.assign.declaration.to" translated
        And I should not see the key "pel.send.to.lrp" translated
        And I should not see the key "pel.reject" translated
        # PEL-987:Hide redirection for experimentation phase
        #And I should not see the key "pel.unit.reassign" translated
        And I should see the key "pel.comment" translated
        And I should see the key "pel.summary" translated
        And I should see the key "pel.declarant.identity" translated
        And I should see the key "pel.description.of.facts" translated
        And I should see the key "pel.objects.concerned" translated
        And I should see the key "pel.additional.informations" translated
        And I should see the key "pel.appointment.management" translated
        And I should see the key "pel.complaint.online" translated
        And I should see the key "pel.portal" translated
        And I should see "Brigade de proximité de Voiron"
        And I should see the key "pel.dashboard" translated
        And I should see the key "pel.the.declarations" translated
        And I should see the key "pel.faq.space" translated
        And I should see the key "pel.identification" translated
        And I should see the key "pel.civility" translated
        And I should see the key "pel.family.situation" translated
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
        And I should see "Célibataire"
        And I should see "07/03/1967"
        And I should see "France"
        And I should see "FRANCAISE"
        And I should see "Boulanger"
        And I should see "75"
        And I should see "15 rue PAIRA, Meudon, 92190"
        And I should see "92190"
        And I should see "Meudon"
        And I should see "06 12 34 45 57"
        And I should see "jean.dupont@gmail.com"
        And I should see "Le vol à eu lieu à mon domicile"
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
        And I should see the key "pel.objects.degraded" translated
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
        And I should see "Pièce jointe 1"
        And I should see "Pièce jointe 2"
        And I should see the key "pel.still.on.when.mobile.stolen" translated
        And I should see the key "pel.keyboard.locked.when.mobile.stolen" translated
        And I should see the key "pel.pin.enabled.when.mobile.stolen" translated
        And I should see the key "pel.mobile.insured" translated

    @func
    Scenario: If the declarant is a Person Legal Representative, I should be able to see the victim informations
        Given I am on "/plainte/recapitulatif/151"
        And I should see the key "pel.victim.identity" translated
        And I should see "Monsieur DUPONT Jeremy"
        And I should see "Célibataire"
        And I should see "14/02/2000"
        And I should see "France"
        And I should see "92"
        And I should see "FRANCAISE"
        And I should see "15 rue PAIRA, Meudon, 92190"
        And I should see "Etudiant"
        And I should see "06 76 54 32 10"
        And I should see "jeremy.dupont@gmail.com"

    @func
    Scenario: If the declarant is a Corporation Representative, I should be able to see the victim informations
        Given I am on "/plainte/recapitulatif/161"
        And I should see the key "pel.victim.identity" translated
        And I should see the key "pel.siren.number" translated
        And I should see "123456789"
        And I should see the key "pel.company.name" translated
        And I should see "Netflix"
        And I should see the key "pel.declarant.position" translated
        And I should see "PDG"
        And I should see the key "pel.nationality" translated
        And I should see "FRANCAISE"
        And I should see the key "pel.email" translated
        And I should see "pdg@netflix.com"
        And I should see the key "pel.phone" translated
        And I should see "0612345678"
        And I should see the key "pel.country" translated
        And I should see "France"
        And I should see the key "pel.address" translated
        And I should see "1 Rue de la république, Paris, 75000"

    @func
    Scenario: I can click on the menu's summary button
        Given I am on "/plainte/recapitulatif/91"
        When I follow "Récapitulatif"
        And I should be on "/plainte/recapitulatif/91"
        And the response status code should be 200

    @javascript
    Scenario: I can toggle the reject modal
        Given I am on "/plainte/recapitulatif/101"
        When I press "Rejeter"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see the key "pel.you.will.reject.the.declaration" translated
        And I should see the key "pel.other" translated
        And I should see the key "pel.reorientation.other.solution" translated
        And I should see the key "pel.drafting.victim.to.another.teleservice" translated
        And I should see the key "pel.drafting.a.handrail.declaration" translated
        And I should see the key "pel.insufisant.quality.to.act" translated
        And I should see the key "pel.absence.of.penal.offense.object.loss" translated
        And I should see the key "pel.absence.of.penal.offense" translated
        And I should see the key "pel.incoherent.statements" translated
        And I should see the key "pel.victime.carence.at.appointment" translated
        And I should see the key "pel.victime.carence.at.appointment.booking" translated
        And I should see the key "pel.abandonment.of.the.procedure.by.victim" translated
        And I should see the key "pel.free.text" translated
        And I should see the key "pel.back" translated
        And I should see the key "pel.validate.the.refusal" translated
        When I press "complaint-reject-button-back"
        Then I should not see a ".modal[aria-modal=true]" element

    @javascript
    Scenario: I can submit the reject form successfully
        Given I am on "/plainte/recapitulatif/101"
        When I press "Rejeter"
        And I select "reorientation-other-solution" from "reject_refusalReason"
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
        When I click the "th:nth-of-type(10)" element
        And I should see 1 ".btn-danger" element

    @javascript
    Scenario: I can see form errors the reject form when reject_refusalText is too short
        Given I am on "/plainte/recapitulatif/102"
        When I press "Rejeter"
        And I select "reorientation-other-solution" from "reject_refusalReason"
        And I fill in "reject_refusalText" with "Lorem ipsum dolor sit amet"
        And I press "Valider le refus"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see a ".invalid-feedback" element

    @javascript
    Scenario: I can toggle the send to LRP modal
        Given I am on "/plainte/recapitulatif/103"
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
        Given I am on "/plainte/recapitulatif/103"
        When I press "Envoi à LRP"
        Then I should see a ".modal[aria-modal=true]" element
        When I press "Valider l'envoi vers le LRP"
        Then I should not see a ".modal[aria-modal=true]" element
        Given I am on "/plainte/recapitulatif/103"
        Then I should see the key "pel.declaration.status" translated
        Then I should see "En cours LRP"
        Given I am on the homepage
        When I click the "th:nth-of-type(10)" element
        Then I should see 6 ".btn-warning" element

    @javascript
    Scenario: I can toggle the send report to victim modal
        Given I am on "/plainte/recapitulatif/111"
        When I press "Envoyer PV au déclarant et clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see the key "pel.please.drop.the.report.to.send.to.the.victim" translated
        And I should see the key "pel.complaint.assignation" translated
        And I should see the key "pel.complaint.validation.sending.to.lrp" translated
        And I should see the key "pel.drop.report.and.send.to.the.victim" translated
        And I should see the key "pel.drop.the.report.then.validate.the.sending.to.the.victim" translated
        And I should see the key "pel.drag.and.drop.or.click.here.to.browse" translated
        And I should see the key "pel.back" translated
        And I should see the key "pel.send.report.to.the.victim" translated
        When I press "complaint-send-report-to-the-victim-button-back"
        Then I should not see a ".modal[aria-modal=true]" element

    @javascript
    Scenario: I can see form errors when the send report file field is empty
        Given I am on "/plainte/recapitulatif/111"
        When I press "Envoyer PV au déclarant et clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        When I press "complaint-send-report-to-the-victim-button-validate"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see a ".invalid-feedback" element
        And I should see the key 'pel.you.must.choose.a.file' translated

    @javascript
    Scenario: I can see form errors when I submit another type file than PDF
        Given I am on "/plainte/recapitulatif/111"
        When I press "Envoyer PV au déclarant et clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        When I attach the file "blank.xls" to ".dz-hidden-input" field
        When I press "complaint-send-report-to-the-victim-button-validate"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see a ".invalid-feedback" element

    @javascript
    Scenario: I can submit the send report form successfully
        Given I am on "/plainte/recapitulatif/111"
        When I press "Envoyer PV au déclarant et clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        When I attach the file "blank.pdf" to ".dz-hidden-input" field
        And I press "complaint-send-report-to-the-victim-button-validate"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see a ".toast" element
        And I should see the key "pel.the.report.has.been.sent.to.the.victim.the.complaint.is.closed" translated

    @javascript
    Scenario: I can submit the send report form successfully with a png file
        Given I am on "/plainte/recapitulatif/111"
        When I press "Envoyer PV au déclarant et clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        When I attach the file "iphone.png" to ".dz-hidden-input" field
        And I press "complaint-send-report-to-the-victim-button-validate"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see a ".toast" element
        And I should see the key "pel.the.report.has.been.sent.to.the.victim.the.complaint.is.closed" translated

    @javascript
    Scenario: I can submit the send report form successfully with a jpg file and a pdf file
        Given I am on "/plainte/recapitulatif/111"
        When I press "Envoyer PV au déclarant et clôturer"
        Then I should see a ".modal[aria-modal=true]" element
        When I attach the file "iphone.png" to ".dz-hidden-input" field
        And I attach the file "blank.pdf" to ".dz-hidden-input" field
        And I press "complaint-send-report-to-the-victim-button-validate"
        Then I should see 2 ".dz-preview" element
        And I should not see a ".modal[aria-modal=true]" element
        And I should see a ".toast" element
        And I should see the key "pel.the.report.has.been.sent.to.the.victim.the.complaint.is.closed" translated

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

  # PEL-987:Hide redirection for experimentation phase

#    @javascript
#    Scenario: I can toggle the unit reassign modal
#        Given I am on "/plainte/recapitulatif/101"
#        When I press "Réorienter vers autres services"
#        Then I should see a ".modal[aria-modal=true]" element
#        And I should see the key "pel.select.the.unit.to.reassign" translated
#        And I should see the key "pel.back" translated
#        And I should see the key "pel.validate.the.unit.reassignment" translated
#        When I press "complaint-unit-reassign-button-back"
#        Then I should not see a ".modal[aria-modal=true]" element

#    @javascript
#    Scenario: I can submit the unit reassign form successfully as a supervisor and Louise THOMAS should have a notification
#        Given I am on "/plainte/recapitulatif/101"
#        When I press "Réorienter vers autres services"
#        And I fill in the autocomplete "unit_reassign_unitToReassign-ts-control" with "Commissariat de police de Voiron" and click "103131"
#        And I fill in "unit_reassign_unitReassignText" with "Cette plainte n'est pas censée être attribuer à mon unité."
#        And I press "Réorienter"
#        And I wait 500 ms
#        And I should be on the homepage
#        When I am authenticated with H3U3XCGF from PN
#        And I am on the homepage
#        When I click the "#notifications-dropdown" element
#        Then I should see "La déclaration PEL-2023-00000101 a été réorientée vers votre unité"
#        When I am on "/plainte/recapitulatif/101"
#        Then I should see a "#comment-title" element
#        And I should see the key "pel.comment.unit.reassignment.reason" translated

#    @javascript
#    Scenario: I can submit the unit reassign form successfully as an agent and validate it as a supervisor
#        Given I am authenticated with PR5KTQSD from GN
#        And I am on "/plainte/recapitulatif/110"
#        When I press "Réorienter vers autres services"
#        And I fill in the autocomplete "unit_reassign_unitToReassign-ts-control" with "Commissariat de police de Voiron" and click "103131"
#        And I fill in "unit_reassign_unitReassignText" with "Cette plainte n'est pas censée être attribuer à mon unité."
#        And I press "Réorienter"
#        Then I should not see a ".modal[aria-modal=true]" element
#        And I should see a ".toast" element
#        And I should see the key "pel.the.unit.reassignment.of.the.declaration.to" translated
#        And I should see the key "pel.has.been.ordered" translated
#        And I should see "La demande de réorientation de la déclaration vers Commissariat de police de Voiron à été prise en compte"
#        Then I am authenticated with PR5KTZ9R from GN
#        And I am on the homepage
#        When I click the "#notifications-dropdown" element
#        Then I should see "Philippe RIVIERE vient de vous adresser une demande de réorientation vers autre service PEL-2023-00000110"
#        When I follow "Philippe RIVIERE vient de vous adresser une demande de réorientation vers autre service PEL-2023-00000110"
#        Then I should be on "/plainte/recapitulatif/110?showUnitReassignmentValidationModal=1"
#        And I should see a ".modal[aria-modal=true]" element
#        When I press "Accepter la demande de réorientation"
#        And I wait 500 ms
#        Then I should be on the homepage
#        And I should see a ".toast" element
#        And I should see the key "pel.the.unit.reassignment.has.been.accepted" translated
#        Given I am authenticated with PR5KTQSD from GN
#        And I am on the homepage
#        When I click the "#notifications-dropdown" element
#        Then I should see "Thomas DURAND vient de valider votre demande de réorientation vers un autre service pour la déclaration PEL-2023-00000110"
#        When I follow "Thomas DURAND vient de valider votre demande de réorientation vers un autre service pour la déclaration PEL-2023-00000110"
#        Then I should be on "/"

#    @javascript
#    Scenario: I can submit the unit reassign form successfully as an agent and reject it as a supervisor
#        Given I am authenticated with PR5KTQSD from GN
#        And I am on "/plainte/recapitulatif/110"
#        When I press "Réorienter vers autres services"
#        And I fill in the autocomplete "unit_reassign_unitToReassign-ts-control" with "Commissariat de police de Voiron" and click "103131"
#        And I fill in "unit_reassign_unitReassignText" with "Cette plainte n'est pas censée être attribuer à mon unité."
#        And I press "Réorienter"
#        Then I should not see a ".modal[aria-modal=true]" element
#        And I should see a ".toast" element
#        And I should see the key "pel.the.unit.reassignment.of.the.declaration.to" translated
#        And I should see the key "pel.has.been.ordered" translated
#        And I should see "La demande de réorientation de la déclaration vers Commissariat de police de Voiron à été prise en compte"
#        Then I am authenticated with PR5KTZ9R from GN
#        And I am on the homepage
#        When I click the "#notifications-dropdown" element
#        Then I should see "Philippe RIVIERE vient de vous adresser une demande de réorientation vers autre service PEL-2023-00000110"
#        When I follow "Philippe RIVIERE vient de vous adresser une demande de réorientation vers autre service PEL-2023-00000110"
#        Then I should be on "/plainte/recapitulatif/110?showUnitReassignmentValidationModal=1"
#        And I should see a ".modal[aria-modal=true]" element
#        And I fill in "unit_reassign_unitReassignText" with "Je refuse cette réorientation."
#        When I press "Refuser la réorientation"
#        And I should see a ".toast" element
#        And I should see the key "pel.the.unit.reassignment.has.been.rejected.your.agent.stays.assigned" translated
#        And I should see the key "pel.comment.unit.reassignment.reject.reason" translated
#        And I should see "Je refuse cette réorientation."
#        Given I am authenticated with PR5KTQSD from GN
#        And I am on the homepage
#        When I click the "#notifications-dropdown" element
#        Then I should see "Thomas DURAND vient de refuser votre demande de réorientation vers un autre service pour la déclaration PEL-2023-00000110"
#        When I follow "Thomas DURAND vient de refuser votre demande de réorientation vers un autre service pour la déclaration PEL-2023-00000110"
#        Then I should be on "/plainte/recapitulatif/110"

    @func
    Scenario: I can see the comments space on the summary page
        Given I am authenticated with H3U3XCGD from PN
        And I am on "/plainte/recapitulatif/11"
        Then I should see a "#comment_content" element
        And I should see a ".comment-box" element
        And I should see a "#comments-feed-title" element
        And I should see the key "pel.comments.feed" translated
        And the "#comments-feed-title" element should contain "Espace commentaires (5)"
        And I should see 3 ".comment-left" element
        And I should see 2 ".comment-right" element
        And I should see 5 "#comment-author" element
        And I should see 5 "#comment-published-at" element
        And I should see "Jean DUPONT" in the ".comment-right" element
        And I should see "Philippe RIVIERE" in the ".comment-left" element

    @javascript
    Scenario: I can click the "Comment" button, and it focus the comment field
        Given I am authenticated with H3U3XCGD from PN
        And I am on "/plainte/recapitulatif/11"
        And I should not focus the "comment_content" element
        Then I press "complaint-comment-button"
        And I should focus the "comment_content" element

    @javascript
    Scenario: I can add a comment from the summary page
        Given I am authenticated with H3U3XCGD from PN
        And I am on "/plainte/recapitulatif/11"
        And the "#comments-feed-title" element should contain "Espace commentaires (5)"
        Then I fill in "comment_content" with "Ceci est un commentaire test."
        When I press "comment-button"
        And I should see 3 ".comment-right" element
        And the "#comments-feed-title" element should contain "Espace commentaires (6)"

    @func
    Scenario: I can open the attachments
        Given I am authenticated with H3U3XCGD from PN
        And I am on "/plainte/recapitulatif/11"
        When I follow "Pièce jointe 1"
        Then I should be on "/voir-piece-jointe/11/blank.pdf"
        Given I am on "/plainte/recapitulatif/11"
        When I follow "Pièce jointe 2"
        Then I should be on "/voir-piece-jointe/11/iphone.png"

    @javascript
    Scenario: I can self assign a declaration as a supervisor
        Given I am authenticated with PR5KTZ9R from GN
        And I am on "/plainte/recapitulatif/93"
        When I press "M'attribuer la déclaration"
        Then I should see a ".toast" element
        And I should see the key "pel.the.declaration.has.been.self.assigned" translated
        And I should see "Déclaration attribuée à : Thomas DURAND"
        And I should see the key "pel.declaration.assigned.to" translated

    @javascript
    Scenario: I can't self assign a declaration as a supervisor when the declaration is already assigned
        Given I am authenticated with PR5KTZ9R from GN
        And I am on "/plainte/recapitulatif/110"
        Then I should not see a "#complaint-self-assign-button" element

    @func
    Scenario: I can see owner information when an Administrative document doesn't belongs to the declarant
        Given I am on "/plainte/recapitulatif/151"
        And I should see the key "pel.owner.lastname.firstname" translated
        And I should see "Dulac Raymond"
        And I should see the key "pel.owner.email" translated
        And I should see "raymond.dulac@example.fr"
        And I should see the key "pel.owner.company" translated
        And I should see "Amazon"
        And I should see the key "pel.owner.address" translated
        And I should see "100 Rue de l'église 69000 Lyon"
        And I should see the key "pel.owner.phone" translated
        And I should see "0612345678"
        And I should see the key "pel.document.issued.by" translated
        And I should see "Préfecture de Lyon"
        And I should see the key "pel.document.issued.on" translated
        And I should see "10/03/2022"
        And I should see the key "pel.document.number" translated
        And I should see "123"
        And I should see the key "pel.document.validity.end.date" translated
        And I should see "05/12/2025"

     #PEL-987:Hide redirection for experimentation phase
#    @javascript
#    Scenario: As a supervisor, I can see a badge on the unit reassignment button is an agent asks for a redirection on this complaint
#        Given I am on "/plainte/recapitulatif/110"
#        And I should not see a "#badge-unit-reassignment-asked" element
#        Then I am authenticated with PR5KTQSD from GN
#        And I am on "/plainte/recapitulatif/110"
#        When I press "Réorienter vers autres services"
#        And I fill in the autocomplete "unit_reassign_unitToReassign-ts-control" with "Commissariat de police de Voiron" and click "103131"
#        And I fill in "unit_reassign_unitReassignText" with "Cette plainte n'est pas censée être attribuer à mon unité."
#        And I press "Réorienter"
#        Then I am authenticated with PR5KTZ9R from GN
#        And I am on "/plainte/recapitulatif/110"
#        Then I should see a "#badge-unit-reassignment-asked" element

    @func
    Scenario: As a user, I should see the home phone number if it has been filled by the victim
        Given I am authenticated with PR5KTZ9R from GN
        And I am on "/plainte/recapitulatif/91"
        Then I should not see the key "pel.home.phone" translated
        And I should not see "01 23 45 67 89"
        When I am on "/plainte/recapitulatif/102"
        Then I should see the key "pel.home.phone" translated
        And I should see "01 23 45 67 89"
        When I am on "plainte/rendez-vous/102"
        Then I should see the key "pel.home.phone" translated
        And I should see "01 23 45 67 89"
