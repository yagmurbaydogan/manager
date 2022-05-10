SELECT COUNT(m.ip) as "Machines With Available Network and Disk Capacity", m.datacenter_loc as "Location"
FROM Machine m, MachineModel mm
WHERE mm.model_number = m.model_number and m.disk_used < mm.disk_capacity and m.network_used < mm.network_capacity
GROUP BY m.datacenter_loc
HAVING COUNT(*) > 1;