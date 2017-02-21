Feature: Public API
  In order to serve the front end
  As a client software developer
  I need to be able to retrieve "grunde" trough the API.

#  $salgsomraade->setType($row['Type']);
#  $salgsomraade->setTitel($row['Titel']);
#  $salgsomraade->setVej($row['Vej']);
#  $salgsomraade->setAnnonceres($row['Annonceres']);
#  $salgsomraade->setPostby($postBy);
#
#  $point = $this->hydrateWKT($row['geometry']);
#  $salgsomraade->setSpGeometry($point);
#  $salgsomraade->setSrid($row['srid']);

  Background:
    Given the following salgsomraader exist:
      | Type           | Titel                   | Vej       | Annonceres | srid  | geometry                                         |
      | Parcelhusgrund | LP 826 Elsted/Elmehaven | Elmehaven | 1          | 25832 | POINT(577481.3338018466 6233798.938254305),25832 |

  @createSchema

  Scenario: Response is OK and JSON
    When I send a "GET" request to "public/api/udstykning/1"
    Then the response status code should be 200
    And the response should be in JSON

#  Scenario: Grunde are filtered by Salgsomraade
#    When I send a "GET" request to "public/api/udstykning/1/grunde"
#    Then the JSON node "grunde" should have 5 elements
#
#  Scenario: Grunde are filtered by AnnonceresEj
#    When I send a "GET" request to "public/api/udstykning/2/grunde"
#    Then the JSON node "grunde" should have 2 elements
#
#  Scenario: Grunde are filtered by DatoAnnonce
#    When I send a "GET" request to "public/api/udstykning/3/grunde"
#    Then the JSON node "grunde" should have 1 elements
#
#  Scenario: Grunde are sorted correctly by address
#    When I send a "GET" request to "public/api/udstykning/1/grunde"
#    Then the JSON node "grunde[0].address" should be equal to "Andenvej 171A"
#    Then the JSON node "grunde[1].address" should be equal to "Andenvej 171B"
#    Then the JSON node "grunde[2].address" should be equal to "Andenvej 171C"
#    Then the JSON node "grunde[3].address" should be equal to "Førstevej 11"
#    Then the JSON node "grunde[4].address" should be equal to "Førstevej 13"
#
#  Scenario: Grunde with danish characters/names are sorted correctly by address
#    When I send a "GET" request to "public/api/udstykning/4/grunde"
#    Then the JSON node "grunde[0].address" should be equal to "Zebravej 1"
#    Then the JSON node "grunde[1].address" should be equal to "Ældstevej 3"
#    Then the JSON node "grunde[2].address" should be equal to "Østervej 5"
#    Then the JSON node "grunde[3].address" should be equal to "Åvej 7"
#
#  Scenario: GeoJSON is returned correctly
#    When I send a "GET" request to "public/api/udstykning/1/grunde/geojson"
#    Then the response status code should be 200
#    Then the response should be in JSON
#    Then the JSON node "type" should be equal to "FeatureCollection"
#    Then the JSON node "crs.type" should be equal to "link"
#    Then the JSON node "crs.properties.href" should be equal to "http://spatialreference.org/ref/epsg/25832/proj4/"
#    Then the JSON node "crs.properties.type" should be equal to "proj4"
#    Then the JSON node "features[0].geometry.type" should be equal to "Polygon"
#    Then the JSON node "features[0].geometry.coordinates[0][0][0]" should be equal to "577343.19117235,6231919.4808124"

  @dropSchema
  Scenario: Drop schema
