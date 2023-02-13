@func
Feature: Create/update user according to SSO attributes

    Scenario: Create user on first connection
        Given the following HTTP headers
            | name        | value     |
            | matricule   | 987654321 |
            | appelation  | Foo Bar   |
            | institution | PN        |
            | codeservice | service_3 |
            | superviseur | 1         |

        When I am on "/"
        Then the "user" 987654321 from PN exists with:
            | attribute    | value                     |
            | appellation  | Foo Bar                   |
            | service_code | service_3                 |
            | roles        | ROLE_SUPERVISOR,ROLE_USER |

    Scenario: Update user if exists
        Given the following HTTP headers
            | name        | value       |
            | matricule   | H3U3XCGD    |
            | appelation  | Jean DUPOND |
            | institution | PN          |
            | codeservice | service_2   |
            | superviseur | 1           |

        When I am on "/"
        Then the "user" H3U3XCGD from PN exists with:
            | attribute    | value                     |
            | appellation  | Jean DUPOND               |
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
