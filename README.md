# Project2023 Group 12

## Run Code Step
1. Install Docker Dasktop
    - Link : [Docker Docs - Get Docker](https://docs.docker.com/get-docker/)
    - Install Docker Desktop which includes both Docker Engine and Docker Compose
2. use `docker-compose.yml` to build up images and container
    - [docker compose Docs](https://docs.docker.com/compose/)
    - Database data will auto insert when build up images

## Index Link
- Admin Panel : http://localhost/web/admin/
- Teacher Panel : http://localhost/web/teacher/
- Student Panel : http://localhost/web/student/

## Database Account
- phpMyAdmin: http://localhost:8080/
- DB user : `root`
- DB pwd : `root`
- DB Name : `seproject`

## Grade

| Required Score  | Grade        |
| --------------- | ------------ |
| 100             | A-           |
| > 92.5          | A-           |
| > 82.5          | B+           |
| > 75            | B            |
| > 67.5          | B-           |
| > 57.5          | C+           |
| > 50            | C            |
| > 45            | Fail-Resit   |
| >= 0            | Fail         |


## Test Account
#### Admin
| Login ID        | Password     |
| --------------- | ------------ |
| admin1          | test123      |
| admin2          | test123      |
| admin3          | test123      |

---

#### Admin
| Login ID        | Password     |
| --------------- | ------------ |
| teacher1        | test123      |
| teacher2        | test123      |
| teacher3        | test123      |
| teacher4        | test123      |
| teacher5        | test123      |
| teacher6        | test123      |
| teacher7        | test123      |
| teacher8        | test123      |
| teacher9        | test123      |
| teacher10       | test123      |

---

#### Admin
| Login ID        | Password     |
| --------------- | ------------ |
| s0000001        | test123      |
| s0000002        | test123      |
| s0000003        | test123      |
| s0000004        | test123      |
| s0000005        | test123      |
| s0000006        | test123      |
| s0000007        | test123      |
| s0000008        | test123      |
| s0000009        | test123      |
| s0000010        | test123      |
| s0000011        | test123      |
| s0000012        | test123      |
| s0000013        | test123      |
| s0000014        | test123      |
| s0000015        | test123      |
| s0000016        | test123      |
| s0000017        | test123      |
| s0000018        | test123      |
| s0000019        | test123      |
| s0000020        | test123      |
| s0000021        | test123      |
| s0000022        | test123      |
| s0000023        | test123      |
| s0000024        | test123      |
| s0000025        | test123      |
| s0000026        | test123      |
| s0000027        | test123      |
| s0000028        | test123      |
| s0000029        | test123      |
| s0000030        | test123      |

## Genernal Function

### Profile

View own information

---

### Change Password

Change own password

1. Enter `Current Password`
2. Enter `New password` and type `to Conform Password` field again
3. Click `Submit` Button

## Admin Function

### Program

- Manage Programs

---

### Course

- Manage Courses

---

### Admin

- Manage Admin account

- `Login ID` is Unique

---

### Teacher

- Manage Teacher account

- `Login ID` is Unique

- Teacher can view student score when course and teacher under same program

- Teacher can edit student score when course assigned to teacher

---

### Student

- Manage Student account

- `Student ID` is Unique, Follow format `s0000000`

- After assigned course, admin must be enter `Study Year` and `Semester` inside `Edit Course` page under `student record` by click `Course` button on student record row.

- When enter `Study Year` and `Semester`, Student will appear to `Teacher Panel - Course` function and allow teacher to insert score.

---

### Timetable

Manage Courses Timetable

- When timetable record was added, record will show into `Student Panel - Timetable`

## Teacher Function

### Course

- Permission : 
    - `Read` : `Course` and `Teacher` under same `Program`
    - `Edit` and `Read` : `Teacher` assigned `course`

- When select course, user can see Course Detail.

---

#### Course Detail
- Click `View Score` button, user can view student score filter by `Study Year`

#### Student Course
- Filter
    - User can select `Study Year` to filter student
- View Chart
    - Show filtered score `statistic chart`
- Download CSV
    - Output filtered student score and information as `CSV` format
- Upload CSV
    - Input student score use `CSV` format filter
    - User can download templeate file inside upload page
    - Grade will auto generate when user submit
- Edit Course Score
    - User can update student score one by one
    - Grade will auto generate when user submit

## Student Function

### Timetable
- Timetable record was filtered by `assigned course`, `Study Year` and 
`Semester`

---

### Course
- Show student score, grade and course information here

---

### Academic Statistic
- Show student `CGPA`, `GPA of each year`, `statistic chart`
- Statistic chart provided `Study Year` filter

---

### Transcript
- Output student informationm, assigned programs, academic record and GPA as `PDF` format