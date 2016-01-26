# How to deploy these docs to rf.rocketmedia.com/docs

1. Make sure you have the `dv3` SSH alias set up (you do)
2. `bundle install` if you haven't already
3. `middleman deploy --build-before` and it'll rsync to dv3.

# How to watch changes during local development

1. `EXECJS_RUNTIME=Node bundle exec middleman`