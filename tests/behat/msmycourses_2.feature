@javascript @msag_plugin @overlook @mycourse_2

Feature: Testing the mycourse block. Testing "Basic configuration"

# php vendor\bin\behat --profile=geckodriver --format pretty --config C:\web\xampp\behatdata\behatrun\behat\behat.yml --tags=@msmycourses_2

  Background:
    Given the following "users" exist:
      | username | email              |
      | student1 | student1@test.test |
      | student2 | student2@test.test |
      | teacher1 | teacher@test.test  |
    And the following "categories" exist:
      | name        | category    | idnumber |
      | Category A  | 0           | 1cat     |
      | Category B  | 0           | 2cat     |
    And the following "courses" exist:
      | fullname  | shortname | category    | summary        |
      | course1   | c1        | 1cat        | Test course 1  |
      | course2   | c2        | 2cat        | Test course 2  |
      | course3   | c3        | 1cat        | Test course 3  |
      | course4   | c4        | 2cat        | Test course 4  |
    And the following "course enrolments" exist:
      | user     | course  | role    |
      | student1 | c1 | student |
      | student1 | c2 | student |
      | student1 | c3 | student |
      | student1 | c4 | student |
      | student2 | c1 | student |
      | student2 | c2 | student |
      | student2 | c3 | student |
      | student2 | c4 | student |
      | teacher1 | c1 | editingteacher |
      | teacher1 | c2 | editingteacher |
      | teacher1 | c3 | editingteacher |
      | teacher1 | c4 | editingteacher |
    And the following "activities" exist:
      | activity | name      | course  |
      | assign   | activity1 | c1 |
      | book     | activity2 | c1 |
      | resource | activity1 | c2 |
      | scorm    | activity2 | c2 |
      | assign   | activity1 | c3 |
      | book     | activity2 | c3 |
      | resource | activity3 | c3 |
      | assign   | activity1 | c4 |
      | book     | activity2 | c4 |
      | resource | activity3 | c4 |

  Scenario: Set Title and Course Title. Disable summary. Display.
    Given I log in as "teacher1"
    And I am on "course1" course homepage
    And I turn editing mode on
    And I add the "msmycourses" block
    And I configure the "Courses" block
    And pause
#    And I set the following fields to these values:
#      | Title               | Courses and courses        |
#      | Course Title        | full and short course name |
#      | Show course summary | No                         |
#      | Display             | list                       |
#    And I press "Save changes"
#    Then "Courses and courses" "block" should exist
#    And "Courses" "block" should not exist
#    And I should see "course3 c3" in the "Courses and courses" "block"
#    And I should see "course1 c1" in the "Courses and courses" "block"
#    And I should not see "Test course 3" in the "Courses and courses" "block"
#    And I should not see "Test course 1" in the "Courses and courses" "block"
#
#    And ".list" "css_element" should be visible
#    And ".tiles" "css_element" should not be visible
#    And ".items.menu.tiles" "css_element" should not be visible
#
#    Then I configure the "Courses and courses" block
#    And I set the following fields to these values:
#      | Course Title        | short course name |
#      | Display             | tiles              |
#    And I press "Save changes"
#    And I should see "c3" in the "Courses and courses" "block"
#    And I should not see "course 3" in the "Courses and courses" "block"
#    And I should not see "course 1" in the "Courses and courses" "block"
#
#    And ".list" "css_element" should not be visible
#    And ".tiles" "css_element" should be visible
#    And ".items.menu.tiles" "css_element" should not be visible
#
#    Then I configure the "Courses and courses" block
#    And I set the following fields to these values:
#      | Course Title        | Hide  |
#    And I press "Save changes"
#    And I should not see "c3" in the "Courses and courses" "block"
#    And I should not see "c1" in the "Courses and courses" "block"
#    And I should not see "course 3" in the "Courses and courses" "block"
#    And I should not see "course 1" in the "Courses and courses" "block"
#
#    Then I configure the "Courses and courses" block
#    And I set the following fields to these values:
#      | Course Title        | full and short course name  |
#      | Display             | Menu                        |
#    And I press "Save changes"
#    And I should not see "course3 c3" in the "Courses and courses" "block"
#    And I should not see "course1 c1" in the "Courses and courses" "block"
#    And I should see "Category A" in the "Courses and courses" "block"
#    And I should see "Category B" in the "Courses and courses" "block"
#
#    And ".items.menu.tiles" "css_element" should be visible
#    And ".list" "css_element" should not be visible
#    And ".tiles" "css_element" should be visible
#    And I click on "Category A" "text"
#    And I should see "course3 c3" in the "Courses and courses" "block"
#    And I should see "course1 c1" in the "Courses and courses" "block"
#    And I click on ".item.category.back_button" "css_element"
#    Then I should see "Category A" in the "Courses and courses" "block"
#
#    And I log in as "student1"
#    And I am on "course1" course homepage
#    And "Courses and courses" "block" should exist
#    And I should see "Category A" in the "Courses and courses" "block"
#    And I should see "Category B" in the "Courses and courses" "block"
#
#  Scenario: Display tiles. Tiles per row.
#    Given I log in as "teacher1"
#
#    When I am on "course1" course homepage
#    And I turn editing mode on
#    And I add the "msmycourses" block
#    And I should see "Courses" in the "Courses" "block"
#    And I should see "course3" in the "Courses" "block"
#    And I should see "Test course 3" in the "Courses" "block"
#    And I configure the "Courses" block
#    And I set the following fields to these values:
#      | Title               | Courses and courses        |
#      | Course Title        | full and short course name |
#      | Show course summary | No                         |
#      | Display             | tiles                      |
#      | tiles per row       | 2                          |
#    And I press "Save changes"
#    Then ".items.tiles.tiles_per_row2" "css_element" should be visible
#    And ".items.tiles.tiles_per_row1" "css_element" should not be visible
#    And ".items.tiles.tiles_per_row3" "css_element" should not be visible
#
#    And I log in as "student1"
#    And I am on "course1" course homepage
#    And "Courses and courses" "block" should exist
#    Then ".items.tiles.tiles_per_row2" "css_element" should be visible
#    And ".items.tiles.tiles_per_row1" "css_element" should not be visible
#    And ".items.tiles.tiles_per_row3" "css_element" should not be visible



