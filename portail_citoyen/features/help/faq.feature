Feature:
    In order to show faq
    As a user
    I need to see a page with 2 buttons and 41 information texts

    @func
    Scenario: Show faq on /faq route with 200 status code and a body
        Given I am on "/faq"
        Then the response status code should be 200
        And I should see "Centre d’aide - Plainte en ligne" in the "title" element
        And I should see 1 "body" element

    @func
    Scenario Outline: Show FAQ with all translated elements
        Given I am on "/faq"
        Then I should see the key "<trans>" translated

        Examples:
            | trans                                                                     |
            | pel.help.faq.title                                                        |
            | pel.help.faq.heading                                                      |
            | pel.check.our.faq                                                         |
            | pel.help.faq.concerned.with                                               |
            | pel.help.faq.victim.of.damage.to.property                                 |
            | pel.help.faq.victim.of.damage.to.property.examples                        |
            | pel.help.faq.theft.victim                                                 |
            | pel.help.faq.theft.victim.examples                                        |
            | pel.help.faq.victim.of.credit.card.theft                                  |
            | pel.help.faq.victim.of.credit.card.theft.examples                         |
            | pel.help.faq.victim.of.offline.scams                                      |
            | pel.help.faq.victim.of.offline.scams.examples                             |
            | pel.help.faq.victim.of.other.property.crimes                              |
            | pel.help.faq.victim.of.other.property.crimes.examples                     |
            | pel.fsi.general.orienteer                                                 |
            | pel.help.faq.fill.a.complaint                                             |
            | pel.check.our.faq.heading                                                 |
            | pel.what.is.legal.representative                                          |
            | pel.what.is.legal.representative.text                                     |
            | pel.can.i.make.a.physical.appointment                                     |
            | pel.can.i.make.a.physical.appointment.text                                |
            | pel.why.i.require.a.physical.appointment                                  |
            | pel.why.i.require.a.physical.appointment.text                             |
            | pel.why.i.require.a.physical.appointment.text.theft.with.violence         |
            | pel.why.i.require.a.physical.appointment.text.theft.of.registered.vehicle |
            | pel.why.i.require.a.physical.appointment.text.continue.pel.without.log.in |
            | pel.why.i.require.a.physical.appointment.text.other.complex.offenses      |
            | pel.what.is.goods.damages                                                 |
            | pel.what.is.goods.damages.text                                            |
            | pel.why.we.ask.me.to.be.france.connected                                  |
            | pel.identity.of.the.complainant.is.mandatory                              |
            | pel.france.connect.allow.this.verification                                |
            | pel.guaranteed.identity.informations                                      |
            | pel.if.you.dont.have.a.france.connect.account                             |
            | pel.otherwise.you.will.have.to.go.to.the.office                           |
            | pel.what.if.i.know.the.offense.author                                     |
            | pel.what.if.i.know.the.offense.author.text                                |
            | pel.what.is.online.complaint                                              |
            | pel.what.is.online.complaint.text                                         |
            | pel.what.subsidiary.question                                              |
            | pel.what.subsidiary.question.text                                         |

    @func
    Scenario: Press button to continue filing the complaint
        Given I am on "/faq"
        When I follow "pel-confirm-online-complaint-that"
        Then I should be on "/accueil-confirmation#pel-confirm-online-complaint-that"
