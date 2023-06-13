# SCR4 PV-Assistance Project, Jack Heseltine

![image](https://github.com/heseltime/php-myseql-pv-assistance/assets/66922223/ae0379f5-cce3-43df-af7d-840b12b29282)

Based on php-mysql-bookstore-dev-8 sample project, FHOOe/Hagenberg SCR4 2023.

### Test-Login (admin)

#### user:
#### password:

### DDEV Instructions

```
ddev import-db --src=db/pv-assistance.sql
ddev describe
ddev start
ddev stop
```

### URLs

#### home: https://pv-assistance.ddev.site/

#### phpmyadmin: https://pv-assistance.ddev.site:8037/

## Idea/Project Description and Architecture

### Idea

The core architectural idea is a no-credentials log in for the application part, so it is accessible, and a protected area for Sachbearbeiter. The "Sachbearbeiter" (admin) can log in with their credentials and have access to the protected area. The admin can then create, read, update and delete data from the database.

![image](https://github.com/heseltime/php-myseql-pv-assistance/assets/66922223/c25edf32-95a6-47ed-8325-3312e5df2b67)

The main application-UI is the form for submission, for admins it is a simple UI allowing status updates and data manipulation (accepted or rejected, optionally notes). There is also a token-protected area for getting the latest version of the application, for the applicant.

### Architecture

The major component is an application form submitted to the Controller (POST-request) to handle input checks server-side, as specified, and either display feedback or a submission confirmation for the application. The DataManager handles writing to the database when all input checks have passed (createUser(), createApplication()): the central element is construction php objects from the query results, and vice versa. These can then also be accessed in the Controller.

Input-Error Case:

![image](https://github.com/heseltime/php-myseql-pv-assistance/assets/66922223/e8412ec6-b233-49ab-a9ee-57df7386e1d0)

Success-Case:

![image](https://github.com/heseltime/php-myseql-pv-assistance/assets/66922223/e9210ab7-dd22-4541-92ca-1a36b6086483)

Everything is also tracked per user and application, especially, including IP address:

![image](https://github.com/heseltime/php-myseql-pv-assistance/assets/66922223/7a66cbd6-9f87-44c0-8f34-7299339f0c14)

Admin log-in:

...

Admin processing:

...

Application display once processed (with result):

(Approved case:)

...

(Rejected case:)

...

## DB-Structure (UML)

## DB-Structure (SQL)

## Test Cases

Some major tests were already covered in the 
