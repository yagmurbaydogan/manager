CREATE TABLE Clusters(
    id Integer PRIMARY KEY
);

CREATE TABLE DataCenter(
    loc char(10) PRIMARY KEY
);

CREATE TABLE MachineModel(
    model_number char(10) PRIMARY KEY,
    disk_capacity integer,
    network_capacity integer
);

CREATE TABLE Machine(
    ip char(15) PRIMARY KEY,
    model_number char(10),
    cluster_id integer NOT NULL,
    datacenter_loc char(10) NOT NULL,
    disk_used integer,
    network_used integer,
    FOREIGN KEY (model_number) REFERENCES MachineModel(model_number) ON DELETE SET NULL,
    FOREIGN KEY (cluster_id) REFERENCES Clusters(id),
    FOREIGN KEY (datacenter_loc) REFERENCES DataCenter(loc)
);

CREATE TABLE Alias(
    alias char(10) PRIMARY KEY,
    email char(30)
);

CREATE TABLE Staff(
    employee_id integer PRIMARY KEY,
    staff_alias char(10) UNIQUE,
    first_name char(30),
    last_name char(30),
    position char(10),
    FOREIGN KEY (staff_alias) REFERENCES Alias(alias)
);

CREATE TABLE Microservice(
    id integer PRIMARY KEY,
    ms_name char(30)
);

CREATE TABLE Application(
    app_ver integer,
    app_name char(30),
    PRIMARY KEY (app_ver, app_name)
);

CREATE TABLE DevTeam(
    department char(30) PRIMARY KEY
);

CREATE TABLE UseAndWorkOn(
    app_ver integer,
    app_name char(30),
    microservice_id integer,
    department char(30),
    PRIMARY KEY (app_ver, app_name, microservice_id),
    FOREIGN KEY (app_ver, app_name) REFERENCES Application(app_ver, app_name) ON DELETE CASCADE,
    FOREIGN KEY (microservice_id) REFERENCES Microservice(id) ON DELETE CASCADE,
    FOREIGN KEY (department) REFERENCES DevTeam(department) ON DELETE SET NULL
);

CREATE TABLE Hosting(
    machine_ip char(15),
    app_ver integer,
    app_name char(30),
    microservice_id integer,
    PRIMARY KEY (machine_ip, app_ver, app_name, microservice_id),
    FOREIGN KEY (machine_ip) REFERENCES Machine(ip) ON DELETE CASCADE,
    FOREIGN KEY (app_ver, app_name, microservice_id) REFERENCES UseAndWorkOn(app_ver, app_name, microservice_id) ON DELETE CASCADE
);

CREATE TABLE OnCallGroup(
    alias char(10),
    department char(30),
    PRIMARY KEY (alias, department),
    FOREIGN KEY (department) REFERENCES DevTeam(department) ON DELETE CASCADE
);

CREATE TABLE WorkOn(
    employee_id integer,
    app_ver integer,
    app_name char(30),
    PRIMARY KEY (employee_id, app_ver, app_name),
    FOREIGN KEY (employee_id) REFERENCES Staff(employee_id) ON DELETE CASCADE,
    FOREIGN KEY (app_ver, app_name) REFERENCES Application(app_ver, app_name) ON DELETE CASCADE
);

CREATE TABLE PartOf(
    oncall_alias char(10),
    oncall_dept char(30),
    employee_id integer,
    PRIMARY KEY (oncall_alias, oncall_dept, employee_id),
    FOREIGN KEY (oncall_alias, oncall_dept) REFERENCES OnCallGroup(alias, department) ON DELETE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES Staff(employee_id) ON DELETE CASCADE
);

CREATE TABLE Maintains(
    datacenter_loc char(10),
    employee_id integer,
    PRIMARY KEY (datacenter_loc, employee_id),
    FOREIGN KEY (datacenter_loc) REFERENCES DataCenter(loc) ON DELETE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES Staff(employee_id) ON DELETE CASCADE
);
