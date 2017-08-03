Feature: Visning af Delområder
  Som bruger af systemet har jeg  behov for at kunne se og redigere Delområder

  Background:
    Given the following delomraader exist:
      | Anvendelse           |
      | Erhverv              |

  @createSchema

  Scenario: Response is OK and JSON
    When I go to "/"
    Then the response status code should be 200
    And I should see "Parcelhusgrunde"

  @dropSchema
  Scenario: Drop schema
