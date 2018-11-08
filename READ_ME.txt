CREATE DATABASE participantconsent;

CREATE TABLE `consents` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`Email` varchar(100) DEFAULT NULL,
`InterviewDate` date NOT NULL,
`InterviewTime` varchar(11) NOT NULL,
`ParticipantName` varchar(50) DEFAULT NULL,
`Phone` varchar(11) DEFAULT NULL,
`Status` varchar(30) DEFAULT NULL,
`DateConsented` date DEFAULT NULL,
PRIMARY KEY (`ID`),
UNIQUE KEY `InterviewDate` (`InterviewDate`,`InterviewTime`),
UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1


app user
username phpuser
password phpuser

admin login
admin/admin
