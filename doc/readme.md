# SCR4 PV-Assistance Project, Jack Heseltine

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

The main application-UI is the form for submission, for admins it is a simple UI allowing status updates and data manipulation (accepted or rejected, optionally notes).

### Architecture

The major component is an application form submitted to the Controller (POST-request) to handle input checks server-side, as specified, and either display feedback or a submission confirmation for the application. The DataManager handles writing to the database when all input checks have passed (createUser(), createApplication()): the central element is construction php objects from the query results, and vice versa. These can then also be accessed in the Controller.

![image](https://github.com/heseltime/php-myseql-pv-assistance/assets/66922223/e8412ec6-b233-49ab-a9ee-57df7386e1d0)



## DB-Structure (UML)

## DB-Structure (SQL)

## Test Cases
