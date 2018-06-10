drop table xpo_delivery_details;
create table xpo_delivery_details (
 xpo_id int(11) primary key AUTO_INCREMENT,
 Phone_No varchar(100) not null,
 Customer_Name varchar(255) not null,
 Order_No varchar(250) not null,
 schd_date datetime not null,
 rescheduled_date varchar(255),
 comments varchar(255),
 created_date datetime,
 last_update datetime,
 u_ts timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
 unique key(order_no)
);

create table xpo_chat_discussion(
 xpo_id int(11) primary key AUTO_INCREMENT,
 Order_No varchar(250) not null,
 message  varchar(1024) not null,
 created_by varchar(100)not null,
 u_ts timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
);

insert into  xpo_delivery_details ( Phone_No, Customer_Name, Order_No, schd_date,  created_date) 
values('+919003772935', 'Sureshkumar Selvaraj', 'OR4567',now(),now());

select Phone_No, Customer_Name, Order_No, schd_date, rescheduled_date, comments,  created_date, last_update from  xpo_delivery_details order by xpo_id;