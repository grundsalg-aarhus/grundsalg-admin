Feature: Visning af Delområder
  Som bruger af systemet har jeg  behov for at kunne se og redigere Delområder

  Background:
    Given the following delomraader exist:
      | kpl1 | kpl2 | kpl3 | kpl4 | Anvendelse          |
      | 11   | 22   | 33   | BL   | Erhverv             |

  @createSchema

  Scenario: I can view a Delområde
    When I am logged in with role "Admin"
    And I go to "/"
    And I follow "Delområder"
    And I follow "Vis"
    Then the response status code should be 200
    And I should see "11"
    And I should see "22"
    And I should see "33"
    And I should see "BL"
    And I should see "Rediger"

  @dropSchema
  Scenario: Drop schema
