Feature:
    In order to show homepage
    As a user
    I need to see a page with 5 buttons and 2 information texts

    @func
    Scenario: Show terms of use page on /conditions-generales-dutilisation route with 200 status code and a body
        Given I am on "/conditions-generales-dutilisation"
        Then the response status code should be 200
        And I should see "Conditions générales d'utilisation - Plainte en ligne" in the "title" element
        And I should see 1 "body" element

    @func
    Scenario Outline: Show terms of use page with all translated elements
        Given I am on "/conditions-generales-dutilisation"
        Then I should see the key "<trans>" translated

        Examples:
            | trans                                                     |
            | pel.terms.of.use.article.1.text.1                         |
            | pel.terms.of.use.article.2.text.3                         |
            | pel.terms.of.use.article.3.text.1                         |
            | pel.terms.of.use.article.3.text.2                         |
            | pel.terms.of.use.article.3.text.3                         |
            | pel.terms.of.use.article.3.text.4                         |
            | pel.terms.of.use.article.3.text.5                         |
            | pel.terms.of.use.article.4.text.1                         |
            | pel.terms.of.use.article.4.text.2                         |
            | pel.terms.of.use.article.5.text.1                         |
            | pel.terms.of.use.article.5.text.2                         |
            | pel.terms.of.use.article.6.text.1                         |
            | pel.terms.of.use.article.6.text.2                         |
            | pel.terms.of.use.article.7.text.1                         |
            | pel.terms.of.use.article.7.text.2                         |
            | pel.terms.of.use.article.8.text.1                         |
            | pel.terms.of.use.article.9.text.1                         |
            | pel.terms.of.use.article.10.text.1                        |
            | pel.terms.of.use.article.10.text.2                        |
            | pel.terms.of.use.article.11.text.1                        |
            | pel.terms.of.use.article.11.text.2                        |
            | pel.terms.of.use.article.12.text.1                        |
            | pel.terms.of.use.article.12.text.2                        |
