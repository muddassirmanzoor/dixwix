#1725021039
ls
#1725021043
cd htdocs/
#1725021052
cd dixwix.com/
#1725021054
ls
#1725021080
sudo apt update
#1725021091
sudo apt upgrade
#1725021150
exit
#1725013790
ls
#1725013795
cd htdocs/
#1725013796
ls
#1725013802
cd dixwix.com/
#1725013803
ls
#1725013813
php artisan
#1725013851
php artisan cache:clear
#1725013880
php artisan config:cache
#1725014538
ls
#1725014542
cd htdocs/
#1725014543
ls
#1725014548
cd dixwix.com/
#1725014550
ls
#1725014573
php artisan config:cache
#1725015435
ls
#1725015439
cd htdocs/
#1725015444
cd dixwix.com/
#1725015454
php artisan config:cache
#1725015463
php artisan cache:clear
#1725024742
ls
#1725024748
cd htdocs/
#1725024750
ls
#1725024755
cd dixwix.com/
#1725024858
ls
#1725024871
laravel-echo-server.json
#1725024880
nano laravel-echo-server.json
#1725025079
php artisan config:cache
#1725021256
sudo apt update
#1725021284
sudo apt upgrade
#1725021416
sudo apt install nodejs npm
#1725021498
sudo apt install redis-server
#1725021513
sudo apt install composer
#1725021528
cd htdocs/
#1725021536
cd dixwix.com/
#1725021538
ls
#1725021547
sudo apt install redis-server
#1725021561
sudo apt install composer
#1725021600
sudo npm install -g laravel-echo-server
#1725021629
laravel-echo-server init
#1725022145
laravel-echo-server start
#1725022295
ls
#1725022302
nano laravel-echo-server.json
#1725022428
npm install -g pm2
#1725022558
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.5/install.sh | bash
#1725022573
source ~/.bashrc
#1725022581
nvm ls-remote
#1725022591
nvm install --lts
#1725022606
nvm use --lts
#1725022618
nvm alias default node
#1725022627
sudo chown -R $(whoami) /usr/local/lib/node_modules
#1725022647
sudo npm install -g pm2
#1725022686
npm install pm2
#1725022721
pm2 -v
#1725022736
pm2 start laravel-echo-server --name laravel-echo-server
#1725022784
curl http://dixwix.com:6001
#1725022933
pm2 logs laravel-echo-server
#1725022980
npm list -g --depth=0
#1725023007
npm install -g laravel-echo-server
#1725023059
pm2 start $(which laravel-echo-server) --name laravel-echo-server -- start
#1725023109
pm2 stop laravel-echo-server
#1725023110
pm2 delete laravel-echo-server
#1725023110
pm2 start $(which laravel-echo-server) --name laravel-echo-server -- start
#1725023144
curl http://localhost:6001
#1725023157
pm2 logs laravel-echo-server
#1725023225
ls
#1725023241
nano laravel-echo-server.json
#1725025607
ls
#1725025611
cd htdocs/
#1725025613
ls
#1725025618
cd dixwix.com/
#1725025620
ls
#1725025650
pm2 logs laravel-echo-server
#1725025685
curl http://dixwix.com:6001
#1725025689
pm2 logs laravel-echo-server
#1725025764
nano laravel-echo-server.json 
#1725025817
pm2 restart laravel-echo-server
#1725025836
curl http://localhost:6001
#1725025846
curl http://dixwix.com:6001
#1725025918
pm2 logs laravel-echo-server
#1725025968
sudo ufw allow 6001/tcp
#1725025990
nslookup dixwix.com
#1725026036
sudo systemctl restart nginx
#1725026037
# or
#1725026037
sudo systemctl restart apache2
#1725026051
sudo systemctl restart nginx
#1725026061
sudo ufw status
#1725026213
nano laravel-echo-server.json 
#1725026462
pm2 restart laravel-echo-server
#1725026475
sudo ufw status
#1725026504
php artisan config:cache
#1725026511
nano laravel-echo-server.json 
#1725026614
sudo nano sudo nano /etc/nginx/sites-available/default
#1725026637
cd /etc/nginx/
#1725026668
sudo cd /etc/nginx/
#1725026702
sudo ls -l /etc/nginx/
#1725026737
sudo nano /etc/nginx/sites-available/default
#1725026798
sudo nano /etc/nginx/sites-available/dixwix
#1725026841
sudo ln -s /etc/nginx/sites-available/dixwix /etc/nginx/sites-enabled/
#1725026850
sudo nginx -t
#1725026879
sudo systemctl restart nginx
#1725026890
sudo systemctl status nginx
#1725026943
sudo ls -l /run/nginx.pid
#1725026971
sudo systemctl stop nginx
#1725026981
sudo rm /run/nginx.pid
#1725027003
sudo systemctl restart nginx
#1725027015
sudo systemctl status nginx
#1725027047
sudo systemctl restart nginx
#1725027058
sudo tail -f /var/log/nginx/error.log
#1725027130
sudo nano /etc/nginx/conf.d/
#1725027176
sudo grep -r "ssl_stapling" /etc/nginx/
#1725027204
sudo nano /etc/nginx/nginx.conf
#1725027437
sudo nginx -t
#1725027447
sudo systemctl reload nginx
#1725027456
sudo systemctl restart nginx
#1725027466
sudo systemctl status laravel-echo-server
#1725027497
laravel-echo-server --version
#1725027527
sudo nano /etc/systemd/system/laravel-echo-server.service
#1725027619
sudo systemctl daemon-reload
#1725027629
sudo systemctl start laravel-echo-server
#1725027629
sudo systemctl enable laravel-echo-server
#1725027646
sudo systemctl status laravel-echo-server
#1725027689
sudo nano /etc/systemd/system/laravel-echo-server.service
#1725027718
sudo chown -R dixwix:dixwix /home/dixwix/htdocs/dixwix.com
#1725027723
sudo chmod -R 755 /home/dixwix/htdocs/dixwix.com
#1725027733
sudo systemctl daemon-reload
#1725027734
sudo systemctl restart laravel-echo-server
#1725027734
sudo systemctl status laravel-echo-server
#1725027779
which laravel-echo-server
#1725027807
sudo nano /etc/systemd/system/laravel-echo-server.service
#1725027878
sudo systemctl daemon-reload
#1725027885
sudo systemctl restart laravel-echo-server
#1725027892
sudo systemctl status laravel-echo-server
#1725027945
sudo nano /etc/systemd/system/laravel-echo-server.service
#1725027962
sudo systemctl daemon-reload
#1725027967
sudo systemctl restart laravel-echo-server
#1725027973
sudo nano /etc/systemd/system/laravel-echo-server.service
#1725027987
sudo systemctl daemon-reload
#1725027992
sudo systemctl restart laravel-echo-server
#1725027997
sudo systemctl status laravel-echo-server
#1725028035
sudo nano /etc/systemd/system/laravel-echo-server.service
#1725028105
sudo systemctl daemon-reload
#1725028105
sudo systemctl restart laravel-echo-server
#1725028113
journalctl -u laravel-echo-server
#1725028147
laravel-echo-server stop
#1725028156
ps aux | grep laravel-echo-server
#1725028195
sudo kill -SIGTERM 202352
#1725028203
sudo kill -9 202352
#1725028214
pgrep -fl laravel-echo-server
#1725028239
sudo pkill -f laravel-echo-server
#1725028248
pgrep -fl laravel-echo-server
#1725028274
sudo kill -9 202394
#1725028283
pgrep -fl laravel-echo-server
#1725028350
sudo systemctl cat laravel-echo-server
#1725028406
sudo nano /etc/systemd/system/laravel-echo-server.service
#1725028451
sudo systemctl daemon-reload
#1725028460
sudo systemctl stop laravel-echo-server
#1725028460
sudo systemctl disable laravel-echo-server
#1725028472
pgrep -fl laravel-echo-server
#1725028486
sudo pkill -f laravel-echo-server
#1725028489
pgrep -fl laravel-echo-server
#1725028523
sudo crontab -l
#1725028523
crontab -l
#1725028523
sudo ls -l /etc/cron.*
#1725028554
ps aux | grep laravel-echo-server
#1725028593
sudo systemctl disable laravel-echo-server
#1725028593
sudo systemctl stop laravel-echo-server
#1725028593
sudo rm /etc/systemd/system/laravel-echo-server.service
#1725028593
sudo systemctl daemon-reload
#1725028600
pm2 list
#1725028609
sudo supervisorctl status
#1725028642
pm2 stop laravel-echo-server
#1725028651
pm2 delete laravel-echo-server
#1725028663
pm2 list
#1725028671
pm2 save
#1725028685
sudo apt-get install supervisor
#1725028704
sudo supervisorctl status
#1725028748
sudo ls -l /etc/supervisor/conf.d/
#1725028757
sudo systemctl restart supervisor
#1725028766
sudo supervisorctl status
#1725028774
sudo systemctl list-units --type=service | grep laravel
#1725028785
sudo reboot
#1725028847
cd htdocs/
#1725028853
cd dixwix.com/
#1725028863
pgrep -fl laravel-echo-server
#1725028870
pm2 list
#1725028882
sudo supervisorctl status
#1725028900
sudo systemctl list-units --type=service | grep laravel
#1725028994
ls
#1725029002
npm install -g laravel-echo-server
#1725029073
laravel-echo-server init
#1725029082
ls
#1725029090
sudo nano laravel-echo-server.json 
#1725029127
laravel-echo-server start
#1725029198
npm install --save laravel-echo socket.io-client
#1725033963
cd htdocs/dixwix.com/
#1725033972
sudo nano /etc/systemd/system/laravel-echo-server.service
#1725034065
sudo systemctl daemon-reload
#1725034076
sudo systemctl enable laravel-echo-server
#1725034088
sudo systemctl start laravel-echo-server
#1725034096
sudo systemctl status laravel-echo-server
#1725034164
journalctl -u laravel-echo-server.service -n 50 --no-pager
#1725034237
laravel-echo-server stop
#1725034249
pgrep -fl laravel-echo-server
#1725034280
sudo systemctl restart laravel-echo-server
#1725034290
sudo systemctl status laravel-echo-server
#1725034805
cd htdocs/dixwix.com/
#1725034807
ls
#1725034818
sudo nano laravel-echo-server.json 
#1725035223
openssl rand -hex 32
#1725035232
sudo nano laravel-echo-server.json 
#1725035462
php artisan cache:clear
#1725035473
php artisan config:cache
#1725035666
sudo nano laravel-echo-server.json 
#1725035695
sudo systemctl restart laravel-echo-server
#1725035706
sudo systemctl status laravel-echo-server
#1725035760
sudo journalctl -u laravel-echo-server.service -n 50 --no-pager
#1725035808
sudo nano laravel-echo-server.json 
#1725035825
sudo systemctl restart laravel-echo-server
#1725035829
sudo systemctl status laravel-echo-server
#1725035862
sudo nano laravel-echo-server.json dixwix@srv584735:~/htdocs/dixwix.com$ sudo systemctl status laravel-echo-server
#1725035889
● laravel-echo-server.service - Laravel Echo Server
#1725035890
Aug 30 16:37:05 srv584735 systemd[1]: Started Laravel Echo Server.
#1725035890
Aug 30 16:37:06 srv584735 laravel-echo-server[5519]: L A R A V E L  E C H O  S E R V E R
#1725035890
Aug 30 16:37:06 srv584735 laravel-echo-server[5519]: version 1.6.3
#1725035890
Aug 30 16:37:06 srv584735 laravel-echo-server[5519]: Starting server...
#1725035890
Aug 30 16:37:06 srv584735 laravel-echo-server[5519]: ✔  Running at 0.0.0.0 on port 6001
#1725035890
Aug 30 16:37:06 srv584735 laravel-echo-server[5519]: ✔  Listening for http events...
#1725035890
Aug 30 16:37:06 srv584735 laravel-echo-ser
#1725035899
sudo nano laravel-echo-server.json 
#1725035913
sudo systemctl status laravel-echo-server
#1725036047
sudo nano laravel-echo-server.json 
#1725036096
sudo systemctl restart laravel-echo-server
#1725036100
sudo systemctl status laravel-echo-server
#1725036748
cd htdocs/dixwix.com/
#1725036797
sudo nano /etc/nginx/nginx.conf
#1725036851
sudo find /etc/nginx -name "*.conf"
#1725036891
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1725037324
sudo nginx -t
#1725037356
sudo systemctl reload nginx
#1725037646
cd htdocs/dixwix.com/
#1725037768
sudo nginx -s reload
#1725037780
wscat -c wss://dixwix.com:6001/
#1725037813
sudo npm install -g wscat
#1725037825
wscat -c wss://dixwix.com:6001/
#1725037848
npx wscat -c wss://dixwix.com:6001/
#1725037940
sudo npm install -g https-proxy-agent
#1725037955
sudo npm install -g wscat
#1725037986
npx wscat -c wss://dixwix.com:6001/
#1725038015
sudo netstat -tuln | grep 6001
#1725038031
sudo ss -tuln | grep 6001
#1725038100
find / -name '*server.js' 2>/dev/null
#1725038176
laravel-echo-server status
#1725038201
sudo systemctl status laravel-echo-server
#1725038305
sudo nano /etc/systemd/system/laravel-echo-server.service
#1725038362
sudo systemctl daemon-reload
#1725038362
sudo systemctl reset-failed laravel-echo-server
#1725038362
sudo systemctl start laravel-echo-server
#1725038376
journalctl -u laravel-echo-server.service
#1725038503
sudo ls -l /etc/supervisor/conf.d/ sudo nano /etc/nginx/nginx.conf
#1725038577
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1725038623
sudo nano /etc/systemd/system/laravel-echo-server.service
#1725038628
ls
#1725038636
sudo nano laravel-echo-server.json 
#1725038706
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1725038787
sudo nano laravel-echo-server.json 
#1725038892
sudo systemctl restart laravel-echo-server
#1725038905
sudo systemctl status laravel-echo-server
#1725038948
/home/dixwix/.nvm/versions/node/v20.17.0/bin/laravel-echo-server start
#1725039048
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1725039082
/home/dixwix/.nvm/versions/node/v20.17.0/bin/laravel-echo-server start
#1725039119
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1725039164
sudo nginx -t
#1725039236
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1725039263
sudo nginx -t
#1725039340
ls -l /etc/nginx/ssl-certificates/dixwix.com.key
#1725039340
ls -l /etc/nginx/ssl-certificates/dixwix.com.crt
#1725039358
sudo ls -l /etc/nginx/ssl-certificates/dixwix.com.crt
#1725039398
sudo ls -l /etc/nginx/ssl-certificates/dixwix.com.key
#1725039427
sudo chmod 600 /etc/nginx/ssl-certificates/dixwix.com.key
#1725039440
sudo chmod 644 /etc/nginx/ssl-certificates/dixwix.com.crt
#1725039453
sudo chown root:root /etc/nginx/ssl-certificates/dixwix.com.key
#1725039465
sudo chown root:root /etc/nginx/ssl-certificates/dixwix.com.crt
#1725039475
sudo nginx -t
#1725039496
sudo systemctl reload nginx
#1725039499
sudo nginx -t
#1725039937
sudo nano /etc/nginx/nginx.conf
#1725039981
sudo nginx -t
#1725039997
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1725040259
sudo nginx -t
#1725040285
ls
#1725040292
sudo nano laravel-echo-server.json 
#1725040437
sudo systemctl restart nginx
#1725040605
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1725040654
sudo systemctl restart nginx
#1725040663
sudo nginx -t
#1725040971
cd htdocs/dixwix.com/
#1725040982
sudo nano laravel-echo-server.
#1725040990
sudo nano laravel-echo-server.json 
#1725041452
sudo systemctl restart nginx
#1725041505
sudo nano laravel-echo-server.json 
#1725041844
sudo systemctl restart nginx
#1725041874
sudo nginx -t
#1725042347
cd htdocs/dixwix.com/
#1725042499
cd /etc/nginx/
#1725042500
ls
#1725042507
cd config/
#1725042509
ls
#1725042521
cd ../
#1725042525
ls
#1725042550
cd /etc/nginx/
#1725042584
sudo nano /etc/nginx/nginx.conf
#1725042791
sudo systemctl restart nginx
#1725042844
sudo nginx -t
#1725054462
cd htdocs/dixwix.com/
#1725054510
sudo nano /etc/nginx/nginx.conf
#1725110629
php -v
#1725110670
sudo nano /etc/php/8.2/fpm/php.ini
#1725110830
cd htdocs/dixwix.com/
#1725110838
php artisan view:clear
#1725110839
php artisan cache:clear
#1725111455
cd htdocs/dixwix.com/
#1725111469
php artisan storage:link
#1725111509
ls -l public/storage
#1725111544
rm public/storage
#1725111553
php artisan storage:link
#1725111566
ls -l public/storage
#1725115759
cd htdocs/dixwix.com/
#1725115768
composer update --ignore-platform-reqs
#1725384574
git -version
#1725384577
git
#1725384587
git --version
#1725980335
git status
#1725980673
git init
#1725980683
git status
#1725980711
git remote add origin https://github.com/thedigitalhubgit/dixnew.git
#1725980726
git remote -v
#1725980836
git status
#1725980848
git add .
#1725981112
git  commit -m "Initial server commit merging"
#1725981162
git config --global user.email "dixwixdev@gmail.com"
#1725981183
git config --global user.name "Dixwix Server"
#1725981189
git  commit -m "Initial server commit merging"
#1725981214
git pull origin master --rebase
#1725981311
git merge --reset
#1725981318
git reset --merge
#1725981329
git status
#1725981653
git reflog
#1725981689
git reset --hard 084a045e
#1725981738
git status
#1725981750
git push origin -u master
#1725981830
git checkout -b main
#1725981835
git status
#1725981845
git push origin -u main
#1725990991
git status
#1725990997
git add .
#1725991018
git commit -m "Fix issues"
#1725991027
git push origin main
#1725991218
git rebase --abort
#1725991231
git status
#1725991238
git checkout main
#1725991257
git pull origin main
#1726054249
ls
#1726054274
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726054389
sudo nginx -t
#1726054405
sudo systemctl reload nginx
#1726054484
sudo certbot --nginx -d dixwix.com -d www.dixwix.com
#1726054556
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726054926
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726054996
sudo systemctl reload nginx
#1726055030
sudo certbot --nginx -d dixwix.com -d www.dixwix.com
#1726055086
sudo less /var/log/letsencrypt/letsencrypt.log
#1726055189
sudo certbot --nginx -d dixwix.com -d www.dixwix.com
#1726055295
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726055339
sudo nginx -t  # Test the configuration
#1726055339
sudo systemctl reload nginx  # Apply the changes
#1726055364
sudo certbot --nginx -d dixwix.com -d www.dixwix.com
#1726055519
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726055567
sudo nginx -t  # Test the configuration
#1726055572
sudo systemctl reload nginx  # Apply the changes
#1726065145
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726065452
sudo systemctl restart nginx
#1726065508
sudo nginx -t
#1726065667
sudo certbot certonly --webroot -w /home/dixwix/htdocs/dixwix.com/public -d dixwix.com -d www.dixwix.com
#1726065710
sudo certbot certonly --webroot -w /home/dixwix/htdocs/dixwix.com/public/ -d dixwix.com -d www.dixwix.com
#1726065725
sudo certbot certonly --webroot -w /home/dixwix/htdocs/dixwix.com/public/index.php -d dixwix.com -d www.dixwix.com
#1726065742
sudo certbot certonly --webroot -w /home/dixwix/htdocs/dixwix.com/public/ -d dixwix.com -d www.dixwix.com
#1726065848
sudo nginx -t
#1726066037
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726066079
sudo systemctl restart nginx
#1726066088
sudo nginx -t
#1726066207
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726066249
sudo systemctl restart nginx
#1726066253
sudo nginx -t
#1726066488
sudo certbot certonly --webroot -w /home/dixwix/htdocs/dixwix.com/public/ -d dixwix.com -d www.dixwix.com
#1726066873
sudo certbot certonly --staging --webroot -w /home/dixwix/htdocs/dixwix.com/public/ -d dixwix.com -d www.dixwix.com
#1726067101
sudo mkdir -p /home/dixwix/htdocs/dixwix.com/public/.well-known/acme-challenge/
#1726067101
sudo chmod -R 755 /home/dixwix/htdocs/dixwix.com/public/.well-known
#1726067166
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726067214
echo "test" | sudo tee /home/dixwix/htdocs/dixwix.com/public/.well-known/acme-challenge/test-file
#1726067418
exit
#1726067919
sudo certbot certonly --staging --webroot -w /home/dixwix/htdocs/dixwix.com/public/ -d dixwix.com -d www.dixwix.com
#1726067974
exit
#1726061211
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726061426
sudo mkdir -p /home/dixwix/htdocs/dixwix.com/public/.well-known/acme-challenge/
#1726061426
sudo chown -R www-data:www-data /home/dixwix/htdocs/dixwix.com/public
#1726061426
sudo chmod -R 755 /home/dixwix/htdocs/dixwix.com/public
#1726061434
sudo nginx -t  # Test configuration for syntax errors
#1726061434
sudo systemctl reload nginx  # Apply changes
#1726061535
dig A dixwix.com
#1726061543
dig AAAA dixwix.com
#1726061583
sudo ufw status
#1726061635
sudo mkdir -p /home/dixwix/htdocs/dixwix.com/public/.well-known/acme-challenge/
#1726061635
echo "test-file" | sudo tee /home/dixwix/htdocs/dixwix.com/public/.well-known/acme-challenge/test.txt
#1726061717
ping6 dixwix.com
#1726061730
ping6 www.dixwix.com
#1726061800
sudo certbot certonly --webroot -w /home/dixwix/htdocs/dixwix.com/public -d dixwix.com -d www.dixwix.com
#1726062245
sudo chmod -R 755 /home/dixwix/htdocs/dixwix.com/public
#1726062257
sudo chown -R www-data:www-data /home/dixwix/htdocs/dixwix.com/public
#1726062329
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726062707
sudo nginx -t
#1726062771
nginx -v
#1726062785
openssl version
#1726062842
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726062931
sudo nginx -t
#1726062973
sudo apt-get update
#1726062996
sudo apt-get upgrade nginx
#1726063253
sudo nginx -t
#1726063267
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726063373
sudo nginx -t
#1726063573
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726063601
sudo nginx -t
#1726063634
sudo systemctl reload nginx
#1726063637
sudo nginx -t
#1726063687
sudo certbot certonly --webroot -w /home/dixwix/htdocs/dixwix.com/public -d dixwix.com -d www.dixwix.com
#1726063903
dig dixwix.com
#1726063903
dig www.dixwix.com
#1726063949
sudo ufw status
#1726063968
sudo mkdir -p /home/dixwix/htdocs/dixwix.com/public/.well-known/acme-challenge
#1726063968
echo "test" | sudo tee /home/dixwix/htdocs/dixwix.com/public/.well-known/acme-challenge/test-file
#1726064055
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726064545
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726064621
sudo chown -R www-data:www-data /home/dixwix/htdocs/dixwix.com/public
#1726064621
sudo chmod -R 755 /home/dixwix/htdocs/dixwix.com/public
#1726064631
sudo systemctl restart nginx
#1726064675
sudo certbot certonly --webroot -w /home/dixwix/htdocs/dixwix.com/public -d dixwix.com -d www.dixwix.com
#1726072787
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726051111
git status
#1726051130
sudo apt-get update
#1726051298
sudo apt-get install certbot python3-certbot-nginx -y
#1726051379
sudo certbot --nginx -d dixwix.com -d www.dixwix.com
#1726051440
sudo nano /etc/nginx/nginx.conf
#1726052268
sudo mkdir -p /var/log/nginx
#1726052268
sudo touch /var/log/nginx/access.log
#1726052268
sudo touch /var/log/nginx/error.log
#1726052268
sudo chown www-data:www-data /var/log/nginx/access.log /var/log/nginx/error.log
#1726052278
sudo nginx -t
#1726052332
sudo nano /etc/nginx/nginx.conf
#1726052464
sudo grep -r "access_log" /etc/nginx/
#1726052464
sudo grep -r "error_log" /etc/nginx/
#1726052507
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726052902
sudo nano /etc/nginx/nginx.conf
#1726052949
sudo nginx -t
#1726053012
sudo grep -r "access_log" /etc/nginx/
#1726053012
sudo grep -r "error_log" /etc/nginx/
#1726053035
sudo nano /etc/nginx/sites-enabled/srv584735.hstgr.cloud.conf
#1726053216
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726053260
sudo nano /etc/nginx/sites-enabled/srv584735.hstgr.cloud.conf
#1726053276
sudo nano /etc/nginx/nginx.conf
#1726053437
sudo nginx -t
#1726053463
sudo systemctl reload nginx
#1726053473
sudo systemctl status nginx
#1726053498
sudo certbot --nginx -d dixwix.com -d www.dixwix.com
#1726053833
sudo nano /etc/nginx/sites-available/dixwix.com.conf
#1726053855
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726055151
sudo tail -f /var/log/letsencrypt/letsencrypt.log
#1726067732
sudo tail -f /var/log/nginx/error.log
#1726067801
sudo tail -f /var/log/nginx/access.log
#1726067940
sudo tail -f /var/log/nginx/error.log
#1726067961
sudo tail -f /var/log/letsencrypt/letsencrypt.log
#1726069822
dig TXT _acme-challenge.dixwix.com
#1726069932
sudo certbot certonly --manual --preferred-challenges dns -d dixwix.com -d www.dixwix.com
#1726069990
ps aux | grep certbot
#1726070027
sudo kill -9 296121
#1726070027
sudo kill -9 296122
#1726070027
sudo kill -9 296123
#1726070041
ps aux | grep certbot
#1726070074
sudo certbot certonly --manual --preferred-challenges dns -d dixwix.com -d www.dixwix.com
#1726072553
sudo nano /etc/nginx/sites-enabled/dixwix.com.conf
#1726072849
sudo nginx -t
#1726072859
sudo systemctl reload nginx
#1726147702
git status
#1727095629
cd 
#1727095630
ls
#1727095841
cd dixwix_main_files/
#1727095841
ls
#1727095843
ls -la
#1727095848
ls
#1727095850
ls -la
#1727095859
cat .env
#1727095879
exit
#1727095894
cd 
#1727095895
ls
#1727095897
ls -la
#1727095913
cd dixwix_main_files/
#1727095916
ls -la
#1727095919
nano .htaccess 
#1727106710
ls
#1727106712
ls -la
#1727106716
exit
#1727096834
cd 
#1727096835
ls
#1727096842
ls -la
#1727096854
cd dixwix_main_files/
#1727096855
ls
#1727096873
ls -la
#1727097911
cd ../
#1727097922
ll
#1727097925
ls
#1727097997
cd htdocs/
#1727098117
php artisan storage:link
#1727098127
php artisan
#1727098321
php -v
#1727098325
ll
#1727098331
cd ..
#1727098332
ll
#1727098335
ls
#1727098350
cd dixwix_main_files/
#1727098351
ll
#1727098353
ls
#1727098354
dir
#1727098358
php artisan 
#1727098362
clear
#1727098364
ls
#1727098367
ll
#1727098375
cd ..
#1727098631
clear
#1727098654
ls
#1727098661
cd dixwix_main_files/
#1727098664
ls
#1727098666
ll
#1727098695
cd storage/
#1727098697
ls
#1727098702
cd app/
#1727098741
ls
#1727098755
cd storage/
#1727098757
ls
#1727098761
pwd
#1727098780
cd 
#1727098784
cd htdocs/
#1727098787
ls
#1727098791
cd dixwix.com/
#1727098793
ls
#1727098820
cd public
#1727098901
pwd
#1727098967
mkdir public
#1727098977
rm public 
#1727098979
ll
#1727098980
ls
#1727098986
mkdir public
#1727098987
ll
#1727098989
ls
#1727098994
cd public/
#1727098997
pwd
#1727099031
cd
#1727099034
ln -s /home/dixwix/dixwix_main_files/storage/app/storage /home/dixwix/htdocs/dixwix.com/public
#1727099044
dir
#1727099047
ll
#1727099053
cd htdocs/dixwix.com/
#1727099054
ll
#1727099055
ls
#1727099064
ls public/
#1727099075
ls public/storage
#1727099079
cd ,,
#1727099081
cd ..
#1727099086
cd dixwix.com/
#1727099087
ls
#1727099093
rm sabir 
#1727099096
rm storage 
#1727099097
ll
#1727099099
ls
#1727099105
rm demo
#1727099989
ls
#1727099995
cat index.php 
#1727100041
ls
#1727100048
cd
#1727100055
ls
#1727100063
cd dixwix_main_files/
#1727100065
ls
#1727100092
mkdir public
#1727100094
ls
#1727100103
cd public/
#1727100106
pwd
#1727100129
cd
#1727100130
ln -s /home/dixwix/dixwix_main_files/storage/app/storage /home/dixwix/dixwix_main_files/public
#1727100139
cd dixwix_main_files/public/
#1727100141
ls
#1727100144
cd
#1727100297
cd dixwix_main_files/storage/app/storage/
#1727100298
ls
#1727102008
ls -ld /home/dixwix/dixwix_main_files/storage/app/public
#1727103148
sudo chmod -R 777
#1727103553
clear
#1727103567
sudo chmod -R 777
#1727103693
clear
#1727103796
sudo chmod -R 777
#1727103932
clear
#1727103937
sudo chmod -R 777
#1727104387
ls
#1727104412
cd ./
#1727104417
cd ../
#1727104420
ls
#1727104433
rm public
#1727105836
ll
#1727105838
ls
#1727105848
cd public/
#1727105850
ls
#1727105856
ls group_pictures/
#1727105881
ls group_pictures/ | grep LA4CuKRM2prbE4qbkp2kDdIuObhepSalDq6JT2u6.png
#1727105970
ls group_pictures/
#1727105976
dir
#1727105978
cd
#1727106001
ln -s /home/dixwix/dixwix_main_files/storage/app/storage /home/dixwix/dixwix_main_files/public
#1727106073
clear
#1727106076
ls
#1727106083
cd dixwix_main_files/
#1727106084
ls
#1727106089
ls storage/
#1727106095
cd storage/app/
#1727106097
ls
#1727106101
cd public/
#1727106103
ls
#1727106109
ls group_pictures/
#1727106119
ll
#1727106242
ls media/
#1727106268
cd ../../
#1727106270
cd ../
#1727106272
ls
#1727106275
cd public/
#1727106276
ls
#1727106280
ls storage 
#1727106286
cd storage
#1727106291
rm storage 
#1727106292
ll
#1727106294
ls
#1727106319
cd ..
#1727106320
ll
#1727106322
ls
#1727106328
cd storage/app/public/
#1727106330
ls
#1727106335
cd media/
#1727106338
ls
#1727106353
mv group-dummy.jpg group-dummy2.jpg 
#1727106355
ls
#1727106383
cd
#1727106388
cd htdocs/dixwix.com/
#1727106390
ls
#1727106393
cd public/
#1727106394
ls
#1727106407
rm storage 
#1727106409
ls
#1727106411
cd
#1727106442
history | grep ln
#1727106458
ln -s /home/dixwix/dixwix_main_files/storage/app/storage /home/dixwix/htdocs/dixwix.com/public
#1727106609
ln -s /home/dixwix/dixwix_main_files/storage/app/storage /home/dixwix/dixwix_main_files/public
#1727106618
cd dixwix_main_files/public/
#1727106619
ll
#1727106620
ls
#1727106631
ls storage 
#1727106639
cd storage
#1727106645
rm storage 
#1727106646
ll
#1727106648
cd
#1727106669
cd htdocs/dixwix.com/
#1727106670
ls
#1727106674
cd public/
#1727106675
ls
#1727106683
rm storage 
#1727106686
ls
#1727106789
cd ../
#1727106792
ls
#1727106917
cd
#1727106937
ln -s /home/dixwix/dixwix_main_files/storage/app/public  /home/dixwix/dixwix_main_files/public
#1727107044
ll
#1727107046
ls -la
#1727107056
ls -ltr
#1727107072
cd htdocs/dixwix.com/
#1727107072
ls
#1727107074
ls -la
#1727107082
pwd
#1727107116
cat /etc/nginx/
#1727107117
ls
#1727107118
exit
#1727106853
cd 
#1727106853
ls
#1727106857
cd htdocs/
#1727106857
ls
#1727106862
cd dixwix.com/
#1727106862
ls
#1727106877
ls -la
#1727106882
cat robots.txt 
#1727106905
pwd
#1727107157
cd 
#1727107163
cd htdocs/dixwix.com/
#1727107163
ls
#1727107165
pwd
#1727107190
ls -la
#1727107206
cd ..
#1727107206
ls
#1727107209
ls -la
#1727107219
pwd
#1727107222
cd 
#1727107222
ls
#1727107229
cd 
#1727107234
ls -la
#1727107247
cp -R htdocs/
#1727107278
cp -R dixwix_main_files htdocs/
#1727107295
ls
#1727107299
cd htdocs/
#1727107299
ls
#1727107316
cd dixwix.com/
#1727107317
ls -la
#1727107322
cd public/
#1727107322
pwd
#1727107376
cd ..
#1727107376
ls
#1727107378
cd ..
#1727107378
ls
#1727107399
cd dixwix_main_files/storage/app
#1727107399
ls
#1727107415
cd public/
#1727107415
ls
#1727107417
pwd
#1727107444
cd 
#1727107446
cd htdocs/
#1727107447
ls
#1727107451
cd dixwix.com/
#1727107452
ls
#1727107453
ls -la
#1727107458
cd public/
#1727107460
ls -la
#1727107464
ln -S /home/dixwix/htdocs/dixwix_main_files/storage/app/public /home/dixwix/htdocs/dixwix.com/public
#1727107472
ln -s /home/dixwix/htdocs/dixwix_main_files/storage/app/public /home/dixwix/htdocs/dixwix.com/public
#1727107475
ls -la
#1727107484
cd ..
#1727107485
ls
#1727107487
ls -la
#1727107507
cd /home/dixwix/htdocs/dixwix_main_files/storage/app/public
#1727107508
ls -la
#1727107513
cd 
#1727107513
ls
#1727107515
cd htdocs/
#1727107515
ls
#1727107516
ls -la
#1727107522
ls -ltr
#1727107556
cd dixwix.com/public/
#1727107556
ls
#1727107558
ls -la
#1727107624
unlink /home/dixwix/htdocs/public
#1727107631
pwd
#1727107644
unlink /home/dixwix/htdocs/dixwix.com/public/
#1727107670
ls -la
#1727107672
cd ..
#1727107673
ls
#1727107675
ls -la
#1727107677
cd public/
#1727107678
ls
#1727107685
ls -la
#1727107693
rm public
#1727107694
ls -la
#1727107696
cd ..
#1727107696
ls
#1727107699
cd ..
#1727107699
ls
#1727107710
rm -rf dixwix_main_files/
#1727107713
cd ..
#1727107713
ls
#1727107722
cd dixwix_main_files/
#1727107723
pwd
#1727107746
cd storage/app/public/
#1727107747
ls
#1727107748
pwd
#1727107771
cd ../../.
#1727107771
ls
#1727107773
wcd ..
#1727107774
cd ..
#1727107775
ls
#1727107777
cd ..
#1727107777
ls
#1727107781
cd htdocs/dixwix.com/
#1727107781
ls
#1727107783
cd public/
#1727107783
ls -la
#1727107784
clear
#1727107787
ls -la
#1727107793
ln -s /home/dixwix/dixwix_main_files/storage/app/public /home/dixwix/htdocs/dixwix.com/public
#1727107795
ls -a
#1727107797
ls -la
#1727107799
ls -ltr
#1727108000
ls -la
#1727108013
pwd
#1727108050
cd /home/dixwix/dixwix_main_files/storage/app/public
#1727108052
ls -la
#1727108066
ls
#1727108075
cd group_pictures/
#1727108075
ls
#1727108091
cd ..
#1727108092
ls
#1727108093
ls -la
#1727108136
cd media/
#1727108136
ls
#1727108148
cd 
#1727108149
ls
#1727108166
cd htdocs/
#1727108167
ls
#1727108170
cd dixwix.com/
#1727108170
ls
#1727108173
ls -la
#1727108191
cat robots.txt 
#1727108214
nano robots.txt 
#1727108236
cd public/
#1727108237
ls
#1727108246
ls -la
#1727108250
pwd
#1727108253
cd public
#1727108254
ls
#1727108261
cd ..
#1727108261
ls
#1727108265
rm public
#1727108266
cd ..
#1727108266
ls
#1727108273
cd public/
#1727108273
ls
#1727108275
ls -la
#1727108276
cd ..
#1727108277
ls
#1727108282
rm -r public/
#1727108284
pwd
#1727108299
ln -s /home/dixwix/dixwix_main_files/storage/app/public /home/dixwix/htdocs/dixwix.com/public
#1727108301
ls -la
#1727108325
cd public
#1727108325
ls
#1727108328
pwd
#1727108386
ls -la
#1727108388
cd ..
#1727108388
ls
#1727108389
ls -la
#1727108396
rm -r public
#1727108398
ls -la
#1727108405
cd ..
#1727108406
ls
#1727108411
cd ..
#1727108411
ls
#1727108424
cd dixwix_main_files/storage/app/public/
#1727108425
ls
#1727108426
cd ..
#1727108426
ls
#1727108436
mv public/ storage/
#1727108438
ls -la
#1727108440
cd storage/
#1727108441
ls
#1727108442
pwd
#1727108505
cd 
#1727108506
;s
#1727108507
ls
#1727108509
cd htdocs/
#1727108512
cd dixwix.com/
#1727108513
ls
#1727108515
ls -la
#1727108518
ln -s /home/dixwix/dixwix_main_files/storage/app/storage /home/dixwix/htdocs/dixwix.com/public
#1727108520
ls -la
#1727108525
cd public
#1727108525
ls
#1727108528
pwd
#1727108570
ls
#1727108571
cd ..
#1727108572
ls
#1727108579
ls -la
#1727108593
rm public
#1727108594
ls -la
#1727108601
mkdir public
#1727108603
cd public/
#1727108603
ls
#1727108605
pwd
#1727108632
ln -s /home/dixwix/dixwix_main_files/storage/app/storage /home/dixwix/htdocs/dixwix.com/public/storage
#1727108633
ls -la
#1727189696
CD 
#1727189696
LS
#1727189703
CD 
#1727189704
cd 
#1727189820
cd 
#1727189821
ls
#1727189827
cd htdocs/dixwix.com/
#1727189828
ls
#1727189829
sl -la
#1727189832
ls -la
#1727190031
ls
#1727190033
ls -la
#1727190035
cd public/
#1727190036
ls
#1727190037
ls -la
#1727190046
rm storage
#1727190054
history | grep storage
#1727190066
ln -s /home/dixwix/dixwix_main_files/storage/app/storage /home/dixwix/htdocs/dixwix.com/public/storage
#1727190068
ls -la
#1727190092
cd /home/dixwix/dixwix_main_files/storage/app/storage
#1727190093
ls
#1727190101
cd group_pictures/
#1727190101
ls
#1727193151
ls
#1727193153
cd
#1727193156
cd htdocs/
#1727193158
ls
#1727193161
ls lt
#1727193164
ls -ltr
#1727193168
cd
#1727193172
ls -ltr
#1727193199
pwd
#1727193288
cd htdocs/
#1727193292
ls
#1727193306
cd dixwix.com/
#1727193308
ls
#1727193329
ls -lt
#1727193344
cd public/
#1727193347
ls
#1727193355
la
#1727193378
ls -ltr
#1727193436
rm cd
#1727193438
cd
#1727193444
cd dixwix_main_files/
#1727193446
cd storage/
#1727193448
ll
#1727193449
ls
#1727193452
cd app/
#1727193455
ls
#1727193471
ls -ltr
#1727193479
ped
#1727193481
pwd
#1727193591
mv storage/* public/
#1727193637
ls -ltr
#1727193639
cd public/
#1727193641
ls
#1727193653
ls -ltr
#1727193655
cd ..
#1727193667
ls storage -ltr
#1727193689
cd
#1727193810
pwd
#1727193840
cd htdocs/dixwix.com/
#1727193842
ls -ltr
#1727193845
cd public/
#1727193847
ls -ltr
#1727193852
rm storage
#1727193854
ls
#1727193855
pwd
#1727193892
cd
#1727193894
ln -s /home/dixwix/dixwix_main_files/storage/app/public /home/dixwix/htdocs/dixwix.com/public/storage
#1727193907
cd htdocs/dixwix.com/public/
#1727193910
ls -ltr
