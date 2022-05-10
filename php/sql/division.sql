SELECT m.IP as "Machines That Hosts All of the Applications"
FROM  Machine m
WHERE NOT EXISTS ((SELECT a.app_name, a.APP_VER
                   FROM Application a)
                  MINUS
                  (SELECT h.app_name, h.APP_VER
                   FROM Hosting h
                   WHERE h.MACHINE_IP = m.IP));
