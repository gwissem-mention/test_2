Feature:
    In order to show tos
    As a user
    I need to see a page with 2 buttons and 12 information texts

    @func
    Scenario: Show faq on /cgu route with 200 status code and a body
        Given I am on "/cgu"
        Then the response status code should be 200
        And I should see "Conditions d’utilisation du service - Plainte en ligne" in the "title" element
        And I should see 1 "body" element

    @func
    Scenario Outline: Show TOS with all translated elements
        Given I am on "/cgu"
        Then I should see the key "<trans>" translated

        Examples:
            | trans                                                                     |
            | pel.help.tos.title                                                        |
            | pel.help.tos.heading                                                      |
            | pel.help.tos.type.of.offence.credit.card.theft                            |
            | pel.help.tos.type.of.offence.personal.offences                            |
            | pel.help.tos.visit.masecurite.website                                     |
            | pel.help.tos.find.my.procedure                                            |
            | pel.help.tos.my.status                                                    |
            | pel.help.tos.i.know.the.offense.author                                    |
            | pel.help.tos.i.am.minor                                                   |
            | pel.help.tos.i.am.neither.victim.nor.legal.representative                 |
            | pel.help.tos.visit.police.station                                      |
            | pel.help.tos.locate.police.station                             |
