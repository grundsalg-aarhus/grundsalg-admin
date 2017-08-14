Feature: Visning af Lokalplan
  Som bruger af systemet har jeg  behov for at kunne se oversigter og og søge i lokalplaner

  Background:
    Given the following lokalplaner exist:
      | nr | titel                          | samletareal | salgbartareal | projektleder | telefon  |
      | 59 | Areal ved Gungdyvej            | 70000       | 30525         | Peder Leder  | 11223344 |
      | 11 | Boliger ved Elmehaven i Elsted | 120000      | 60525         | Peder Leder  | 11223344 |

  @createSchema

  Scenario: Table overview shows
    When I am logged in with role "Admin"
    And I go to "/?entity=lokalplan&action=list"
    Then the response status code should be 200
    And I should see "Lokalplaner"
    And I should see "Areal ved Gungdyvej"

  Scenario: Sorting table columns doesn't produce an error
    When I am logged in with role "Admin"
    And I go to "/?entity=lokalplan&action=list"
    And I follow "LPNr"
    And I follow "Titel"
    And I follow "Samlet Areal"
    And I follow "Salgbart Areal"
    And I follow "Forbrugsandel"
    Then I should see "Areal ved Gungdyvej"

  @debug
  Scenario: List should be searchable - numeric
    When I am logged in with role "Admin"
    And I go to "/?entity=lokalplan&action=list"
    And I fill in the following:
      | query | 59 |
    And I press "Søg"
    Then the response status code should be 200
    And I should see "Gungdyvej"
    And I should not see "Elmehaven"

  @dropSchema
  Scenario: Drop schema
