SELECT Count(machine_ip) as "Number of Machines Running", app_name as "App Name", app_ver as "App Version"
FROM Hosting h
GROUP BY app_name, app_ver;