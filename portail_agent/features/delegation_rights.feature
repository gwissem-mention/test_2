@javascript
Feature:
    In order to be able to delegate my rights
    As a supervisor
    I can use the right delegation system

    Background:
        Given I am authenticated with ZSBVHOAY from PN
        And I am on the homepage
        When I press "user_avatar"
        Then I should see a "#profile-sidebar" element
        When I follow "Délégation des droits"
        Then I should see a ".modal[aria-modal=true]" element
        And I select a date range on "right_delegation_dateRangePicker" Flatpickr element from "2025-02-01" to "2025-02-28"
        Then I should see the key "pel.selected.delegation.period" translated
        And I should see "du 01/02/2025 au 28/02/2025"
        And I should see the key "pel.select.one.or.more.delegated.agents" translated
        Then I click the "label[for='right_delegation_delegatedAgents_1']" element
        And I press "rights-delegation-validate"
        Then I should not see a ".modal[aria-modal=true]" element

    Scenario: As a supervisor, I can see the delegation set-up
        Given I press "user_avatar"
        And I follow "Délégation des droits"
        Then I should see the key "pel.you.have.selected.a.delegation" translated
        And I should see the key "pel.from" translated
        And I should see the key "pel.to" translated
        And I should see the key "pel.to.delegate.your.right.to" translated
        And I should see the key "pel.modify.delegation" translated
        And I should see the key "pel.cancel.delegation" translated
        Then I should see "Vous avez sélectionné une durée limitée du 01/02/2025 au 28/02/2025 pour déléguer vos droits à Frédérique BONNIN"

    Scenario: As a supervisor, I can modify the delegation set-up
        Given I press "user_avatar"
        And I follow "Délégation des droits"
        Then I press "rights-delegation-modify"
        And I should see "du 01/02/2025 au 28/02/2025"
        And I should see the "#right_delegation_delegatedAgents_1" field checked
        Then I press "Modifier les dates"
        And I should see a ".flatpickr-calendar" element
        And I select a date range on "right_delegation_dateRangePicker" Flatpickr element from "2025-03-01" to "2025-03-31"
        And I should see "du 01/03/2025 au 31/03/2025"
        Then I click the "label[for='right_delegation_delegatedAgents_1']" element
        And I click the "label[for='right_delegation_delegatedAgents_23']" element
        And I press "rights-delegation-validate"
        When I press "user_avatar"
        And I follow "Délégation des droits"
        Then I should see "Vous avez sélectionné une durée limitée du 01/03/2025 au 31/03/2025 pour déléguer vos droits à Bernard EVRARD"
