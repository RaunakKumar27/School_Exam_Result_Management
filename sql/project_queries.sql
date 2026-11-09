
-- SCHOOL EXAMINATION RESULTS MANAGEMENT SYSTEM
-- SQL QUERY COLLECTION

USE school_exam_db;


-- QUERY 1: Display all students
SELECT *
FROM Students;


-- QUERY 2: Display all subjects
SELECT *
FROM Subjects;


-- QUERY 3: Display all examinations
SELECT *
FROM Exams;


-- QUERY 4: Find students belonging to Class 10A
SELECT *
FROM Students
WHERE class_name = '10A';


-- QUERY 5: Find the highest and lowest marks
SELECT
    MAX(marks_obtained) AS highest_marks,
    MIN(marks_obtained) AS lowest_marks
FROM Marks;


-- QUERY 6: Display student-wise marks (JOIN)
SELECT
    s.student_name,
    s.class_name,
    sub.subject_name,
    m.marks_obtained
FROM Marks m
INNER JOIN Students s
    ON m.student_id = s.student_id
INNER JOIN Subjects sub
    ON m.subject_id = sub.subject_id;


-- QUERY 7: Display examination-wise student marks (JOIN)
SELECT
    s.student_name,
    e.exam_name,
    sub.subject_name,
    m.marks_obtained
FROM Marks m
INNER JOIN Students s
    ON m.student_id = s.student_id
INNER JOIN Exams e
    ON m.exam_id = e.exam_id
INNER JOIN Subjects sub
    ON m.subject_id = sub.subject_id;


-- QUERY 8: Calculate total marks obtained by each student
SELECT
    s.student_name,
    e.exam_name,
    SUM(m.marks_obtained) AS total_marks
FROM Marks m
INNER JOIN Students s
    ON m.student_id = s.student_id
INNER JOIN Exams e
    ON m.exam_id = e.exam_id
GROUP BY
    s.student_id,
    s.student_name,
    e.exam_id,
    e.exam_name;


-- QUERY 9: Calculate student percentage
SELECT
    s.student_name,
    e.exam_name,
    SUM(m.marks_obtained) AS obtained_marks,
    SUM(sub.max_marks) AS maximum_marks,
    ROUND(
        SUM(m.marks_obtained) /
        NULLIF(SUM(sub.max_marks), 0) * 100,
        2
    ) AS percentage
FROM Marks m
INNER JOIN Students s
    ON m.student_id = s.student_id
INNER JOIN Subjects sub
    ON m.subject_id = sub.subject_id
INNER JOIN Exams e
    ON m.exam_id = e.exam_id
GROUP BY
    s.student_id,
    s.student_name,
    e.exam_id,
    e.exam_name;


-- QUERY 10: Count students in each class
SELECT
    class_name,
    COUNT(*) AS total_students
FROM Students
GROUP BY class_name;
