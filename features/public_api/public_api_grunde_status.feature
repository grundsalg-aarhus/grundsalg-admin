Feature: Public API
  In order to serve the front end
  As a client software developer
  I need to be able to retrieve "grunde" trough the API.

  Background:
    Given the following grunde exist:
      | Vej     | Husnummer | Bogstav | Salgsomraade | Annonceres | DatoAnnonce | Status       | SalgStatus       |
      | Testvej | 0         |         | 1            | 1          | -1 day      | Fremtidig    | Accepteret       |
      | Testvej | 1         | A       | 1            | 1          | -1 day      | Fremtidig    | Ledig            |
      | Testvej | 2         | C       | 1            | 1          | -1 day      | Ledig        | Ledig            |
      | Testvej | 3         | B       | 1            | 1          | -1 day      | Solgt        | Ledig            |
      | Testvej | 4         |         | 1            | 1          | -1 day      | Ledig        | Reserveret       |
      | Testvej | 5         |         | 1            | 1          | -1 day      | Ledig        | Skøde rekvireret |
      | Testvej | 6         |         | 1            | 1          | -1 day      | Auktion slut | Skøde rekvireret |
      | Testvej | 7         |         | 1            | 1          | -1 day      | Ledig        | Solgt            |
      | Testvej | 8         |         | 1            | 1          | -1 day      | Auktion slut | Solgt            |
      | Testvej | 9         |         | 1            | 1          | -1 day      | Annonceret   | Ledig            |

  @createSchema
  Scenario: Response is OK and JSON
    When I send a "GET" request to "public/api/udstykning/1/grunde"
    Then the response status code should be 200
    And the response should be in JSON

  Scenario: Grunde are filtered by Salgsomraade
    When I send a "GET" request to "public/api/udstykning/1/grunde"
    Then the JSON node "grunde" should have 10 elements

  Scenario: Grunde have the correct public status
    When I send a "GET" request to "public/api/udstykning/1/grunde"
    Then the JSON node "grunde[0].status" should be equal to "Solgt"
    Then the JSON node "grunde[1].status" should be equal to "I udbud"
    Then the JSON node "grunde[2].status" should be equal to "Ledig"
    Then the JSON node "grunde[3].status" should be equal to "Ledig"
    Then the JSON node "grunde[4].status" should be equal to "Reserveret"
    Then the JSON node "grunde[5].status" should be equal to "Solgt"
    Then the JSON node "grunde[6].status" should be equal to "Solgt"
    Then the JSON node "grunde[7].status" should be equal to "Solgt"
    Then the JSON node "grunde[8].status" should be equal to "Solgt"
    Then the JSON node "grunde[9].status" should be equal to "I udbud"

  @dropSchema
  Scenario: Drop schema
