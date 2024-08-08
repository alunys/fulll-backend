Feature: Create a fleet

  Scenario: Successfully create a fleet for a user
    Given A user with id "userid"
    When I create a fleet for this user
    Then I should be able to retrieve the fleet created for user
