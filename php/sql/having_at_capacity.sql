SELECT COUNT(m.ip) as "Machines Running at Disk Capacity", m.datacenter_loc as "Location"
FROM Machine m, MachineModel mm
WHERE mm.model_number = m.model_number and m.disk_used = mm.disk_capacity
GROUP BY m.datacenter_loc
HAVING COUNT(*) >=  1;
