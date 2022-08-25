Feature:
    In order to show job and choose an element
    As a user
    I need to see jobs in a select element

    @func
    Scenario Outline: Show job page and select a job
        Given I am on "/job"
        Then the response status code should be 200
        And I should see 1 "body" element
        When I select "<job>" from "job_job"
        And I should see "<job>" in the ".fr-select" element

        Examples:
            | job             |
            | Policier        |
            | Gendarme        |
            | Sans Profession |
