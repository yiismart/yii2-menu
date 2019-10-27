create table if not exists `menu`
(
    `id` int(10) not null auto_increment,
    `tree` int(10),
    `lft` int(10) not null,
    `rgt` int(10) not null,
    `depth` int(10) not null,
    `name` varchar(100) default null,
    `active` tinyint(1) default 1,
    `type` int(10) default null,
    `url` varchar(200) default null,
    `alias` varchar(200) default null,
    primary key (`id`),
    key `alias` (`alias`)
) engine InnoDB;
