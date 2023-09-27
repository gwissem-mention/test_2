@javascript
Feature:
    In order to fill a complaint
    As a user
    I want to see the appointment step form

    Background:
        Given I am on "/authentification"
        When I press "france_connect_auth_button"
        Then I should be on "/porter-plainte/rappel-a-la-loi"
        And I click the "label[for=law_refresher_lawRefresherAccepted]" element
        And I press "law_refresher_submit"
        Then I should be on "/porter-plainte/identite"
        And I click the "label[for=identity_declarantStatus_0]" element
        And I select "1" from "identity_civilState_familySituation"
        Given I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        When I fill in "facts_description" with "description informations lorem ipsum dol or sit amet lorem ipsum dol or sit amet"
        And I click the "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        And I fill in "facts-startAddress-address" with "1 test street"
        And I fill in "facts-startAddress-address" with "2 test street"
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I wait for "#facts_offenseDate_startDate" to appear
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
        And I fill in "facts_offenseDate_hour" with "15:00"
        And I select "2" from "facts_placeNature"
        And I press "facts_submit"
        Then I should be on "/porter-plainte/objets"
        Given I select "5" from "objects_objects_0_category"
        And I select "1" from "objects_objects_0_status"
        And I fill in "objects_objects_0_label" with "Object 1"
        And I fill in "objects_objects_0_amount" with "100"
        And I press "objects_objects_add"
        And I select "5" from "objects_objects_1_category"
        And I select "1" from "objects_objects_1_status"
        And I fill in "objects_objects_1_label" with "Object 2"
        And I fill in "objects_objects_1_amount" with "100"
        And I press "objects_submit"
        Then I should be on "/porter-plainte/informations-complementaires"
        When I click the "label[for=additional_information_suspectsChoice_0]" element
        And I fill in "additional_information_suspectsText" with "suspects informations"
        And I should see the key "pel.facts.suspects.informations.text" translated
        And I click the "label[for=additional_information_witnessesPresent_0]" element
        And I fill in "additional_information_witnesses_0_description" with "Jean DUPONT"
        And I fill in "additional_information_witnesses_0_email" with "jean.dupont@example.com"
        And I fill in "additional_information_witnesses_0_phone_number" with "0602030405"
        And I click the "label[for=additional_information_fsiVisit_0]" element
        And I click the "label[for=additional_information_observationMade_0]" element
        And I click the "label[for=additional_information_cctvPresent_0]" element
        And I click the "label[for=additional_information_cctvAvailable_0]" element
        And I press "additional_information_submit"
        Then I should be on "/porter-plainte/recapitulatif"
        When I click the "label[for=summary_appointmentAsked_0]" element
        And I press "summary_submit"
        Then I should be on "/porter-plainte/rendez-vous"

    Scenario: I should see the informative text
        Then I should see the key "pel.appointment.message.3" translated
    Scenario: I can click on the back button
        When I follow "Étape précédente"
        Then I should be on "/porter-plainte/recapitulatif"

    Scenario: I can fill the appointment textarea and submit
        When I fill in "appointment_appointmentContactText" with "Between 10am and 12am"
        And I press "appointment_submit"
        Then I should be on "/porter-plainte/fin"

    Scenario: I can submit with no filling the appointment textarea
        When I press "appointment_submit"
        Then I should be on "/porter-plainte/fin"

    Scenario: I should see error if I valid with no filling the appointment textarea when I am victim of violence
        Given I am on "/porter-plainte/faits"
        When I click the "label[for=facts_victimOfViolence]" element
        And I fill in "facts_victimOfViolenceText" with "Violence informations"
        And I press "facts_submit"
        Then I should be on "/porter-plainte/objets"
        Given I am on "/porter-plainte/rendez-vous"
        When I press "appointment_submit"
        Then I should be on "/porter-plainte/rendez-vous"
        And I should see a "#form-errors-appointment_appointmentContactText" element

    Scenario: I should see error if I valid with no filling the appointment textarea when an object is a stolen registered vehicle
        Given I am on "/porter-plainte/objets"
        When I select "Véhicules immatriculés" from "objects_objects_0_category"
        And I select "CAMION" from "objects_objects_0_registeredVehicleNature"
        And I fill in "objects_objects_0_brand" with "Renault"
        And I fill in "objects_objects_0_registrationNumber" with "AA-229-AA"
        And I press "objects_submit"
        Then I should be on "/porter-plainte/informations-complementaires"
        Given I am on "/porter-plainte/rendez-vous"
        When I press "appointment_submit"
        Then I should be on "/porter-plainte/rendez-vous"
        And I should see a "#form-errors-appointment_appointmentContactText" element

    Scenario: I should see error if I valid with no filling the appointment textarea when I am not france connected
        Given I am on "/authentification"
        When I press "no_france_connect_auth_button"
        And I follow "no_france_connect_auth_button_confirm"
        Then I should be on "/porter-plainte/rappel-a-la-loi"
        And I check "law_refresher_lawRefresherAccepted"
        And I press "law_refresher_submit"
        Then I should be on "/porter-plainte/identite"
        And I click the "label[for=identity_declarantStatus_0]" element
        And I click the "label[for=identity_civilState_civility_0]" element
        And I fill in "identity_civilState_birthName" with "Dupont"
        And I fill in "identity_civilState_firstnames" with "Jean Pierre Marie"
        And I select "1" from "identity_civilState_familySituation"
        And I fill in "identity_civilState_birthDate" with "01/01/2000"
        And I fill in the autocomplete "identity_civilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
        And I fill in the autocomplete "identity_civilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
        And I fill in "contact-information-address" with "avenue de la république paris"
        And I click the "#contact-information-address-75111_8158" element
        And I fill in "identity_contactInformation_email" with "jean@test.com"
        And I fill in "identity_contactInformation_confirmationEmail" with "jean@test.com"
        And I fill in "identity_contactInformation_phone_number" with "0102020304"
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        When I fill in "facts_description" with "description informations lorem ipsum dol or sit amet lorem ipsum dol or sit amet"
        And I click the "label[for=facts_address_addressOrRouteFactsKnown_0]" element
        And I fill in "facts-startAddress-address" with "1 test street"
        And I fill in "facts-startAddress-address" with "2 test street"
        And I click the "label[for=facts_offenseDate_exactDateKnown_0]" element
        And I wait for "input[name='facts_offenseDate_startDate']" to appear
        And I fill in "facts_offenseDate_startDate" with "01/01/2022"
        And I click the "label[for=facts_offenseDate_choiceHour_0]" element
        And I fill in "facts_offenseDate_hour" with "15:00"
        And I select "2" from "facts_placeNature"
        And I press "facts_submit"
        Then I should be on "/porter-plainte/objets"
        Given I select "5" from "objects_objects_0_category"
        And I select "1" from "objects_objects_0_status"
        And I fill in "objects_objects_0_label" with "Object 1"
        And I fill in "objects_objects_0_amount" with "100"
        And I press "objects_objects_add"
        And I select "5" from "objects_objects_1_category"
        And I select "1" from "objects_objects_1_status"
        And I fill in "objects_objects_1_label" with "Object 2"
        And I fill in "objects_objects_1_amount" with "100"
        And I press "objects_submit"
        Then I should be on "/porter-plainte/informations-complementaires"
        When I click the "label[for=additional_information_suspectsChoice_0]" element
        And I fill in "additional_information_suspectsText" with "suspects informations"
        And I should see the key "pel.facts.suspects.informations.text" translated
        And I click the "label[for=additional_information_witnessesPresent_0]" element
        And I fill in "additional_information_witnesses_0_description" with "Jean DUPONT"
        And I fill in "additional_information_witnesses_0_email" with "jean.dupont@example.com"
        And I fill in "additional_information_witnesses_0_phone_number" with "0602030405"
        And I click the "label[for=additional_information_fsiVisit_0]" element
        And I click the "label[for=additional_information_observationMade_0]" element
        And I click the "label[for=additional_information_cctvPresent_0]" element
        And I click the "label[for=additional_information_cctvAvailable_0]" element
        And I press "additional_information_submit"
        Then I should be on "/porter-plainte/recapitulatif"
        When I press "summary_submit"
        Then I should be on "/porter-plainte/rendez-vous"
        When I press "appointment_submit"
        Then I should be on "/porter-plainte/rendez-vous"
        And I should see a "#form-errors-appointment_appointmentContactText" element

    # Person Legal Representative must be hidden for the experimentation
#    Scenario: I should see error if I valid with no filling the appointment textarea when I am person legal representative
#        Given I am on "/porter-plainte/identite"
#        And I click the "label[for=identity_declarantStatus_1]" element
#        When I click the "label[for=identity_representedPersonCivilState_civility_0]" element
#        And I fill in "identity_representedPersonCivilState_birthName" with "Dupont"
#        And I fill in "identity_representedPersonCivilState_firstnames" with "Jean Pierre Marie"
#        And I select "1" from "identity_representedPersonCivilState_familySituation"
#        And I fill in "identity_representedPersonCivilState_birthDate" with "01/01/2000"
#        And I fill in the autocomplete "identity_representedPersonCivilState_birthLocation_frenchTown-ts-control" with "Paris" and click "75056"
#        And I fill in the autocomplete "identity_representedPersonCivilState_job-ts-control" with "Abatteur de bestiaux" and click "abatteur_de_bestiaux"
#        And I fill in "represented-person-address" with "avenue de la république paris"
#        And I click the "#represented-person-address-75111_8158" element
#        And I fill in "identity_representedPersonContactInformation_email" with "jean@test.com"
#        And I fill in "identity_representedPersonContactInformation_phone_number" with "0102020304"
#        And I press "identity_submit"
#        Then I should be on "/porter-plainte/faits"
#        Given I am on "/porter-plainte/rendez-vous"
#        When I press "appointment_submit"
#        Then I should be on "/porter-plainte/rendez-vous"
#        And I should see a "#form-errors-appointment_appointmentContactText" element

    Scenario: I should see error if I valid with no filling the appointment textarea when I am corporation legal representative
        Given I am on "/porter-plainte/identite"
        # Change the following value when the Person Legal Representative is reenabled
        And I click the "label[for=identity_declarantStatus_1]" element
        When I fill in "identity_corporation_siret" with "12345678900000"
        And I fill in "identity_corporation_name" with "Test Company"
        And I fill in "identity_corporation_function" with "Developer"
        And I fill in "identity_corporation_email" with "jean@test.com"
        And I fill in "identity_corporation_phone_number" with "0102020304"
        And I fill in "corporation-address" with "avenue de la république paris"
        And I wait for "#corporation-address-38485_0570" to appear
        And I click the "#corporation-address-38485_0570" element
        And I press "identity_submit"
        Then I should be on "/porter-plainte/faits"
        Given I am on "/porter-plainte/rendez-vous"
        When I press "appointment_submit"
        Then I should be on "/porter-plainte/rendez-vous"
        And I should see a "#form-errors-appointment_appointmentContactText" element

    Scenario: I should see a search input, a google maps and a appointment detail text textarea
        Then I should see a "div#appointment-map" element
        And I should see a ".gm-style" element
        And I should see a "textarea#appointment_appointmentContactText" element
        And I should see a "input#map-search" element

    Scenario: I should see units markers when I search in the search input
        When I fill in the map autocomplete "map-search" with "Pessac" and click on the first result
        Then I should see 5 ".unit-list-item" element
        And I should see "5 résultats"
        When I click the "div[data-unit-id-anonym=4017] .unit-select" element
        Then I should see a "div[data-unit-id-anonym=4017] .unit-unselect" element
        Then I should not see a "div[data-unit-id-anonym=4017] .unit-unselect.fr-hidden" element
        And I should see a "div[data-unit-id-anonym=4017] .unit-select.fr-hidden" element
        And I should see the key "pel.chosen" translated

    Scenario: I can pick a unit and submit the page
        When I fill in the map autocomplete "map-search" with "Pessac" and click on the first result
        And I click the "div[data-unit-id-anonym=4017] .unit-select" element
        And I press "appointment_submit"
        Then I should be on "/porter-plainte/fin"
        And I should see "Commissariat de police de Bordeaux"
        And I should see "23 Rue François de Sourdis 33000 BORDEAUX"

    Scenario: I can unpick a unit
        When I fill in the map autocomplete "map-search" with "Pessac" and click on the first result
        And I click the "div[data-unit-id-anonym=4017] .unit-select" element
        And I click the "div[data-unit-id-anonym=4017] .unit-unselect" element
        Then I should see a "div[data-unit-id-anonym=4017] .unit-unselect.fr-hidden" element
        And I should not see a "div[data-unit-id-anonym=4017] .unit-select.fr-hidden" element

    Scenario: I can click on a marker and see the unit details on the left menu
        When I fill in the map autocomplete "map-search" with "Pessac" and click on the first result
        And I click on the marker at index 4017
        Then I should not see a "div[data-unit-id-anonym=4017] .unit-unselect.fr-hidden" element
        And I should see a "div[data-unit-id-anonym=4017] .unit-select.fr-hidden" element
        When I press "appointment_submit"
        Then I should be on "/porter-plainte/fin"
        And I should see "Commissariat de police de Bordeaux"
        And I should see "23 Rue François de Sourdis 33000 BORDEAUX"

    @flaky
    Scenario: I can see the gendarmerie accessibility information
        When I fill in the map autocomplete "map-search" with "Pessac" and click on the first result
        And I click on the marker at index 1004099
        And I should see a "div[data-unit-id-anonym=1004099]" element
        When I press "unit-id-1004099"
        Then I should see a "#fr-modal-unit-accessibility-information" element
        And I wait 2000 ms
        And I should see "Gendarmerie - Brigade de Villegouge"
        And I should see "5 Route des Acacias 33141 Villegouge"
        And I should see "tél. 05 57 84 86 50"
        And I should see the key "pel.nearby.transport" translated
        Then I click the "#accordion-1" element
        And I should see "Arrêt de transport en commun à moins de 200 mètres de l'établissement"
        And I should see "Informations sur l'accessibilité par les transports en commun : Arrêt de bus devant le Vival"
        And I should see the key "pel.parking.on.the.premises" translated
        Then I click the "#accordion-1" element
        And I click the "#accordion-2" element
        And I should see "Pas de place de stationnement disponible au sein de la parcelle de l'établissement"
        And I should see "Des places de stationnement sont disponibles à moins de 200 mètres de l'établissement"
        And I should see "Des places de stationnement adaptées sont disponibles à moins de 200 mètres de l'établissement"
        And I should see the key "pel.outer.path" translated
        Then I click the "#accordion-2" element
        And I click the "#accordion-3" element
        And I wait 1000 ms
        And I should see "L'accès à l'entrée depuis la voirie se fait par un chemin extérieur"
        And I should see "Le revêtement est stable (absence de pavés, gravillons, terre, herbe, sable, ou toute surface non stabilisée)"
        And I should see "L'accès à cet espace n'est pas de plain-pied et présente une rupture brutale de niveau"
        And I should see "Nombre de marches de l'escalier : 0"
        And I should see "Présence d'une rampe fixe ou amovible : Fixe"
        And I should see "Le chemin n'est pas en pente"
        And I should see "Dévers ou inclinaison transversale du chemin : Aucun"
        And I should see "Pas de bande de guidage au sol facilitant le déplacement d'une personne aveugle ou malvoyante"
        And I should see "Pas de rétrécissement inférieur à 90 centimètres du chemin pour atteindre la zone d'accueil"
        And I should see the key "pel.entrance" translated
        Then I click the "#accordion-3" element
        And I click the "#accordion-4" element
        And I should see "L'entrée de l'établissement est facilement repérable"
        And I should see "Présence d'une porte à l'entrée de l'établissement"
        And I should see "Mode d'ouverture de la porte : Porte battante"
        And I should see "Type de porte : Manuelle"
        And I should see "La porte d'entrée est vitrée"
        And I should see "Des éléments contrastés permettent de visualiser les parties vitrées de l'entrée"
        And I should see "L'entrée se fait de plain-pied, c'est à dire sans rupture brutale de niveau"
        And I should see "Présence d'un dispositif comme une sonnette pour signaler sa présence"
        And I should see "Dispositifs d'appels présents : Interphone"
        And I should see "Pas de balise sonore facilitant son repérage par une personne aveugle ou malvoyante"
        And I should see "Possibilité d'une aide humaine au déplacement"
        And I should see "Largeur minimale de la porte d'entrée : 200"
        And I should see "Pas d'entrée secondaire spécifique dédiée aux personnes à mobilité réduite"
        And I should see the key "pel.reception.and.services" translated
        Then I click the "#accordion-4" element
        And I click the "#accordion-5" element
        And I wait 1000 ms
        And I should see "La zone d'accueil (guichet d'accueil, caisse, secrétariat, etc) est visible depuis l'entrée du bâtiment"
        And I should see "L'accès à cet espace se fait de plain-pied, c'est à dire sans rupture brutale de niveau"
        And I should see "Pas de rétrécissement inférieur à 90 centimètres du chemin pour atteindre la zone d'accueil"
        And I should see "Personnel à l'accueil des personnes handicapées : Personnels non-formés"
        And I should see "Pas de produits ou prestations dédiés aux personnes sourdes ou malentendantes"
        And I should see the key "pel.toilets" translated
        Then I click the "#accordion-5" element
        And I click the "#accordion-6" element
        And I wait 1000 ms
        And I should see "Des sanitaires sont mis à disposition dans l'établissement"
        And I should see "Aucun sanitaire adapté mis à disposition dans l'établissement"
        And I should see the key "pel.accessibility.information.provided.by" translated
        And I should see the key "pel.acces.libre.api" translated

    Scenario: If the appointment is required, I should only have a "Continue" button
        When I follow "Étape précédente"
        Then I should be on "/porter-plainte/recapitulatif"
        When I follow "update-facts"
        Then I should be on "/porter-plainte/faits/1"
        When I click the "label[for=facts_victimOfViolence]" element
        And I fill in "facts_victimOfViolenceText" with "Violence informations"
        And I press "facts_submit_recap"
        Then I should be on "/porter-plainte/recapitulatif"
        And I should see "Continuer"

    Scenario: I can make an appointment when it is not required
        When I follow "Étape précédente"
        Then I should be on "/porter-plainte/recapitulatif"
        When I click the "label[for=summary_appointmentAsked_0]" element
        And I press "summary_submit"
        Then I should be on "/porter-plainte/rendez-vous"
        When I press "appointment_submit"
        Then I should be on "/porter-plainte/fin"

    Scenario: I can finish the complaint without making an appointment if it is not required
        When I follow "Étape précédente"
        Then I should be on "/porter-plainte/recapitulatif"
        When I click the "label[for=summary_appointmentAsked_1]" element
        And I press "summary_submit"
        Then I should be on "/porter-plainte/fin"
