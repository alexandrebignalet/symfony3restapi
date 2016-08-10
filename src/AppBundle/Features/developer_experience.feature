# src/AppBundle/Features/developer_experience.feature

Feature: To improve the Developer Experience of using this API

  In order to offer an API user a better experience
  As a client software developer
  I need to return useful information when situations may be otherwise confusing


  Background:
    Given there are users with the following details:
      | uid  | username | email          | password |
      | a1   | peter    | peter@test.com | testpass |
    And I am successfully logged in with username: "peter", and password: "testpass"
    And when consuming the endpoint I use the "headers/content-type" of "application/json"


  Scenario: User must use the right Content-type
    When I have forgotten to set the "headers/content-type"
    And I send a "PATCH" request to "/users/a1"
    Then the response code should be 415
    And the response should contain json:
      """
      {
        "code": 415,
        "message": "Invalid or missing Content-type header"
      }
      """