SELECT AVG(usage) AS " Average Network Usage", cluster_id "Cluster ID"
FROM (SELECT sum(m.network_used) as usage, m.cluster_id as cluster_id
      FROM Machine m
      GROUP BY m.cluster_id)
GROUP BY cluster_id;

