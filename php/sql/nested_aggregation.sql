-- for each cluster, get the machine with the most free space and free bandwidth, break ties by IP
select R1.cluster_id as "Cluster",
       R1.ip as "Most Free Disk IP",
       "Free Disk (GB)",
       R2.ip as "Most Free Bandwidth IP",
       "Free Bandwidth (MB/s)"
from
     (
         select M1.cluster_id, MIN(M1.ip) as ip, (MM1.disk_capacity - M1.disk_used) as "Free Disk (GB)"
         from Machine M1, MachineModel MM1
         where M1.model_number = MM1.model_number
           and MM1.disk_capacity - M1.disk_used =
               (
                   select MAX(MM2.disk_capacity - M2.disk_used) as "Max Free Space (GB)"
                   from Machine M2, MachineModel MM2
                   where M2.model_number = MM2.model_number
                     and M2.cluster_id = M1.cluster_id
               )
         group by M1.cluster_id, (MM1.disk_capacity - M1.disk_used)
         order by M1.cluster_id
    ) R1,

    (
        select M1.cluster_id, MIN(M1.ip) as ip, (MM1.network_capacity - M1.network_used) as "Free Bandwidth (MB/s)"
        from Machine M1, MachineModel MM1
        where M1.model_number = MM1.model_number
          and MM1.network_capacity - M1.network_used =
              (
                  select MAX(MM2.network_capacity - M2.network_used) as "Max Free Bandwidth (MB/s)"
                  from Machine M2, MachineModel MM2
                  where M2.model_number = MM2.model_number
                    and M2.cluster_id = M1.cluster_id
              )
        group by M1.cluster_id, (MM1.network_capacity - M1.network_used)
        order by M1.cluster_id
    ) R2
where R1.cluster_id = R2.cluster_id;



