Feature: Public API
  In order to serve the front end
  As a client software developer
  I need to be able to retrieve "grunde" trough the API.

  Background:
    Given the following grunde exist:
      | Vej       | Husnummer | Bogstav | Salgsomraade | AnnonceresEj | DatoAnnonce |
      | Førstevej | 13        |         | 1            | 0            | -1 day      |
      | Førstevej | 11        |         | 1            | 0            | -1 day      |
      | Andenvej  | 171       | A       | 1            | 0            | -1 day      |
      | Andenvej  | 171       | C       | 1            | 0            | -1 day      |
      | Andenvej  | 171       | B       | 1            | 0            | -1 day      |
      | Tredjevej | 1         |         | 2            | 1            | -1 day      |
      | Tredjevej | 3         |         | 2            | 0            | -1 day      |
      | Tredjevej | 5         |         | 2            | 0            | -1 day      |
      | Fjerdevej | 3         | B       | 3            | 0            | -1 day      |
      | Fjerdevej | 3         | C       | 3            | 0            | +1 day      |
      | Åvej      | 7         |         | 4            | 0            | -1 day      |
      | Ældstevej | 3         |         | 4            | 0            | -1 day      |
      | Østervej  | 5         |         | 4            | 0            | -1 day      |
      | Zebravej  | 1         |         | 4            | 0            | -1 day      |


  @createSchema

  Scenario: Response is OK and JSON
    When I send a "GET" request to "public/api/udstykning/1/grunde"
    Then the response status code should be 200
    And the response should be in JSON

  Scenario: Grunde are filtered by Salgsomraade
    When I send a "GET" request to "public/api/udstykning/1/grunde"
    Then the JSON node "grunde" should have 5 elements

  Scenario: Grunde are filtered by AnnonceresEj
    When I send a "GET" request to "public/api/udstykning/2/grunde"
    Then the JSON node "grunde" should have 2 elements

  Scenario: Grunde are filtered by DatoAnnonce
    When I send a "GET" request to "public/api/udstykning/3/grunde"
    Then the JSON node "grunde" should have 1 elements

  Scenario: Grunde are sorted correctly by address
    When I send a "GET" request to "public/api/udstykning/1/grunde"
    Then the JSON node "grunde[0].address" should be equal to "Andenvej 171A"
    Then the JSON node "grunde[1].address" should be equal to "Andenvej 171B"
    Then the JSON node "grunde[2].address" should be equal to "Andenvej 171C"
    Then the JSON node "grunde[3].address" should be equal to "Førstevej 11"
    Then the JSON node "grunde[4].address" should be equal to "Førstevej 13"

  Scenario: Grunde with danish characters/names are sorted correctly by address
    When I send a "GET" request to "public/api/udstykning/4/grunde"
    Then the JSON node "grunde[0].address" should be equal to "Zebravej 1"
    Then the JSON node "grunde[1].address" should be equal to "Ældstevej 3"
    Then the JSON node "grunde[2].address" should be equal to "Østervej 5"
    Then the JSON node "grunde[3].address" should be equal to "Åvej 7"

  @dropSchema
  Scenario: Drop schema
