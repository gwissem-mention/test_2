Feature:
    In order to be informed of my rights
    As a user
    I need to see a page presenting a law refresher

    @func
    Scenario: Show law refresher on /rappel-a-la-loi route with 200 status code
        Given I am on "/rappel-a-la-loi"
        Then the response status code should be 200
        And I should see the key "pel.step" translated
        And I should see the key "pel.on" translated
        And I should see the key "pel.next.step" translated
        And I should see the key "pel.before.online.complaint" translated
        And I should see the key "pel.legal.dispositions" translated
        And I should see the key "pel.before.starting.complaint" translated
        And I should see the key "pel.article.10.2" translated
        And I should see the key "pel.officers.and.agents" translated
        And I should see the key "pel.obtain.compensation.for.their.loss" translated
        And I should see the key "pel.filing.a.civil.suit" translated
        And I should see the key "pel.to.be.assisted" translated
        And I should see the key "pel.to.be.assisted.by.a.service" translated
        And I should see the key "pel.refer.the.matter" translated
        And I should see the key "pel.706.3" translated
        And I should see the key "pel.706.14" translated
        And I should see the key "pel.of.this.code" translated
        And I should see the key "pel.to.be.informed.of.the.protective.measures" translated
        And I should see the key "pel.for.victims" translated
        And I should see the key "pel.to.be.accompanied" translated
        And I should see the key "pel.to.declare.a.third.party.address" translated
        And I should see the key "pel.in.the.case.of.violence" translated
        And I should see the key "pel.article.8.2.2" translated
        And I should see the key "pel.when.a.victim.is.about.to.lodge" translated
        And I should see the key "pel.that.the.online.complaint" translated
        And I should see the key "pel.filing.a.complaint.online" translated
        And I should see the key "pel.in.any.case" translated
        And I should see the key "pel.in.addition.to.cases" translated
        And I should see the key "pel.to.the.penal.code" translated
        And I should see the key "pel.nota" translated
        And I should see the key "pel.in.accordance.to.article.11" translated
        And I should see the key "pel.in.accordance.to.article.2" translated
        And I should see the key "pel.complete.my.declaration" translated
