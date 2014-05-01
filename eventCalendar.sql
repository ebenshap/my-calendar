 create table calendarEvents(
`eventId` int(11) primary key not null auto_increment,
`eventDate` date,
`eventTitle` varchar(100),
`eventDescription` text,
key `eventDate` (`eventDate`)
);