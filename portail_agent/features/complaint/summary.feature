Feature:
    In order to show a complaint's summary page
    As a user
    I want to see the summary informations

    Background:
        Given I am authenticated with PR5KTZ9R from GN

    @func
    Scenario: I can see a sidebar with current user information
        Given I am on "/plainte/recapitulatif/91"
        Then I should see the key "pel.profile" translated
        And I should see the key "pel.close" translated
        And I should see the key "pel.display.settings" translated
        And I should see the key "pel.settings" translated
        And I should see the key "pel.rights.delegation" translated
        And I should see the key "pel.select.the.delegation.period" translated
        And I should see the key "pel.selected.delegation.period" translated
        And I should see the key "pel.from" translated
        And I should see the key "pel.to" translated
        And I should see the key "pel.select.one.or.more.delegated.agents" translated
        And I should see the key "pel.logout" translated

    @func
    Scenario: I can access the complaint's summary page if the complaint is assigned to my unit, but I can't access a complaint assigned to another unit
        Given I am on "/plainte/recapitulatif/91"
        And the response status code should be 200
        Then I am on "/plainte/recapitulatif/1"
        And the response status code should be 403

    @func
    Scenario: I can access the complaint's summary page for all my unit complaints
        Given I am authenticated with PR5KTQSD from GN
        And I am on "/plainte/recapitulatif/101"
        And the response status code should be 200
        Then I am on "/plainte/recapitulatif/91"
        And the response status code should be 200

    @func
    Scenario: I can navigate to the complaint page
        Given I am on "/plainte/recapitulatif/91"
        Then the response status code should be 200
        And I should see a "nav" element
        And I should see a "main" element
        And I should see the key "pel.declaration.number" translated
        And I should see "2300000091"
        And I should see the key "pel.status" translated
        And I should see "À attribuer"
        And I should see the key "pel.alert" translated
        And I should see "Alert de test trop longue"
        And I should see a "button[data-bs-toggle='modal']" element
        And I should see 26 "button" element
        And I should see the key "pel.assign.declaration.to" translated
        And I should not see the key "pel.send.to.lrp" translated
        # PEL-987:Hide redirection for experimentation phase
        #And I should not see the key "pel.unit.reassign" translated
        And I should see the key "pel.comment" translated
        And I should see the key "pel.summary" translated
        And I should see the key "pel.identification" translated
        And I should see the key "pel.description.of.facts" translated
        And I should see the key "pel.objects.description" translated
        And I should see the key "pel.additional.informations" translated
        And I should see the key "pel.complaint.online" translated
        And I should see the key "pel.portal" translated
        And I should see "Brigade territoriale autonome de Cestas"
        And I should see the key "pel.dashboard" translated
        And I should see the key "pel.the.declarations" translated
        And I should see the key "pel.faq.space" translated
        And I should see the key "pel.identification" translated
        And I should see the key "pel.civility" translated
        And I should see the key "pel.family.situation" translated
        And I should see the key "pel.born.on" translated
        And I should see the key "pel.at" translated
        And I should see the key "pel.at.in" translated
        And I should see the key "pel.department" translated
        And I should see the key "pel.resides.at" translated
        And I should see the key "pel.phone" translated
        And I should see the key "pel.email" translated
        And I should see the key "pel.consent.confirmation" translated
        And I should see the key "pel.by.email" translated
        And I should see the key "pel.by.sms" translated
        And I should see the key "pel.on.the.judicial.portal" translated
        And I should see the key "pel.is.designated.as" translated
        And I should see the key "pel.sir" translated
        And I should see the key "pel.job" translated
        And I should see the key "pel.job.thesaurus" translated
        And I should see the key "pel.nationality" translated
        And I should see "Jean"
        And I should see "DUPONT"
        And I should see "BERNARD"
        And I should see "Célibataire"
        And I should see "07/03/1967"
        And I should see "Paris"
        And I should see "France"
        And I should see "FRANCAISE"
        And I should see "Boulanger"
        And I should see "BOULANGER"
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
        And I should see "RESTAURANT"
        And I should see "25 Avenue Georges Pompidou, Lyon, 69003"
        And I should see "Place Charles Hernu, Villeurbanne, 69100"
        And I should see "01/12/2022"
        And I should see "09h00"
        And I should see "10h00"
        And I should see the key "pel.objects.description" translated
        And I should see the key "pel.objects.stolen" translated
        And I should see the key "pel.objects.degraded" translated
        And I should see the key "pel.number" translated
        And I should see the key "pel.total" translated
        And I should see the key "pel.total.message.one" translated
        And I should see the key "pel.total.message.amount" translated
        And I should see "Téléphone"
        And I should see "TELEPHONE PORTABLE"
        And I should see "Iphone 13"
        And I should see "Apple"
        And I should see "999 €"
        And I should see "Orange"
        And I should see "Description smartphone"
        And I should see "Numéro de téléphone portable"
        And I should see "06 12 34 56 67"
        And I should see "voiture particuliere"
        And I should see "Voiture"
        And I should see "Citroën"
        And I should see "Rétroviseur cassé"
        And I should see "C3"
        And I should see "17 000 €"
        And I should see "CARTE BANCAIRE"
        And I should see "Visa principale"
        And I should see "LCL"
        And I should see "987654321"
        And I should see "PERMIS DE CONDUIRE"
        And I should see "France"
        And I should see "1234"
        And I should see "Préfecture de Paris"
        And I should see "01/01/2010"
        And I should see "01/01/2030"
        And I should see "Blouson"
        And I should see "Blouson Adidas de couleur bleu"
        And I should see "A9999"
        And I should see "100 €"
        And I should see "AUTRE NATURE MULTIMEDIA"
        And I should see "Console"
        And I should see "Playstation 4"
        And I should see "Sony"
        And I should see "1234567890"
        And I should see "ABCD-1234"
        And I should see "Description console"
        And I should see "CHEQUIER"
        And I should see "Mon chéquier"
        And I should see "987654321"
        And I should see "N° de chèque / chéquier : 1234567890"
        And I should see "Premier N° de chèque / chéquier : AAA"
        And I should see "Dernier N° de chèque / chéquier : XXX"
        And I should see "Numéro de carte : 4624 7482 3324 9080"
        And I should see "Vous avez ajouté 8 objets pour un montant total de 19 099 €"
        And I should see the key "pel.additional.informations" translated
        And I should see the key "pel.facts.witnesses" translated
        And I should see "Description du témoin n°1"
        And I should see "Adresse e-mail du témoin n°1"
        And I should see "Numéro de téléphone portable n°1"
        And I should see "Jean DUPONT"
        And I should see "06 12 34 45 57"
        And I should see "jean@example.com"
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
        And I should see the key "pel.identification" translated
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
        And I should see the key "pel.identification" translated
        And I should see the key "pel.siret.number" translated
        And I should see "12345678900000"
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
        And I should see the key "pel.reject.the.declaration" translated
        And I should see the key "pel.select.the.reason.of.rejection" translated
        And I should see the key "pel.other" translated
        And I should see the key "pel.reorientation.other.solution" translated
        And I should see the key "pel.drafting.victim.to.another.teleservice" translated
        And I should see the key "pel.drafting.a.handrail.declaration" translated
        And I should see the key "pel.insufisant.quality.to.act" translated
        And I should see the key "pel.absence.of.penal.offense.object.loss" translated
        And I should see the key "pel.absence.of.penal.offense" translated
        And I should see the key "pel.prescription.of.facts" translated
        And I should see the key "pel.victime.carence.at.appointment" translated
        And I should see the key "pel.victime.carence.at.appointment.booking" translated
        And I should see the key "pel.abandonment.of.the.procedure.by.victim" translated
        And I should see the key "pel.comment" translated
        And I should see the key "pel.cancel" translated
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
        When I press "complaint-comment-button"
        Then I should see the key "pel.comment.complaint.refusal.reason" translated
        And I should see "Motif de refus de la plainte - Réorientation de la victime vers autre démarche (civil, prudhomme, médiation...)"
        And I should see "Thomas DURAND"
        And I should see "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus scelerisque ante id dui lacinia eu."
        And I am on "/"
        When I click the "th:nth-of-type(10)" element
        And I should see 28 ".background-red" element

    @javascript
    Scenario: I can see form errors the reject form when reject_refusalText is too short
        Given I am on "/plainte/recapitulatif/102"
        When I press "Rejeter"
        And I select "reorientation-other-solution" from "reject_refusalReason"
        And I fill in "reject_refusalText" with "Lorem ipsum dolor sit amet"
        And I press "Valider le refus"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see a "ul" element

    @javascript
    Scenario: I can toggle the send to LRP modal
        Given I am on "/plainte/recapitulatif/103"
        When I press "complaint-send-to-lrp-button"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see the key "pel.validation.sending.complain.to.lrp" translated
        And I should see the key "pel.to.simplify.the.process" translated
        And I should see the key "pel.after.treatment.in.lrp" translated
        And I should see the key "pel.cancel" translated
        And I should see the key "pel.validate.the.sending.to.the.lrp" translated
        When I press "complaint-send-to-lrp-button-back"
        Then I should not see a ".modal[aria-modal=true]" element

    @javascript
    Scenario: I can validate the send to LRP action successfully
        Given I am on "/plainte/recapitulatif/103"
        When I press "complaint-send-to-lrp-button"
        Then I should see a ".modal[aria-modal=true]" element
        When I press "Valider l'envoi vers le LRP"
        Then I should not see a ".modal[aria-modal=true]" element
        Given I am on "/plainte/recapitulatif/103"
        Then I should see the key "pel.declaration.number" translated
        Then I should see the key "pel.status" translated
        Then I should see the key "pel.assigned.to" translated
        Then I should see the key "pel.nature.of.the.facts" translated
        Then I should see "En cours LRP"
        Given I am on the homepage
        When I click the "th:nth-of-type(10)" element
        Then I should see 0 ".background-yellow" element

    @javascript
    Scenario: I can toggle the assign modal
        Given I am on "/plainte/recapitulatif/93"
        When I press "Attribuer à"
        And I wait 2000 ms
        Then I should see a ".modal[aria-modal=true]" element
        And I should see the key "pel.assign.to.agent" translated
        And I should see the key "pel.select.the.agent.you.want.to.assign.the.declaration" translated
        And I should see the key "pel.cancel" translated
        And I should see the key "pel.validate.the.assignment" translated
        When I press "complaint-assign-button-back"
        Then I should not see a ".modal[aria-modal=true]" element

    @javascript
    Scenario: I can submit the assign form successfully and Jean DUPONT should have a notif
        Given I am on "/plainte/recapitulatif/93"
        When I press "Attribuer à"
        And I fill in the autocomplete "assign_assignedTo-ts-control" with "Julie" and click "3"
        And I press "Valider l'attribution"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see a ".toast" element
        And I should see the key "pel.the.declaration.has.been.assigned.to" translated
        And I should see "La déclaration a été attribuée à Julie RICHARD"
        Given I am authenticated with PR5KTZ9C from GN
        And I am on the homepage
        When I click the "#notifications-dropdown" element
        Then I should see "La déclaration 2300000093 vient de vous être attribuée"

    @javascript
    Scenario: I can submit the reassign form successfully
        Given I am on "/plainte/recapitulatif/93"
        When I press "complaint-reassign-button"
        And I fill in the autocomplete "assign_assignedTo-ts-control" with "Julie" and click "3"
        And I press "Valider l'attribution"
        And I wait 2000 ms
        And I press "complaint-reassign-button"
        And I fill in the autocomplete "assign_assignedTo-ts-control" with "Philippe" and click "4"
        And I press "Valider la réattribution"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see a ".toast" element
        And I should see the key "pel.the.declaration.has.been.assigned.to" translated
        And I should see "La déclaration a été attribuée à Philippe RIVIERE"

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
#        When I am authenticated with ZSBVHOAY from PN
#        And I am on the homepage
#        When I click the "#notifications-dropdown" element
#        Then I should see "La déclaration 2300000101 a été réorientée vers votre unité"
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
#        Then I should see "Philippe RIVIERE vient de vous adresser une demande de réorientation vers autre service 2300000110"
#        When I follow "Philippe RIVIERE vient de vous adresser une demande de réorientation vers autre service 2300000110"
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
#        Then I should see "Thomas DURAND vient de valider votre demande de réorientation vers un autre service pour la déclaration 2300000110"
#        When I follow "Thomas DURAND vient de valider votre demande de réorientation vers un autre service pour la déclaration 2300000110"
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
#        Then I should see "Philippe RIVIERE vient de vous adresser une demande de réorientation vers autre service 2300000110"
#        When I follow "Philippe RIVIERE vient de vous adresser une demande de réorientation vers autre service 2300000110"
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
#        Then I should see "Thomas DURAND vient de refuser votre demande de réorientation vers un autre service pour la déclaration 2300000110"
#        When I follow "Thomas DURAND vient de refuser votre demande de réorientation vers un autre service pour la déclaration 2300000110"
#        Then I should be on "/plainte/recapitulatif/110"

    @func
    Scenario: I can see the comments space on the summary page
        Given I am authenticated with ZSBVHOAY from PN
        And I am on "/plainte/recapitulatif/11"
        Then I should see a "#comment_content" element
        And I should see a ".comment-box" element
        And I should see a "#comments-feed-title" element
        And I should see the key "pel.comments.feed" translated
        And the "#comments-feed-title" element should contain "Espace commentaires"
        And I should see 5 ".comment-left" element
        And I should see 0 ".comment-right" element
        And I should see 5 "#comment-author" element
        And I should see 5 "#comment-published-at" element
        And I should see "Frédérique BONNIN" in the ".comment-left" element

    @javascript
    Scenario: I can click the "Comment" button, and it focus the comment field
        Given I am authenticated with ZSBVHOAY from PN
        And I am on "/plainte/recapitulatif/11"
        And I should not focus the "comment_content" element
        Then I press "complaint-comment-button"
        And I should focus the "comment_content" element

    @javascript
    Scenario: I can add a comment from the summary page
        Given I am authenticated with ZSBVHOAY from PN
        And I am on "/plainte/recapitulatif/11"
        Then the "#comments-feed-title" element should contain "Espace commentaires"
        When I press "complaint-comment-button"
        And I fill in "comment_content" with "Ceci est un commentaire test."
        And I press "comment-button"
        Then I should see 1 ".comment-right" element
        And the "#comments-feed-title" element should contain "Espace commentaires"

    @func
    Scenario: I can open the attachments
        Given I am authenticated with ZSBVHOAY from PN
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
        When I press "M'attribuer"
        Then I should see a ".toast" element
        And I should see the key "pel.the.declaration.has.been.self.assigned" translated
        And I should see "La déclaration vous a bien été attribuée"
        Given I am on "/"
        Then I should see "2300000093"

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
