Feature: Redigering af Grund
  Som bruger af systemet har jeg  behov for at kunne se og redigere Delområder

  Background:
    Given the following grunde exist:
      | Status | Resstart | Vej       | Husnummer | Bogstav | Salgsomraade | Annonceres | DatoAnnonce | Type  |
      | Ledig  | -1 day   | Førstevej | 13        |         | 1            | 1          | -2 day      | Andre |


  @createSchema

  Scenario: Admin can edit a Grund
    When I am logged in with role "Admin"
    And I go to "/"
    And I follow "Tilbud Andre Grunde"
    And I follow "Rediger"
    Then the response status code should be 200
    And I should see "Gem"
    And I should see "Opret Interessent"
    And I should see "Hent fra venteliste"
    And I should see "Annuller salg"


  Scenario: Editor can edit a Grund
    When I am logged in with role "Editor"
    And I go to "/"
    And I follow "Tilbud Andre Grunde"
    And I follow "Rediger"
    Then the response status code should be 200
    And I should see "Gem"
    And I should see "Opret Interessent"
    And I should see "Hent fra venteliste"
    And I should see "Annuller salg"

  Scenario: Reader can NOT edit a Grund
    When I am logged in with role "Reader"
    And I go to "/"
    And I follow "Tilbud Andre Grunde"
    And I should not see "Rediger"
    And I follow "Vis"
    And I should not see an ".action-delete" element

  @dropSchema
  Scenario: Drop schema
