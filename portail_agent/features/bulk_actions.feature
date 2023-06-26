Feature:
    As supervisor
    I can use action to assign a bulk of complaints to one of my agent
    I can use action to reassign a bulk of complaints to another unit

    Background:
        Given I am authenticated with PR5KTZ9R from GN

    @javascript
    Scenario: Interaction with simple complaint select
        Given I am on "/"
        And I should see a "table#datatable" element
        And I should see at least 2 "table#datatable tbody tr" elements
        When I check "complaint_91"
        And I check "complaint_92"
        Then I should see a "#groupActionsList" element
        And I should see the key "pel.assign.to" translated
        And I should see the key "pel.validate.the.unit.reassignment" translated

    @javascript
    Scenario: Interaction with all complaint select
        Given I am on "/"
        And I should see a "table#datatable" element
        And I should see at least 2 "table#datatable tbody tr" elements
        When I check "mainSelectorGroupAction"
        Then I should see a "#groupActionsList" element
        And I should see the key "pel.assign.to" translated
        And I should see the key "pel.validate.the.unit.reassignment" translated
        And I should see all ".secondarySelectorGroupAction" checked

    @javascript
    Scenario: I can bulk assign complaints
        Given I am on "/"
        And I should see a "table#datatable" element
        And I should see at least 2 "table#datatable tbody tr" elements
        When I check "complaint_91"
        And I check "complaint_92"
        And I press "Attribuer à"
        And I fill in the autocomplete "bulk_assign_assignedTo-ts-control" with "Julie" and click "4"
        And I press "Valider l'attribution"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see a ".toast" element
        And I should see the key "pel.the.declaration.has.been.assigned.to" translated


    @javascript
    Scenario: I should see an error message when I try to bulk re-assign complaints to an un-assigned complaint
        Given I am on "/"
        And I should see a "table#datatable" element
        And I should see at least 2 "table#datatable tbody tr" elements
        When I check "complaint_91"
        And I check "complaint_92"
        And I press "Réorienter"
        And I fill in the autocomplete "bulk_reassign_unitCodeToReassign-ts-control" with "Commissariat de police de Voiron" and click "103131"
        And I fill in "bulk_reassign_reassignText" with "Ces plaintes ne sont pas censée être attribuées à mon unité."
        And I press "bulk-unit-reassign-button"
        Then I should see a ".modal[aria-modal=true]" element
        And I should see the key "pel.only.assigned.complaint.can.be.reassigned" translated


    @javascript
    Scenario: I can bulk unit reassign complaints
        Given I am on "/"
        And I should see a "table#datatable" element
        And I should see at least 2 "table#datatable tbody tr" elements
        When I check "complaint_155"
        And I check "complaint_156"
        And I press "Réorienter"
        And I fill in the autocomplete "bulk_reassign_unitCodeToReassign-ts-control" with "Commissariat de police de Voiron" and click "103131"
        And I fill in "bulk_reassign_reassignText" with "Ces plaintes ne sont pas censée être attribuées à mon unité."
        And I press "bulk-unit-reassign-button"
        Then I should not see a ".modal[aria-modal=true]" element
        And I should see a ".toast" element
