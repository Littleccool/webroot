#!/bin/bash
cd /data/tmp/report
if [ $# -lt 1 ] ;then
        today=`date -d"0 days ago" "+%Y%m%d"`
else
        today=$1
fi
echo $today
appId=10002
playTimes=`cat report.$today.log | grep 'playInfo|10002' | awk -F'|' '{a[$1$16]+=$6} END{for (k in a) { if(a[k] > 0 )print k,a[k]}}' | wc -l`
echo $playTimes;
sql="replace into game_play_times values($appId,$today,$playTimes)";
echo $sql;
mysql -uroot -p1234 stat -h10.135.72.229 -e"$sql";

exit;
