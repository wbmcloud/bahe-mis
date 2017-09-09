#!/bin/bash
#rsync -avzP --include=.env --exclude=.* --exclude=storage/logs --delete-before --delete-excluded . root@111.230.140.74:/data/wwwroot/bahe-mis
rsync -avzP --exclude=.* --exclude=storage/logs --delete-before . game@111.230.140.74:/data/wwwroot/bahe-mis
