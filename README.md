# Academic Management & Performance Analytics System

## Overview

This project is a role-based academic management platform built with Laravel and MySQL.

The system includes:

1. Authentication with role-based access
2. Student management
3. Course management
4. Enrollment management
5. Marks and grading
6. Analytics dashboard with charts

The application starts at the login page.

## Tech Stack

1. Laravel 13
2. PHP 8.4
3. MySQL
4. Blade templates
5. Tailwind CSS
6. Chart.js for visualization
7. Pest for testing

## Core Modules

1. Authentication and Roles
2. Students
3. Courses
4. Enrollments
5. Marks
6. Dashboard Analytics

## Roles and Permissions

### Admin

Admin has full control over the academic modules in this application.

Admin can:

1. View, create, update, and delete student records
2. View, create, update, and delete courses
3. Assign teachers to courses
4. Enroll students in courses
5. Enter, edit, and delete marks
6. View full analytics dashboard data
7. Access all academic modules without restrictions

### Teacher

Teacher has scoped access related to their own courses and student performance.

Teacher can:

1. View students enrolled in their own courses
2. View courses assigned to them
3. Create courses
4. Update their own courses
5. Enter and edit marks for students in their own courses
6. View enrollments tied to their own courses
7. View analytics scoped to their own courses

Teacher cannot:

1. Create, update, or delete student records
2. Delete courses
3. Create, update, or delete enrollments
4. Delete marks
5. Access data outside their own course scope

### Student

Student has personal read-only academic access.

Student can:

1. View own student profile
2. View own enrolled courses
3. View own marks and grades
4. View personal analytics on dashboard

Student cannot:

1. Create, update, or delete students
2. Create, update, or delete courses
3. Create, update, or delete enrollments
4. Create, update, or delete marks
5. View other students data

## Data Model Summary

Main tables:

1. users
2. students
3. courses
4. enrollments
5. marks

Key relationships:

1. A student belongs to one user
2. A teacher is represented by a user and can be assigned to many courses
3. Students and courses are many-to-many through enrollments
4. Marks are stored per student per course

Data integrity rules:

1. Student email is unique in students table
2. Course code is unique
3. One enrollment per student-course pair
4. One marks record per student-course pair
5. Marks are validated between 0 and 100
6. Grade is auto-calculated from marks

## Student Registration Sync Behavior

When a user is saved with role student, a linked record is automatically ensured in students table.

This keeps users and students synchronized and ensures admin can see registered students in the student module.

## Dashboard Analytics

Dashboard includes:

1. Total students
2. Total courses
3. Average marks per course
4. Pass vs fail ratio
5. Top-performing students

Charts:

1. Bar chart for average marks per course
2. Pie chart for pass vs fail
3. Line chart for top students

Dashboard data is role-scoped:

1. Admin sees all data
2. Teacher sees own course data
3. Student sees own academic data

## How to Use the System

For now one can register as either Student, Teacher or even Admin to test out the features.

### Admin Workflow

You can login as admin with: admin101@gmail.com, pw: admin101

1. Login as admin
2. Go to Students and manage records
3. Go to Courses and create or assign teachers
4. Go to Enrollments and map students to courses
5. Go to Marks and record performance
6. Open Dashboard for full analytics and charts

### Teacher Workflow

1. Login as teacher
2. Open Courses to manage own courses
3. Open Marks to add or edit marks for own courses
4. Review Students and Enrollments scoped to own courses
5. Use Dashboard to view teacher-scoped insights

### Student Workflow

1. Login as student
2. Open Students to view own profile
3. Open Courses to view enrolled courses
4. Open Marks to view own grades
5. Open Dashboard to view personal analytics

## Notes

1. Root URL redirects to login
2. Access control is enforced at backend controller level
3. UI actions are also role-aware, but backend checks are the source of truth

## Future Enhancements

1. Dedicated user-management module for admin
2. Search and filters in all data tables
3. Report export to CSV or PDF
4. API endpoints for integration
5. Notifications for important updates
