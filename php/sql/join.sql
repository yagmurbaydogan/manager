-- find the email of all employees that work on a specific application version
SELECT email
FROM Alias a, Staff s, WorkOn w
WHERE s.staff_alias =  a.alias
AND w.employee_id = s.employee_id
AND w.app_name = (:bind)
AND w.app_ver = (:bind);