Feature: Oprettelse af Lokalplaner
  Som bruger af systemet har jeg  behov for at kunne oprette Lokalplaner

  Background:
    Given the following lokalplaner exist:
      | nr | titel                          | samletareal | salgbartareal | projektleder | telefon  |
      | 59 | Areal ved Gungdyvej            | 70000       | 30525         | Peder Leder  | 11223344 |
      | 11 | Boliger ved Elmehaven i Elsted | 120000      | 60525         | Peder Leder  | 11223344 |

  @createSchema

  Scenario: Admin can create a Lokalplan
    When I am logged in with role "Admin"
    And I go to "/"
    And I follow "Lokalplaner"
    And I follow "Tilføj"
    Then the response status code should be 200
    And I should see "Opret Lokalplan"
    When I fill in the following:
      | LP           | 120          |
      | Titel        | Test område  |
      | Projektleder | Test Testsen |
      | Tlfnr.       | 11223344     |
    And I press "Gem"
    Then the response status code should be 200

  Scenario: Editor can create a Lokalplan
    When I am logged in with role "Editor"
    And I go to "/"
    And I follow "Lokalplaner"
    And I follow "Tilføj"
    Then the response status code should be 200
    And I should see "Opret Lokalplan"
    When I fill in the following:
      | LP           | 120          |
      | Titel        | Test område  |
      | Projektleder | Test Testsen |
      | Tlfnr.       | 11223344     |
    And I press "Gem"
    Then the response status code should be 200

  Scenario: Reader cannot create a Lokalplan
    When I am logged in with role "Reader"
    And I go to "/"
    And I follow "Lokalplaner"
    Then I should not see "Tilføj"

  Scenario: Reader cannot create a Lokalplan
    When I am logged in with role "Reader"
    And I go to "/?action=new&entity=lokalplan"
    Then the response status code should not be 200

  @dropSchema
  Scenario: Drop schema
