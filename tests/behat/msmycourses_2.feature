@javascript @msag_plugin @overlook @msmycourses_2

Feature: Testing the msmycourses block. Testing "Filter configuration"

#  php vendor\bin\behat --profile=geckodriver --format pretty --config C:\web\xampp\behatdata\behatrun\behat\behat.yml --tags=@msmycourses_2
#  php admin\tool\behat\cli\init.php --rerun

  Background:
    Given the following "users" exist:
      | username | email              | idnumber | groups |
      | student1 | student1@test.test | S1       | a      |
      | student2 | student2@test.test | S1       | a      |
      | teacher1 | teacher@test.test  | T1       | b      |
    And the following "categories" exist:
      | name        | category    | idnumber |
      | Category A  | 0           | 1cat     |
      | Category B  | 0           | 2cat     |
      | Category C  | 0           | 3cat     |
    And the following "courses" exist:
      | fullname  | shortname | category    | summary    | enablecompletion | showcompletionconditions |  groupmode |
      | course1   | c1        | 1cat        | Tcourse 1  |              1   |                      1   |        1   |
      | course2   | c2        | 1cat        | Tcourse 2  |              1   |                      1   |        1   |
      | course3   | c3        | 1cat        | Tcourse 3  |              1   |                      1   |        1   |
      | course4   | c4        | 2cat        | Tcourse 4  |              1   |                      1   |        1   |
      | course5   | c5        | 2cat        | Tcourse 5  |              1   |                      1   |        1   |
      | course6   | c6        | 2cat        | Tcourse 6  |              1   |                      1   |        1   |
      | course7   | c7        | 3cat        | Tcourse 7  |              1   |                      1   |        1   |
      | course8   | c8        | 3cat        | Tcourse 8  |              1   |                      1   |        1   |
      | course9   | c9        | 3cat        | Tcourse 9  |              1   |                      1   |        1   |
      | coursea   | c10       | 3cat        | Tcourse 10 |              0   |                      0   |        1   |
      | courseb   | c11       | 3cat        | Tcourse 11 |              1   |                      1   |        1   |
    And the following "course enrolments" exist:
      | user     | course  | role    |
      | student1 | c1      | student |
      | student1 | c2      | student |
      | student1 | c3      | student |
      | student1 | c4      | student |
      | student1 | c5      | student |
      | student1 | c6      | student |
      | student1 | c7      | student |
      | student1 | c8      | student |
      | student1 | c9      | student |
      | student1 | c10     | student |

      | student2 | c1      | student |
      | student2 | c2      | student |
      | student2 | c3      | student |
      | student2 | c4      | student |
      | student2 | c5      | student |
      | student2 | c6      | student |
      | student2 | c7      | student |
      | student2 | c8      | student |
      | student2 | c9      | student |
      | student1 | c10     | student |

      | teacher1 | c1      | editingteacher |
      | teacher1 | c2      | editingteacher |
      | teacher1 | c3      | editingteacher |
      | teacher1 | c4      | editingteacher |
      | teacher1 | c5      | editingteacher |
      | teacher1 | c6      | editingteacher |
      | teacher1 | c7      | editingteacher |
      | teacher1 | c8      | editingteacher |
      | teacher1 | c9      | editingteacher |
      | teacher1 | c10     | editingteacher |
      | teacher1 | c11     | editingteacher |

    And the following "activities" exist:
      | activity | name      | idnumber | course  | completion | completionview |
      | assign   | activity1 | act1     | c1      | 1          |     1          |
      | assign   | activity2 | act2     | c1      | 1          |     1          |

      | assign   | activity1 | act3     | c2      | 1          |     1          |
      | assign   | activity2 | act4     | c2      | 1          |     1          |

      | assign   | activity1 | act5     | c3      | 1          |     1          |
      | assign   | activity2 | act6     | c3      | 1          |     1          |

      | assign   | activity1 | act7     | c4      | 1          |     1          |
      | assign   | activity2 | act8     | c4      | 1          |     1          |

      | assign   | activity1 | act9     | c5      | 1          |     1          |
      | assign   | activity2 | act10    | c5      | 1          |     1          |

      | assign   | activity1 | act11    | c6      | 1          |     1          |
      | assign   | activity2 | act12    | c6      | 1          |     1          |

      | assign   | activity1 | act13    | c7      | 1          |     1          |
      | assign   | activity2 | act14    | c7      | 1          |     1          |

      | assign   | activity1 | act15    | c8      | 1          |     1          |
      | assign   | activity2 | act16    | c8      | 1          |     1          |

      | assign   | activity1 | act17    | c9      | 1          |     1          |
      | assign   | activity2 | act18    | c9      | 1          |     1          |

      | assign   | activity1 | act19    | c10     | 0          |     0          |
      | assign   | activity2 | act20    | c10     | 0          |     0          |

      | assign   | activity1 | act21    | c11     | 0          |     0          |
      | assign   | activity2 | act22    | c11     | 0          |     0          |

    And I log in as "admin"
    And I am on "course1" course homepage
    And I navigate to "Course completion" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Assignment - activity1 | 1 |
      | Assignment - activity2 | 1 |
    And I click on "Save changes" "button"

    And I am on "course2" course homepage
    And I navigate to "Course completion" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Assignment - activity1 | 1 |
      | Assignment - activity2 | 1 |
    And I click on "Save changes" "button"

    And I am on "course3" course homepage
    And I navigate to "Course completion" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Assignment - activity1 | 1 |
      | Assignment - activity2 | 1 |
    And I click on "Save changes" "button"

    And I am on "course4" course homepage
    And I navigate to "Course completion" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Assignment - activity1 | 1 |
      | Assignment - activity2 | 1 |
    And I click on "Save changes" "button"

    And I am on "course5" course homepage
    And I navigate to "Course completion" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Assignment - activity1 | 1 |
      | Assignment - activity2 | 1 |
    And I click on "Save changes" "button"

    And I am on "course6" course homepage
    And I navigate to "Course completion" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Assignment - activity1 | 1 |
      | Assignment - activity2 | 1 |
    And I click on "Save changes" "button"

    And I am on "course7" course homepage
    And I navigate to "Course completion" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Assignment - activity1 | 1 |
      | Assignment - activity2 | 1 |
    And I click on "Save changes" "button"

    And I am on "course8" course homepage
    And I navigate to "Course completion" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Assignment - activity1 | 1 |
      | Assignment - activity2 | 1 |
    And I click on "Save changes" "button"

    And I am on "course9" course homepage
    And I navigate to "Course completion" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Assignment - activity1 | 1 |
      | Assignment - activity2 | 1 |
    And I click on "Save changes" "button"


#  Scenario: Completion filter. Hide courses without configured activity completion.
#
#    Given I log in as "student1"
#
#    When I am on "course1" course homepage
#    And I click on "Mark as done" "button" in the "activity1" "activity"
#    And I am on "course3" course homepage
#    And I click on "Mark as done" "button" in the "activity1" "activity"
#    And I click on "Mark as done" "button" in the "activity2" "activity"
#    And I am on "course4" course homepage
#    And I click on "Mark as done" "button" in the "activity1" "activity"
#    And I am on "course6" course homepage
#    And I click on "Mark as done" "button" in the "activity1" "activity"
#    And I click on "Mark as done" "button" in the "activity2" "activity"
#    And I am on "course7" course homepage
#    And I click on "Mark as done" "button" in the "activity1" "activity"
#    And I am on "course9" course homepage
#    And I click on "Mark as done" "button" in the "activity1" "activity"
#    And I click on "Mark as done" "button" in the "activity2" "activity"
#
#    And I follow "Dashboard"
#    And I turn editing mode on
#    And I add the "msmycourses" block
#    And I configure the "Courses" block
#    And I set the following fields to these values:
#      | Title                    | Courses               |
#      | Display                  | tiles                 |
#      | tiles per row            | 3                     |
#      | Number of shown elements | 11                    |
#      | Completion Filter        | all courses           |
#      | Region                   | content               |
#    And I press "Save changes"
#    And I configure the "Timeline" block
#    And I set the following fields to these values:
#      | Visible | No  |
#    And I press "Save changes"
#    And I configure the "Calendar" block
#    And I set the following fields to these values:
#      | Visible | No  |
#    And I press "Save changes"
#
#    And I configure the "Courses" block
#    And I set the following fields to these values:
#      | Completion Filter | uncompleted courses only |
#    And I press "Save changes"
#    Then I should see "course1" in the "Courses" "block"
#    And I should see "course2" in the "Courses" "block"
#    And I should see "course4" in the "Courses" "block"
#    And I should see "course5" in the "Courses" "block"
#    And I should see "course7" in the "Courses" "block"
#    And I should see "course8" in the "Courses" "block"
#    And I should see "coursea" in the "Courses" "block"
#    And I should not see "course3" in the "Courses" "block"
#    And I should not see "course6" in the "Courses" "block"
#    And I should not see "course9" in the "Courses" "block"
#    And I should not see "courseb" in the "Courses" "block"
#
#    And I configure the "Courses" block
#    And I expand all fieldsets
#    And I set the following fields to these values:
#      | hide courses without configured activity completion | Yes |
#    And I press "Save changes"
#    Then I should see "course1" in the "Courses" "block"
#    And I should see "course2" in the "Courses" "block"
#    And I should see "course4" in the "Courses" "block"
#    And I should see "course5" in the "Courses" "block"
#    And I should see "course7" in the "Courses" "block"
#    And I should see "course8" in the "Courses" "block"
#    And I should not see "course3" in the "Courses" "block"
#    And I should not see "course6" in the "Courses" "block"
#    And I should not see "course9" in the "Courses" "block"
#    And I should not see "coursea" in the "Courses" "block"
#    And I should not see "courseb" in the "Courses" "block"
#
#    And I configure the "Courses" block
#    And I set the following fields to these values:
#      | Completion Filter | uncompleted courses without activity progress |
#      | hide courses without configured activity completion | No |
#    And I press "Save changes"
#    Then I should see "course2" in the "Courses" "block"
#    And I should see "course5" in the "Courses" "block"
#    And I should see "course8" in the "Courses" "block"
#    And I should see "coursea" in the "Courses" "block"
#    And I should not see "course1" in the "Courses" "block"
#    And I should not see "course3" in the "Courses" "block"
#    And I should not see "course4" in the "Courses" "block"
#    And I should not see "course6" in the "Courses" "block"
#    And I should not see "course7" in the "Courses" "block"
#    And I should not see "course9" in the "Courses" "block"
#    And I should not see "courseb" in the "Courses" "block"
#
#    And I configure the "Courses" block
#    And I set the following fields to these values:
#      | Completion Filter | uncompleted courses with activity progress |
#    And I press "Save changes"
#    Then I should see "course1" in the "Courses" "block"
#    And I should see "course4" in the "Courses" "block"
#    And I should see "course7" in the "Courses" "block"
#    And I should not see "course2" in the "Courses" "block"
#    And I should not see "course3" in the "Courses" "block"
#    And I should not see "course5" in the "Courses" "block"
#    And I should not see "course6" in the "Courses" "block"
#    And I should not see "course8" in the "Courses" "block"
#    And I should not see "course9" in the "Courses" "block"
#    And I should not see "coursea" in the "Courses" "block"
#    And I should not see "courseb" in the "Courses" "block"
#
#    And I configure the "Courses" block
#    And I set the following fields to these values:
#      | Completion Filter | completed courses only |
#    And I press "Save changes"
#    And pause
#    Then I should see "course3" in the "Courses" "block"
#    And I should see "course6" in the "Courses" "block"
#    And I should see "course9" in the "Courses" "block"
#    And I should not see "course1" in the "Courses" "block"
#    And I should not see "course2" in the "Courses" "block"
#    And I should not see "course4" in the "Courses" "block"
#    And I should not see "course5" in the "Courses" "block"
#    And I should not see "course7" in the "Courses" "block"
#    And I should not see "course8" in the "Courses" "block"
#    And I should not see "coursea" in the "Courses" "block"
#    And I should not see "courseb" in the "Courses" "block"
#
#    And I configure the "Courses" block
#    And I set the following fields to these values:
#      | Completion Filter      | all courses |
#      | Categories to exclude  | Category B  |
#    And I press "Save changes"
#    And pause
#    Then I should see "course1" in the "Courses" "block"
#    And I should see "course2" in the "Courses" "block"
#    And I should see "course3" in the "Courses" "block"
#    And I should see "course7" in the "Courses" "block"
#    And I should see "course8" in the "Courses" "block"
#    And I should see "course9" in the "Courses" "block"
#    And I should see "coursea" in the "Courses" "block"
#    And I should not see "course4" in the "Courses" "block"
#    And I should not see "course5" in the "Courses" "block"
#    And I should not see "course6" in the "Courses" "block"
#    And I should not see "courseb" in the "Courses" "block"

  Scenario: Courserole.

    Given I log in as "student1"

    When I am on "course1" course homepage
    And I click on "Mark as done" "button" in the "activity1" "activity"
    And I am on "course3" course homepage
    And I click on "Mark as done" "button" in the "activity1" "activity"
    And I click on "Mark as done" "button" in the "activity2" "activity"
    And I am on "course4" course homepage
    And I click on "Mark as done" "button" in the "activity1" "activity"
    And I am on "course6" course homepage
    And I click on "Mark as done" "button" in the "activity1" "activity"
    And I click on "Mark as done" "button" in the "activity2" "activity"
    And I am on "course7" course homepage
    And I click on "Mark as done" "button" in the "activity1" "activity"
    And I am on "course9" course homepage
    And I click on "Mark as done" "button" in the "activity1" "activity"
    And I click on "Mark as done" "button" in the "activity2" "activity"

    And I follow "Dashboard"
    And I turn editing mode on
    And I add the "msmycourses" block
    And I configure the "Courses" block
    And I set the following fields to these values:
      | Title                    | Courses               |
      | Display                  | tiles                 |
      | tiles per row            | 3                     |
      | Number of shown elements | 11                    |
      | Completion Filter        | all courses           |
      | Region                   | content               |
    And I press "Save changes"
    And I configure the "Timeline" block
    And I set the following fields to these values:
      | Visible                   | No  |
    And I press "Save changes"
    And I configure the "Calendar" block
    And I set the following fields to these values:
      | Visible                   | No  |
    And I press "Save changes"
    And I configure the "Courses" block
    And I set the following fields to these values:
      | Courserole  | Teacher |
    And I press "Save changes"
    Then I should not see "course 1" in the "Courses" "block"
    And I should not see "course 2" in the "Courses" "block"
    And I should not see "course 3" in the "Courses" "block"
    And I should not see "course 4" in the "Courses" "block"
    And I should not see "course 5" in the "Courses" "block"
    And I should not see "course 6" in the "Courses" "block"
    And I should not see "course 7" in the "Courses" "block"
    And I should not see "course 8" in the "Courses" "block"
    And I should not see "course 9" in the "Courses" "block"
    And I should not see "course 10" in the "Courses" "block"
    And I should not see "course 11" in the "Courses" "block"

    And I configure the "Courses" block
    And I set the following fields to these values:
      | Courserole  | Student |
    And I press "Save changes"
    Then I should see "course 1" in the "Courses" "block"
    And I should see "course 2" in the "Courses" "block"
    And I should see "course 3" in the "Courses" "block"
    And I should see "course 4" in the "Courses" "block"
    And I should see "course 5" in the "Courses" "block"
    And I should see "course 6" in the "Courses" "block"
    And I should see "course 7" in the "Courses" "block"
    And I should see "course 8" in the "Courses" "block"
    And I should see "course 9" in the "Courses" "block"
    And I should see "course 10" in the "Courses" "block"
    And I should not see "course 11" in the "Courses" "block"

    And I log in as "teacher1"
    And I follow "Dashboard"
    And I turn editing mode on
    And I add the "msmycourses" block
    And I configure the "Courses" block
    And I set the following fields to these values:
      | Title                    | Courses               |
      | Display                  | tiles                 |
      | tiles per row            | 3                     |
      | Number of shown elements | 11                    |
      | Completion Filter        | all courses           |
      | Region                   | content               |
    And I press "Save changes"
    And I configure the "Timeline" block
    And I set the following fields to these values:
      | Visible                   | No  |
    And I press "Save changes"
    And I configure the "Calendar" block
    And I set the following fields to these values:
      | Visible                   | No  |
    And I press "Save changes"

    Then I should see "course 1" in the "Courses" "block"
    And I should see "course 2" in the "Courses" "block"
    And I should see "course 3" in the "Courses" "block"

    And I configure the "Courses" block
    And I set the following fields to these values:
      | Courserole  | Student |
    And I press "Save changes"
    Then I should not see "course 1" in the "Courses" "block"
    And I should not see "course 2" in the "Courses" "block"
    And I should not see "course 3" in the "Courses" "block"
    And I should not see "course 4" in the "Courses" "block"
    And I should not see "course 5" in the "Courses" "block"
    And I should not see "course 6" in the "Courses" "block"
    And I should not see "course 7" in the "Courses" "block"
    And I should not see "course 8" in the "Courses" "block"
    And I should not see "course 9" in the "Courses" "block"
    And I should not see "course 10" in the "Courses" "block"
    And I should not see "course 11" in the "Courses" "block"

    And I configure the "Courses" block
    And I set the following fields to these values:
      | Courserole  | Teacher |
    And I press "Save changes"
    Then I should see "course 1" in the "Courses" "block"
    And I should see "course 2" in the "Courses" "block"
    And I should see "course 3" in the "Courses" "block"
    And I should see "course 4" in the "Courses" "block"
    And I should see "course 5" in the "Courses" "block"
    And I should see "course 6" in the "Courses" "block"
    And I should see "course 7" in the "Courses" "block"
    And I should see "course 8" in the "Courses" "block"
    And I should see "course 9" in the "Courses" "block"
    And I should see "course 10" in the "Courses" "block"
    And I should see "course 11" in the "Courses" "block"
#
##    And I set the following fields to these values:
##      | Course Group Filter   | courses without group membership |
#
##    And I am on "course1" course homepage
##    And pause
##
##    And I log in as "student2"
##    And I am on "course1" course homepage
##    And pause
