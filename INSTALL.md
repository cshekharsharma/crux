##################################################
1: Create a virtual host
##################################################
<VirtualHost *:80>
        # The name to respond to
        ServerName code.me
        # Folder where the files live
        DocumentRoot "</path/to/apache/document/root>/code.me"

        # A few helpful settings...
        <Directory "</path/to/apache/document/root>/code.me">
                #FallbackResource index.php
                Options +FollowSymLinks
                Options -Indexes
                RewriteEngine on
#               RewriteCond %{REQUEST_FILENAME} !-d
                RewriteCond %{REQUEST_FILENAME} !-f

                RewriteCond %{REQUEST_FILENAME} !/(css|js|library|fonts|ico|js|jpg|png|gif|ttf|jpeg)/
                RewriteRule ^(.*)$ index.php?__req=$1 [L,QSA]
                # Enables .htaccess files for this site
                AllowOverride All
        </Directory>
        # Apache will look for these two files, in this order, if no file is specified in the URL
        DirectoryIndex index.php index.html
</VirtualHost>


##################################################
2: Restore database
##################################################
mysql -u root -p <dbname> < codeme.sql


##################################################
3: Extract code files in apache document root -
##################################################
cd /path/to/apache/document/root/
tar -xvzf code-me.tar.gz



##################################################
4: Setup Configurations
##################################################

Enter database related config in /includes/Configuration.php file