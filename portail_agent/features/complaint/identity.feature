Feature:
    In order to the complaint identity page
    As a user
    I want to see the complaint identity page

    @func
    Scenario: I want to show complaint identity page
        Given I am on "/plainte/identite/1"
        And the response status code should be 200
        And I should see a "body" element
        And I should see a "nav" element
        And I should see a "aside" element
        And I should see a "main" element
        And I should see 3 "button[data-bs-toggle='modal']" element
        And I should see 18 "button" element
        And I should see the key "pel.send.to.lrp" translated
        And I should see the key "pel.reject" translated
        And I should see the key "pel.reasign" translated
        And I should see the key "pel.comment" translated
        And I should see the key "pel.summary" translated
        And I should see the key "pel.declarant.civil.status" translated
        And the "identity_civility_0" checkbox should not be checked
        And the "identity_civility_1" checkbox should be checked
        And I should see the key "pel.lastname" translated
        And the "identity_lastname" field should contain "DUPONT"
        And I should see the key "pel.firstname" translated
        And the "identity_firstname" field should contain "Jean"
        And I should see the key "pel.birth.date" translated
        And the "identity_birthday" field should contain "1967-03-07"
        And I should see the key "pel.birth.country" translated
        And the "identity_birthCountry" field should contain "France"
        And I should see the key "pel.birth.city" translated
        And the "identity_birthCity" field should contain "Paris"
        And I should see the key "pel.declarant.contact.information" translated
        And I should see the key "pel.phone.number" translated
        And the "identity_phone" field should contain "06 12 34 45 57"
        And I should see the key "pel.email" translated
        And the "identity_email" field should contain "jean.dupont@gmail.com"
        And I should see the key "pel.sms.notification" translated
        And I should see the key "pel.want.to.receive.sms.notifications" translated
        And the "identity_smsNotifications" checkbox should be checked
        And I should see the key "pel.declarant.address" translated
        And the "identity_address" field should contain "15 Rue PAIRA, Meudon, 92190"
        And I should see the key "pel.address.france.label" translated
        And I should see the key "pel.declarant.situation" translated
        And I should see the key "pel.declarant.complain.as" translated
        And the "identity_declarantStatus" field should contain "1"

