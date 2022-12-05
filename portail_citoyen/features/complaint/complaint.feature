@func
Feature:
    In order to fill a complaint
    As a user
    I want to see the complaint page

    Scenario: I can see the complaint page
        Given I am on "/porter-plainte"
        Then the response status code should be 200
        And I should see "Identité" in the ".fr-breadcrumb__list" element
        And I should see "Faits" in the ".fr-breadcrumb__list" element
        And I should see "Informations complémentaires" in the ".fr-breadcrumb__list" element
        And I should see the key "pel.ministry" translated
        And I should see the key "pel.inside" translated
        And I should see the key "pel.and.overseas" translated
        And I should see the key "pel.header.baseline" translated
        And I should see the key "pel.complaint.nature.of.the.facts" translated
        And I should see the key "pel.address.or.route.facts" translated
        And I should see the key "pel.additional.place.information" translated
        And I should see a "#facts_address_addressAdditionalInformation" element
        And I should not see a "#place_nature_moreInfoText" element
        And I should see the key "pel.complaint.exact.date.known" translated
        And I should see the key "pel.do.you.know.hour.facts" translated
        And I should see the key "pel.do.you.have.informations.on.potential.suspects" translated
        And I should see the key "pel.facts.witnesses" translated
        And I should see the key "pel.fsi.visit" translated
        And I should see the key "pel.cctv.present" translated
        And I should see the key "pel.object.category" translated
        And I should see the key "pel.object.category.choose" translated
        And I should see the key "pel.objects" translated
        And I should see the key "pel.objects.add" translated
        And I should see the key "pel.object" translated
        And I should see the key "pel.facts.description.precise" translated

    Scenario Outline: I can see the declarant status inputs
        Given I am on "/porter-plainte"
        Then I should see 3 "input[type=radio][name='identity[declarantStatus]']" elements
        And I should see "<declarant_status>" in the "<element>" element

        Examples:
            | element                               | declarant_status                           |
            | label[for=identity_declarantStatus_0] | Victime                                    |
            | label[for=identity_declarantStatus_1] | Représentant légal d'une personne physique |
            | label[for=identity_declarantStatus_2] | Représentant légal d'une personne morale   |

    Scenario Outline: I can see the offense nature checkboxes
        Given I am on "/porter-plainte"
        Then I should see 3 "input[type=checkbox][name='facts[offenseNature][offenseNatures][]']" elements
        And I should see "<offense_nature>" in the "<element>" element

        Examples:
            | element                                         | offense_nature           |
            | label[for=facts_offenseNature_offenseNatures_0] | Vol                      |
            | label[for=facts_offenseNature_offenseNatures_1] | Dégradation              |
            | label[for=facts_offenseNature_offenseNatures_2] | Autre atteinte aux biens |

    Scenario Outline: I can see the place natures list
        Given I am on "/porter-plainte"
        Then I should see "<place_nature>" in the "#facts_placeNature" element

        Examples:
            | place_nature           |
            | Domicile/Logement      |
            | Parking / garage       |
            | Voie publique / Rue    |
            | Commerce               |
            | Transports en commun   |
            | Autres natures de lieu |
            | Lieu indéterminé       |

    Scenario Outline: I can see the offense exact date known radio buttons
        Given I am on "/porter-plainte"
        Then I should see 2 "input[type=radio][name='facts[offenseDate][exactDateKnown]']" elements
        And I should see "<exact_date_known>" in the "<element>" element

        Examples:
            | element                                       | exact_date_known |
            | label[for=facts_offenseDate_exactDateKnown_0] | Oui              |
            | label[for=facts_offenseDate_exactDateKnown_1] | Non              |

    Scenario Outline: I can see the offense choice hour radio buttons
        Given I am on "/porter-plainte"
        Then I should see 3 "input[type=radio][name='facts[offenseDate][choiceHour]']" elements
        And I should see "<choice_hour>" in the "<element>" element

        Examples:
            | element                                   | choice_hour                             |
            | label[for=facts_offenseDate_choiceHour_0] | Oui je connais l'heure exacte des faits |
            | label[for=facts_offenseDate_choiceHour_1] | Non mais je connais le créneau horaire  |
            | label[for=facts_offenseDate_choiceHour_2] | Je ne connais pas l'heure des faits     |

    Scenario Outline: I can see the object category choice list
        Given I am on "/porter-plainte"
        When I select "<object_category>" from "objects_objects_0_category"
        Then I should see "<object_category>" in the "#objects_objects_0_category" element

        Examples:
            | object_category            |
            | Documents                  |
            | Moyens de paiement         |
            | Multimédia                 |
            | Véhicules immatriculés     |
            | Véhicules non immatriculés |
            | Autres                     |

    Scenario Outline: I can see the suspectsChoice choices
        Given I am on "/porter-plainte"
        Then I should see 2 "input[type=radio][name='additional_information[suspectsChoice]']" elements
        And I should see "<choice>" in the "<element>" element

        Examples:
            | element                                            | choice |
            | label[for=additional_information_suspectsChoice_0] | Oui    |
            | label[for=additional_information_suspectsChoice_1] | Non    |

    Scenario Outline: I can see the witnesses choices
        Given I am on "/porter-plainte"
        Then I should see 2 "input[type=radio][name='additional_information[witnesses]']" elements
        And I should see "<choice>" in the "<element>" element

        Examples:
            | element                                       | choice |
            | label[for=additional_information_witnesses_0] | Oui    |
            | label[for=additional_information_witnesses_1] | Non    |

    Scenario Outline: I can see the fsi visit choices
        Given I am on "/porter-plainte"
        Then I should see 2 "input[type=radio][name='additional_information[fsiVisit]']" elements
        And I should see "<choice>" in the "<element>" element

        Examples:
            | element                                      | choice |
            | label[for=additional_information_fsiVisit_0] | Oui    |
            | label[for=additional_information_fsiVisit_1] | Non    |

    Scenario Outline: I can see the cctv choices
        Given I am on "/porter-plainte"
        Then I should see 3 "input[type=radio][name='additional_information[cctvPresent]']" elements
        And I should see "<choice>" in the "<element>" element

        Examples:
            | element                                         | choice         |
            | label[for=additional_information_cctvPresent_0] | Oui            |
            | label[for=additional_information_cctvPresent_1] | Non            |
            | label[for=additional_information_cctvPresent_2] | Je ne sais pas |
