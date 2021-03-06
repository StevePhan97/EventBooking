-- List out the event category preference of each user
SELECT users.id, categories.name, count(*) as count
FROM booking INNER JOIN events 
ON booking.eventID = events.id
INNER JOIN categories
ON events.category = categories.id
INNER JOIN users
ON booking.userid = users.id
GROUP BY email, categories.name;

-- Select top preference of a particular user
SELECT email, categories.id , categories.name, count(*) as count
FROM booking INNER JOIN events 
ON booking.eventID = events.id
INNER JOIN categories
ON events.category = categories.id
INNER JOIN users
ON booking.userid = users.id
WHERE users.id = 54
GROUP BY email, categories.name
ORDER BY count DESC
LIMIT 1;

SELECT booking.userID , categories.id , count(*) as count
FROM booking INNER JOIN events 
ON booking.eventID = events.id
INNER JOIN categories
ON events.category = categories.id
WHERE booking.userID = 4
GROUP BY userID, categories.id
ORDER BY count DESC
LIMIT 1;


-- List out the number of users booking in each category of events
SELECT categories.name, count(*) 
from booking INNER JOIN events
on booking.eventID = events.id
INNER JOIN categories
ON events.category = categories.id
GROUP BY categories.name;


(SELECT categories.name, count(*) as absent
from absent INNER JOIN events
on absent.eventID = events.id
INNER JOIN categories
ON events.category = categories.id
GROUP BY categories.name);

-- Attendance Stat by cat
select booking as expectedAttendance , (booking - absence) as realAttendance , A.category from
(select count(*) as booking , events.category
from booking INNER JOIN events
on booking.eventID = events.id
group by events.category) A
INNER JOIN
(select count(*) as absence, events.category
from absent INNER JOIN events
on absent.eventID = events.id
GROUP BY events.category) B
ON A.category = B.category;

-- Attendance Stat by month
SELECT booking as expectedAttendance , (booking - absence) as realAttendance , A.month from
(select count(*) as booking , MONTHNAME(time) as month
from booking INNER JOIN events
on booking.eventID = events.id
WHERE events.time BETWEEN '2017-09-01' AND '2018-04-30'
group by MONTHNAME(time)
ORDER BY time) A
INNER JOIN
(select count(*) as absence, MONTHNAME(time) as month
from absent INNER JOIN events
on absent.eventID = events.id
WHERE events.time BETWEEN '2017-09-01' AND '2018-04-30'
GROUP BY MONTHNAME(time)
ORDER BY time) B
ON A.month = B.month;

-- Attendance Stat by student type
SELECT count(*) as booking, categories.name FROM
booking INNER JOIN events
ON booking.eventID = events.id
INNER JOIN users
ON booking.userID = users.id
INNER JOIN categories
ON events.category = categories.id
WHERE role = 2
GROUP BY categories.name;

-- Profit Stat by category
SELECT SUM(total), categories.name, concat('#',SUBSTRING((lpad(hex(@curRow := @curRow + 10),6,0)),-6)) AS color 
FROM buy INNER JOIN events
ON buy.eventID = events.id
INNER JOIN categories
ON categories.id = events.category
INNER JOIN (SELECT @curRow := 5426175) color_start_point
GROUP BY categories.name;

-- Profit Stat by time
SELECT SUM(total), date(time) as time
FROM buy INNER JOIN events
ON buy.eventID = events.id
WHERE time BETWEEN '2017-09-01' AND '2018-04-30'
GROUP BY time
ORDER BY time ASC;


-- Emails of whom interested in particular event category
SELECT email, categoryID FROM subscribe INNER JOIN users
ON subscribe.userID = users.id

-- Select email of person who has made the most profit 
SELECT email, id FROM users WHERE id = 
(SELECT userID 
FROM buy
GROUP BY userID
ORDER BY total DESC
LIMIT 1);

-- Select random promocode of an event
SELECT title,location,time, A.id as promoCode from events INNER JOIN
(SELECT id, eventID FROM promo_codes
WHERE eventID = 757
ORDER BY RAND()
LIMIT 1) A
on events.id = A.eventID;

-- Check if event has promo code
SELECT events.id, events.title, promo_codes.id as isPromoted 
FROM events LEFT JOIN promo_codes
ON events.id = promo_codes.eventID
WHERE events.id=757
LIMIT 1;

-- Get category of events which the user has subscribed
SELECT categories.name, A.userID FROM categories LEFT JOIN
(SELECT categoryID,userID FROM subscribe
WHERE userID = 5) A
ON categories.id = A.categoryID
ORDER BY categories.name;


ALTER TABLE users
ADD degree TINYINT(1);
/*
	CS,IT    		0
	Engineer    	1
	Math, Stat 		2
	Biology			3
	Law				4
	BIT, Business   5 
	Language		6
	Media			7
	Arts			8

*/

ALTER TABLE users
ADD favoriteClubType TINYINT(1);
/*
	Academic			0
	Community			1
	Cultural			2
	Professional		3
	Political			4
	Spiritual			5
	Sport				6
	Special Interests	7

*/

SELECT stddev(age)
FROM users
WHERE id IN (SELECT userID from booking);

