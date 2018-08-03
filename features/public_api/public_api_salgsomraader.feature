Feature: Public API
  In order to serve the front end
  As a client software developer
  I need to be able to retrieve "grunde" trough the API.

  Background:
    Given the following salgsomraader exist:
      | Type           | Titel                   | Vej     | Annonceres | srid  | Geometry                                         |
      | Parcelhusgrund | LP 826 Elsted/Elmehaven | Ølhaven | 1          | 25832 | POINT(577481.3338018466 6233798.938254305),25832 |
      | Parcelhusgrund | LP 827 Nopublic         | Nowhere | 0          | 25832 | POINT(577481.3338018466 6233798.938254305),25832 |

  @createSchema

  Scenario: Response is OK and JSON
    When I send a "GET" request to "/public/api/udstykning/1"
    Then the response status code should be 200
    And the response should be in JSON

  Scenario: Data is correct
    When I send a "GET" request to "/public/api/udstykning/1"
    Then the response status code should be 200
    And the JSON node "type" should be equal to "Parcelhusgrund"
    And the JSON node "vej" should be equal to "Ølhaven"

  Scenario: "Annonceres ikke" kan ikke tilgåes
    When I send a "GET" request to "/public/api/udstykning/2"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    []
    """
    And print last JSON response

  Scenario: GeoJSON is returned correctly
    When I send a "GET" request to "/public/api/udstykning/1"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "geometry.type" should be equal to "Point"
    And the JSON node "geometry.coordinates[0]" should be equal to "577481.33380185"
    And the JSON node "geometry.coordinates[1]" should be equal to "6233798.9382543"

  @dropSchema
  Scenario: Drop schema
