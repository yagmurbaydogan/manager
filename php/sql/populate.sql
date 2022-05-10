INSERT ALL
    INTO Clusters VALUES (1)
    INTO Clusters VALUES (2)
    INTO Clusters VALUES (3)
    INTO Clusters VALUES (4)
    INTO Clusters VALUES (5)
    INTO DataCenter Values ('YVR')
    INTO DataCenter Values ('JFK')
    INTO DataCenter Values ('LAX')
    INTO DataCenter Values ('YYR')
    INTO DataCenter Values ('HKG')
    INTO MachineModel Values ('A1', 512, 512)
    INTO MachineModel Values ('A2', 1024, 1024)
    INTO MachineModel Values ('A3', 2048, 2048)
    INTO MachineModel Values ('A4', 4096, 4096)
    INTO MachineModel Values ('A5', 8192, 8192)
    INTO Machine Values ('55.222.109.180', 'A3', 3, 'LAX', 1200, 1024)
    INTO Machine Values ('55.335.109.152', 'A3', 3, 'LAX', 1206, 1034)
    INTO Machine Values ('175.124.8.152', 'A5', 1, 'YVR', 6712, 5048)
    INTO Machine Values ('84.154.137.152', 'A3', 2, 'YYR', 1200, 684)
    INTO Machine Values ('46.41.181.171', 'A1', 4, 'JFK', 300, 480)
    INTO Machine Values ('79.61.161.191', 'A1', 4, 'JFK', 300, 480)
    INTO Machine Values ('98.61.143.143', 'A5', 4, 'JFK', 6000, 4800)
    INTO Machine Values ('40.49.121.101', 'A1', 4, 'JFK', 512, 420)
    INTO Machine Values ('191.79.121.106', 'A1', 4, 'JFK', 512, 410)
    INTO Machine Values ('109.210.6.46', 'A4', 5, 'YVR', 2304, 2250)
    INTO Alias Values ('bluo', 'bluo@awesome.com')
    INTO Alias Values ('ybaydogan', 'ybaydogan@awesome.com')
    INTO Alias Values ('klei', 'klei@awesome.com')
    INTO Alias Values ('rpottinger', 'rpottinger@awesome.com')
    INTO Alias Values ('jdoe', 'jdoe@awesome.com')
    INTO Staff Values (1, 'bluo', 'Bowen', 'Luo', 'SDE')
    INTO Staff Values (2, 'ybaydogan', 'Yagmur', 'Baydogan', 'SDE')
    INTO Staff Values (3, 'klei', 'Karina', 'Lei', 'Technician')
    INTO Staff Values (4, 'rpottinger', 'Rachel', 'Pottinger', 'SDE')
    INTO Staff Values (5, 'jdoe', 'John', 'Doe', 'Technician')
    INTO Microservice Values (1, 'Blue')
    INTO Microservice Values (2, 'Yellow')
    INTO Microservice Values (3, 'Red')
    INTO Microservice Values (4, 'Green')
    INTO Microservice Values (5, 'Purple')
    INTO Application Values (1, 'HelloWorld')
    INTO Application Values (1, 'FooBar')
    INTO Application Values (2, 'Leetcode')
    INTO Application Values (1, 'League')
    INTO Application Values (2, 'SuperSecret')
    INTO DevTeam Values ('FrontEnd')
    INTO DevTeam Values ('BackEnd')
    INTO DevTeam Values ('Security')
    INTO DevTeam Values ('Research')
    INTO DevTeam Values ('FullStack')
    INTO UseAndWorkOn Values (1, 'HelloWorld', 3, 'BackEnd')
    INTO UseAndWorkOn Values (1, 'FooBar', 3, 'FullStack')
    INTO UseAndWorkOn Values (2, 'Leetcode', 5, 'Research')
    INTO UseAndWorkOn Values (1, 'League', 4, 'FrontEnd')
    INTO UseAndWorkOn Values (2, 'SuperSecret', 2, 'Security')
    INTO Hosting Values ('109.210.6.46', 1, 'HelloWorld', 3)
    INTO Hosting Values ('109.210.6.46', 1, 'FooBar', 3)
    INTO Hosting Values ('109.210.6.46', 2, 'Leetcode', 5)
    INTO Hosting Values ('109.210.6.46', 1, 'League', 4)
    INTO Hosting Values ('109.210.6.46', 2, 'SuperSecret', 2)
    INTO Hosting Values ('46.41.181.171', 2, 'Leetcode', 5)
    INTO Hosting Values ('55.222.109.180', 2, 'SuperSecret', 2)
    INTO Hosting Values ('175.124.8.152', 2, 'SuperSecret', 2)
    INTO Hosting Values ('84.154.137.152', 1, 'League', 4)
    INTO Hosting Values ('84.154.137.152', 1, 'FooBar', 3)
    INTO Hosting Values ('84.154.137.152', 2, 'SuperSecret', 2)
    INTO Hosting Values ('84.154.137.152', 2, 'Leetcode', 5)
    INTO Hosting Values ('84.154.137.152', 1, 'HelloWorld', 3)
    INTO Hosting Values ('55.222.109.180', 1, 'FooBar', 3)
    INTO OnCallGroup Values ('web', 'FrontEnd')
    INTO OnCallGroup Values ('citadel', 'Security')
    INTO OnCallGroup Values ('sql', 'BackEnd')
    INTO OnCallGroup Values ('stack', 'FullStack')
    INTO OnCallGroup Values ('secret', 'Security')
    INTO WorkOn Values (2, 1, 'HelloWorld')
    INTO WorkOn Values (1, 1, 'FooBar')
    INTO WorkOn Values (4, 2, 'Leetcode')
    INTO WorkOn Values (2, 1, 'League')
    INTO WorkOn Values (1, 2, 'SuperSecret')
    INTO WorkOn Values (2, 1, 'FooBar')
    INTO PartOf Values ('web', 'FrontEnd', 5)
    INTO PartOf Values ('citadel', 'Security', 3)
    INTO PartOf Values ('sql', 'BackEnd', 3)
    INTO PartOf Values ('stack', 'FullStack', 2)
    INTO PartOf Values ('secret', 'Security', 1)
    INTO Maintains Values ('YVR', 3)
    INTO Maintains Values ('LAX', 5)
    INTO Maintains Values ('HKG', 3)
    INTO Maintains Values ('JFK', 5)
    INTO Maintains Values ('YYR', 3)
SELECT * FROM dual;
