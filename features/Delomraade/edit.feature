Feature: Visning af Delområder
  Som bruger af systemet har jeg  behov for at kunne se og redigere Delområder

  Background:
    Given the following delomraader exist:
      | kpl1 | kpl2 | kpl3 | kpl4 | Anvendelse          |
      | 11   | 22   | 33   | BL   | Erhverv             |

  @createSchema

#  Scenario: Admin can edit a Delområde
#    When I am logged in with role "Admin"
#    And I go to "/"
#    And I follow "Delområder"
#    And I follow "Rediger"
#    Then the response status code should be 200
#    And I should see "Rediger Delområde"

  @debug
  Scenario: Editor can edit a Delområde
    When I am logged in with role "Editor"
    And I go to "/"
    And I follow "Delområder"
    And I follow "Rediger"
    And I should see "Rediger Delområde"
    And I put a breakpoint

  Scenario: Reader can NOT edit a Delområde
    When I am logged in with role "Reader"
    And I go to "/"
    And I follow "Delområder"
    Then I should not see "Rediger"
    And I follow "Vis"
    And I put a breakpoint
    Then I should not see "Slet"

  @dropSchema
  Scenario: Drop schema
