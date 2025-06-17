mysqldump -u root -p --databases gorno > C:\ospanel\domains\gorno\backups\gorno.sql
     powershell Compress-Archive -Path C:\ospanel\domains\gorno\backups\gorno.sql -DestinationPath C:\ospanel\domains\gorno\backups\gorno_%date:~-10,2%-%date:~-7,2%-%date:~-4,4%.zip
     del C:\ospanel\domains\gorno\backups\gorno.sql