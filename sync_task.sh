#!/bin/bash
#rsync -avzP --include=.env --exclude=.* --exclude=storage/logs --delete-before --delete-excluded . root@111.230.140.74:/data/wwwroot/bahe-mis
rsync -avzP --exclude=.* --exclude=*.tar.gz --exclude=storage --delete-before . root@111.230.142.155:/data/wwwroot/bahe-mis-task
