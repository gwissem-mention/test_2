Feature:
    In order to show homepage
    As a user
    I need to see a page with 5 buttons and 2 information texts

    @func
    Scenario: Show homepage on / route with 200 status code and a body
        Given I am on "/"
        Then the response status code should be 200
        And I should see 1 "body" element

    @func
    Scenario Outline: Show homepage with all translated elements
        Given I am on "/"
        Then I should see the key "<trans>" translated

        Examples:
            | trans                                               |
            | pel.ministry                                        |
            | pel.inside                                          |
            | pel.and.overseas                                    |
            | pel.online.complaint                                |
            | pel.portal                                          |
            | pel.home.intro                                      |
            | pel.a.dematerialized.service                        |
            | pel.a.dematerialized.service.text.1                 |
            | pel.a.dematerialized.service.text.2                 |
            | pel.a.dematerialized.service.text.3                 |
            | pel.compose.the.17                                  |
            | pel.if.accident.ongoing                             |
            | pel.if.suspect.still.present                        |
            | pel.if.person.injured.or.in.danger                  |
            | pel.be.sure.facts.are.relevant.for.this.service     |
            | pel.use.online.complaint.for                        |
            | pel.damage.to.a.vehicle                             |
            | pel.damage.except.vehicle                           |
            | pel.thefts                                          |
            | pel.drive.complaints                                |
            | pel.fraudulent.use.of.stolen.credit.card            |
            | pel.scam.except.online                              |
            | pel.other.goods.damages                             |
            | pel.you.dont.know.offense.author                    |
            | pel.fill.a.complaint                                |
            | pel.dont.use.online.complaint                       |
            | pel.if.you.know.offense.author                      |
            | pel.if.you.are.minor                                |
            | pel.if.you.are.victim.of.online.scam                |
            | pel.if.you.are.victim.of.credit.card.fraudulent.use |
            | pel.if.you.are.victim.of.violence                   |
            | pel.fsi.general.orienteer.message                   |
            | pel.fsi.general.orienteer                           |
            | pel.check.our.faq                                   |
            | pel.can.i.make.a.physical.appointment               |
            | pel.can.i.make.a.physical.appointment.text          |
            | pel.make.appointment                                |
            | pel.what.is.online.complaint                        |
            | pel.what.is.online.complaint.text                   |
            | pel.what.is.goods.damages                           |
            | pel.what.is.goods.damages.text                      |

    @func
    Scenario: Press button to be fsi general orienteer
        Given I am on "/"
        When I follow "Soyez guidé vers la bonne solution !"

    @func
    Scenario: Press button to be redirect to appointment
        Given I am on "/"
        Then I follow "Prendre un rendez-vous"
        And I should be on "/rendez-vous"

    @javascript
    Scenario Outline: I can open a confirmation modal when I click on "Continue" and I can click on modal buttons
        Given I am on "/"
        When I press "Déposer une plainte pour une atteinte aux bien"
        Then I should see 1 "#fr-modal-complaint-confirm[open=true]" element
        And I should see the key "<key>" translated
        When I follow "Retour à l'accueil"
        Then I should be on "/"
        Given I am on "/"
        When I press "Déposer une plainte pour une atteinte aux bien"
        And I follow "Je confirme"
        Then I should be on "/accueil-deroule"

        Examples:
            | key                                      |
            | pel.close                                |
            | pel.offense.context                      |
            | pel.could.you.confirm.complaint.validity |
            | pel.being.major                          |
            | pel.being.victim.or.representative       |
            | pel.dont.know.the.offense.author         |
            | pel.i.confirm                            |
            | pel.back.to.homepage                     |
