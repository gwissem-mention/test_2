Feature:
    In order to show the homepage
    As a user
    I want to see the homepage

    @func
    Scenario: I can navigate on the homepage and I should see a header and an image
        When I am on the homepage
        Then the response status code should be 200
        And I should see a "nav" element
        And I should see the key "pel.search" translated
        And the response should contain the key "pel.home" translated
        And I should see the key "pel.agent.complaint.online" translated
        And I should see the key "pel.header.baseline" translated
