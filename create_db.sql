-- Create database
create database if not exists simple_contest;

-- Create Tables
create table if not exists simple_contest.contests(
	id bigint not null auto_increment primary key,
	name varchar(255) not null,
	description text,
	question varchar(255) not null,
	start_at datetime not null,
	end_at datetime not null,
	created_at datetime not null,
	updated_at datetime not null
	);
create table if not exists simple_contest.contestants(
	id bigint not null auto_increment primary key,
	contest_id bigint not null,
	name varchar(100),
	email varchar(255),
	phone varchar(20),
	answers text,
	created_at datetime not null,
	updated_at datetime not null
	);
	
-- Sample Data
insert into simple_contest.contests(
	name, 
	description, 
	question, 
	start_at, 
	end_at, 
	created_at, 
	updated_at
	)
	values(
	'Sample Contest',
	'Please complete the following fields to submit entry.',
	'What are your favorite television shows?',
	'2013-05-29 00:00:00',
	'2013-05-29 00:00:00' + interval 1 month,
	now(),
	now()
	);