Feature: Public API
  In order to serve the front end
  As a client software developer
  I need to be able to retrieve "grunde" trough the API.

  Background:
    Given the following grunde exist:
      | Vej       | Husnummer | Bogstav | Salgsomraade | Annonceres | DatoAnnonce | Type           |
      | Førstevej | 13        |         | 1            | 1          | -1 day      | Parcelhusgrund |
      | Førstevej | 11        |         | 1            | 1          | -1 day      | Parcelhusgrund |
      | Andenvej  | 171       | A       | 1            | 1          | -1 day      | Parcelhusgrund |
      | Andenvej  | 171       | C       | 1            | 1          | -1 day      | Parcelhusgrund |
      | Andenvej  | 171       | B       | 1            | 1          | -1 day      | Parcelhusgrund |
      | Tredjevej | 1         |         | 2            | 0          | -1 day      | Parcelhusgrund |
      | Tredjevej | 3         |         | 2            | 1          | -1 day      | Parcelhusgrund |
      | Tredjevej | 5         |         | 2            | 1          | -1 day      | Parcelhusgrund |
      | Fjerdevej | 3         | B       | 3            | 1          | -1 day      | Parcelhusgrund |
      | Fjerdevej | 3         | C       | 3            | 1          | +1 day      | Parcelhusgrund |
      | Åvej      | 7         |         | 4            | 1          | -1 day      | Parcelhusgrund |
      | Ældstevej | 3         |         | 4            | 1          | -1 day      | Parcelhusgrund |
      | Østervej  | 5         |         | 4            | 1          | -1 day      | Parcelhusgrund |
      | Zebravej  | 1         |         | 4            | 1          | -1 day      | Parcelhusgrund |

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
