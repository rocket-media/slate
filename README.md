# How to deploy these docs to rf.rocketmedia.com/docs

1. Make sure you have the `dv3` SSH alias set up (you do)
2. `bundle install` if you haven't already
3. `middleman deploy --build-before` and it'll rsync to dv3.

Note: If `bundle install` dies on `libv8` try installing with `bundle update` instead.

# How to watch changes during local development

1. `EXECJS_RUNTIME=Node bundle exec middleman`

# How to update the demo page on

1. On dv3 `cd /var/www/vhosts/rf.rocketmedia.com/rocket-forms`
2. `sudo git pull`

Note: Make sure you've updated `dist/demo.html` in the repo, as this is the file that gets served.