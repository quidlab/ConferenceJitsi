CREATE TABLE `conference_ConferenceJitsi` (
  `id` bigint(20) NOT NULL,
  `idConference` bigint(20) NOT NULL DEFAULT '0',  
  `emailuser` varchar(255) DEFAULT NULL,
  `displayname` varchar(255) DEFAULT NULL,
  `ismoderator` varchar(1) DEFAULT '0',
  `extra_conf` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `conference_ConferenceJitsi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idConference` (`idConference`);


ALTER TABLE `conference_ConferenceJitsi`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
