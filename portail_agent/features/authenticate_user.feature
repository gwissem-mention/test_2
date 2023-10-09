@func
Feature: Create/update user according to SSO attributes

    Scenario: Create user on first connection
        Given the following HTTP headers
            | name        | value       |
            | Matricule   | QSWWHQQT    |
            | Appelation  | Foo Bar     |
            | Institution | PN          |
            | Codeservice | service_3   |
            | Profil      | superviseur |

        When I am on "/"
        Then the "user" QSWWHQQT from PN exists with:
            | attribute    | value                     |
            | appellation  | Foo Bar                   |
            | service_code | service_3                 |
            | roles        | ROLE_SUPERVISOR,ROLE_USER |

    Scenario: Update user if exists
        Given the following HTTP headers
            | name        | value           |
            | Matricule   | ZSBVHOAY        |
            | Appelation  | Margaud MARCHAL |
            | Institution | PN              |
            | Codeservice | service_2       |
            | Profil      | superviseur     |

        When I am on "/"
        Then the "user" ZSBVHOAY from PN exists with:
            | attribute    | value                     |
            | appellation  | Margaud MARCHAL           |
            | service_code | service_2                 |
            | roles        | ROLE_SUPERVISOR,ROLE_USER |

    Scenario: Empty headers
        Given the following HTTP headers
            | name        | value |
            | Matricule   |       |
            | Appelation  |       |
            | Institution |       |
            | Codeservice |       |
            | Profil      |       |

        When I am on "/"
        Then the response status code should be 401

    Scenario: Institution does not exists
        Given the following HTTP headers
            | name        | value |
            | Matricule   | 12345 |
            | Appelation  | foo   |
            | Institution | bar   |
            | Codeservice | baz   |
            | Profil      | fsi   |

        When I am on "/"
        Then the response status code should be 401
