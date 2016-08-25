Feature: Manage data via the RESTful API

  In order to manage whatever resource via an hypermedia API
  As a super admin
  I need to be able to retrieve, create, update, and delete JSON encoded resources


  Background:
    Given there are Admin Users with the following details:
      | uid | username | email          | password  | roles            |
      | u7  | alex     | alex@test.com  | adminpass | ROLE_ADMIN       |
      | u1  | peter    | peter@test.com | testpass  | ROLE_USER        |
      | u2  | john     | john@test.org  | johnpass  | ROLE_USER        |
    And I am successfully logged in with username: "alex", and password: "adminpass"
    And when consuming the endpoint I use the "headers/content-type" of "application/json"


  Scenario: User can GET a Collection of User objects
    When I send a "GET" request to "/users"
    Then the response code should be 200

  Scenario: User can GET their personal data by their unique ID
    When I send a "GET" request to "/users/u7"
    Then the response code should be 200
    And the response should contain json:
      """
      {
        "id": "u7",
        "email": "alex@test.com",
        "username": "alex"
      }
      """


  Scenario: User can GET a different User's personal data
    When I send a "GET" request to "/users/u2"
    Then the response code should be 200
    And the response should contain json:
      """
      {
        "id": "u2",
        "email": "john@test.org",
        "username": "john"
      }
      """

  Scenario: User can POST to the Users collection
    When I send a "POST" request to "/users" with body:
      """
      {
        "email": "marie@test.org",
        "username": "marie",
        "plainPassword": {
            "first": "mariepass",
            "second": "mariepass"
        }
      }
      """
    Then the response code should be 201

  Scenario: User can PATCH to update their personal data
    When I send a "PATCH" request to "/users/u7" with body:
      """
        {
          "email": "alex@something-else.net",
          "current_password": "adminpass"
        }
      """
    Then the response code should be 204
    And I send a "GET" request to "/users/u7"
    And the response should contain json:
      """
      {
        "id": "u7",
        "email": "alex@something-else.net",
        "username": "alex"
      }
      """

  Scenario: User can PATCH a different User's personal data
    When I send a "PATCH" request to "/users/u1" with body:
      """
      {
        "email": "peter@something-else-new.net",
        "current_password": "adminpass"
      }
      """
    Then the response code should be 204
    And I send a "GET" request to "/users/u1"
    And the response should contain json:
      """
      {
        "id": "u1",
        "email": "peter@something-else-new.net",
        "username": "peter"
      }
      """


  Scenario: User cannot PATCH a none existent User
    When I send a "PATCH" request to "/users/u100"
    Then the response code should be 403


  Scenario: User cannot PUT to replace their personal data
    When I send a "PUT" request to "/users/u7"
    Then the response code should be 405


  Scenario: User cannot PUT a different User's personal data
    When I send a "PUT" request to "/users/u2"
    Then the response code should be 405


  Scenario: User cannot PUT a none existent User
    When I send a "PUT" request to "/users/u100"
    Then the response code should be 405


  Scenario: User can DELETE their personal data
    When I send a "DELETE" request to "/users/u7"
    Then the response code should be 204


  Scenario: User can DELETE a different User's personal data
    When I send a "DELETE" request to "/users/u2"
    Then the response code should be 204
    And I send a "GET" request to "/users/u2"
    Then the response code should be 403


  Scenario: User cannot DELETE a none existent User
    When I send a "DELETE" request to "/users/u100"
    Then the response code should be 403