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
            | trans                                                                 |
            | pel.help.faq.title                                                    |
            | pel.help.faq.heading                                                  |
            | pel.check.our.faq                                                     |
            | pel.help.faq.concerned.with                                           |
            | pel.following.offenses                                                |
            | pel.degradation                                                       |
            | pel.hit.and.run                                                       |
            | pel.help.faq.theft.victim                                             |
            | pel.help.faq.theft.victim.examples                                    |
            | pel.help.faq.victim.of.credit.card.theft                              |
            | pel.help.faq.victim.of.credit.card.theft.examples                     |
            | pel.help.faq.victim.of.credit.card.theft.not.stolen                   |
            | pel.help.faq.victim.of.offline.scams                                  |
            | pel.help.faq.victim.of.offline.scams.examples                         |
            | pel.help.faq.scam.must.not.have.been.committed.online                 |
            | pel.help.faq.victim.of.other.property.crimes                          |
            | pel.help.faq.victim.of.other.property.crimes.examples                 |
            | pel.help.faq.victim.of.other.property.crimes.breach.of.trust          |
            | pel.help.faq.victim.of.other.property.crimes.extorsion                |
            | pel.help.faq.victim.of.other.property.crimes.blackmail                |
            | pel.help.faq.victim.of.other.property.crimes.trickery                 |
            | pel.fsi.general.orienteer                                             |
            | pel.help.faq.fill.a.complaint                                         |
            | pel.check.our.faq.heading                                             |
            | pel.what.is.complaint                                                 |
            | pel.what.is.complaint.text                                            |
            | pel.difference.between.online.declaration.and.a.complaint.report      |
            | pel.difference.between.online.declaration.and.a.complaint.report.text |
            | pel.what.is.a.complaint.against.an.unknown.person                     |
            | pel.what.is.a.complaint.against.an.unknown.person.text                |
            | pel.difference.complaint.and.handrail                                 |
            | pel.difference.complaint.and.handrail.text                            |
            | pel.who.can.file.a.complaint                                          |
            | pel.what.is.legal.representative                                      |
            | pel.what.is.legal.entity                                              |
            | pel.what.is.legal.entity.text                                          |
            | pel.what.is.an.infraction                                             |
            | pel.what.is.an.infraction.text                                        |
            | pel.offenses.classified.according.to.their.seriousness                |
            | pel.contraventions                                                    |
            | pel.misdemeanors                                                      |
            | pel.felonies                                                          |
            | pel.attempt                                                           |
            | pel.how.can.I.file.a.complaint                                        |
            | pel.how.can.I.file.a.complaint.text                                   |
            | pel.what.is.the.deadline.for.filing.a.complaint                       |
            | pel.what.is.the.deadline.for.filing.a.complaint.text                  |
            | pel.one.year.infraction                                               |
            | pel.six.year.offense                                                  |
            | pel.damage.to.property                                                |
            | pel.online.complaint.presentation                                     |
            | pel.who.can.file.an.online.complaint                                  |
            | pel.who.can.file.an.online.complaint.text                             |
            | pel.what.grounds.can.I.make.an.online.complaint                       |
            | pel.what.grounds.can.I.make.an.online.complaint.text                  |
            | pel.damage.to.property.and.hit-and-run                                |
            | pel.theft                                                             |
            | pel.theft.of.my.bank.card.followed.by.fraudulent.use                  |
            | pel.victim.of.an.internet.scam                                        |

    @func
    Scenario: Press button to continue filing the complaint
        Given I am on "/faq"
        When I follow "pel-confirm-online-complaint-that"
        Then I should be on "/accueil-confirmation#pel-confirm-online-complaint-that"
