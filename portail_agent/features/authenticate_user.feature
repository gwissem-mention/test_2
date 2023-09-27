@func
Feature: Create/update user according to SSO attributes

    Scenario: Create user on first connection
        Given the following HTTP headers
            | name        | value     |
            | matricule   | QSWWHQQT |
            | appelation  | Foo Bar   |
            | institution | PN        |
            | codeservice | service_3 |
            | superviseur | 1         |

        When I am on "/"
        Then the "user" QSWWHQQT from PN exists with:
            | attribute    | value                     |
            | appellation  | Foo Bar                   |
            | service_code | service_3                 |
            | roles        | ROLE_SUPERVISOR,ROLE_USER |

    Scenario: Update user if exists
        Given the following HTTP headers
            | name        | value           |
            | matricule   | ZSBVHOAY        |
            | appelation  | Margaud MARCHAL |
            | institution | PN              |
            | codeservice | service_2       |
            | superviseur | 1               |

        When I am on "/"
        Then the "user" ZSBVHOAY from PN exists with:
            | attribute    | value                     |
            | appellation  | Margaud MARCHAL           |
            | service_code | service_2                 |
            | roles        | ROLE_SUPERVISOR,ROLE_USER |

    Scenario: Empty headers
        Given the following HTTP headers
            | name        | value |
            | matricule   |       |
            | appelation  |       |
            | institution |       |
            | codeservice |       |
            | superviseur |       |

        When I am on "/"
        Then the response status code should be 401

    Scenario: Institution does not exists
        Given the following HTTP headers
            | name        | value |
            | matricule   | 12345 |
            | appelation  | foo   |
            | institution | bar   |
            | codeservice | baz   |
            | superviseur | 0     |

        When I am on "/"
        Then the response status code should be 401
