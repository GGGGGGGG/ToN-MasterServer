CREATE DATABASE patches;
GRANT SELECT ON patches.* TO 'masterserver'@'localhost';
CREATE DATABASE masterserver;
GRANT ALL ON masterserver.* TO 'masterserver'@'localhost';