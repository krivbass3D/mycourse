@javascript @msag_plugin @overlook @block_msmycourses2 @block_msmycourses2__basic_configuration
Feature: Checking the metadata fields filter of courses
  As administrator
  Background:
    Given the following config values are set as admin:
      | enable_tracking_courses | 1 | local_mscore |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
#      | Course 2 | C2        | 0        |
#      | Course 3 | C3        | 0        |
      And I log in as "admin"
      And I am on site homepage
      And I turn editing mode on
      And I wait "1" seconds
      And pause
      And I add the "msmycourses" block

  Scenario: I change name the block msmycourses2
    Given I log in as "admin"
      And I am on site homepage
      And I turn editing mode on
      And I wait "1" seconds
    When I configure the "Courses" block
    And I set the field "Title" to "Courses block1"
    And I press "Save changes"
    And I should see "Courses block1"
    And pause